<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthService
{
    public function register(array $userData)
    {
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
        ]);

        return [
            'message' => 'User registered successfully',
            'user_id' => $user->id,
        ];
    }

    public function login(array $credentials)
    {
        if (! $token = Auth::attempt($credentials)) {
            return false;
        }

        return $token;
    }

    public function logout()
    {
        auth()->logout();
    }

    public function refresh()
    {
        return auth()->refresh();
    }

    public function me()
    {
        return auth()->user();
    }

    public function getUserProfileById($id)
    {
        return User::findOrFail($id);
    }
}
