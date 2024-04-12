<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;

class SqlHelper
{
    public static function BarangayHelper()
    {
        $model = DB::table('voters_table')->select("*", DB::raw('sum(if(vote=1,1,0)) as yes'), DB::raw('sum(if(vote=0,1,0)) as no'))
            ->groupBy('barangay')
            ->get();
        return $model;
    }

    public static function PrecintHelper($barangay)
    {
        $model = DB::table('voters_table')->select("*", DB::raw('sum(if(vote=1,1,0)) as yes'), DB::raw('sum(if(vote=0,1,0)) as no'))
            ->where('barangay', $barangay)
            ->groupBy('precinct')
            ->get();
        return $model;
    }
    public static function get_barangay()
    {
        $model = DB::table('voters_table')->select("barangay")->groupBy("barangay")->get();
        return $model;
    }
    public static function get_count($value = "")
    {
        $model = "";
        if ($value == "" || $value == "votes") {
            $model = DB::table('voters_table')->get();
        } elseif ($value == "barangay") {
            $model = DB::table('voters_table')->groupBy('barangay')->get();
        } elseif ($value == "precinct") {
            $model = DB::table('voters_table')->groupBy('precinct')->get();
        } elseif ($value == "yes") {
            $model = DB::table('voters_table')->where("vote", 1)->get();
        } elseif ($value == "no") {
            $model = DB::table('voters_table')->where("vote", 0)->get();
        }
        return $model->count();
    }
}
