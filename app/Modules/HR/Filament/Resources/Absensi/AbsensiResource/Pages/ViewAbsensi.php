<?php

namespace App\Modules\HR\Filament\Resources\Absensi\AbsensiResource\Pages;

use App\Modules\HR\Filament\Resources\Absensi\AbsensiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAbsensi extends ViewRecord
{
    protected static string $resource = AbsensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
