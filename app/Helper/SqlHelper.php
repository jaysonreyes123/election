<?php

namespace App\Helper;

use App\Models\Voters;
use Illuminate\Support\Facades\DB;

class SqlHelper
{
    public static function BarangayHelper()
    {
        $model = Voters::select("*", DB::raw('sum(if(vote=1,1,0)) as yes'), DB::raw('sum(if(vote=0,1,0)) as no'))
            ->groupBy('barangay')
            ->get();
        return $model;
    }

    public static function PrecintHelper($barangay)
    {
        $model = Voters::select("*", DB::raw('sum(if(vote=1,1,0)) as yes'), DB::raw('sum(if(vote=0,1,0)) as no'))
            ->where('barangay', $barangay)
            ->groupBy('precinct')
            ->get();
        return $model;
    }
    public static function get_barangay()
    {
        $model = Voters::select("barangay")->groupBy("barangay")->get();
        return $model;
    }
    public static function get_count($value = "")
    {
        $model = "";
        if ($value == "" || $value == "votes") {
            $model = Voters::get();
        } elseif ($value == "barangay") {
            $model = Voters::groupBy('barangay')->get();
        } elseif ($value == "precinct") {
            $model = Voters::groupBy('precinct')->get();
        } elseif ($value == "yes") {
            $model = Voters::where("vote", 1)->get();
        } elseif ($value == "no") {
            $model = Voters::where("vote", 0)->get();
        }
        return $model->count();
    }
}
