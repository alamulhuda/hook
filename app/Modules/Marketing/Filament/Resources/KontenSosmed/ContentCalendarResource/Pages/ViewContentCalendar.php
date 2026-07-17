<?php

namespace App\Modules\Marketing\Filament\Resources\KontenSosmed\ContentCalendarResource\Pages;

use App\Modules\Marketing\Filament\Resources\KontenSosmed\ContentCalendarResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContentCalendar extends ViewRecord
{
    protected static string $resource = ContentCalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
