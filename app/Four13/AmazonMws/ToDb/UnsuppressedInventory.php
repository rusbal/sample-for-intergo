<?php

namespace Four13\AmazonMws\ToDb;


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
    private function updateRow($row, $asin)
    {
        return AmazonUnsuppressedInventory::where('asin', $asin)->update([
            'sku' => (int) $row[0],
            'fnsku' =>$row[1],
            'product_name' =>$row[3],
            'condition' =>$row[4],
            'your_price' => (float) $row[5],
            'mfn_listing_exists' =>$row[6],
            'mfn_fulfillable_quantity' =>$row[7],
            'afn_listing_exists' =>$row[8],
            'afn_warehouse_quantity' => (float) $row[9],
            'afn_fulfillable_quantity' => (float) $row[10],
            'afn_unsellable_quantity' => (float) $row[11],
            'afn_reserved_quantity' => (float) $row[12],
            'afn_total_quantity' => (float) $row[13],
            'per_unit_volume' => (float) $row[14],
            'afn_inbound_working_quantity' => (float) $row[15],
            'afn_inbound_shipped_quantity' => (float) $row[16],
            'afn_inbound_receiving_quantity' => (float) $row[17],
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
        $row = AmazonUnsuppressedInventory::create([
            'sku' => (int) $row[0],
            'fnsku' =>$row[1],
            'asin' =>$row[2],
            'product_name' =>$row[3],
            'condition' =>$row[4],
            'your_price' => (float) $row[5],
            'mfn_listing_exists' =>$row[6],
            'mfn_fulfillable_quantity' =>$row[7],
            'afn_listing_exists' =>$row[8],
            'afn_warehouse_quantity' => (float) $row[9],
            'afn_fulfillable_quantity' => (float) $row[10],
            'afn_unsellable_quantity' => (float) $row[11],
            'afn_reserved_quantity' => (float) $row[12],
            'afn_total_quantity' => (float) $row[13],
            'per_unit_volume' => (float) $row[14],
            'afn_inbound_working_quantity' => (float) $row[15],
            'afn_inbound_shipped_quantity' => (float) $row[16],
            'afn_inbound_receiving_quantity' => (float) $row[17],
        ]);

        return $row ? 1 : 0;
    }
}