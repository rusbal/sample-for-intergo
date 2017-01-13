<?php

namespace Four13\AmazonMws;


use App\AmazonMws;
use App\AmazonRequestQueue;
use App\AmazonRequestHistory;
use Four13\AmazonMws\ToDb\ToDbInterface;
use Zaffar\AmazonMws\AmazonReport;
use Illuminate\Support\Facades\File;
use Zaffar\AmazonMws\AmazonReportRequest;
use Four13\AmazonMws\ToDb\MerchantListing;
use Zaffar\AmazonMws\AmazonReportRequestList;

class RequestReport
{
    private $storeName;
    private $requestId;

    /**
     * @var ToDbInterface
     */
    private $toDb;

    public function __construct($storeName, $requestId, ToDbInterface $toDb)
    {
        $this->storeName = $storeName;
        $this->requestId = $requestId;
        $this->toDb = $toDb;
    }

    public static function queue($storeName, $type)
    {
        $item = AmazonRequestQueue::queueOne($storeName, 'report', $type, '', '');

        if ($item->request_id == '') {
            self::initiate($item);
        }
    }

    public static function initiate($item)
    {
        $obj = new AmazonReportRequest($item->store_name);
        $obj->setReportType($item->type);
        $obj->setMarketplaces(AmazonMws::UNITED_STATES_MARKETPLACE_ID);
        $obj->requestReport();
        $response = $obj->getResponse();

        if ($response) {
            $item->request_id = $response['ReportRequestId'];
            $item->response = serialize($response);
            $item->save(); // update
        }

        return $response;
    }

    public static function process(MerchantListing $toDb, $item)
    {
        return self::poke($toDb, $item['store_name'], $item['request_id']);
    }

    public static function poke($toDb, $storeName, $requestId)
    {
        $instance = new static($storeName, $requestId, $toDb);

        $obj = new AmazonReportRequestList($storeName);
        $obj->setRequestIds($requestId);
        $obj->fetchRequestList();
        $list = $obj->getList();

        if (empty($list)) {
            return false;
        }

        if ($instance->checkIfDone($list)) {
            $instance->moveQueueToHistory();
            return true;
        }

        return false;
    }

    private function moveQueueToHistory()
    {
        $item = AmazonRequestQueue::where('request_id', $this->requestId);

        AmazonRequestHistory::logHistory($item->first()->toArray());

        $item->delete();
    }

    public function checkIfDone($list)
    {
        $done = false;

        foreach ($list as $item) {
            if ($item['ReportProcessingStatus'] == '_DONE_') {
                $oneItemIsDone = $this->getReport($item['GeneratedReportId']);
                $done = $done ?: $oneItemIsDone;
            }
        }

        return $done;
    }

    public function getReport($reportId)
    {
        $obj = new AmazonReport($this->storeName);
        $obj->setReportId($reportId);
        $report = $obj->fetchReport();

        if ($report) {
            $this->toDb->saveToDb($report);
            $obj->saveReport($this->filename());
            return true;
        }

        return false;
    }

    private function filename($extension = '.txt')
    {
        $amazonStoragePath = storage_path('amazon');

        if (! file_exists($amazonStoragePath)) {
            File::makeDirectory($amazonStoragePath);
        }

        $filename = $this->requestId . '.' . microtime(true) . $extension;
        return $amazonStoragePath . DIRECTORY_SEPARATOR . $filename;
    }
}