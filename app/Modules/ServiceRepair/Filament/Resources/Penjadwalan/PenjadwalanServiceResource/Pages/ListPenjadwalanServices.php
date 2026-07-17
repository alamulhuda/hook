<?php

namespace App\Modules\ServiceRepair\Filament\Resources\Penjadwalan\PenjadwalanServiceResource\Pages;

use App\Modules\ServiceRepair\Filament\Resources\Penjadwalan\PenjadwalanServiceResource;
use Filament\Actions;
use Filament\Actions\Action;

use Filament\Actions\StaticAction;

use Filament\Resources\Pages\ListRecords;

class ListPenjadwalanServices extends ListRecords
{
    protected static string $resource = PenjadwalanServiceResource::class;
    protected static ?string $title = 'Penerimaan Service';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah')
                ->icon('hugeicons-add-01'),
        ];
    }
}
