<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialStoreRequest;
use App\Http\Requests\MaterialUpdateRequest;
use App\Http\Resources\MaterialResource;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return MaterialResource::collection(Material::all());
    }

    public function indexDeleted(): AnonymousResourceCollection
    {
        return MaterialResource::collection(Material::onlyTrashed()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return MaterialResource
     */
    public function store(MaterialStoreRequest $request): MaterialResource
    {
        $material = Material::create([
            'name' => $request->name,
            'minimum_stock' => $request->minimum_stock
        ]);
        $material->category()->associate($request->category)->save();
        $material->mark()->associate($request->mark)->save();
        $material->measure_unit()->associate($request->measure_unit)->save();
        $material->warehouses()->sync($request->warehouses);
        return (new MaterialResource($material))->additional(['message' => 'Material Registrado']);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return MaterialResource
     */
    public function show(Material $material): MaterialResource
    {
        return new MaterialResource($material);
    }

    public function showDeleted($name): MaterialResource
    {
        $material = Material::onlyTrashed()->where('name', $name)->firstOrFail();
        return new MaterialResource($material);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return MaterialResource
     */
    public function update(MaterialUpdateRequest $request, Material $material): MaterialResource
    {
        $material->update([
            'name' => $request->name
        ]);
        $material->category()->associate($request->category)->save();
        $material->mark()->associate($request->mark)->save();
        $material->measure_unit()->associate($request->measure_unit)->save();
        $material->warehouses()->sync($request->warehouses);
        return (new MaterialResource($material))->additional(['message' => 'Material Actualizado']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Material $material
     * @return MaterialResource
     */
    public function destroy(Material $material): MaterialResource
    {
        $material->delete();
        return (new MaterialResource($material))->additional(['message' => 'Material Eliminado']);

    }

    public function restore($name): MaterialResource
    {
        $material = Material::onlyTrashed()->where('name', $name)->firstOrFail();
        $material->restore();
        return (new MaterialResource($material))->additional(['message' => 'Material Restaurado']);
    }
}
