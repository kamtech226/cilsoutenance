<?php

return [

    // Routes soumises au CORS
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],

    'allowed_methods' => ['*'],

    // ⚠️ Avec cookies, pas de "*" : liste précisément tes origines front
    'allowed_origins' => ['http://127.0.0.1:5173', 'http://localhost:5173'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Requis pour Sanctum (cookies)
    'supports_credentials' => true,
];
