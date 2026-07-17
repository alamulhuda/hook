<?php

namespace App\Modules\Inventory\Filament\Resources\RequestOrderResource\Pages;

use App\Modules\Inventory\Filament\Resources\RequestOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequestOrders extends ListRecords
{
    protected static string $resource = RequestOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah RO')
                ->icon('heroicon-o-plus'),
        ];
    }
}
