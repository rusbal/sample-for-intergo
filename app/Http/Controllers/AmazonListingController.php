<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AmazonMerchantListing;
use Illuminate\Support\Facades\Auth;

class AmazonListingController extends Controller
{
    protected $user;

    protected $columns = [
        'id',
        'seller_sku',
        'asin1',
        'item_name',
        'quantity_available',

        // Monitoring
        'will_monitor',
        'minimum_advertized_price',
        'maximum_offer_quantity',

        // Detail Row
        'price',
        'product_id',
        'fulfillment_channel',
        'condition_type',
        'warehouse_condition_code',
    ];

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        return AmazonMerchantListing::selectSortAndFilter(
            $this->columns,
            $this->getCondition($request),
            $request->get('sort'),
            $request->get('filter')
        )->paginate($request->get('per_page'));
    }

    /**
     * Private
     */

    /**
     * @param Request $request
     * @return array
     */
    private function getCondition($request)
    {
        $condition = [
            ['user_id', $this->user->id]
        ];

        if ($monitor = $request->get('monitor')) {
            $condition[] = ['will_monitor', $monitor];
        }

        return $condition;
    }
}
