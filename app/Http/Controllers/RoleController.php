<?php

namespace App\Http\Controllers;

use App\Helper\CreateUserPrivileges;
use App\Models\Role;
use App\Models\Tab;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    //
    public function index()
    {
        return view('content.admin.role');
    }
    public function list()
    {
        $model = Role::all();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn("action", function () {
                return "<a href='javascript:void(0)' class='edit' ><span class='bi bi-pen-fill text-warning'></span></a>";
            })
            ->rawColumns(['create_', 'edit_', 'delete_', 'import_', 'action'])
            ->make(true);
    }
    public function save(Request $request)
    {
        $id = $request->id;
        $request->validate([
            "role" => "required"
        ]);
        $model = null;
        if ($id == "") {
            $model = new Role;
        } else {
            $model = Role::find($id);
        }
        $model->name = $request->role;
        if ($model->save()) {
            if ($id == "") {
                $tab_models = Tab::all();
                foreach ($tab_models as $tab_model) {
                    CreateUserPrivileges::save($tab_model->id, $model->id, 1, 1, 1, 1);
                }
            }
        }
        return $model;
    }
}
