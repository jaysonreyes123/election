<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public function module_()
    {
        return $this->hasOne(Tab::class, 'id', 'tabid');
    }
    public function report_filters_()
    {
        return $this->hasMany(ReportFilter::class, 'report_id', 'id');
    }
}
