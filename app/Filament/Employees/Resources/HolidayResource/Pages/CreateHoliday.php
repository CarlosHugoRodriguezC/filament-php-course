<?php

namespace App\Filament\Employees\Resources\HolidayResource\Pages;

use App\Filament\Employees\Resources\HolidayResource;
use App\Mail\HolidayPending;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Mail;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['type'] = 'pending';

        return $data;
    }

    protected function getCreatedNotification(): Notification
    {
        $recipient = auth()->user();
        return Notification::make()
            ->title('Holiday created')
            ->body('The holiday has been created successfully.')
            ->success()
            ->sendToDatabase($recipient);
    }
}
