<?php

namespace App\Modules;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * BaseModuleServiceProvider
 *
 * Abstract base class for all Hook ERP module providers.
 *
 * Each module extends this and implements:
 *   - moduleName()  — string identifier used for route prefix & logging
 *   - resources()   — list of Filament Resource class names to register
 *   - routesFile()  — path to the module's routes.php (or null if none)
 *
 * Modules MUST NOT:
 *   - Modify app/Providers/AppServiceProvider.php
 *   - Modify routes/web.php or routes/inertia.php
 *   - Reference core controllers directly (use Core's public API or models only)
 */
abstract class BaseModuleServiceProvider extends ServiceProvider
{
    /**
     * Unique module name identifier (e.g., 'hr', 'akunting').
     */
    abstract protected function moduleName(): string;

    /**
     * Array of fully-qualified Filament Resource class names.
     *
     * @return array<class-string>
     */
    protected function resources(): array
    {
        return [];
    }

    /**
     * Absolute path to the module's routes file (return null if none).
     */
    protected function routesFile(): ?string
    {
        return null;
    }

    /**
     * Register module services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Boot the module — registers routes and Filament resources.
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerFilamentResources();
    }

    /**
     * Register the module's routes under /app/modules/{module-name}/.
     */
    protected function registerRoutes(): void
    {
        $routesFile = $this->routesFile();

        if ($routesFile && file_exists($routesFile)) {
            Route::middleware(['web', 'auth'])
                ->prefix('app/modules/' . strtolower($this->moduleName()))
                ->group($routesFile);
        }
    }

    /**
     * Register Filament resources by discovering all resource classes
     * within this module's Filament/Resources directory.
     *
     * Override resources() to return explicit class list, or override
     * this method entirely for custom registration logic.
     */
    protected function registerFilamentResources(): void
    {
        // Resources are now auto-discovered directly by AdminPanelProvider
        // during panel configuration, ensuring routes are properly generated.
    }

    /**
     * Absolute path to this module's root directory.
     * e.g. /path/to/app/Modules/HR
     */
    protected function moduleDirectory(): string
    {
        return app_path('Modules/' . $this->moduleName());
    }

    /**
     * Root PHP namespace for this module.
     * e.g. App\Modules\HR
     */
    protected function moduleNamespace(): string
    {
        return 'App\\Modules\\' . $this->moduleName();
    }
}
