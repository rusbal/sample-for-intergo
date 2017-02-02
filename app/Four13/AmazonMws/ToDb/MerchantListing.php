<?php

namespace Four13\AmazonMws\ToDb;

use Four13\TextLTSV\LTSV;
use App\AmazonMerchantListing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MerchantListing extends ToDb
{
    const IGNORE_FIRST_LINE_WITH_FIRST_COLUMN = 'item-name';

    protected $rows;
    protected $user;
    protected $reportClass;

    public function __construct($reportClass, $user = null)
    {
        $this->reportClass = $reportClass;
        $this->user = $this->getUser($user);
    }

    private function doesFirstLineContainLabels()
    {
        return isset($this->rows[0][0])
            && $this->rows[0][0] == self::IGNORE_FIRST_LINE_WITH_FIRST_COLUMN;
    }

    private function getUser($user)
    {
        $user = $user ?: Auth::user();
        if (! $user) {
            throw new \Exception('Cannot instantiate ' . __CLASS__ . ' when there is no authenticated user.');
        }
        return $user;
    }

    public function saveToDb($fileContents)
    {
        $ltsv = new LTSV();
        $this->rows = $ltsv->parseString(
            utf8_encode($fileContents)
        );

        if ($this->doesFirstLineContainLabels()) {
            unset($this->rows[0]);
        }

        DB::transaction(function () {
            $countInserted = 0;
            $countUpdated = 0;
            $count26Cols = 0;
            $count27Cols = 0;
            $count28Cols = 0;

            /**
             * NOTE: Column count varies at 26, 27, 28 columns.
             * Data is written to database all the time.
             */

            foreach ($this->rows as $row) {
                if (!$this->isValid($row)) {
                    continue;
                }

                $columnCount = count($row);

                if ($columnCount == 26) {
                    $count26Cols += 1;

                } elseif ($columnCount == 27) {
                    $count27Cols += 1;

                } elseif ($columnCount == 28) {
                    $count28Cols += 1;

                } else {
                    Log::info(__CLASS__ . '@saveToDb'
                        . " invalid but writing it anyway: [expected column count of 26, 27, 28"
                        . " but got " . $columnCount . "]");
                    
                    continue;
                }

                $listingId = $row[2];

                if (AmazonMerchantListing::where('listing_id', $listingId)->exists()) {
                    $countUpdated += $this->updateRow($row);
                } else {
                    $countInserted += $this->insertRow($row);
                }
            }

            Log::info(__CLASS__ . '@saveToDb' . " inserted: $countInserted");
            Log::info(__CLASS__ . '@saveToDb' . " updated: $countUpdated");
            Log::info(__CLASS__ . '@saveToDb' . " 26 cols: $count26Cols");
            Log::info(__CLASS__ . '@saveToDb' . " 27 cols: $count27Cols");
            Log::info(__CLASS__ . '@saveToDb' . " 28 cols: $count28Cols");
        });
    }

    /**
     * Private
     */

    /**
     * Updates a row and count of affected rows, basically 1 or 0.
     *
     * @param $row
     * @return integer
     */
    private function updateRow($row)
    {
        $listingId = $row[2];

        $openDate = (new \DateTime($row[6]))->format( 'Y-m-d H:i:s');

        $data = [
            'user_id' => $this->user->id,
            'item_name' => $row[0],
            'item_description' => $row[1],
            'seller_sku'  => (int) $row[3],
            'price'  => (float) $row[4],
            'quantity'  => (int) $row[5],
            'open_date'  => $openDate,
            'image_url'  => $row[7],
            'item_is_marketplace'  => $row[8],
            'product_id_type'  => (int) $row[9],
            'zshop_shipping_fee'  => (float) $row[10],
            'item_note'  => $row[11],
            'item_condition'  => (int) $row[12],
            'zshop_category1'  => $row[13],
            'zshop_browse_path'  => $row[14],
            'zshop_storefront_feature'  => $row[15],
            'asin1'  => $row[16],
            'asin2'  => $row[17],
            'asin3'  => $row[18],
            'will_ship_internationally'  => (int) $row[19],
            'expedited_shipping'  => $row[20],
            'zshop_boldface'  => $row[21],
            'product_id'  => $row[22],
            'bid_for_featured_placement'  => $row[23],
            'add_delete'  => $row[24],
            'pending_quantity'  => (int) $row[25],
        ];

        if (isset($row[26])) {
            $data['fulfillment_channel'] = $row[26];
        }

        return AmazonMerchantListing::where('listing_id', $listingId)->update($data);
    }

    /**
     * Inserts a row and returns 1 on success, 0 on failure.
     *
     * @param $row
     * @return int
     */
    private function insertRow($row)
    {
        $openDate = (new \DateTime($row[6]))->format( 'Y-m-d H:i:s' );


        /*
        | Before listing was requested from Amazon using _GET_MERCHANT_LISTINGS_DATA_
        | Because of blank item names on Revenue reports, it was changed to _GET_MERCHANT_LISTINGS_ALL_DATA_.
        |
        | field: 28      merchant-shipping-group        ***NOT SAVED ON DB
        |
        | merchant-shipping-group is not saved on table: amazon_merchant_listing
        */

        /**
         * ErrorException: Undefined offset 26
         *
         * NOTE: Writing it anyway.  Check if set, else, just write the data and ignore.
         */

        $data = [
            'user_id' => $this->user->id,
            'item_name' => $row[0],
            'item_description' => $row[1],
            'listing_id'  => $row[2],
            'seller_sku'  => (int) $row[3],
            'price'  => (float) $row[4],
            'quantity'  => (int) $row[5],
            'open_date'  => $openDate,
            'image_url'  => $row[7],
            'item_is_marketplace'  => $row[8],
            'product_id_type'  => (int) $row[9],
            'zshop_shipping_fee'  => (float) $row[10],
            'item_note'  => $row[11],
            'item_condition'  => (int) $row[12],
            'zshop_category1'  => $row[13],
            'zshop_browse_path'  => $row[14],
            'zshop_storefront_feature'  => $row[15],
            'asin1'  => $row[16],
            'asin2'  => $row[17],
            'asin3'  => $row[18],
            'will_ship_internationally'  => (int) $row[19],
            'expedited_shipping'  => $row[20],
            'zshop_boldface'  => $row[21],
            'product_id'  => $row[22],
            'bid_for_featured_placement'  => $row[23],
            'add_delete'  => $row[24],
            'pending_quantity'  => (int) $row[25],
            'fulfillment_channel' => isset($row[26]) ? $row[26] : '--PROGRAMMER-- row 26 not set',
        ];

        $row = AmazonMerchantListing::create($data);

        return $row ? 1 : 0;
    }
}