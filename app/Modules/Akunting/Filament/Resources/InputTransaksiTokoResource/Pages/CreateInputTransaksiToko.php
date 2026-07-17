<?php

namespace App\Modules\Akunting\Filament\Resources\InputTransaksiTokoResource\Pages;

use App\Modules\Akunting\Filament\Resources\InputTransaksiTokoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInputTransaksiToko extends CreateRecord
{
    protected static string $resource = InputTransaksiTokoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
