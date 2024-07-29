<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Report;
use App\Models\ReportFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DashboardMaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('content.dashboard_maintenance');
    }
    public function list(){
        $model = Report::with(['module_', 'report_filters_',"dashboard_"])->where('type',"widget")->where("user_id",Auth::id())->get();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn("action", function ($item) {
                $button = "<a href='javascript:void(0)' title='show' class='show' style='margin-left:20px' ><span class='bi bi-eye-slash-fill text-primary'></span></a>";
                if($item->dashboard_->pin == 1){
                    $button = "<a href='javascript:void(0)' title='hide' class='hide' style='margin-left:20px' ><span class='bi bi-eye-fill text-primary'></span></a>";
                }
                return "
                    $button
                    <a href='javascript:void(0)' class='edit' style='margin-left:20px' ><span class='bi bi-pen-fill text-warning'></span></a>
                    <a href='javascript:void(0)' title='delete' class='delete' style='margin-left:20px' ><span class='bi bi-trash text-danger'></span></a>
                    
                ";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            "module" => "required",
            "name" => "required",
            "type" => "required"
        ]);
        
        $id = $request->id;
        $model = null;
        if ($id == "") {
            $model = new Report;
        } else {
            $model = Report::find($id);
        }
        $model->tabid = $request->module;
        $model->name = $request->name;
        $model->type = "widget";
        $model->user_id = Auth::id();
        $model->save();
        if ($model->save()) {
            $report_id = $model->id;

            $dashboard_model_check = Dashboard::where("report_id",$report_id)->first();
            $dashboard_model = new Dashboard();
            if($dashboard_model_check){
                $dashboard_model = Dashboard::where("report_id",$report_id)->first();
            }
            $value = "";
            $column = "";
            if($request->type == "count"){
                $value = $request->type;
            }
            else{
                $type_ = explode("-",$request->type);
                $column = $type_[0];
                $value = $type_[1];
            }
            $dashboard_model->report_id = $report_id;
            $dashboard_model->columnname  = $column;
            $dashboard_model->dashboard_type  = $value;
            $dashboard_model->position = '{"w":3,"h":0}';
            $dashboard_model->sort = Report::where('type','widget')->where("user_id",Auth::id())->count();
            $dashboard_model->save();

            ReportFilter::where('report_id', $report_id)->delete();
            if (isset($request->and_field)) {
                foreach ($request->and_field as $key => $val) {
                    if ($val != null && $val != "") {
                        $report_filter_model = new ReportFilter;
                        $report_filter_model->report_id = $report_id;
                        $report_filter_model->columnname = $val;
                        $report_filter_model->filter_type = $request->and_operator[$key];
                        $report_filter_model->filter_value = $request->and_value[$key] == null ? "" : $request->and_value[$key];
                        $report_filter_model->operator = "and";
                        $report_filter_model->save();
                    }
                }
            }

            if (isset($request->or_field)) {
                foreach ($request->or_field as $key => $val) {
                    if ($val != null && $val != "") {
                        $report_filter_model = new ReportFilter;
                        $report_filter_model->report_id = $report_id;
                        $report_filter_model->columnname = $val;
                        $report_filter_model->filter_type = $request->or_operator[$key];
                        $report_filter_model->filter_value = $request->or_value[$key] == null ? "" : $request->or_value[$key];
                        $report_filter_model->operator = "or";
                        $report_filter_model->save();
                    }
                }
            }
        }
        return $model;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Dashboard::where("report_id",$id)->update(["pin" => $request->pin]);
        return response()->json([],200);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Report::find($id)->delete();
        return response()->json([],200);
    }
}
