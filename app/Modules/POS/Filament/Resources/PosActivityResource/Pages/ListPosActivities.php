<?php

namespace App\Modules\POS\Filament\Resources\PosActivityResource\Pages;

use App\Modules\POS\Filament\Resources\PosActivityResource;
use App\Modules\POS\Filament\Resources\PosActivityResource\Widgets\PosActivityStats;
use Filament\Resources\Pages\ListRecords;

class ListPosActivities extends ListRecords
{
    protected static string $resource = PosActivityResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            PosActivityStats::class,
        ];
    }
}
