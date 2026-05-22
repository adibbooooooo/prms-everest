<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCrewLead
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isCrewLead()) {
            abort(403, 'Crew lead access required.');
        }

        return $next($request);
    }
}
