<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    protected function invalidJson($request, ValidationException $exception): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message'=>('Los datos proporcionado no son válidos'),
            'errors' => $exception->errors(),
        ],$exception->status);
    }
    public function render($request, Throwable $e)
    {
        if ($e instanceof  ModelNotFoundException){
            return response()->json([
                "errors" => ["error"=>"Recurso no encontrado"]
            ],400);
        }
        return parent::render($request,$e);
    }
}
