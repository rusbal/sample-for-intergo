<?php

namespace App\Http\Controllers\Api;

use App\AmazonMerchantListing;
use App\Transformer\AmazonMerchantListingTransformer as AMLTransformer;

class AmlController extends BaseController
{
    public function listing()
    {
        $userId = $this->requireParam($this->request, 'user');

        $listings = AmazonMerchantListing::where('user_id', $userId)->paginate(1);

        return $this->response->withPaginator($listings, new AMLTransformer());
    }
}
