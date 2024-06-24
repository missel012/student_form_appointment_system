<?php

return [
    'defaults' => [
        'guard' => 'api',       // Default guard used for authentication
        'passwords' => 'users', // Default password resetter used
    ],

    'guards' => [
        'api' => [               // Definition of the 'api' guard
            'driver' => 'jwt',   // Driver used for this guard (JWT authentication)
            'provider' => 'users', // Provider used for fetching users (defined in 'providers' section)
        ],
    ],

    'providers' => [
        'users' => [             // Definition of the 'users' provider
            'driver' => 'eloquent', // Driver used for this provider (Eloquent ORM)
            'model' => \App\Models\User::class // Eloquent model representing the user
        ]
    ]
];
