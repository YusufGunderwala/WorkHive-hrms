@extends('layouts.app')

@section('content')
    <x-employee-header 
        title="My Leaves" 
        description="Manage your leave requests and balances."
    >
        <x-slot:actions>
            <a href="{{ route('employee.leaves.create') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm fw-bold">
                <i class="fas fa-plus me-2"></i> Apply Leave
            </a>
        </x-slot:actions>
    </x-employee-header>

    <!-- Glass Table Card -->
    <div class="glass-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr style="border-bottom: 2px solid rgba(0,0,0,0.05);">
                        <th class="ps-4 py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Type</th>
                        <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">From</th>
                        <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">To</th>
                        <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Reason</th>
                        <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Status</th>
                        <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Admin Comment</th>
                    </tr>
                </thead>
                <tbody class="bg-white bg-opacity-50">
                    @foreach($leaves as $leave)
                        <tr class="border-bottom border-light">
                            <td class="ps-4 fw-bold text-dark py-3">{{ ucfirst($leave->type) }}</td>
                            <td class="text-secondary">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                            <td class="text-secondary">{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                            <td class="text-muted small" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $leave->reason }}
                            </td>
                            <td>
                                @if($leave->status == 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-10">
                                        <i class="fas fa-check-circle me-1"></i> Approved
                                    </span>
                                @elseif($leave->status == 'rejected')
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-10">
                                        <i class="fas fa-times-circle me-1"></i> Rejected
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 border border-warning border-opacity-10">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="small text-secondary">{{ $leave->admin_comment ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-3 border-top border-light border-opacity-50">
            {{ $leaves->links() }}
        </div>
    </div>
@endsection