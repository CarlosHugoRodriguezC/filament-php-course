<?php

namespace App\Filament\Employees\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StaffStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {

        $user = Auth::user();

        return [
            Stat::make('Pending Holidays', $this->getPendingHolidays($user)),
            Stat::make('Approved Holidays', $this->getApprovedHolidays($user)),
            Stat::make('Total Work', $this->getTotalWork($user)),
        ];
    }

    protected function getPendingHolidays(User $user)
    {
        return Holiday::where('user_id', )
            ->where('type', 'pending')
            ->count();
    }

    protected function getApprovedHolidays(User $user)
    {
        return Holiday::where('user_id', $user->id)
            ->where('type', 'approved')
            ->count();
    }

    protected function getTotalWork(User $user)
    {
        $timesheets = Timesheet::query()->where([
            'user_id' => $user->id,
            'type' => 'work',
        ])->get();

        $time_diffs = $timesheets->map(function ($timesheet) {
            return Carbon::parse($timesheet->day_in)->diffInMinutes(Carbon::parse($timesheet->day_out));
        });

        $total_hours = round($time_diffs->sum() / 60, 2);



        return "{$total_hours} hours";
    }
}
