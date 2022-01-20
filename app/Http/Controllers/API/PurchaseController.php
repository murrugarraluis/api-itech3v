<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PurchaseResource::collection(Purchase::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $purchase = Purchase::create([
            'way_to_pay' => $request->way_to_pay,
            'type_document' => $request->type_document,
            'number' => $request->number,
            'type_purchase' => $request->type_purchase,
            'status'=>'Ingresado'
        ]);
        foreach ($request->materials as $material) {
            
            $material_id = $material['id'];
            $material_quantity = $material['quantity'];
            $material_price = $material['price'];

            $purchase->materials()->attach($material_id, ['quantity' => $material_quantity,'price' => $material_price ]);
        }
        $purchase->supplier()->associate($request->supplier)->save();
        if ($request->document_number) {
            $purchase->purchase_order()->associate($request->document_number)->save();
        }
        return (new PurchaseResource($purchase))->additional(['message' => 'Compra Registrada']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        return new PurchaseResource($purchase);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return (new PurchaseResource($purchase))->additional(['message' => 'Compra Eliminada']);
    }
}
