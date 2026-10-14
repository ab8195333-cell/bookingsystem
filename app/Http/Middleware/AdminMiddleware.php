<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
   public function handle(Request $request, Closure $next): Response
{
    // التحقق هل المستخدم مسجل دخول وهل الـ role الخاص به هو admin
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }

    abort(403, 'غير مصرح لك بالوصول.');
}
}
