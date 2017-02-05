<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PriceViolationReportGenerated extends Mailable
{
    use Queueable, SerializesModels;

    public $reportData;
    public $reportTitle;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reportData, $reportTitle)
    {
        $this->reportData = $reportData;
        $this->reportTitle = $reportTitle;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("[" . config('app.name') . "] {$this->reportTitle}")
            ->view('emails.report-price-violation');
    }
}
