<?php

namespace App\Http\Controllers;

use App\Models\UserPrivilege;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserPrivilegesController extends Controller
{
    //
    public function index()
    {
        return view('content.admin.user-privileges');
    }

    public function list($roleid)
    {
        $model = UserPrivilege::where('roleid', $roleid)->with('tabs_')->get();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function () {
                return "<a href='javascript:void(0)' class='edit' ><span class='bi bi-pen-fill text-warning'></span></a>";
            })
            ->addColumn("create_", function ($item) {
                $item_ = "";
                if ($item->create == 1) {
                    $item_ = "<span class='bi bi-check2 text-success'></span>";
                } else {
                    $item_ = "<span class='bi bi-x text-danger'></span>";
                }
                return $item_;
            })
            ->addColumn("edit_", function ($item) {
                $item_ = "";
                if ($item->edit == 1) {
                    $item_ = "<span class='bi bi-check2 text-success'></span>";
                } else {
                    $item_ = "<span class='bi bi-x text-danger'></span>";
                }
                return $item_;
            })
            ->addColumn("delete_", function ($item) {
                $item_ = "";
                if ($item->delete == 1) {
                    $item_ = "<span class='bi bi-check2 text-success'></span>";
                } else {
                    $item_ = "<span class='bi bi-x text-danger'></span>";
                }
                return $item_;
            })
            ->addColumn("import_", function ($item) {
                $item_ = "";
                if ($item->import == 1) {
                    $item_ = "<span class='bi bi-check2 text-success'></span>";
                } else {
                    $item_ = "<span class='bi bi-x text-danger'></span>";
                }
                return $item_;
            })
            ->rawColumns(['create_', 'edit_', 'delete_', 'import_', 'action'])
            ->make(true);
    }

    public function save(Request $request)
    {
        $id = $request->id;
        $model = null;
        if ($id == "") {
            $model = new UserPrivilege;
        } else {
            $model = UserPrivilege::find($id);
        }

        $model->create = $request->create;
        $model->edit = $request->edit;
        $model->delete = $request->delete_;
        $model->import = $request->import_;
        $model->save();
        return $model;
    }
}
