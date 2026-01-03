@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="fw-bold text-dark mb-1">Dashboard Overview</h2>
            <p class="text-muted mb-0">Welcome back, Administrator.</p>
        </div>
        <button class="btn btn-light shadow-sm text-primary fw-bold">
            <i class="fas fa-calendar-alt me-2"></i> {{ now()->format('d M, Y') }}
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3 animate__animated animate__fadeInUp delay-100">
            <div class="glass-card p-4 border-0 position-relative overflow-hidden h-100">
                <div class="d-flex justify-content-between align-items-start z-1">
                    <div>
                        <h6 class="text-uppercase text-secondary fw-bold small mb-2">Total Employees</h6>
                        <h2 class="fw-bold mb-0 text-dark display-5">{{ \App\Models\Employee::count() }}</h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 h-100 bg-gradient-primary opacity-5"
                    style="z-index: 0; transform: scale(3) translate(10%, 10%); border-radius: 50%;"></div>
            </div>
        </div>
        <div class="col-md-3 animate__animated animate__fadeInUp delay-200">
            <div class="glass-card p-4 border-0 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase text-secondary fw-bold small mb-2">Present Today</h6>
                        <h2 class="fw-bold mb-0 text-success display-5">
                            {{ \App\Models\Attendance::where('date', \Carbon\Carbon::today())->where('status', 'present')->count() }}
                        </h2>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="fas fa-user-check fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 animate__animated animate__fadeInUp delay-300">
            <div class="glass-card p-4 border-0 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase text-secondary fw-bold small mb-2">On Leave</h6>
                        <h2 class="fw-bold mb-0 text-warning display-5">
                            {{ \App\Models\Attendance::where('date', \Carbon\Carbon::today())->where('status', 'leave')->count() }}
                        </h2>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="fas fa-plane-departure fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 animate__animated animate__fadeInUp delay-300">
            <div class="glass-card p-4 border-0 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase text-secondary fw-bold small mb-2">Pending Requests</h6>
                        <h2 class="fw-bold mb-0 text-info display-5">
                            {{ \App\Models\Leave::where('status', 'pending')->count() }}
                        </h2>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                        <i class="fas fa-envelope-open-text fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-8 animate__animated animate__fadeInUp delay-300">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4">Attendance Trends</h5>
                <canvas id="attendanceChart" height="120"></canvas>
            </div>
        </div>
        <div class="col-md-4 animate__animated animate__fadeInUp delay-300">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4">Department Distribution</h5>
                <canvas id="deptChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent -->
    <div class="row g-4">
        <div class="col-md-8 animate__animated animate__fadeInUp delay-300">
            <div class="glass-card p-0 overflow-hidden">
                <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Recent Employees</h5>
                    <a href="{{ route('admin.employees.index') }}"
                        class="btn btn-sm btn-link text-decoration-none fw-bold">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Employee</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Employee::with('user')->latest()->take(5)->get() as $emp)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center me-3 text-white fw-bold shadow-sm"
                                                style="width: 36px; height: 36px; font-size: 0.8rem;">
                                                {{ substr($emp->user->name, 0, 1) }}
                                            </div>
                                            <span class="fw-bold text-dark">{{ $emp->user->name }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-secondary border">{{ $emp->designation }}</span></td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td class="text-secondary small fw-medium">{{ $emp->joining_date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4 animate__animated animate__fadeInUp delay-300">
            <div class="glass-card p-4">
                <h5 class="fw-bold mb-4">Quick Actions</h5>
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.employees.create') }}"
                        class="btn btn-light shadow-sm py-3 text-start fw-bold text-secondary d-flex align-items-center transition-hover">
                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3 text-primary"><i class="fas fa-plus"></i>
                        </div>
                        Add New Employee
                    </a>
                    <a href="{{ route('admin.payroll.index') }}"
                        class="btn btn-light shadow-sm py-3 text-start fw-bold text-secondary d-flex align-items-center transition-hover">
                        <div class="bg-success bg-opacity-10 p-2 rounded me-3 text-success"><i
                                class="fas fa-file-invoice-dollar"></i></div>
                        Generate Payroll
                    </a>
                    <a href="{{ route('admin.leaves.index') }}"
                        class="btn btn-light shadow-sm py-3 text-start fw-bold text-secondary d-flex align-items-center transition-hover">
                        <div class="bg-warning bg-opacity-10 p-2 rounded me-3 text-warning"><i class="fas fa-tasks"></i>
                        </div>
                        Review Leaves
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Attendance Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Present Employees',
                    data: {!! json_encode($data) !!},
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Dept Chart
        const deptCtx = document.getElementById('deptChart').getContext('2d');
        new Chart(deptCtx, {
            type: 'doughnut',
            data: {
                labels: ['IT', 'HR', 'Finance', 'Sales'],
                datasets: [{
                    data: [
                            {{ \App\Models\Employee::where('department', 'IT')->count() }},
                            {{ \App\Models\Employee::where('department', 'HR')->count() }},
                            {{ \App\Models\Employee::where('department', 'Finance')->count() }},
                        {{ \App\Models\Employee::where('department', 'Sales')->count() }}
                    ],
                    backgroundColor: ['#6366f1', '#ec4899', '#10b981', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                cutout: '70%'
            }
        });
    </script>
@endsection