<?php

namespace App\Console\Commands;

use App\AmazonMerchantListing;
use App\AmazonMws;
use Illuminate\Console\Command;

class ShowInventoryFetchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:show-inventory-fetch-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows total of merchant listing with or without quantity';

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

        $totalNullCount = 0;
        $totalWithDataCount = 0;

        $this->info(
            str_pad('USER', 7) .
            str_pad('NAME', 30) .
            str_pad('MERCHANT ID', 15) .
            str_pad('WITH DATA', 15, ' ', STR_PAD_LEFT) .
            str_pad('NULL', 10, ' ', STR_PAD_LEFT)
        );

        foreach ($mws as $row) {

            $nullCount = $this->getCount($row->user_id, true);
            $totalNullCount += $nullCount;

            $withDataCount = $this->getCount($row->user_id, false);
            $totalWithDataCount += $withDataCount;

            $this->info(
                str_pad($row->user_id, 7) .
                str_pad($row->user->name, 30) .
                str_pad($row->merchant_id, 15) .
                str_pad($withDataCount, 15, ' ', STR_PAD_LEFT) .
                str_pad($nullCount, 10, ' ', STR_PAD_LEFT)
            );
        }

        $this->info(
            str_pad('Total', 7) .
            str_pad('', 30) .
            str_pad('', 15) .
            str_pad($totalWithDataCount, 15, ' ', STR_PAD_LEFT) .
            str_pad($totalNullCount, 10, ' ', STR_PAD_LEFT)
        );
    }

    /**
     * Private
     */

    private function getCount($userId, $isNull)
    {
        $builder = AmazonMerchantListing::where('user_id', $userId);

        if ($isNull) {
            $builder->where('afn_asin', null);
        } else {
            $builder->where('afn_asin', '!=', null);
        }

        return $builder->count();
    }
}
