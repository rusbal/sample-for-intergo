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

            foreach ($this->rows as $row) {
                if (!$this->isValid($row)) {
                    continue;
                }

                if ($this->reportClass == 'report-update') {
                    $countUpdated += $this->updateRow($row);
                } else {
                    $countInserted += $this->insertRow($row);
                }
            }

            Log::info(__CLASS__ . '@saveToDb' . " inserted: $countInserted");
            Log::info(__CLASS__ . '@saveToDb' . " updated: $countUpdated");
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

        $openDate = (new \DateTime($row[6]))->format( 'Y-m-d H:i:s' );

        return AmazonMerchantListing::where('listing_id', $listingId)->update([
            'user_id' => $this->user->id,
            'item_name' => $row[0],
            'item_description' => $row[1],
            'seller_sku'  => (int) $row[3],
            'price'  => $row[4],
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
            'fulfillment_channel' => $row[26],
        ]);
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

        $row = AmazonMerchantListing::create([
            'user_id' => $this->user->id,
            'item_name' => $row[0],
            'item_description' => $row[1],
            'listing_id'  => $row[2],
            'seller_sku'  => (int) $row[3],
            'price'  => $row[4],
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
            'fulfillment_channel' => $row[26],
        ]);

        return $row ? 1 : 0;
    }

    private function doesFirstLineContainLabels()
    {
        return isset($this->rows[0][0])
            && $this->rows[0][0] == self::IGNORE_FIRST_LINE_WITH_FIRST_COLUMN;
    }
}