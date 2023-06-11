<?php

use App\Http\Controllers\Api\JabatanController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\UserAccessController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('units', UnitController::class);
Route::resource('jabatan', JabatanController::class);
Route::resource('pegawai', UserController::class);
Route::get('user-access', UserAccessController::class);


Route::get('/summary/user', [UserController::class, 'summary']);
Route::get('/summary/user-login', [UserController::class, 'summaryLogin']);
Route::get('/summary/user-top-login', [UserController::class, 'summaryTopLogin']);
Route::get('/summary/unit', [UnitController::class, 'summary']);
Route::get('/summary/jabatan', [JabatanController::class, 'summary']);
