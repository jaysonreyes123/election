<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UserControllers extends Controller
{
    public function view()
    {
        return view('content.admin.user');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
