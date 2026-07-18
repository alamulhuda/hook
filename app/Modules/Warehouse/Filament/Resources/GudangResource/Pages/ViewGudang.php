<?php

namespace App\Modules\Warehouse\Filament\Resources\GudangResource\Pages;

use App\Modules\Warehouse\Filament\Resources\GudangResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGudang extends ViewRecord
{
    protected static string $resource = GudangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
