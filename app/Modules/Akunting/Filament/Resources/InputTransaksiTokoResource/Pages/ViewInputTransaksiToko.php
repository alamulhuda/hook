<?php

namespace App\Modules\Akunting\Filament\Resources\InputTransaksiTokoResource\Pages;

use App\Modules\Akunting\Filament\Resources\InputTransaksiTokoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInputTransaksiToko extends ViewRecord
{
    protected static string $resource = InputTransaksiTokoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
