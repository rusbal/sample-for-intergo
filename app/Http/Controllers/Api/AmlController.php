<?php

namespace App\Http\Controllers\Api;

use App\AmazonMerchantListing;

class AmlController extends BaseController
{
    public function update($listingId)
    {
        $affectedRows = $this->updateOrFail($listingId);
        return $this->success("affected rows: $affectedRows");
    }

    private function updateOrFail($listingId)
    {
        $inputData = $this->request->all();

        $fields = AmazonMerchantListing::fillableFields();

        $filteredData = array_filter($inputData, function ($key) use ($fields) {
            return in_array($key, $fields);
        }, ARRAY_FILTER_USE_KEY);

        return AmazonMerchantListing::builderOrFail($listingId)
            ->update($filteredData);
    }

    /**
     * Get Amazon Inventory Listing
     * URI: api/aml/{method}
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|mixed
     */
    public function listing()
    {
        $this->userId = $this->requireParam('user');

        $columns = [
            'id',
            'seller_sku',
            'asin1',
            'item_name',
            'quantity_available',
            'will_monitor',

            // Detail Row
            'price',
            'product_id',
            'fulfillment_channel',
            'condition_type',
            'warehouse_condition_code',
        ];

        $condition = [
            ['user_id', $this->requireParam('user')]
        ];

        if ($monitor = $this->request->input('monitor')) {
            $condition[] = ['will_monitor', $monitor];
        }

        return AmazonMerchantListing::selectSortAndFilter(
            $columns,
            $condition,
            $this->request->input('sort'),
            $this->request->input('filter')
        )->paginate($this->request->input('per_page'));
    }
}

