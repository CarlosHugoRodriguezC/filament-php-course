<?php

namespace App\Filament\Resources\TimesheetResource\Pages;

use App\Filament\Resources\TimesheetResource;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Barryvdh\DomPDF\Facade\Pdf;


class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->color('gray'),
            Action::make('createPDF')
                ->label('Export PDF')
                ->color('gray')
                ->requiresConfirmation()
                ->action(function () {
                    $model = TimesheetResource::getModel();
                    return response()->streamDownload(function () use ($model) {
                        $data = [
                            'timesheets' => TimesheetResource::getModel()::all(),
                        ];
                        echo Pdf::loadView('pdf.timesheet', $data)->stream();
                        
                    }, 'timesheet.pdf');
                }),
            Actions\CreateAction::make(),
        ];
    }
}
