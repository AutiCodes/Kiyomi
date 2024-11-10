<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {
    Route::resource('admin', AdminController::class)->names('admin');
    Route::get('downloadFlightsGov', [AdminController::class, 'downloadFlightsGov']);
    Route::get('destroy-form/{id}', [AdminController::class, 'destroy']);
});

Route::get('get-users/{code}', [ApiController::class, 'getUsers']);
Route::get('get-flights/{code}', [ApiController::class, 'getFlights']);

