<?php

return [
    'driver' => env('SESSION_DRIVER', 'file'),  // Session driver (default: file)
    'lifetime' => 120,                          // Session lifetime in minutes (default: 120 minutes)
    'expire_on_close' => false,                 // Whether to expire the session on close (default: false)
    'encrypt' => false,                         // Whether to encrypt session data (default: false)
    'files' => storage_path('framework/sessions'), // Path where session files are stored
    'connection' => null,                       // Database connection to use (default: null)
    'table' => 'sessions',                      // Database table used for sessions (default: 'sessions')
    'store' => null,                            // Custom session store (default: null)
    'lottery' => [2, 100],                      // Probability of garbage collection (default: [2, 100])
    'cookie' => env('SESSION_COOKIE', 'lumen_session'), // Session cookie name
    'path' => '/',                              // Cookie path (default: '/')
    'domain' => env('SESSION_DOMAIN', null),    // Cookie domain
    'secure' => env('SESSION_SECURE_COOKIE', false), // Whether to use secure cookies (default: false)
    'http_only' => true,                        // HTTP only cookie flag (default: true)
    'same_site' => null,                        // SameSite attribute for cookies (default: null)
];
