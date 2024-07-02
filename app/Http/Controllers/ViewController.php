<?php

namespace App\Http\Controllers;

use App\Helper\BlockHelper;
use App\Helper\FieldHelper;
use App\Helper\ModuleHelper;
use App\Helper\UserPrivilegesHelper;
use App\Models\Field;
use App\Models\UserPrivilege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ViewController extends Controller
{
    //
    public function index($module)
    {
        $getModuleID = ModuleHelper::getModuleID($module);
        $user_privileges = UserPrivilegesHelper::getUserPrivileges($getModuleID);
        $field_column = FieldHelper::getColumn($getModuleID);
        return view(
            'content.view',
            compact([
                'module',
                'getModuleID',
                'user_privileges',
                'field_column'
            ])
        );
    }
    public function list($module)
    {
        $table = ModuleHelper::getModuleTable($module);
        $getModuleID = ModuleHelper::getModuleID($module);
        $user_privileges = UserPrivilegesHelper::getUserPrivileges($getModuleID);

        $model =  DB::table($table)->where('is_delete', 0)->get();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($item) use ($user_privileges, $module) {
                $id_ = ModuleHelper::getModuleKey($module);
                $id = $item->$id_;
                $button = "<a title='View Details' href='/view/module/$module/$id' class='detail' ><span class='bi bi-eye-fill text-primary p-2'></span></a>";
                if ($user_privileges->edit == 1) {
                    $button .= "<a title='Edit' href='/edit/module/$module/$id' class='edit' ><span class='bi bi-pen-fill text-warning p-2'></span></a>";
                }
                if($module == "Barangays"){
                    $button .= "<a title='Print' href='javascript:void(0)' data-barangay='".$item->name."' class='print' ><span class='bi bi-file-earmark text-success p-2'></span></a>";
                }
                if ($user_privileges->delete == 1) {
                    $button .= "<a title='Delete' href='javascript:void(0)' data-redirect='/delete/module/$module/$id' class='delete' ><span class='bi bi-trash text-danger p-2'></span></a>";
                }
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function detail($module, $id)
    {
        $getModuleID = ModuleHelper::getModuleID($module);
        $getBlock = BlockHelper::getBlockModule($getModuleID);
        $table = ModuleHelper::getModuleTable($module);
        $id_ = ModuleHelper::getModuleKey($module);
        $field_data = DB::table($table)->where($id_, $id)->first();
        return view('content.detail', compact(['module', 'id', 'getBlock', 'getModuleID', 'field_data']));
    }
    public function edit($module, $id = "")
    {
        $getModuleID = ModuleHelper::getModuleID($module);
        $getBlock = BlockHelper::getBlockModule($getModuleID);
        $table = ModuleHelper::getModuleTable($module);
        $id_ = ModuleHelper::getModuleKey($module);
        $field_data = DB::table($table)->where($id_, $id)->first();
        return view('content.edit', compact(['module', 'id', 'getBlock', 'getModuleID', 'field_data']));
    }
    public function save(Request $request)
    {
        $module = $request->module;
        $getModuleID = ModuleHelper::getModuleID($module);
        $table = ModuleHelper::getModuleTable($module);
        $id_ = ModuleHelper::getModuleKey($module);
        $fields = FieldHelper::getfieldModule($getModuleID);
        $rules = array();
        $data = array();
        $error_name = array();
        foreach ($fields as $field) {
            $rule_array = array();
            if ($field->mandatory == 1) {
                $rule_array[] = "required";
            }
            if ($field->datatype == "int" && $field->mandatory == 1) {
                $rule_array[] = "numeric";
            }
            if (count($rule_array) > 0) {
                $rules[$field->columnname] = implode("|", $rule_array);
            }
            $error_name[$field->columnname] = $field->label;
            $data[$field->columnname] = $request->get($field->columnname);
        }

        $request->validate($rules);
        $primaryKey = ModuleHelper::getModuleKey($module);
        $id = $request->id;
        $id__ = $id;
        if ($id == "") {
            $insert = DB::table($table)->insertGetId($data);
            $id__ = $insert;
        } else {
            DB::table($table)->where($primaryKey, $id)->update($data);
        }
        $reponse = array("message" => "succesfully saved", "redirect" => "/view/module/$module/$id__");
        return response()->json($reponse, 200);
    }
    public function delete($module, $id)
    {
        $primaryKey = ModuleHelper::getModuleKey($module);
        $table = ModuleHelper::getModuleTable($module);
        DB::table($table)->where($primaryKey, $id)->update(["is_delete" => 1]);
        return redirect("/view/module/$module");
    }
}
