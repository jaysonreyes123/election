<?php

namespace App\Http\Controllers;

use App\Helper\Dashboardhelper;
use App\Helper\ModuleHelper;
use App\Helper\SqlHelper;
use App\Models\Barangay;
use App\Models\Dashboard;
use App\Models\Precinct;
use App\Models\Report;
use App\Models\Voters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $barangay_model = Barangay::get();
        $precinct_model = Precinct::get();
        $dashboard_model = Report::with(['report_filters_','dashboard_' => function($query){
            return $query->where("pin",1);
        }])
        ->where("type","widget")
        ->where("user_id",Auth::id())
        ->get()
        ->sortBy('dashboard_.sort');
        $widget = "";
        $output = [];
        if(!$dashboard_model->isEmpty()){
            foreach($dashboard_model as $dashboard){
                if($dashboard->dashboard_){
                    $module = ModuleHelper::getModuleName($dashboard->tabid);
                    $table = ModuleHelper::getModuleTable($module);
                    $filter = Dashboardhelper::Filter($table,$dashboard->report_filters_);
                    $query = Dashboardhelper::dashboardQuery($module,$table,$dashboard->dashboard_->columnname,$dashboard->dashboard_->dashboard_type,$filter);
                    $widget = $dashboard->dashboard_->dashboard_type == "count" ? DB::select($query)[0]->widget : number_format(DB::select($query)[0]->widget,2);
                    $sub_output = [];
                    $sub_output["id"] = $dashboard->id;
                    $sub_output["name"] = $dashboard->name;
                    $sub_output["widget"] = $widget;
                    $sub_output["position"] = json_decode($dashboard->dashboard_->position,true);
                    $output[] = $sub_output;
                }
            }   
        }
        return view('content.dashboard', compact(['output']));
    }
    public function update_grid(Request $request){
        foreach($request->widget_id as $key => $widget_id){
            $position = $request->widget_position[$key];
            Dashboard::where("report_id",$widget_id)->update(["position" => $position , "sort" => $key ]);
        }
    }
    public function barangay()
    {
        $models = SqlHelper::BarangayHelper();
        $yes = array();
        $no = array();
        $total = array();
        $categories = array();
        foreach ($models as $model) {
            $yes[] = (int) $model->yes;
            $no[] =  (int) $model->no;
            $categories[] = $model->barangay;
        }
        $max = collect(array_merge($yes, $no))->max();
        return $this->barchart($yes, $no, $max, $categories, true, false, $models->count() * 20);
    }

    public function precinct($barangay)
    {
        $models = SqlHelper::PrecintHelper($barangay);
        $yes = array();
        $no = array();
        $total = array();
        $categories = array();
        foreach ($models as $model) {
            $yes[] = (int) $model->yes;
            $no[] =  (int) $model->no;
            $categories[] = $model->precinct;
        }
        $height = 500;
        if ($models->count() > 10) {
            $height =  $models->count() * 20;
        } else if ($models->count() < 5) {
            $height =  200;
        }
        $max = collect(array_merge($yes, $no))->max();
        return $this->barchart($yes, $no, $max, $categories, true, false, $height);
    }

    public function precinct_piechart($barangay)
    {
        $models = SqlHelper::PrecintHelper($barangay);
        $yes = 0;
        $no = 0;
        foreach ($models as $model) {
            $yes += $model->yes;
            $no += $model->no;
        }
        $series = array($yes, $no);
        $labels = array("Win " . $yes, "Loss " . $no);
        return $this->piechart($series, $labels);
    }

    public function barchart($yes, $no, $max, $categories, $horizontal, $stack, $height)
    {
        $barchart = array();
        $barheight = 0;
        if (count($yes) < 10) {
            $barheight = 30;
        } else {
            $barheight = 80;
        }
        $barheight .= "%";

        $barchart["series"] = array(array("name" => "yes", "data" => $yes), array("name" => "no", "data" => $no));
        $barchart["colors"] = array("rgb(0, 227, 150)", "rgb(255, 76, 81)", "#FFB700");
        $barchart["chart"] = array("type" => "bar", "height" => $height, "stacked" => $stack);
        $barchart["plotOptions"] = array("bar" => array("horizontal" => $horizontal, "barHeight" => $barheight, "endingShape" => "rounded", "dataLabels" => array('position' => 'top')));
        $barchart["dataLabels"] = array("enabled" => true, "offsetX" => 20, "style" => array('colors' => array('rgb(0, 227, 150)', 'rgb(255, 76, 81)'), "fontSize" => "9px"));
        $barchart["stroke"] = array("show" => true, "width" => 2, "colors" => array("#fff"));
        $barchart["xaxis"] = array("categories" => $categories, "max" => $max * 2);
        $barchart["fill"] = array("opacity" => 1);
        $barchart["tooltip"] = array("intersect" => false, "shared" => true);

        return $barchart;
    }

    public function piechart($series, $labels)
    {
        $piechart["series"] = $series;
        $piechart["colors"] = array("rgb(0, 227, 150)", "rgb(255, 76, 81)");
        $piechart["chart"] = array("type" => "pie", "height" => '220%');
        $piechart["labels"] = $labels;
        $piechart["responsive"] = array(
            array(
                "breakpoint" => 480,
                "options" => array(
                    "chart" => array("width" => 200),
                ),
                "legend" => array("position" => "bottom")
            )
        );
        return $piechart;
    }
}
