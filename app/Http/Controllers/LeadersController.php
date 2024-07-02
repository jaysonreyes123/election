<?php

namespace App\Http\Controllers;

use App\Helper\FieldHelper;
use App\Helper\ModuleHelper;
use App\Models\Barangay;
use App\Models\Leaders;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToArray;
use Barryvdh\DomPDF\Facade\Pdf;

class LeadersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $model = Leaders::where('barangay',$id)->first();
        $module_id = ModuleHelper::getModuleID('Leaders');
        $fields = [];
        if($model != ""){
            foreach(json_decode($model) as $key => $val){
                $parse = explode("_",$key);
                if(count($parse) == 2){
                    if($parse[1] == "leader"){
                        $fields_label = FieldHelper::getSingleFieldModule($module_id,$key);
                        $fields[$fields_label->label] = $val;
                    }
                }
            }
        }
        
        $model2 = Barangay::where('name',$id)->first();
        $module_id2 = ModuleHelper::getModuleID('Barangays');
        $fields2 = [];
        $image = "";
        if($model2 != ""){
            foreach(json_decode($model2) as $key => $val){
                $fields_label2 = FieldHelper::getSingleFieldModule($module_id2,$key);
                
                if(!empty($fields_label2)){
                    if($fields_label2->type == "integer"){
                        if($val != ""){
                            $fields2[$fields_label2->label] = $val;
                        }   
                    }
                    else if($fields_label2->type == "file"){
                        $image = $val;
                    }
                }
            }
        }
        return Pdf::loadView('content.admin.report.print_barangay',
        [
        "table1" => $fields,
        "table2" => $fields2,
        "barangay" => $id,
        "barangay_map" => $image
        ])
        ->setPaper('a4')
        ->stream("$id.pdf");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
