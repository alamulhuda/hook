<?php

namespace App\Modules\Notifications\Filament\Resources\NotificationResource\Pages;

use App\Modules\Notifications\Filament\Resources\NotificationResource;
use Filament\Resources\Pages\ListRecords;

class ListNotifications extends ListRecords
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
