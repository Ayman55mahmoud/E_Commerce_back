<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    // 📝 Register
    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('auth_token')->plainTextToken
        ], 201);
    }

    // 🔐 Login
    public function login(LoginRequest $request)
    {
        $data = $this->service->login($request->validated());

        if (!$data) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'user' => new UserResource($data['user']),
            'token' => $data['token']
        ]);
    }

    // 🚪 Logout
    public function logout()
    {
        $this->service->logout(auth()->user());

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
