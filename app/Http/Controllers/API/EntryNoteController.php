<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EntryNoteResource;
use App\Models\EntryNote;
use App\Models\Material;
use Dotenv\Parser\Entry;
use Illuminate\Http\Request;

class EntryNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EntryNoteResource::collection(EntryNote::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entry_note = EntryNote::create([
            'date' => $request->date,
            'type_entry' => $request->type_entry,
            'comment' => $request->comment,
            'document_number' => $request->document_number
        ]);
        foreach ($request->materials as $material) {

            $warehouse = $request->warehouse;
            $material_id = $material['id'];
            $material_quantity = $material['quantity'];
            $material_find = Material::find($material_id);


            $ActualStock = $material_find->warehouses()->find($warehouse)->pivot->quantity;
            $newStock = $ActualStock + $material_quantity;
            $material_find->warehouses()->updateExistingPivot($warehouse, [
                'quantity' => $newStock
            ]);

            $entry_note->materials()->attach($material_id, ['quantity' => $material_quantity]);
        }
        $entry_note->warehouse()->associate($warehouse)->save();
        return (new EntryNoteResource($entry_note))->additional(['message' => 'Nota de Ingreso Registrada']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(EntryNote $entryNote)
    {
        return new EntryNoteResource($entryNote);
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
    public function destroy(EntryNote $entryNote)
    {
        foreach ($entryNote->materials as $material) {

            $warehouse_id = $entryNote->warehouse->id;
            $material_find = Material::find($material->id);
            $material_quantity = $material->pivot->quantity;

            $ActualStock = $material_find->warehouses()->find($warehouse_id)->pivot->quantity;
            $newStock = $ActualStock - $material_quantity;
            $material->warehouses()->updateExistingPivot($warehouse_id, [
                'quantity' => $newStock
            ]);
        }
        $entryNote->delete();
        return (new entryNoteResource($entryNote))->additional(['message' => 'Nota de Salida Eliminada']);
    }
}
