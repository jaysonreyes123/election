<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    //
    public function index()
    {
        return view('content.admin.user');
    }
    public function list()
    {
        $model = User::where('id', '<>', Auth::id())->with('roles_')->get();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function () {
                return "<a href='javascript:void(0)' class='edit' ><span class='bi bi-pen-fill text-warning'></span></a>
                <a href='javascript:void(0)' class='delete' class='ml-5'><span class='bi bi-trash-fill text-danger'></span></a>
                ";
            })
            ->addColumn("status_", function ($item) {
                $status = "";
                if ($item->status == "0") {
                    $status = "<span class='badge bg-danger'>Inactive</span>";
                } else {
                    $status = "<span class='badge bg-success'>Active</span>";
                }
                return $status;
            })
            ->rawColumns(['action', 'status_'])
            ->make(true);
    }
    public function save(Request $request)
    {
        $id = $request->id;
        $model = null;
        if ($id == "") {
            $request->validate([
                "firstname" => "required",
                "lastname"  => "required",
                "password"  => "required",
                "username" => ['required', Rule::unique('users', 'username')->ignore($id, 'id')],
                "email" => ['required', 'email', Rule::unique('users', 'email')->ignore($id, 'id')],
            ]);

            $model = new User;
            $model->password = Hash::make($request->password);
        } else {
            $model = User::find($id);
            $request->validate([
                "firstname" => "required",
                "lastname"  => "required",
                "username" => ['required', Rule::unique('users', 'username')->ignore($id, 'id')],
                "email" => ['required', 'email', Rule::unique('users', 'email')->ignore($id, 'id')],
            ]);
        }


        $model->firstname = $request->firstname;
        $model->lastname = $request->lastname;
        $model->username = $request->username;
        $model->email = $request->email;
        $model->role = $request->role;
        $model->status = $request->status;
        $model->save();
        return $model;
    }

    public function profile()
    {
        $user_model = User::where('id', Auth::id())->first();
        return view('content.user_profile', compact(['user_model']));
    }
    public function profile_save(Request $request)
    {
        $request->validate([
            "firstname" => "required",
            "lastname" => "required",
            "username" => ["required", Rule::unique('users', 'username')->ignore(Auth::id(), 'id')],
            "email" => ["required", "email", Rule::unique('users', 'email')->ignore(Auth::id(), 'id')],
        ]);

        $model = User::find(Auth::id())->first();
        $model->firstname = $request->firstname;
        $model->lastname = $request->lastname;
        $model->username = $request->username;
        $model->email  = $request->email;
        $model->save();
        return response()->json([], 200);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            "password" => "required",
            "new_password" => "required|same:confirmation_password",
            "confirmation_password" => "required"
        ]);
        $password = User::find(Auth::id())->first();
        $message = [];
        $status = 200;
        if (!Hash::check($request->password, $password->password)) {
            $message["errors"] = ["password" => ["Incorrect password"]];
            $status = 442;
        }

        $password->password = Hash::make($request->new_password);
        $password->save();
        return response()->json($message, $status);
    }
}
