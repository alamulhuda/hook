<?php

namespace App\Modules\Marketing\Filament\Resources\KontenSosmed\ContentCalendarResource\Pages;

use App\Modules\Marketing\Filament\Resources\KontenSosmed\ContentCalendarResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContentCalendar extends CreateRecord
{
    protected static string $resource = ContentCalendarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Simpan')
                ->icon('heroicon-m-check')
                ->color('primary')
                ->submit(null)
                ->action('create'),
            ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
            $this->getCancelFormAction()
                ->label('Batal')
                ->icon('heroicon-m-x-mark')
                ->color('danger'),
        ];
    }
}
