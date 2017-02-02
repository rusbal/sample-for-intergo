<?php

namespace Four13\AmazonMws\ToDb;

use Four13\TextLTSV\LTSV;
use App\AmazonMerchantListing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class MerchantSkuQuantity extends ToDb
{
    const IGNORE_FIRST_LINE_WITH_FIRST_COLUMN = 'seller-sku';

    protected $rows;

    protected $filters = [
        'seller_sku' => [ 'index' => 0, 'condition' => '>', 'value' => 0 ],
    ];

    private function doesFirstLineContainLabels()
    {
        return isset($this->rows[0][0])
            && $this->rows[0][0] == self::IGNORE_FIRST_LINE_WITH_FIRST_COLUMN;
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
            /**
             * Counts
             */
            $matched = 0;
            $unmatched = 0;
            $invalid = 0;

            foreach ($this->rows as $row) {
                if (! $this->isValid($row)) {
                    $invalid++;
                    continue;
                }

                $sellerSku = (int) $row[0];

                if ($sellerSku == 0) {
                    $invalid++;
                    continue;
                }

                $updateBuilder = AmazonMerchantListing::where('seller_sku', $sellerSku);

                if ($updateBuilder->exists()) {
                    $this->saveRow($updateBuilder, $row);
                    $matched++;
                } else {
                    $unmatched++;
                }
            }

            Log::info(__CLASS__ . '@saveToDb' . " matched: $matched");
            Log::info(__CLASS__ . '@saveToDb' . " unmatched: $unmatched");
            Log::info(__CLASS__ . '@saveToDb' . " invalid: $invalid");
        });
    }

    /**
     * Private
     */

    /**
     * @param Builder $updateBuilder
     * @param $row array
     */
    private function saveRow(Builder $updateBuilder, $row)
    {
        $updateBuilder->update([
            'afn_asin' => $row[1],
            'fulfillment_channel_sku'  => $row[2],
            'condition_type'  => $row[3],
            'warehouse_condition_code'  => $row[4],

            /**
             * This field is no longer updated here.
             * Instead, it is updated in ToDb/UnsuppressedInventory
             * where it uses _GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA_
             *
             * THINKING: Since request to _GET_AFN_INVENTORY_DATA_ was only for the purpose
             * of getting the quantity, is there still a need for this code?
             *
             * 'quantity_available'  => (int) $row[5],
             */
        ]);
    }
}