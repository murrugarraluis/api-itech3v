<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeasureUnitStoreRequest;
use App\Http\Requests\MeasureUnitUpdateRequest;
use App\Http\Resources\MeasureUnitResource;
use App\Models\MeasureUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MeasureUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return MeasureUnitResource::collection(MeasureUnit::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MeasureUnitStoreRequest $request
     * @return MeasureUnitResource
     */
    public function store(MeasureUnitStoreRequest $request): MeasureUnitResource
    {
        return (new MeasureUnitResource(MeasureUnit::create($request->all())))->additional(['message'=>'Unidad de Medida Registrada']);
    }

    /**
     * Display the specified resource.
     *
     * @param MeasureUnit $measureUnit
     * @return MeasureUnitResource
     */
    public function show(MeasureUnit $measureUnit): MeasureUnitResource
    {
        return new MeasureUnitResource($measureUnit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MeasureUnitUpdateRequest $request
     * @param MeasureUnit $measureUnit
     * @return MeasureUnitResource
     */
    public function update(MeasureUnitUpdateRequest $request, MeasureUnit $measureUnit): MeasureUnitResource
    {
        $measureUnit->update($request->all());
        return (new MeasureUnitResource($measureUnit))->additional(['message'=>'Unidad de Medida Actualizada']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MeasureUnit $measureUnit
     * @return MeasureUnitResource
     */
    public function destroy(MeasureUnit $measureUnit): MeasureUnitResource
    {
        $measureUnit->delete();
        return (new MeasureUnitResource($measureUnit))->additional(['message'=>'Unidad de Medida Eliminada']);
    }
}
