<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password,)
        ]);

        return response()->json([
            'message' => 'Usuario Registrado'
        ], 201);
    }
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['Credenciales Incorrectas'],
            ]);
        } else {
            $token = $user->createToken($request->email)->plainTextToken;

            return UserResource::collection(User::where('email', $request->email)->get())->additional([
                'token' => $token
            ]);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        //$request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Token Eliminado'
        ], 200);
    }
}
