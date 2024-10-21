<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsrfCookieController extends Controller
{
    public function getCookie(Request $request)
    {
        return response()->json(['csrf_token' => csrf_token()]);
    }
}
