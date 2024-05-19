<?php

namespace App\Filament\Employees\Resources\HolidayResource\Pages;

use App\Filament\Employees\Resources\HolidayResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['type'] = 'pending';

        return $data;
    }
}
