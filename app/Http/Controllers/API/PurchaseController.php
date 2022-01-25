<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->date_min && $request->date_max) {

            $purchases = Purchase::whereBetween('date_required', [$request->date_min, $request->date_max])
                ->orderBy('date_required', 'desc')
                ->get();
            $suppliers = Supplier::all();


            $array_suppliers = [];
            $array_amount_purchase_supplier = [];

            foreach ($purchases as $purchase) {
                if (!in_array($purchase->supplier->name, $array_suppliers)) {
                    array_push($array_suppliers, $purchase->supplier->name);
                    array_push($array_amount_purchase_supplier, $this->getTotalPurchasesPerSupplier($purchases, $purchase->supplier));
                }
            }
            return PurchaseResource::collection($purchases)
                ->additional(['additional' => ['suppliers' => $array_suppliers, 'amount_purchases' => $array_amount_purchase_supplier]]);
        }
        return PurchaseResource::collection(Purchase::all());
    }
    private function getTotalPurchasesPerSupplier($purchases, $supplier)
    {
        $suma = 0;
        foreach ($purchases as $purchase) {
            if ($purchase->supplier_id == $supplier->id) {
                $suma += 1;
            }
        }
        return $suma;
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
            'status' => 'Ingresado'
        ]);
        foreach ($request->materials as $material) {

            $material_id = $material['id'];
            $material_quantity = $material['quantity'];
            $material_price = $material['price'];

            $purchase->materials()->attach($material_id, ['quantity' => $material_quantity, 'price' => $material_price]);
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
