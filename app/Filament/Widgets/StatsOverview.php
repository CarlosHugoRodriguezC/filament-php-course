<?php

namespace App\Filament\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $total_employees = User::count();
        $total_holidays = Holiday::where(['type' => 'approved'])->count();
        $total_timesheets = Timesheet::count();

        return [
            Stat::make('Employees', $total_employees)
                ->description('Total number of employees'),
            Stat::make('Pending Holidays', $total_holidays)
                ->description('Total number of pending holidays'),
            Stat::make('Timesheets', $total_timesheets)
                ->description('Total number of timesheets'),
        ];
    }
}
