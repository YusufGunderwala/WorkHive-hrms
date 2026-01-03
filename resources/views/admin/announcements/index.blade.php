@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Announcements</h2>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i> Create Announcement
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success animate__animated animate__fadeIn">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="ps-4 py-3">Title</th>
                            <th>Department</th>
                            <th>Type</th>
                            <th>Schedule</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $announcement->title }}</div>
                                    <div class="small text-muted text-truncate" style="max-width: 200px;">
                                        {{ Str::limit($announcement->content, 50) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $announcement->department }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $typeClass = match ($announcement->type) {
                                            'urgent' => 'bg-danger-subtle text-danger',
                                            'warning' => 'bg-warning-subtle text-warning',
                                            default => 'bg-primary-subtle text-primary'
                                        };
                                        $icon = match ($announcement->type) {
                                            'urgent' => 'fa-exclamation-circle',
                                            'warning' => 'fa-bell',
                                            default => 'fa-info-circle'
                                        };
                                    @endphp
                                    <span class="badge {{ $typeClass }}">
                                        <i class="fas {{ $icon }} me-1"></i> {{ ucfirst($announcement->type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($announcement->start_date)->format('d M') }} -
                                        {{ \Carbon\Carbon::parse($announcement->end_date)->format('d M, Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light rounded-circle text-primary d-flex align-items-center justify-content-center me-2"
                                            style="width: 24px; height: 24px;">
                                            <i class="fas fa-user small"></i>
                                        </div>
                                        <span class="small">{{ $announcement->creator->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $today = now()->format('Y-m-d');
                                        $isActive = $today >= $announcement->start_date && $today <= $announcement->end_date;
                                    @endphp
                                    @if($isActive)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        @if($today < $announcement->start_date)
                                            <span class="badge bg-secondary">Scheduled</span>
                                        @else
                                            <span class="badge bg-dark">Expired</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this announcement?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fas fa-bullhorn fa-3x opacity-25"></i>
                                    </div>
                                    <p class="mb-0">No announcements found. Create one to get started!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
@endsection