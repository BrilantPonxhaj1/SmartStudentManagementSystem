<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'oauth/token'],
    'allowed_methods' => ['GET, POST, PUT, DELETE, OPTIONS'],
    'allowed_origins' => ['*'], // Change '*' to specific domains for security
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
