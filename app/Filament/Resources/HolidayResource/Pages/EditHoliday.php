<?php

namespace App\Filament\Resources\HolidayResource\Pages;

use App\Filament\Resources\HolidayResource;
use App\Mail\HolidayApproved;
use App\Mail\HolidayDeclined;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Mail;

class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        // Send email to user if status is approved

        $record->load('user');

        if ($record->type === 'approved') {
            $dataToSend = [
                'day' => $record->day,
                'name' => $record->user->name,
                'email' => $record->user->email,
            ];

            Mail::to($record->user->email)->send(
                new HolidayApproved(
                    $dataToSend
                )
            );

            $this->record->load('user');
            Notification::make()
                ->title('Holiday approved')
                ->body("Your holiday on {$record->day} has been approved.")
                ->success()
                ->sendToDatabase($record->user);
            Notification::make()
                ->title('Holiday approved')
                ->body("You have approved the holiday on {$record->day} for {$record->user->name}.")
                ->success()
                ->sendToDatabase(auth()->user());
        }

        if ($record->type === 'declined') {
            $dataToSend = [
                'day' => $record->day,
                'name' => $record->user->name,
                'email' => $record->user->email,
            ];

            Mail::to($record->user->email)->send(
                new HolidayDeclined(
                    $dataToSend
                )
            );

            $this->record->load('user');
            Notification::make()
                ->title('Holiday declined')
                ->body("Your holiday on {$record->day} has been declined.")
                ->success()
                ->sendToDatabase($record->user);
            Notification::make()
                ->title('Holiday declined')
                ->body("You have declined the holiday on {$record->day} for {$record->user->name}.")
                ->success()
                ->sendToDatabase(auth()->user());
        }

        return $record;
    }

    protected function getSavedNotification(): Notification
    {
        $recipient = auth()->user();
        return Notification::make()
            ->title('Holiday updated')
            ->body('The holiday has been updated successfully.')
            ->success()
            ->sendToDatabase($recipient);
    }
}
