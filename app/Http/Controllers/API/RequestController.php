<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreRequest;
use App\Http\Requests\RequestUpdateRequest;
use App\Http\Resources\MaterialRequestResource;
use App\Http\Resources\MaterialResource;
use App\Http\Resources\RequestResource;
use App\Models\Material;
use App\Models\Request as RequestC;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;

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
            $requests = RequestC::where('status_message', $request->status_message)->get();
            return RequestResource::collection($requests);
        }
        if ($request->status && $request->type_request && $request->date_min && $request->date_max) {
            $requests = RequestC::where('status', $request->status)
            ->where('type_request', $request->type_request)
            ->whereBetween('date_required', [$request->date_min, $request->date_max])
            ->orderBy('date_required', 'desc')
            ->get();
            


            $date_min = $request->date_min;
            $date_max = $request->date_max;

            $comienzo = new DateTime($date_min);
            $final = new DateTime($date_max);
            // Necesitamos modificar la fecha final en 1 día para que aparezca en el bucle
            $final = $final->modify('+1 day');

            $intervalo = DateInterval::createFromDateString('1 month');
            $periodo = new DatePeriod($comienzo, $intervalo, $final);

            $months = [];
            $amountMonths = [];
            foreach ($periodo as $dt) {
                array_push($months,$dt->format("M-y"));
                array_push($amountMonths,$this->getTotalRequestPerMonth($requests,$dt->format("M")));
            }
            return RequestResource::collection($requests)
            ->additional(['additional' => ['months' => $months,'amount_months'=> $amountMonths]]);
        }
        return RequestResource::collection(RequestC::all());

    }

    private function getTotalRequestPerMonth($data,$month)
    {
        $suma = 0;
        foreach ($data as $d){
            $date = new DateTime($d->date_required);
            if ($date->format("M") == $month) {
                $suma += 1;
            }
        }
        return $suma;
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
            'date_required' => $request->date_required,
            'type_request' => $request->type_request,
            'importance' => $request->importance,
            'comment' => $request->comment,
            'status' => 'Pendiente',
            'status_message' => 'Enviado a Logistica'
        ]);
        foreach ($request->materials as $material) {
            $requestCreate->materials()->attach($material['id'], ['quantity' => $material['quantity']]);
        }
        $requestCreate->user()->associate($request->user_id)->save();
        return (new RequestResource($requestCreate))->additional(['message' => 'Requerimiento Registrado']);
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
    public function evaluate(RequestC $request)
    {
        $materials_detail = [];
        foreach ($request->materials as $material) {
            $materialBase = Material::find($material->id);
            $quatityHas = $this->stock($materialBase->warehouses);
            $quatityRequest = $material->pivot->quantity;
            if ($quatityRequest > $quatityHas) {

                // Almacenar Material para enviar un detalle de los materiales no satisfechos
                array_push($materials_detail, $material);
            }
        }
        if (!empty($materials_detail)) {
            // Retornar Detalle de materiales que no cumplen

            // return response()->json([
            //     'data'=>[
            //         'materials'=>$materials_detail
            //     ],
            //     'message'=>''
            // ]);
            return MaterialRequestResource::collection($materials_detail)->additional(['message' => 'Requerimiento Insatisfecho']);
            // return (new MaterialRequestResource($materials_detail))->additional(['message' => 'Requerimiento Insatisfecho']);
        }
        return (new RequestResource($request))->additional(['message' => 'Requerimiento Satisfecho']);
    }
    private function stock($warehouses)
    {
        $suma = 0;
        foreach ($warehouses as $warehouse) {
            $suma += $warehouse->pivot->quantity;
        }
        return $suma;
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
        return (new RequestResource($request))->additional(['message' => 'Requerimiento Actualizado']);
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
