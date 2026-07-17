<?php

namespace App\Modules\ServiceRepair\Filament\Resources\Penjadwalan\Service\ListAplikasiResource\Pages;

use App\Modules\ServiceRepair\Filament\Resources\Penjadwalan\Service\ListAplikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListAplikasis extends ListRecords
{
    protected static string $resource = ListAplikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
