@extends('layouts.app')

@section('content')
    <h2 class="fw-bold mb-4">Attendance Records</h2>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>Employee</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $att)
                            <tr>
                                <td class="ps-4 text-muted">{{ $att->date }}</td>
                                <td class="fw-medium">{{ $att->user->name }}</td>
                                <td>{{ $att->check_in }}</td>
                                <td>{{ $att->check_out ?? '-' }}</td>
                                <td>
                                    @if($att->status == 'present')
                                        <span class="badge bg-success-subtle text-success border border-success">Present</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger">Absent</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $attendances->links() }}
        </div>
    </div>
@endsection