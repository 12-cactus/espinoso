<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/handle-update',
        '/new-handle-update',
        '/set-webhook',
        '/get-updates',
        '/webhooks/github/commits'
    ];
}
