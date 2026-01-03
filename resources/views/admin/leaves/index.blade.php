@extends('layouts.app')

@section('content')
    <h2 class="fw-bold mb-4">Leave Requests</h2>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Employee</th>
                            <th>Type</th>
                            <th>Duration</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $leave)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $leave->user->name }}</td>
                                <td>{{ ucfirst($leave->type) }}</td>
                                <td>
                                    <div class="small text-muted">{{ $leave->start_date }} to</div>
                                    <div>{{ $leave->end_date }}</div>
                                </td>
                                <td class="small text-muted" style="max-width: 200px;">{{ Str::limit($leave->reason, 50) }}</td>
                                <td>
                                    @if($leave->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leave->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($leave->status == 'pending')
                                        <div class="d-flex gap-1">
                                            <form action="{{ route('admin.leaves.updateStatus', $leave->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-success" title="Approve"><i
                                                        class="fas fa-check"></i></button>
                                            </form>
                                            <form action="{{ route('admin.leaves.updateStatus', $leave->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Reject"><i
                                                        class="fas fa-times"></i></button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
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