<?php

namespace App\Modules\POS;

use App\Modules\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected function moduleName(): string
    {
        return 'POS';
    }
}
