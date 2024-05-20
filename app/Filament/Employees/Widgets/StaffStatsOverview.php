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
        // cast as User
        $user = Auth::user();
        // $user = User::find(Auth::id());


        return [
            Stat::make('Pending Holidays', $this->getPendingHolidays($user)),
            Stat::make('Approved Holidays', $this->getApprovedHolidays($user)),
            Stat::make('Total Work Of Week', $this->getTotalTimeSheets($user, 'work')),
            Stat::make('Total Pause Of Week', $this->getTotalTimeSheets($user, 'pause')),
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

    protected function getTotalTimeSheets(User $user, string $type = 'work')
    {
        $timesheets = Timesheet::query()->where([
            'user_id' => $user->id,
            'type' => $type,
        ])
            ->where(
                fn($query) => $query
                    ->where('day_in', '>=', Carbon::today()->startOfWeek())
                    ->where('day_out', '<=', Carbon::now()->endOfWeek())
            )->get();

        $time_diffs = $timesheets->map(function ($timesheet) {
            return [
                'hours' => Carbon::parse($timesheet->day_in)->diffInHours($timesheet->day_out),
                'minutes' => Carbon::parse($timesheet->day_in)->diffInMinutes($timesheet->day_out) % 60,
                'seconds' => Carbon::parse($timesheet->day_in)->diffInSeconds($timesheet->day_out) % 60,
            ];
        });

        $total_hours = $time_diffs->reduce(function ($carry, $time_diff) {
            return $carry + $time_diff['hours'];
        });

        $total_minutes = $time_diffs->reduce(function ($carry, $time_diff) {
            return $carry + $time_diff['minutes'];
        });

        $total_seconds = $time_diffs->reduce(function ($carry, $time_diff) {
            return $carry + $time_diff['seconds'];
        });

        $total_hours += floor($total_minutes / 60);
        $total_minutes += floor($total_seconds / 60);
        $total_seconds = $total_seconds % 60;

        return sprintf('%02d:%02d:%02d', $total_hours, $total_minutes, $total_seconds);

    }
}
