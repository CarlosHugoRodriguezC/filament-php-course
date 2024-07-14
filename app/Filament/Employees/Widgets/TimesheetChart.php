<?php

namespace App\Filament\Employees\Widgets;

use App\Models\Timesheet;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TimesheetChart extends ChartWidget
{
    protected static ?string $heading = 'Timesheet Chart';

    public ?string $filter = 'week';

    protected int|string|array $columnSpan = 'full';

    protected function getFilters(): array|null
    {
        return [
            'week' => 'Week',
            'month' => 'Month',
            'year' => 'Year',
        ];
    }


    protected function getData(): array
    {

        $activeFilter = $this->filter;

        if ($activeFilter === 'week') {
            $start = now()->startOfWeek();
            $end = now()->endOfWeek();
        } elseif ($activeFilter === 'month') {
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
        } elseif ($activeFilter === 'year') {
            $start = now()->startOfYear();
            $end = now()->endOfYear();
        }

        $data = Trend::query(
            Timesheet::query()
                ->where([
                    'user_id' => auth()->id(),
                    'type' => 'work',
                ])->distinct('date')
        )
            ->dateColumn('day_in')
            ->between(
                start: $start,
                end: $end,
            )

            ->perDay()
            ->count();



        return [
            'datasets' => [
                [
                    'label' => 'Work Timesheets',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#3490dc',
                    'borderColor' => '#3490dc',
                ],

            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
