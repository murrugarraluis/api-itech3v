<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PurchaseOrderResource::collection(PurchaseOrder::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $purchaseOrder = PurchaseOrder::create([
            'date_required' => $request->date_required,
            'date_agreed' => $request->date_agreed,
            'importance' => $request->importance,
            'type_purchase_order' => $request->type_purchase_order,
            'status'=>'Usado'
        ]);
        foreach ($request->materials as $material) {
            
            $material_id = $material['id'];
            $material_quantity = $material['quantity'];
            $material_price = $material['price'];

            $purchaseOrder->materials()->attach($material_id, ['quantity' => $material_quantity,'price' => $material_price ]);
        }
        $purchaseOrder->supplier()->associate($request->supplier)->save();
        if ($request->document_number) {
            $purchaseOrder->quotation()->associate($request->document_number)->save();
        }
        return (new PurchaseOrderResource($purchaseOrder))->additional(['message' => 'Orden de Compra Registrada']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        return new PurchaseOrderResource($purchaseOrder);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return (new PurchaseOrderResource($purchaseOrder))->additional(['message' => 'Orden de Compra Eliminada']);
    }
}
