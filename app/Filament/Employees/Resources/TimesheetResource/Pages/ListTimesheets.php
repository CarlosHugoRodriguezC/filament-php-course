<?php

namespace App\Filament\Employees\Resources\TimesheetResource\Pages;

use App\Filament\Employees\Resources\TimesheetResource;
use App\Models\Timesheet;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('inwork')
                ->label('Start Work')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $user = auth()->user();
                    $timesheet = new Timesheet();
                    $timesheet->user_id = $user->id;
                    $timesheet->calendar_id = 1;
                    $timesheet->type = 'work';
                    $timesheet->day_in = now();
                    $timesheet->day_out = now();
                    $timesheet->save();
                }),
            Actions\Action::make('inpause')
                ->label('Start Pause')
                ->color('info')
                ->requiresConfirmation(),
            Actions\CreateAction::make(),
        ];
    }
}
