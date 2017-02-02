<?php

namespace Four13\AmazonMws\ToDb;


use App\AmazonMerchantListing;
use Four13\TextLTSV\LTSV;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\AmazonUnsuppressedInventory;

class UnsuppressedInventory extends ToDb
{
    const IGNORE_FIRST_LINE_WITH_FIRST_COLUMN = 'sku';

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
            $countInserted = 0;
            $countUpdated = 0;
            $countQuantityUpdated = 0;

            foreach ($this->rows as $row) {
                if (!$this->isValid($row)) {
                    continue;
                }

                $asin = $row[2];

                if (AmazonUnsuppressedInventory::where('asin', $asin)->exists()) {
                    $countUpdated += $this->updateRow($row, $asin);
                } else {
                    $countInserted += $this->insertRow($row);
                }

                /**
                 * Update quantity on listings table
                 */
                $afnFulfillableQuantity = (int) $row[10];
                $countQuantityUpdated += $this->updateListingQuantity($asin, $afnFulfillableQuantity);
            }

            Log::info(__CLASS__ . '@saveToDb' . " inserted: $countInserted");
            Log::info(__CLASS__ . '@saveToDb' . " updated: $countUpdated");
            Log::info(__CLASS__ . '@saveToDb' . " quantity updated: $countQuantityUpdated");
        });
    }

    /**
     * Private
     */

    /**
     * Updates listing quantity
     *
     * @param string $asin
     * @param int $quantity
     * @return int 1 or 0
     */
    private function updateListingQuantity($asin, $quantity)
    {
        return (int) AmazonMerchantListing::where('asin1', $asin)->update([
            'quantity_available' => $quantity,
        ]);
    }

    /**
     * Updates a row and count of affected rows, basically 1 or 0.
     *
     * @param $row
     * @return integer
     */
    private function updateRow($row, $asin)
    {
        // Truncated error? Hmmm...
        $productName = substr($row[3], 0, 255);

        return AmazonUnsuppressedInventory::where('asin', $asin)->update([
            'sku' => (int) $row[0],
            'fnsku' =>$row[1],
            'product_name' => $productName,
            'condition' =>$row[4],
            'your_price' => (float) $row[5],
            'mfn_listing_exists' =>$row[6],
            'mfn_fulfillable_quantity' => (int) $row[7],
            'afn_listing_exists' =>$row[8],
            'afn_warehouse_quantity' => (int) $row[9],
            'afn_fulfillable_quantity' => (int) $row[10],
            'afn_unsellable_quantity' => (int) $row[11],
            'afn_reserved_quantity' => (int) $row[12],
            'afn_total_quantity' => (int) $row[13],
            'per_unit_volume' => (float) $row[14],
            'afn_inbound_working_quantity' => (int) $row[15],
            'afn_inbound_shipped_quantity' => (int) $row[16],
            'afn_inbound_receiving_quantity' => (int) $row[17],
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
        // Truncated error? Hmmm...
        $productName = substr($row[3], 0, 255);

        $row = AmazonUnsuppressedInventory::create([
            'sku' => (int) $row[0],
            'fnsku' =>$row[1],
            'asin' =>$row[2],
            'product_name' => $productName,
            'condition' =>$row[4],
            'your_price' => (float) $row[5],
            'mfn_listing_exists' =>$row[6],
            'mfn_fulfillable_quantity' => (int) $row[7],
            'afn_listing_exists' =>$row[8],
            'afn_warehouse_quantity' => (int) $row[9],
            'afn_fulfillable_quantity' => (int) $row[10],
            'afn_unsellable_quantity' => (int) $row[11],
            'afn_reserved_quantity' => (int) $row[12],
            'afn_total_quantity' => (int) $row[13],
            'per_unit_volume' => (float) $row[14],
            'afn_inbound_working_quantity' => (int) $row[15],
            'afn_inbound_shipped_quantity' => (int) $row[16],
            'afn_inbound_receiving_quantity' => (int) $row[17],
        ]);

        return $row ? 1 : 0;
    }
}