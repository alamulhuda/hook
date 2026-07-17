<?php

namespace App\Modules\Reports\Filament\Resources\PembelianReportResource\Pages;

use App\Modules\Reports\Filament\Resources\PembelianReportResource;
use Filament\Resources\Pages\ListRecords;

class ListPembelianReports extends ListRecords
{
    protected static string $resource = PembelianReportResource::class;

    public function mount(): void
    {
        parent::mount();

        if (blank($this->tableFilters)) {
            $this->tableFilters = [
                'periodik' => [
                    'isActive' => true,
                    'period_type' => 'monthly',
                    'month' => now()->month,
                    'year' => now()->year,
                ],
            ];
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
