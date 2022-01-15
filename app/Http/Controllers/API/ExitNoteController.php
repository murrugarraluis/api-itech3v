<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExitNoteStoreRequest;
use App\Http\Resources\ExitNoteResource;
use App\Models\ExitNote;
use App\Models\Material;
use Illuminate\Http\Request;

class ExitNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ExitNoteResource::collection(ExitNote::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExitNoteStoreRequest $request)
    {
        $exit_note = ExitNote::create([
            'date' => $request->date,
            'type_exit' => $request->type_exit,
            'comment' => $request->comment,
            'document_number' => $request->document_number
        ]);
        foreach ($request->materials as $material) {

            $warehouse = $request->warehouse;
            $material_id = $material['id'];
            $material_quantity = $material['quantity'];
            $material_find = Material::find($material_id);


            $ActualStock = $material_find->warehouses()->find($warehouse)->pivot->quantity;
            $newStock = $ActualStock - $material_quantity;
            $material_find->warehouses()->updateExistingPivot($warehouse, [
                'quantity' => $newStock
            ]);

            $exit_note->materials()->attach($material_id, ['quantity' => $material_quantity]);
        }
        $exit_note->warehouse()->associate($warehouse)->save();
        return (new ExitNoteResource($exit_note))->additional(['message' => 'Nota de Salida Registrada']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExitNote  $exitNote
     * @return \Illuminate\Http\Response
     */
    public function show(ExitNote $exitNote)
    {
        return new ExitNoteResource($exitNote);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExitNote  $exitNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExitNote $exitNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExitNote  $exitNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExitNote $exitNote)
    {
        foreach ($exitNote->materials as $material) {

            $warehouse_id = $exitNote->warehouse->id;
            $material_find = Material::find($material->id);
            $material_quantity = $material->pivot->quantity;

            $ActualStock = $material_find->warehouses()->find($warehouse_id)->pivot->quantity;
            $newStock = $ActualStock + $material_quantity;
            $material->warehouses()->updateExistingPivot($warehouse_id, [
                'quantity' => $newStock
            ]);
        }
        $exitNote->delete();
        return (new ExitNoteResource($exitNote))->additional(['message' => 'Nota de Salida Eliminada']);
    }
}
