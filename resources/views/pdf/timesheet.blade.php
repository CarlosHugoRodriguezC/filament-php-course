<h1>
    <center>
        <strong>Timesheet</strong>
    </center>
</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Employee</th>
            <th>Project</th>
            <th>Start</th>
            <th>End</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($timesheets as $timesheet)
            <tr>
                <td>{{ $timesheet->user->name }}</td>
                <td>{{ $timesheet->calendar->name }}</td>
                <td>{{ $timesheet->day_in }}</td>
                <td>{{ $timesheet->day_out }}</td>
                <td>{{ $timesheet->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
