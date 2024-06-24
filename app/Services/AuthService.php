<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth; // Importing the Auth facade to manage authentication
use Illuminate\Support\Facades\Hash; // Importing the Hash facade for password hashing
use App\Models\User; // Importing the User model

class AuthService
{
    /**
     * Register a new user.
     *
     * @param  array  $userData  User data including name, email, and password
     * @return array  Message and user ID of the newly registered user
     */
    public function register(array $userData)
    {
        // Create a new user record in the database
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']), // Hash the password for security
        ]);

        // Return success message and user ID
        return [
            'message' => 'User registered successfully',
            'user_id' => $user->id,
        ];
    }

    /**
     * Authenticate the user and generate a JWT token for login.
     *
     * @param  array  $credentials  User credentials including email and password
     * @return string|false  JWT token if login successful, otherwise false
     */
    public function login(array $credentials)
    {
        // Attempt to authenticate the user using provided credentials
        if (! $token = Auth::attempt($credentials)) {
            return false; // Return false if authentication fails
        }

        return $token; // Return JWT token if authentication succeeds
    }

    /**
     * Logout the authenticated user.
     *
     * @return void
     */
    public function logout()
    {
        auth()->logout(); // Logout the currently authenticated user
    }

    /**
     * Refresh the JWT token.
     *
     * @return string  Refreshed JWT token
     */
    public function refresh()
    {
        return auth()->refresh(); // Refresh the current JWT token
    }

    /**
     * Get the authenticated user instance.
     *
     * @return \App\Models\User|null  Authenticated user instance or null if not authenticated
     */
    public function me()
    {
        return auth()->user(); // Retrieve the authenticated user instance
    }

    /**
     * Get user profile by ID.
     *
     * @param  int  $id  User ID
     * @return \App\Models\User  User instance with the given ID
     */
    public function getUserProfileById($id)
    {
        return User::findOrFail($id); // Retrieve user profile by ID or throw a 404 error if not found
    }
}

