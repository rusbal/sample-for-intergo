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
            'quantity_available'  => (int) $row[5],
        ]);
    }

    private function doesFirstLineContainLabels()
    {
        return isset($this->rows[0][0])
            && $this->rows[0][0] == self::IGNORE_FIRST_LINE_WITH_FIRST_COLUMN;
    }
}