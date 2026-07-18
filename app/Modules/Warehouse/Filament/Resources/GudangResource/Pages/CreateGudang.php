<?php

namespace App\Modules\Warehouse\Filament\Resources\GudangResource\Pages;

use App\Modules\Warehouse\Filament\Resources\GudangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGudang extends CreateRecord
{
    protected static string $resource = GudangResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
