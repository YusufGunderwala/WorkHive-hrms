@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">My Leave Requests</h2>
        <a href="{{ route('employee.leaves.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Apply Leave
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Admin Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $leave)
                            <tr>
                                <td class="ps-4 fw-medium">{{ ucfirst($leave->type) }}</td>
                                <td>{{ $leave->start_date }}</td>
                                <td>{{ $leave->end_date }}</td>
                                <td class="text-muted small">{{ Str::limit($leave->reason, 40) }}</td>
                                <td>
                                    @if($leave->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leave->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $leave->admin_comment ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $leaves->links() }}
        </div>
    </div>
@endsection