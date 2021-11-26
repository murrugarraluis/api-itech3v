<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseStoreRequest;
use App\Http\Requests\WarehouseUpdateRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return WarehouseResource::collection(Warehouse::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WarehouseStoreRequest $request
     * @return WarehouseResource
     */
    public function store(WarehouseStoreRequest $request): WarehouseResource
    {
        return (new WarehouseResource(Warehouse::create($request->all())))->additional(['message'=>'Almacén Registrado']);
    }

    /**
     * Display the specified resource.
     *
     * @param Warehouse $warehouse
     * @return WarehouseResource
     */
    public function show(Warehouse $warehouse): WarehouseResource
    {
        return new WarehouseResource($warehouse);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param WarehouseUpdateRequest $request
     * @param Warehouse $warehouse
     * @return WarehouseResource
     */
    public function update(WarehouseUpdateRequest $request, Warehouse $warehouse): WarehouseResource
    {
        $warehouse->update($request->all());
        return (new WarehouseResource($warehouse))->additional(['message'=>'Almacén Actualizado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Warehouse $warehouse
     * @return WarehouseResource
     */
    public function destroy(Warehouse $warehouse): WarehouseResource
    {
        $warehouse->delete();
        return (new WarehouseResource($warehouse))->additional(['message'=>'Almacén Eliminado']);
    }

    public function showDeleted($name)
    {
        $warehouse = Warehouse::onlyTrashed()->where('name',$name)->first();
        if ($warehouse){
            return new WarehouseResource($warehouse);
        }
        return response()->json([
            "errors" => ["error"=>"Recurso no encontrado"]
        ],400);
    }
    public function restore($name)
    {
        $warehouse = Warehouse::onlyTrashed()->where('name',$name)->first();
        if ($warehouse){
            $warehouse->restore();
            return (new WarehouseResource($warehouse))->additional(['message'=>'Almacén Restaurado']);
        }
        return response()->json([
            "errors" => ["error"=>"Recurso no encontrado"]
        ],400);
    }
}
