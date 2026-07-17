<?php

namespace App\Modules\ServiceRepair;

use App\Modules\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected function moduleName(): string
    {
        return 'ServiceRepair';
    }
}
