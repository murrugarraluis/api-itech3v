<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuotationStoreRequest;
use App\Http\Resources\QuotationResource;
use App\Models\Quotation;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QuotationResource::collection(Quotation::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuotationStoreRequest $request)
    {
        $quotation = Quotation::create([
            'date_agreed' => $request->date_agreed,
            'way_to_pay' => $request->way_to_pay,
            'type_quotation' => $request->type_quotation,
            'document_number' => $request->document_number,
            'status'=>$request->status
        ]);
        foreach ($request->materials as $material) {
            
            $material_id = $material['id'];
            $material_quantity = $material['quantity'];
            $material_price = $material['price'];

            $quotation->materials()->attach($material_id, ['quantity' => $material_quantity,'price' => $material_price ]);
        }
        $quotation->supplier()->associate($request->supplier)->save();
        return (new QuotationResource($quotation))->additional(['message' => 'Cotizacion Registrada']);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Quotation $quotation)
    {
        return new QuotationResource($quotation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return (new QuotationResource($quotation))->additional(['message' => 'Cotizacion Eliminada']);
    
    }
}
