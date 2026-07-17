<?php

namespace App\Modules\Akunting;

use App\Modules\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected function moduleName(): string
    {
        return 'Akunting';
    }

    protected function routesFile(): ?string
    {
        return __DIR__ . '/routes.php';
    }
}
