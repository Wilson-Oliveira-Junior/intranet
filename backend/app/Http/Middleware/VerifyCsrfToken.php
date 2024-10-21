<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;
use Closure;

class VerifyCsrfToken extends Middleware
{
    protected $addHttpCookie = true;

    protected $except = [

    ];

}
