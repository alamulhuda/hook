<?php

namespace App\Http\Middleware;

use App\Services\ModuleRegistryService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $registry = app(ModuleRegistryService::class);

        if (!$registry->isModuleEnabled($module)) {
            abort(404, "Module [{$module}] is currently disabled or not available.");
        }

        return $next($request);
    }
}
