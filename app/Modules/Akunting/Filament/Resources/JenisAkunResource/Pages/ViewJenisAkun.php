<?php

namespace App\Modules\Akunting\Filament\Resources\JenisAkunResource\Pages;

use App\Modules\Akunting\Filament\Resources\JenisAkunResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJenisAkun extends ViewRecord
{
    protected static string $resource = JenisAkunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
