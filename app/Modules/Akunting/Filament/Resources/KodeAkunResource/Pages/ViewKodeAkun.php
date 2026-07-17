<?php

namespace App\Modules\Akunting\Filament\Resources\KodeAkunResource\Pages;

use App\Modules\Akunting\Filament\Resources\KodeAkunResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKodeAkun extends ViewRecord
{
    protected static string $resource = KodeAkunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
