<?php

namespace App\Http\Controllers; // Declare the namespace for the Controllers

use Illuminate\Http\Request; // Import the Request class from Illuminate\Http
use App\Services\AuthService; // Import the AuthService class from App\Services

class AuthController extends Controller // Define the AuthController class extending the base Controller class
{
    protected $authService; // Declare a protected property for the AuthService

    public function __construct(AuthService $authService) // Constructor method to initialize the AuthService
    {
        $this->authService = $authService; // Assign the injected AuthService instance to the property
    }

    public function register(Request $request) // Method to handle user registration
    {
        $this->validate($request, [ // Validate the incoming request data
            'name' => 'required|string', // Name is required and must be a string
            'email' => 'required|email|unique:users', // Email is required, must be a valid email address, and unique in the users table
            'password' => 'required|string|min:8', // Password is required, must be a string, and at least 8 characters long
        ]);

        $userData = [ // Create an array of user data from the request input
            'name' => $request->input('name'), // Get the name from the request
            'email' => $request->input('email'), // Get the email from the request
            'password' => $request->input('password'), // Get the password from the request
        ];

        $response = $this->authService->register($userData); // Call the register method of the AuthService with the user data

        return response()->json($response, 201); // Return a JSON response with the registration response and a 201 status code
    }

    public function login(Request $request) // Method to handle user login
    {
        $this->validate($request, [ // Validate the incoming request data
            'email' => 'required|string|email', // Email is required, must be a string, and a valid email address
            'password' => 'required|string|min:8', // Password is required, must be a string, and at least 8 characters long
        ]);
    
        $credentials = $request->only(['email', 'password']); // Get only the email and password from the request
    
        if (! $token = $this->authService->login($credentials)) { // Attempt to authenticate the user and get a token
            return response()->json(['message' => 'Invalid credentials. Please check your email and password and try again.'], 401); // Return an error message if authentication fails
        }
    
        return $this->respondWithToken($token); // Return a response with the generated token if authentication is successful
    }
    
    public function logout() // Method to handle user logout
    {
        $this->authService->logout(); // Call the logout method of the AuthService

        return response()->json(['message' => 'Successfully logged out']); // Return a JSON response indicating successful logout
    }

    public function refresh() // Method to refresh the authentication token
    {
        try {
            $token = $this->authService->refresh(); // Call the refresh method of the AuthService to get a new token
            return $this->respondWithToken($token); // Return a response with the new token
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to refresh token: ' . $e->getMessage()], 401); // Return an error message if the token refresh fails
        }
    }

    protected function respondWithToken($token) // Protected method to format the token response
    {
        return response()->json([
            'access_token' => $token, // Include the access token in the response
            'token_type' => 'bearer', // Specify the token type as 'bearer'
            'user' => $this->authService->me(), // Include the authenticated user information
            'expires_in' => auth()->factory()->getTTL() * 60 * 24 // Include the token expiration time in minutes
        ]);
    }

    public function getUserProfileById($id) // Method to get a user's profile by their ID
    {
        try {
            $user = $this->authService->getUserProfileById($id); // Call the getUserProfileById method of the AuthService with the user ID
            return response()->json($user); // Return a JSON response with the user profile data
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found'], 404); // Return an error message if the user is not found
        }
    }
}
