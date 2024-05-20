<?php

namespace App\Filament\Employees\Resources\TimesheetResource\Pages;

use App\Filament\Employees\Resources\TimesheetResource;
use App\Models\Timesheet;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        $pending_timesheet = Timesheet::where([
            'user_id' => auth()->user()->id,
            'day_out' => null,
        ])->latest()->first();

        return [
            Actions\Action::make('inwork')
                ->label('Start Work')
                ->color('success')
                ->visible(!$pending_timesheet)
                ->requiresConfirmation()
                ->action(function () use ($pending_timesheet) {
                    $user = auth()->user();

                    if ($pending_timesheet) {
                        $pending_timesheet->day_out = now();
                        $pending_timesheet->save();
                    }

                    $timesheet = new Timesheet();
                    $timesheet->user_id = $user->id;
                    $timesheet->calendar_id = 1;
                    $timesheet->type = 'work';
                    $timesheet->day_in = now();
                    $timesheet->day_out = null;
                    $timesheet->save();

                    Notification::make()
                        ->title('Start working')
                        ->body('You have started working')
                        ->success()
                        ->color('success')
                        ->send();
                }),
            Actions\Action::make('outwork')
                ->label('Stop Work')
                ->color('success')
                ->visible($pending_timesheet && $pending_timesheet->type === 'work')
                ->requiresConfirmation()
                ->action(function () use ($pending_timesheet) {

                    if ($pending_timesheet) {
                        $pending_timesheet->day_out = now();
                        $pending_timesheet->save();

                        Notification::make()
                            ->title('Stop working')
                            ->body('You have stopped working')
                            ->success()
                            ->color('success')
                            ->send();
                    }
                }),
            Actions\Action::make('inpause')
                ->label('Start Pause')
                ->color('info')
                ->visible($pending_timesheet && $pending_timesheet->type === 'work')
                ->requiresConfirmation()
                ->action(function () use ($pending_timesheet) {
                    $user = auth()->user();

                    if ($pending_timesheet) {
                        $pending_timesheet->day_out = now();
                        $pending_timesheet->save();
                    }

                    $timesheet = new Timesheet();
                    $timesheet->user_id = $user->id;
                    $timesheet->calendar_id = 1;
                    $timesheet->type = 'pause';
                    $timesheet->day_in = now();
                    $timesheet->day_out = null;
                    $timesheet->save();

                    Notification::make()
                        ->title('Pause started')
                        ->body('You have started a pause')
                        ->info()
                        ->color('info')
                        ->send();

                }),
            Actions\Action::make('stoppause')
                ->label('Stop Pause')
                ->color('info')
                ->visible($pending_timesheet && $pending_timesheet->type === 'pause')
                ->requiresConfirmation()
                ->action(function () use ($pending_timesheet) {
                    $user = auth()->user();

                    if ($pending_timesheet) {
                        $pending_timesheet->day_out = now();
                        $pending_timesheet->save();
                    }

                    $timesheet = new Timesheet();
                    $timesheet->user_id = $user->id;
                    $timesheet->calendar_id = 1;
                    $timesheet->type = 'work';
                    $timesheet->day_in = now();
                    $timesheet->day_out = null;
                    $timesheet->save();

                    Notification::make()
                        ->title('Pause stopped')
                        ->body('You have stopped the pause and started working again')
                        ->info()
                        ->color('info')
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
