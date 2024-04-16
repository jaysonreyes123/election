<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Helper\FieldHelper;
use App\Helper\ModuleHelper;
use App\Helper\ReportHelper;
use App\Models\Report;
use App\Models\ReportFilter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    //
    public function index()
    {
        return view('content.admin.report');
    }
    public function list()
    {
        $model = Report::with(['module_', 'report_filters_'])->get();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn("action", function ($item) {
                $modulename = \App\Helper\ModuleHelper::getModuleName($item->tabid);
                $reportid = $item->id;
                return "
                <a href='/report/$modulename/$reportid'><span class='bi bi-eye-fill text-primary'></span></a>
                <a href='javascript:void(0)' class='edit' style='margin-left:20px' ><span class='bi bi-pen-fill text-warning'></span></a>
                ";
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function save(Request $request)
    {

        $id = $request->id;
        $request->validate([
            "name" => "required",
            "module" => "required",
            "column" => "required"
        ]);
        $model = null;
        if ($id == "") {
            $model = new Report;
        } else {
            $model = Report::find($id);
        }
        $model->tabid = $request->module;
        $model->name = $request->name;
        $model->column = implode(",", $request->column);
        $model->save();
        if ($model->save()) {
            $report_id = $model->id;
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

    public function export($module, $extenstion, $id)
    {

        $moduleid = ModuleHelper::getModuleID($module);
        $report_details = Report::where('id', $id,)->where('tabid', $moduleid)->first();
        $headers = explode(",", $report_details->column);

        $header_label = array();
        foreach ($headers as $header) {
            $header_helper = FieldHelper::getSingleFieldModule($moduleid, $header);
            $header_label[] = $header_helper->label;
        }
        $report_model = $this->reportDetails($module, $report_details->column, $report_details->report_filters_);

        if ($extenstion == "pdf") {
            $pdf = Pdf::loadView('content.admin.report.report', [
                "headers" => $headers,
                "header_label" => $header_label,
                "report_model" => $report_model
            ])->setPaper('a4', 'portrait');
            return $pdf->download("$report_details->name.pdf");
        } else {
            return Excel::download(new ReportExport($headers, $header_label, $report_model), "$report_details->name.$extenstion");
        }
    }
    public function view($module, $id)
    {
        $limit = 100;
        $moduleid = ModuleHelper::getModuleID($module);
        $report_details = Report::with('report_filters_')->where('id', $id,)->where('tabid', $moduleid)->first();
        $headers = explode(",", $report_details->column);
        $report_model = $this->reportDetails($module, $report_details->column, $report_details->report_filters_, $limit);
        return view('content.admin.report_view', compact(['module', 'report_details', 'id', 'headers', 'moduleid', 'report_model']));
    }
    public function reportDetails($module, $column, $filter, $limit = "")
    {

        $table = ModuleHelper::getModuleTable($module);
        $column = ReportHelper::queryColumn($table, $column);
        $filter = ReportHelper::reportFilter($table, $filter);
        $report_query = ReportHelper::reportQuery($table, $column, $filter, $limit);
        $report_model = DB::select($report_query);
        return $report_model;
    }

    public function get_fields($module)
    {
        $fields = FieldHelper::getfieldModule($module);
        return $fields;
    }
}
