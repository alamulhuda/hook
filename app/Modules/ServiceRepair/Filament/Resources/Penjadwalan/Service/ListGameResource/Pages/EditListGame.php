<?php

namespace App\Modules\ServiceRepair\Filament\Resources\Penjadwalan\Service\ListGameResource\Pages;

use App\Modules\ServiceRepair\Filament\Resources\Penjadwalan\Service\ListGameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListGame extends EditRecord
{
    protected static string $resource = ListGameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
