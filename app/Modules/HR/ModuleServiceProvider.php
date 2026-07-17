<?php

namespace App\Modules\HR;

use App\Modules\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected function moduleName(): string
    {
        return 'HR';
    }

    protected function routesFile(): ?string
    {
        return __DIR__ . '/routes.php';
    }
}
