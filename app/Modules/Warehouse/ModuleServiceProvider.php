<?php

namespace App\Modules\Warehouse;

use App\Modules\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected function moduleName(): string
    {
        return 'Warehouse';
    }

    protected function routesFile(): ?string
    {
        return __DIR__ . '/routes.php';
    }
}
