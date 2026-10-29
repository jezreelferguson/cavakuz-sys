<?php

use Laravel\Sanctum\Sanctum;

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
    ))),

    'guard' => ['api'],

    'expiration' => null,

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    'middleware' => [
        'authenticate_session' => Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        'encrypt_cookies' => Illuminate\Cookie\Middleware\EncryptCookies::class,
        'add_queue_flush' => Illuminate\Queue\Middleware\AddQueuedCookiesToResponse::class,
        'start_session' => Illuminate\Session\Middleware\StartSession::class,
        'share_errors' => Illuminate\View\Middleware\ShareErrorsFromSession::class,
        'verify_csrf' => Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        'substitute_bindings' => Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'aliases' => [
        'auth' => [
            'guard' => 'web',
        ],
    ],
];
