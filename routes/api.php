<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{WarehouseController,CategoryController,MarkController,MeasureUnitController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned thpe "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('warehouses/deleted',[WarehouseController::class,'indexDeleted']);
Route::get('warehouses/deleted/{name}',[WarehouseController::class,'showDeleted']);
Route::put('warehouses/deleted/{name}/restore',[WarehouseController::class,'restore']);
Route::apiResource('warehouses',WarehouseController::class);


//Route::apiResource('categories',CategoryController::class);
//Route::apiResource('marks',MarkController::class);
//Route::apiResource('measure-units',MeasureUnitController::class);


