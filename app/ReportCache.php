<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportCache extends Model
{
    protected $fillable = [
        'date_days_report_user_id',
        'data',
        'start_date',
        'report',
        'n_days',
        'user_id',
    ];
}
