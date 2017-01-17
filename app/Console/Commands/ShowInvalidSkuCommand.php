<?php

namespace App\Console\Commands;

use App\AmazonMerchantListing;
use App\AmazonMws;
use Illuminate\Console\Command;

class ShowInvalidSkuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:show-sku-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows total of sku invalid totals';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mws = AmazonMws::all();

        $totalZeroCount = 0;
        $totalValidCount = 0;

        $this->info(
            str_pad('USER', 7) .
            str_pad('NAME', 30) .
            str_pad('MERCHANT ID', 15) .
            str_pad('VALID SKU', 15, ' ', STR_PAD_LEFT) .
            str_pad('ZERO SKU', 15, ' ', STR_PAD_LEFT)
        );

        foreach ($mws as $row) {

            $zeroCount = $this->getCount($row->user_id, true);
            $totalZeroCount += $zeroCount;

            $validCount = $this->getCount($row->user_id, false);
            $totalValidCount += $validCount;

            $this->info(
                str_pad($row->user_id, 7) .
                str_pad($row->user->name, 30) .
                str_pad($row->merchant_id, 15) .
                str_pad($validCount, 15, ' ', STR_PAD_LEFT) .
                str_pad($zeroCount, 15, ' ', STR_PAD_LEFT)
            );
        }

        $this->info(
            str_pad('Total', 7) .
            str_pad('', 30) .
            str_pad('', 15) .
            str_pad($totalValidCount, 15, ' ', STR_PAD_LEFT) .
            str_pad($totalZeroCount, 15, ' ', STR_PAD_LEFT)
        );
    }

    /**
     * Private
     */

    private function getCount($userId, $isZero)
    {
        $builder = AmazonMerchantListing::where('user_id', $userId);

        if ($isZero) {
            $builder->where('seller_sku', 0);
        } else {
            $builder->where('seller_sku', '!=', 0);
        }

        return $builder->count();
    }
}
