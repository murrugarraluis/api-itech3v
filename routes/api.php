<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{
    WarehouseController,
    CategoryController,
    MarkController,
    MeasureUnitController,
    MaterialController,
    RequestController,
    AuthenticationController,
    EntryNoteController,
    ExitNoteController,
    PurchaseController,
    QuotationController,
    SupplierController,
    PurchaseOrderController,
    UploadController
};
use App\Http\Controllers\UserController;


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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/





Route::post('login', [AuthenticationController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('register', [AuthenticationController::class, 'register']);
    Route::post('logout', [AuthenticationController::class, 'logout']);

    Route::get('warehouses/deleted', [WarehouseController::class, 'indexDeleted']);
    Route::get('warehouses/deleted/{name}', [WarehouseController::class, 'showDeleted']);
    Route::get('warehouses/{warehouse}/materials', [WarehouseController::class, 'showMaterialsByWarehouse']);
    Route::put('warehouses/deleted/{name}/restore', [WarehouseController::class, 'restore']);
    Route::apiResource('warehouses', WarehouseController::class);

    Route::get('categories/deleted', [CategoryController::class, 'indexDeleted']);
    Route::get('categories/deleted/{name}', [CategoryController::class, 'showDeleted']);
    Route::put('categories/deleted/{name}/restore', [CategoryController::class, 'restore']);
    Route::apiResource('categories', CategoryController::class);

    Route::get('marks/deleted', [MarkController::class, 'indexDeleted']);
    Route::get('marks/deleted/{name}', [MarkController::class, 'showDeleted']);
    Route::put('marks/deleted/{name}/restore', [MarkController::class, 'restore']);
    Route::apiResource('marks', MarkController::class);

    Route::get('measure-units/deleted', [MeasureUnitController::class, 'indexDeleted']);
    Route::get('measure-units/deleted/{name}', [MeasureUnitController::class, 'showDeleted']);
    Route::put('measure-units/deleted/{name}/restore', [MeasureUnitController::class, 'restore']);
    Route::apiResource('measure-units', MeasureUnitController::class);

    Route::get('materials/deleted', [MaterialController::class, 'indexDeleted']);
    Route::get('materials/deleted/{name}', [MaterialController::class, 'showDeleted']);
    Route::put('materials/deleted/{name}/restore', [MaterialController::class, 'restore']);
    Route::apiResource('materials', MaterialController::class);


    Route::get('requests/{request}/evaluate', [RequestController::class, 'evaluate']);
    Route::patch('requests/{request}/change-status', [RequestController::class, 'changeStatus']);
    Route::apiResource('requests', RequestController::class);


    Route::get('users/{user}/requests', [UserController::class, 'showRequests']);

    Route::apiResource('exit-notes', ExitNoteController::class);
    Route::apiResource('entry-notes', EntryNoteController::class);
    Route::apiResource('quotations', QuotationController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
});
Route::apiResource('purchases', PurchaseController::class);
Route::post('upload-file', [UploadController::class, 'uploadFile2']);
