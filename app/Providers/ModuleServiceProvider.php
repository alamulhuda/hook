<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

/**
 * ModuleServiceProvider
 *
 * Auto-discovers all module service providers located at:
 *   app/Modules/{ModuleName}/ModuleServiceProvider.php
 *
 * Contract for modules:
 *   - Each module MUST have its own ModuleServiceProvider.php
 *   - Modules MUST NOT modify any core files (web.php, AppServiceProvider, etc.)
 *   - Modules register their own routes, Filament resources, and Vue pages independently
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register all discovered module providers.
     */
    public function register(): void
    {
        $modulesPath = app_path('Modules');

        if (! File::isDirectory($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $moduleDir) {
            $providerFile = $moduleDir . '/ModuleServiceProvider.php';

            if (! File::exists($providerFile)) {
                continue;
            }

            $moduleName = basename($moduleDir);

            // Check if module is enabled before registering
            if (! app(\App\Services\ModuleRegistryService::class)->isModuleEnabled($moduleName)) {
                continue;
            }

            $providerClass = "App\\Modules\\{$moduleName}\\ModuleServiceProvider";

            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }

    /**
     * Bootstrap module providers (handled individually by each module).
     */
    public function boot(): void
    {
        //
    }
}
