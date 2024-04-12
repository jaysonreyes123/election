<?php

namespace App\Http\Controllers;

use App\Helper\CreateUserPrivileges;
use App\Helper\ModuleHelper;
use App\Models\Role;
use App\Models\Tab;
use App\Models\User;
use App\Models\UserPrivilege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class TabsController extends Controller
{
    //
    public function index()
    {
        return view("content.admin.tab");
    }
    public function list()
    {
        $model = Tab::all();
        return DataTables::of($model)
            ->addIndexColumn()
            // ->addColumn("action", function () {
            //     return "<a href='javascript:void(0)' class='edit' ><span class='bi bi-pen-fill text-warning'></span></a>";
            // })
            // ->addColumn("status", function ($item) {
            //     $status = "";
            //     if ($item->status == "0") {
            //         $status = "<span class='badge bg-danger'>Inactive</span>";
            //     } else {
            //         $status = "<span class='badge bg-success'>Active</span>";
            //     }
            //     return $status;
            // })
            // ->rawColumns(['status', 'action'])
            ->make(true);
    }
    public function save(Request $request)
    {
        $id = $request->get("id");
        $model = null;
        $request->validate([
            "name" => Rule::unique('tabs', 'name')->ignore($id, 'id')
        ]);
        if ($id == "") {
            $model = new Tab;
        } else {
            $model = Tab::find($id);
        }

        $model->name = $request->get("name");
        $model->status = $request->get("status");
        if ($model->save()) {
            if ($id == "") {
                $tabid = $model->id;
                $role_models = Role::all();
                //create user privilege per role 
                foreach ($role_models as $role_model) {
                    CreateUserPrivileges::save($tabid, $role_model->id, 1, 1, 1, 1);
                }
            }


            $module = $request->get("name");
            $table = ModuleHelper::getModuleTable($module);
            $primary = ModuleHelper::getModuleKey($module);
            $create_table = "CREATE TABLE $table (
                $primary int not null PRIMARY KEY AUTO_INCREMENT,
                is_delete int DEFAULT 0 
            )";
            if (!Schema::hasTable($table)) {
                DB::statement($create_table);
            }
        }
        return $model;
    }
    public function delete($id)
    {
        $user =  Tab::findOrFail($id);
        $user->delete();
    }
}
