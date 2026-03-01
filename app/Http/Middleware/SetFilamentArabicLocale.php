<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetFilamentArabicLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale('ar');
        $request->setLocale('ar');
        Carbon::setLocale('ar');

        return $next($request);
    }
}

