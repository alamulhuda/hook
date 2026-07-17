<?php

namespace App\Modules\Inventory;

use App\Modules\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected function moduleName(): string
    {
        return 'Inventory';
    }

    protected function routesFile(): ?string
    {
        return __DIR__ . '/routes.php';
    }
}
