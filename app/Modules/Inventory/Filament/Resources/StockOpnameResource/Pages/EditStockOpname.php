<?php

namespace App\Modules\Inventory\Filament\Resources\StockOpnameResource\Pages;

use App\Modules\Inventory\Filament\Resources\StockOpnameResource;
use Filament\Resources\Pages\EditRecord;

class EditStockOpname extends EditRecord
{
    protected static string $resource = StockOpnameResource::class;

        protected function getHeaderActions(): array
    {
        return [
            $this->getSaveFormAction()->formId('form'),
            $this->getCancelFormAction(),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
