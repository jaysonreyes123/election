<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TabsController;
use App\Http\Controllers\UserController as UserControllers;
use App\Http\Controllers\UserPrivilegesController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get("/login", [LoginController::class, 'index'])->name('login');
Route::get("/logout", [LoginController::class, 'logout']);
Route::post("/login-process", [LoginController::class, 'login']);

Route::group(["middleware" => 'auth'], function () {

    Route::get("/dashboard", function () {
        return view('content.dashboard');
    });

    //users
    Route::group(["prefix" => "users"], function () {
        Route::get("/",  [UserControllers::class, 'index']);
        Route::get("/list",  [UserControllers::class, 'list']);
        Route::post("/save", [UserControllers::class, 'save']);
    });


    //role
    Route::group(["prefix" => "roles"], function () {
        Route::get("/",  [RoleController::class, 'index']);
        Route::get("/list",  [RoleController::class, 'list']);
        Route::post("/save", [RoleController::class, 'save']);
    });

    //userprivileges
    Route::group(["prefix" => "user-privileges"], function () {
        Route::get("/", [UserPrivilegesController::class, 'index']);
        Route::get("/list/{roleid}", [UserPrivilegesController::class, 'list']);
        Route::post("/save", [UserPrivilegesController::class, 'save']);
    });

    //barangay map
    Route::get("/barangay-map", function () {
        return view('content.barangay_map');
    });


    //tab
    Route::group(["prefix" => "tab"], function () {
        Route::get("/",      [TabsController::class, 'index']);
        Route::get("/list", [TabsController::class, 'list']);
        Route::post("/save", [TabsController::class, 'save']);
        Route::get("/delete/{id}", [TabsController::class, 'delete']);
    });


    //fields
    Route::group(['prefix' => 'field'], function () {
        Route::get("/", [FieldController::class, 'index']);
        Route::post("/save-block", [FieldController::class, 'save']);
        Route::post("/save-field",  [FieldController::class, 'save_field']);
    });

    //blocks
    Route::group(['prefix' => 'block'], function () {
        Route::get("/list/{module}", [FieldController::class, 'block_list']);
    });

    //dashboard
    Route::get("/dashboard/barangay", [DashboardController::class, 'barangay']);
    Route::get("/dashboard/precinct/{barangay}", [DashboardController::class, 'precinct']);
    Route::get("/dashboard/precinct-pie/{barangay}", [DashboardController::class, 'precinct_piechart']);

    //view
    Route::get("/view/module/{module}/", [ViewController::class, 'index']);
    Route::get("/view/module/list/{module}/", [ViewController::class, 'list']);
    Route::get("/view/module/{module}/{id}", [ViewController::class, 'detail']);
    Route::get("/edit/module/{module}/{id?}", [ViewController::class, 'edit']);
    Route::post("/save/module", [ViewController::class, 'save']);
    Route::get("/delete/module/{module}/{id}", [ViewController::class, 'delete']);

    //import
    Route::post("/import-step1", [ImportController::class, 'import_step1']);
    Route::post("/import/save", [ImportController::class, 'save']);

    //report
    Route::group(["prefix" => "report"], function () {
        Route::get("/", [ReportController::class, 'index']);
        Route::get("/list", [ReportController::class, 'list']);
        Route::post("/save", [ReportController::class, 'save']);
        Route::get("/get_fields/{module}", [ReportController::class, 'get_fields']);
        Route::get("/{module}/{id}", [ReportController::class, 'view']);
        Route::get("/export/{module}/{extension}/{id}", [ReportController::class, 'export']);
    });
});
