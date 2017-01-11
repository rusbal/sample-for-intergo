<?php

namespace Four13\AmazonMws;


use App\AmazonMws;
use App\AmazonRequestHistory;
use App\AmazonRequestQueue;
use Illuminate\Support\Facades\File;
use Peron\AmazonMws\AmazonReport;
use Peron\AmazonMws\AmazonReportRequest;
use Peron\AmazonMws\AmazonReportRequestList;

class RequestReport
{
    private $storeName;
    private $requestId;

    public function __construct($storeName, $requestId)
    {
        $this->storeName = $storeName;
        $this->requestId = $requestId;
    }

    public static function queue($storeName, $type)
    {
        $item = AmazonRequestQueue::queueOne($storeName, 'report', $type, '', '');
        self::initiate($item);
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

    public static function process($item)
    {
        self::poke($item['store_name'], $item['request_id']);
    }

    public static function poke($storeName, $requestId)
    {
        $instance = new static($storeName, $requestId);

        $obj = new AmazonReportRequestList($storeName);
        $obj->setRequestIds($requestId);
        $obj->fetchRequestList();
        $list = $obj->getList();

        if ($instance->checkIfDone($list)) {
            $instance->logToHistory();
        }
    }

    private function logToHistory()
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
        if ($obj->fetchReport()) {
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