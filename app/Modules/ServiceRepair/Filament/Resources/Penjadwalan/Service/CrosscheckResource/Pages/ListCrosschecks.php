<?php

namespace App\Modules\ServiceRepair\Filament\Resources\Penjadwalan\Service\CrosscheckResource\Pages;

use App\Modules\ServiceRepair\Filament\Resources\Penjadwalan\Service\CrosscheckResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCrosschecks extends ListRecords
{
    protected static string $resource = CrosscheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
