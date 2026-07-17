<?php

namespace App\Modules\HR\Filament\Resources\Absensi\LiburCutiResource\Pages;

use App\Modules\HR\Filament\Resources\Absensi\LiburCutiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLiburCuti extends EditRecord
{
    protected static string $resource = LiburCutiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
