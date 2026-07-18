<?php

namespace App\Modules\Warehouse\Filament\Resources\GudangResource\Pages;

use App\Modules\Warehouse\Filament\Resources\GudangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGudang extends EditRecord
{
    protected static string $resource = GudangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
