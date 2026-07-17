<?php

namespace App\Modules\Sales;

use App\Modules\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected function moduleName(): string
    {
        return 'Sales';
    }

    protected function routesFile(): ?string
    {
        return __DIR__ . '/routes.php';
    }
}
