<?php

namespace App\Modules\Reports;

use App\Modules\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected function moduleName(): string
    {
        return 'Reports';
    }
}
