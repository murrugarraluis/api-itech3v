<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarkStoreRequest;
use App\Http\Requests\MarkUpdateRequest;
use App\Http\Resources\MarkResource;
use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return MarkResource::collection(Mark::all());
    }
    public function indexDeleted(): AnonymousResourceCollection
    {
        return MarkResource::collection(Mark::onlyTrashed()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MarkStoreRequest $request
     * @return MarkResource
     */
    public function store(MarkStoreRequest $request): MarkResource
    {
        return (new MarkResource(Mark::create($request->all())))->additional(['message'=>'Marca Registrada']);
    }

    /**
     * Display the specified resource.
     *
     * @param Mark $mark
     * @return MarkResource
     */
    public function show(Mark $mark): MarkResource
    {
        return new MarkResource($mark);
    }
    public function showDeleted($name): MarkResource
    {
        $mark = Mark::onlyTrashed()->where('name',$name)->firstOrFail();
        return new MarkResource($mark);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MarkUpdateRequest $request
     * @param Mark $mark
     * @return MarkResource
     */
    public function update(MarkUpdateRequest $request, Mark $mark): MarkResource
    {
        $mark->update($request->all());
        return (new MarkResource($mark))->additional(['message'=>'Marca Actualizada']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Mark $mark
     * @return MarkResource
     */
    public function destroy(Mark $mark): MarkResource
    {
        $mark->delete();
        return (new MarkResource($mark))->additional(['message'=>'Marca Eliminada']);
    }
    public function restore($name): MarkResource
    {
        $mark = Mark::onlyTrashed()->where('name',$name)->firstOrFail();
        $mark->restore();
        return (new MarkResource($mark))->additional(['message'=>'Marca Restaurada']);
    }
}
