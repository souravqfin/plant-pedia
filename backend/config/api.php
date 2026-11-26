<?php

return [
    'jwt' => [
        'secret' => env('JWT_SECRET', env('APP_KEY')),
        'issuer' => env('JWT_ISSUER', 'plant-pedia'),
        'algorithm' => 'HS256',
        'access_ttl' => (int) env('JWT_ACCESS_TTL', 900),
        'admin_access_ttl' => (int) env('JWT_ADMIN_ACCESS_TTL', 600),
    ],

    'refresh' => [
        'ttl' => (int) env('REFRESH_TTL', 60 * 60 * 24 * 30),
        'cookie' => env('REFRESH_COOKIE', 'pp_refresh'),
        'path' => env('REFRESH_COOKIE_PATH', '/api/v1/auth'),
        'domain' => env('REFRESH_COOKIE_DOMAIN'),
        'secure' => (bool) env('REFRESH_COOKIE_SECURE', false),
        'same_site' => env('REFRESH_COOKIE_SAME_SITE', 'lax'),
    ],

    'locales' => ['bn', 'en'],
    'default_locale' => env('APP_LOCALE', 'bn'),
    'default_country' => env('DEFAULT_COUNTRY', 'BD'),

    'rate_limits' => [
        'login' => env('RATE_LIMIT_LOGIN', '5,1'),
        'register' => env('RATE_LIMIT_REGISTER', '3,60'),
        'search' => env('RATE_LIMIT_SEARCH', '60,1'),
        'writes' => env('RATE_LIMIT_WRITES', '20,1'),
    ],
];
