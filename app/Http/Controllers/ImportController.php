<?php

namespace App\Http\Controllers;

use App\Constant\ImportConstant;
use App\Helper\FieldHelper;
use App\Helper\ModuleHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class ImportController extends Controller
{
    //
    public function import_step1(Request $request)
    {
        $module = $request->module;
        $files = $request->file('import_file');
        $import_data = Excel::toArray(collect([]), $files);
        $data = [];
        $header = [];
        $no_data = array();
        $hasheader = $request->hasheader;
        $header[] = $import_data[0][0];
        $new_data = [];
        foreach ($import_data[0][0] as $val) {
            $no_data[] = "";
        }
        foreach($import_data[0][$hasheader] as $data_){
            if($data_ != null){
                $new_data[] = $data_;
            }
        }
        $data[] = $new_data?? $no_data;
        return $this->table($hasheader, $header, $data[0], $module);
    }

    public function table($hasheader, $header, $data, $module)
    {
        $column = count($data);
        $table = "<table class='table'>";
        $table .= "<tr>";
        if ($hasheader == 1) {
            $table .= "<th>Header</th>";
        }
        $table .= "<th>Row 1</th>";
        $table .= "<th>Fields</th>";
        $table .= "</tr>";
        for ($a = 0; $a < $column; $a++) {
            $table .= "<tr>";
            if ($hasheader == 1) {
                $table .= "<td>" . $header[0][$a] . "</td>";
            }
            $table .= "<td>" . $data[$a] ?? "" . "</td>";
            $table .= "<td>" . $this->fields($module) . "</td>";
            $table .= "</tr>";
        }
        $table .= "<input type='hidden' id='required_field' value='" . implode(",", $this->requiredFields($module)) . "' ></table>";
        return $table;
    }

    public function fields($module)
    {
        $module_id = ModuleHelper::getModuleID($module);
        $fields = FieldHelper::getfieldModule($module_id);

        $select = "<select class='form-select import-select'>
            <option value=''>Select an option</option>
        ";
        foreach ($fields as $field) {
            $required = $field->mandatory == 1 ? "<span class='text-danger'>*</span>" : "";
            $select .= "<option value='$field->columnname'>$field->label $required</option>";
        }
        $select .= "</select>";
        return $select;
    }
    public function requiredFields($module)
    {
        $module_id = ModuleHelper::getModuleID($module);
        $fields = FieldHelper::getfieldModule($module_id);
        $required = array();
        foreach ($fields as $field) {
            if ($field->mandatory == 1) {
                $required[] = $field->columnname;
            }
        }
        return $required;
    }
    public function save(Request $request)
    {
        $module = $request->module;
        $files = $request->file('import_file');
        $import_datas = Excel::toArray([],$files);
        $fields = explode(",", $request->fields);
        $is_one_field = 0;
        foreach($fields as $key => $field_){
            if($field_ != ""){
                $is_one_field++;
            }
        }

        $table = ModuleHelper::getModuleTable($module);
        $moduleid = ModuleHelper::getModuleID($module);
        $import_table = "import_user_" . $table . "_" . Auth::id();
        if (Schema::hasTable($import_table)) {
            Schema::dropIfExists($import_table);
        }


        $createtable = "CREATE table $import_table 
        ( id int not null PRIMARY KEY AUTO_INCREMENT,`status` int(2) default null";
        foreach ($fields as $field) {
            if ($field != "") {
                $createtable .= ", " . $field . " varchar(200) default null";
            }
        }
        $createtable .= ")";
        DB::statement($createtable);
        //remove header
        if ($request->hasheader == 1) {
            unset($import_datas[0][0]);
        }
        foreach ($import_datas[0] as $import_data) {
            $insert = array();
            $insert_with_status = array();
            $status = ImportConstant::CREATE;
            if($is_one_field == 1){
                $import_data_ = implode(" ",$import_data);
            }
            else{
               $import_data_ = $import_data[$key];
            }
            foreach ($fields as $key => $field) {
                $getfieldDetails = FieldHelper::getSingleFieldModule($moduleid, $field);
                if ($field != "") {
                    $insert[$field] = trim($import_data_);
                    $insert_with_status[$field] =trim($import_data_);
                    if ($getfieldDetails->type == "integer") {
                        if (!is_numeric($import_data_)) {
                            $status = ImportConstant::NUMERIC_STATUS;
                        }
                    } elseif ($getfieldDetails->type == "date") {
                        $parse = explode("/", $import_data_);
                        if (count($parse) != 3) {
                            $status = ImportConstant::DATE_STATUS;
                        }
                    }
                }
            }

            try {
                DB::table($table)->insert($insert);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
            $insert_with_status["status"] = $status;
            DB::table($import_table)->insert($insert_with_status);
        }
    }
}
