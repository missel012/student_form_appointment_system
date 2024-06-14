<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;

    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $userData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        $response = $this->authService->register($userData);

        return response()->json($response, 201);
    }

    public function login(Request $request)
    {
        // Validate the request input
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
    
        $credentials = $request->only(['email', 'password']);
    
        // Attempt to authenticate the user
        if (! $token = $this->authService->login($credentials)) {
            // Provide a more informative error message if authentication fails
            return response()->json(['message' => 'Invalid credentials. Please check your email and password and try again.'], 401);
        }
    
        // Respond with the generated token if authentication is successful
        return $this->respondWithToken($token);
    }
    

    public function logout()
    {
        $this->authService->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        try {
            $token = $this->authService->refresh();
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to refresh token: ' . $e->getMessage()], 401);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $this->authService->me(),
            'expires_in' => auth()->factory()->getTTL() * 60 * 24
        ]);
    }

    public function getUserProfileById($id)
    {
        try {
            $user = $this->authService->getUserProfileById($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }
}
