<?php

namespace Four13\AmazonMws;


use App\AmazonMws;
use App\AmazonRequestQueue;
use App\AmazonRequestHistory;
use Four13\AmazonMws\ToDb\ToDb;
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
     * @var ToDb
     */
    private $toDb;

    public function __construct($storeName, $requestId, ToDb $toDb)
    {
        $this->storeName = $storeName;
        $this->requestId = $requestId;
        $this->toDb = $toDb;
    }

    /**
     * Initiates a report request saving it to table: amazon_request_queues
     *
     * @param $storeName
     * @param $type
     * @return bool
     */
    public static function queue($storeName, $type, $isUpdateRun = false)
    {
        $reportClass = $isUpdateRun ? 'report-update' : 'report';

        $item = AmazonRequestQueue::queueOne($storeName, $reportClass, $type, '', '');

        if ($item->request_id == '') {
            self::initiate($item);
            return true;
        }

        return false;
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

    public static function process(ToDb $toDb, $item)
    {
        return self::poke($toDb, $item['store_name'], $item['request_id']);
    }

    /**
     * Follows up Amazon on the previous request with request_id.
     *
     * @param $toDb
     * @param $storeName
     * @param $requestId
     * @return bool
     */
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

        /**
         * We only passed one Request ID so we only expect one item from list.
         */
        $singleRequest = $list[0];

        return $instance->checkIfDone($singleRequest);
    }

    /**
     * Gets report if status is _DONE_
     * Deletes request if status is _CANCELLED_
     *
     * @param array $request
     * @return bool
     */
    public function checkIfDone($request)
    {
        if ($request['ReportProcessingStatus'] == '_DONE_') {
            if ($this->getReport($request['GeneratedReportId'])) {
                $this->moveQueueToHistory($request['ReportProcessingStatus']);
                return true;
            }
        }

        if ($request['ReportProcessingStatus'] == '_SUBMITTED_'
         || $request['ReportProcessingStatus'] == '_IN_PROGRESS_') {
            return false;
        }

        /**
         * _CANCELLED_
         * _DONE_NO_DATA_
         */
        $this->moveQueueToHistory($request['ReportProcessingStatus']);
        return false;
    }

    /**
     * Delegates saving of report if fetched successfully
     *
     * @param $reportId
     * @return bool
     */
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

    /**
     * Private
     */

    private function filename($extension = '.txt')
    {
        $queue = AmazonRequestQueue::getRequest($this->requestId);
        $requestType = $queue ? ".{$queue->type}" : '';

        return $this->amazonStoragePath()
            . DIRECTORY_SEPARATOR
            . date("Ymd.g:ia")
            . $requestType
            . '.' . $this->requestId
            . '.' . microtime(true)
            . $extension;
    }

    private function amazonStoragePath()
    {
        $path = storage_path('amazon');

        if (! file_exists($path)) {
            File::makeDirectory($path);
        }

        return $path;
    }

    /**
     * Delete queue and write it to request history table when status is:
     *   _DONE_
     *   _DONE_NO_DATA_
     *   _CANCELLED_
     *
     * @param string $status
     */
    private function moveQueueToHistory($status)
    {
        $queue = AmazonRequestQueue::where('request_id', $this->requestId);

        $data = $queue->first()->toArray();
        $data['status'] = $status;

        AmazonRequestHistory::logHistory($data);

        $queue->delete();
    }
}