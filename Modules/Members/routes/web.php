<?php

use Illuminate\Support\Facades\Route;
use Modules\Members\Http\Controllers\MembersController;

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
    Route::resource('leden', MembersController::class)->names('members');
    Route::get('leden/verwijder/{id}', [MembersController::class, 'destroy']);
});

Route::get('testing-event', [MembersController::class, 'testing']);