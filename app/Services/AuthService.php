<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Role;

class AuthService
{
    public function register($data)
    {
        $role = Role::where('name', 'user')->first();
        
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role_id = $role->id;
        $user->save();

        
        Log::info('User registered', ['id' => $user->id]);

        return $user;
    }

    public function login($data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            Log::warning('Failed login', ['email' => $data['email']]);
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('User logged in', ['id' => $user->id]);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout($user)
    {
        $user->tokens()->delete();

        Log::info('User logged out', ['id' => $user->id]);
    }
}