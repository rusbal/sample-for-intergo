<?php

namespace App\Http\Controllers\Api;

use App\AmazonMerchantListing;
use App\Transformer\AmazonMerchantListingTransformer as AMLTransformer;

class AmlController extends BaseController
{
    protected $userId;

    public function update($listingId)
    {
        $affectedRows = $this->updateOrFail($listingId);
        return $this->success("affected rows: $affectedRows");
    }

    private function updateOrFail($listingId)
    {
        $inputData = $this->request->all();

        $fields = AmazonMerchantListing::fillableFields();

        $filteredData = array_filter($inputData, function($key) use ($fields) {
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

        $limit = $this->request->input('limit');

        if ($limit) {
            return $this->listingPaged($limit);
        }

        return $this->listingAll();
    }

    /**
     * Private
     */

    private function listingAll()
    {
        $listings = AmazonMerchantListing::where('user_id', $this->userId)->get();
        return $this->response->withCollection($listings, new AMLTransformer);
    }

    private function listingPaged($limit)
    {
        $listings = AmazonMerchantListing::where('user_id', $this->userId)->paginate($limit);
        return $this->response->withPaginator($listings, new AMLTransformer);
    }
}
