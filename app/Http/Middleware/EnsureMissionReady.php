<?php

namespace App\Http\Middleware;

use App\Exceptions\CrewRosterNotReady;
use App\Services\CrewLeadService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMissionReady
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            app(CrewLeadService::class)->assertMissionReady();
        } catch (CrewRosterNotReady $exception) {
            abort(403, $exception->getMessage());
        }

        return $next($request);
    }
}
