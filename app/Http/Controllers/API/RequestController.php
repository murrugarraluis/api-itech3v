<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreRequest;
use App\Http\Requests\RequestUpdateRequest;
use App\Http\Resources\RequestResource;
use App\Models\Request as RequestC;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->status_message) {
            $requests = RequestC::where('status_message',$request->status_message)->get();
            return RequestResource::collection($requests);
        }  
        return RequestResource::collection(RequestC::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RequestResource
     */
    public function store(RequestStoreRequest $request)
    {
        $requestCreate = RequestC::create([
            'date_required'=>$request->date_required,
            'type_request'=>$request->type_request,
            'importance'=>$request->importance,
            'comment'=>$request->comment,
            'status'=>'Pendiente',
            'status_message'=>'Enviado a Logistica'
        ]);
        foreach ($request->materials as $material){
            $requestCreate->materials()->attach($material['id'], ['quantity' => $material['quantity']]);
        }
        $requestCreate->user()->associate($request->user_id)->save();
        return (new RequestResource($requestCreate))->additional(['message'=>'Requerimiento Registrado']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return RequestResource
     */
    public function show(RequestC $request): RequestResource
    {
        return new RequestResource($request);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    public function changeStatus(RequestUpdateRequest $requestUpdate, RequestC $request)
    {
        $request->update($requestUpdate->all());
        return (new RequestResource($request))->additional(['message'=>'Requerimiento Actualizado']);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RequestC $request
     * @return RequestResource
     */
    public function destroy(RequestC $request): RequestResource
    {
        $request->delete();
        return (new RequestResource($request))->additional(['message' => 'Requerimiento Eliminado']);

    }
}
