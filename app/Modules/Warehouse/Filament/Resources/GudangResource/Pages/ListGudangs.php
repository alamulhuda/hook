<?php

namespace App\Modules\Warehouse\Filament\Resources\GudangResource\Pages;

use App\Modules\Warehouse\Filament\Resources\GudangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGudangs extends ListRecords
{
    protected static string $resource = GudangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Gudang')
                ->icon('heroicon-m-plus'),
        ];
    }
}
