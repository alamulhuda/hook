<?php

namespace App\Modules\Inventory\Filament\Resources\StockOpnameResource\Pages;

use App\Filament\Actions\InventoryExportHeaderAction;
use App\Filament\Resources\InventoryResource;
use App\Modules\Inventory\Filament\Resources\StockOpnameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;

class ListStockOpnames extends ListRecords
{
    protected static string $resource = StockOpnameResource::class;

    public function table(Table $table): Table
    {
        return parent::table($table);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Stock Opname')
                ->icon('heroicon-o-plus'),
        ];
    }
}
