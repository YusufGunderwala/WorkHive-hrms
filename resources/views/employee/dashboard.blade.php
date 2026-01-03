@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-5 animate__animated animate__fadeInDown">
        <div>
            <h2 class="fw-bold text-dark mb-1">My Dashboard</h2>
            <p class="text-muted mb-0">Overview of your activity and performance.</p>
        </div>

        <div class="d-flex align-items-center gap-3">
            <!-- Unified Time & Announcement Card -->
            <div class="glass-card px-3 py-2 d-flex align-items-center gap-3">
                {{-- Clock Section --}}
                <div class="d-flex align-items-center gap-2 border-end pe-3">
                    <i class="fas fa-clock text-primary"></i>
                    <span class="fw-bold font-monospace text-dark" id="live-clock">{{ now()->format('H:i:s') }}</span>
                </div>

                {{-- Announcement Section (Latest only) --}}
                @if(isset($announcements) && $announcements->count() > 0)
                    @php $latest = $announcements->first(); @endphp
                    <div class="d-flex align-items-center gap-2 animate__animated animate__fadeIn" style="max-width: 400px;">
                        <i class="fas fa-bullhorn text-warning"></i>
                        <div class="text-truncate">
                            <span class="fw-bold text-dark">{{ $latest->title }}:</span>
                            <span class="text-muted small">{{ $latest->content }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <div class="row g-4 mb-5">
        <!-- Attendance Action Card -->
        <div class="col-md-6 animate__animated animate__fadeInUp delay-100">
            <div class="glass-card p-5 h-100 text-center position-relative overflow-hidden">
                <div class="position-relative z-1">
                    @php
                        $todayAttendance = \App\Models\Attendance::where('user_id', Auth::id())->where('date', \Carbon\Carbon::today())->first();
                    @endphp

                    @if(!$todayAttendance)
                        <div class="mb-4">
                            <div class="bg-success bg-opacity-10 p-4 rounded-circle d-inline-block mb-3 text-success">
                                <i class="fas fa-fingerprint fa-3x"></i>
                            </div>
                            <h4 class="fw-bold">Ready to start?</h4>
                            <p class="text-muted">Mark your attendance for today.</p>
                        </div>
                        <form action="{{ route('employee.attendance.checkIn') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-75 shadow-lg rounded-pill fw-bold btn-grad-primary">
                                <i class="fas fa-sign-in-alt me-2"></i> CHECK IN NOW
                            </button>
                        </form>
                        
                        <style>
                            .btn-grad-primary {
                                background-image: linear-gradient(to right, #6366f1 0%, #a855f7 51%, #6366f1 100%);
                                transition: 0.5s;
                                background-size: 200% auto;
                                border: none;
                            }
                            .btn-grad-primary:hover {
                                background-position: right center;
                            }
                        </style>

                    @elseif(!$todayAttendance->check_out)
                        <div class="mb-4">
                            @php
                                $checkInTime = \Carbon\Carbon::parse($todayAttendance->check_in);
                                $isLate = $checkInTime->gt(\Carbon\Carbon::parse('09:30:00'));
                                $isOvertime = \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse('18:00:00'));
                            @endphp

                            <div class="position-relative d-inline-block mb-3">
                                <div class="bg-warning bg-opacity-10 p-4 rounded-circle text-warning {{ $isOvertime ? 'animate__animated animate__pulse animate__infinite' : '' }} {{ $isLate ? 'shake-icon' : '' }}">
                                    <i class="fas {{ $isOvertime ? 'fa-business-time' : 'fa-hourglass-half' }} fa-3x"></i>
                                </div>
                                @if($isLate)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger late-badge-pulse">
                                        LATE
                                    </span>
                                @endif
                            </div>

                            <h4 class="fw-bold">
                                @if($isOvertime)
                                    Working Overtime! 🌟
                                @else
                                    Working...
                                @endif
                            </h4>
                            
                            <p class="text-muted">
                                You clocked in at <span class="fw-bold text-dark">{{ $checkInTime->format('h:i A') }}</span>
                                @if($isLate)
                                    <span class="text-danger small fw-bold ms-1 animate__animated animate__flash animate__slow animate__infinite">(Late)</span>
                                @endif
                            </p>
                        </div>
                        
                        <form action="{{ route('employee.attendance.checkOut') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn {{ $isOvertime ? 'btn-grad-celebrate' : 'btn-warning' }} text-white btn-lg w-75 shadow-lg rounded-pill fw-bold position-relative overflow-hidden">
                                @if($isOvertime)
                                    <span class="position-absolute w-100 h-100 top-0 start-0 confetti-bg"></span>
                                @endif
                                <i class="fas fa-sign-out-alt me-2"></i> COMPLETE DAY
                            </button>
                        </form>

                        <style>
                            .btn-grad-celebrate {
                                background-image: linear-gradient(to right, #FF512F 0%, #DD2476 51%, #FF512F 100%);
                                transition: 0.5s;
                                background-size: 200% auto;
                                border: none;
                            }
                            .btn-grad-celebrate:hover {
                                background-position: right center;
                            }
                            @keyframes confetti {
                                0% { background-position: 0 0; }
                                100% { background-position: 100% 100%; }
                            }
                            
                            /* Late Badge Pulse */
                            .late-badge-pulse {
                                animation: late-pulse 2s infinite;
                            }
                            @keyframes late-pulse {
                                0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); transform: translate(-50%, -50%) scale(1); }
                                50% { transform: translate(-50%, -50%) scale(1.1); }
                                70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); transform: translate(-50%, -50%) scale(1.1); }
                                100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); transform: translate(-50%, -50%) scale(1); }
                            }

                            /* Shake Icon for Late */
                            .shake-icon {
                                animation: subtle-shake 4s infinite;
                            }
                            @keyframes subtle-shake {
                                0%, 100% { transform: rotate(0deg); }
                                2% { transform: rotate(-10deg); }
                                4% { transform: rotate(10deg); }
                                6% { transform: rotate(-10deg); }
                                8% { transform: rotate(0deg); }
                            }
                        </style>
                    @else
                        @php
                            $outTime = \Carbon\Carbon::parse($todayAttendance->check_out);
                            $isEarly = $outTime->hour < 18; // Before 6 PM
                        @endphp
                        
                        <div>
                            @if($isEarly)
                                <div class="bg-warning bg-opacity-10 p-4 rounded-circle d-inline-block mb-3 text-warning animate__animated animate__pulse animate__infinite">
                                    <i class="fas fa-walking fa-3x"></i>
                                </div>
                                <h4 class="fw-bold text-warning">Leaving Early?</h4>
                                <p class="text-muted">You checked out before <span class="fw-bold">06:00 PM</span>.</p>
                            @else
                                <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-block mb-3 text-primary">
                                    <i class="fas fa-check-double fa-3x"></i>
                                </div>
                                <h4 class="fw-bold text-success">Good Job!</h4>
                                <p class="text-muted">You have completed your work day.</p>
                            @endif

                            <div class="d-flex justify-content-center gap-4 mt-4">
                                <div>
                                    <small class="text-uppercase text-muted fw-bold">In Time</small>
                                    <div class="fw-bold fs-5">
                                        {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</div>
                                </div>
                                <div>
                                    <small class="text-uppercase text-muted fw-bold">Out Time</small>
                                    <div class="fw-bold fs-5">
                                        {{ $outTime->format('H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Abstract shapes -->
                <!-- Abstract shapes -->
                <div class="position-absolute blur-orb-1"></div>
                <div class="position-absolute blur-orb-2"></div>
                
                <style>
                    .blur-orb-1 {
                        width: 180px; 
                        height: 180px; 
                        border-radius: 50%;
                        background: linear-gradient(135deg, rgba(99, 102, 241, 0.4), rgba(168, 85, 247, 0.4));
                        filter: blur(40px);
                        top: 0;
                        right: 0;
                        transform: translate(20%, -20%);
                        z-index: 0;
                        animation: floatOne 8s ease-in-out infinite alternate;
                    }
                    .blur-orb-2 {
                        width: 150px; 
                        height: 150px; 
                        border-radius: 50%;
                        background: linear-gradient(135deg, rgba(236, 72, 153, 0.4), rgba(244, 63, 94, 0.4));
                        filter: blur(40px);
                        bottom: 0;
                        left: 0;
                        transform: translate(-20%, 20%);
                        z-index: 0;
                        animation: floatTwo 10s ease-in-out infinite alternate;
                    }
                    @keyframes floatOne {
                        0% { transform: translate(20%, -20%) rotate(0deg) scale(1); }
                        100% { transform: translate(10%, -10%) rotate(20deg) scale(1.1); }
                    }
                    @keyframes floatTwo {
                        0% { transform: translate(-20%, 20%) rotate(0deg) scale(1); }
                        100% { transform: translate(-10%, 10%) rotate(-20deg) scale(1.2); }
                    }
                </style>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="col-md-6 animate__animated animate__fadeInUp delay-200">
            <!-- ... existing stats grid ... -->
            <div class="row g-4 h-100">
                <div class="col-6">
                    <div class="glass-card p-4 h-100 d-flex flex-column justify-content-center align-items-center text-center">
                        <h1 class="display-4 fw-bold text-primary mb-1">
                            {{ Auth::user()->leaves()->where('status', 'approved')->count() }}</h1>
                        <span class="text-secondary fw-bold small text-uppercase">Leaves Approved</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="glass-card p-4 h-100 d-flex flex-column justify-content-center align-items-center text-center">
                        <h1 class="display-4 fw-bold text-success mb-1">
                            {{ Auth::user()->attendances()->where('status', 'present')->count() }}</h1>
                        <span class="text-secondary fw-bold small text-uppercase">Days Worked</span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="glass-card p-4 h-100 d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">My Department</h5>
                            <p class="text-muted mb-0">You are a key member of the <span class="fw-bold text-dark">{{ Auth::user()->employee->department }}</span> team.</p>
                        </div>
                        <div class="bg-light p-3 rounded-circle text-primary">
                            <i class="fas fa-network-wired fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Upcoming Holidays & Leave Tracker Row -->
    <div class="row g-4 mb-5 animate__animated animate__fadeInUp delay-300">
        <!-- Holidays -->
        <div class="col-md-6">
            <h5 class="fw-bold mb-3 d-flex align-items-center">
                <i class="fas fa-umbrella-beach text-info me-2"></i> Upcoming Holidays
            </h5>
            
            @if(isset($upcomingHolidays) && count($upcomingHolidays) > 0)
                <div class="glass-card mb-4 overflow-hidden">
                    <div class="list-group list-group-flush bg-transparent" style="max-height: 300px; overflow-y: auto;">
                        @foreach($upcomingHolidays as $holiday)
                            <div class="list-group-item bg-transparent border-light d-flex align-items-center py-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 text-center me-3" style="min-width: 60px;">
                                    <span class="d-block small text-uppercase fw-bold">{{ \Carbon\Carbon::parse($holiday['date'])->format('M') }}</span>
                                    <span class="h4 fw-bold mb-0">{{ \Carbon\Carbon::parse($holiday['date'])->format('d') }}</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-0 text-dark">{{ $holiday['name'] }}</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($holiday['date'])->format('l') }}</small>
                                </div>
                                @if(\Carbon\Carbon::parse($holiday['date'])->isToday())
                                    <span class="badge bg-success rounded-pill px-3">Today</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="glass-card p-4 text-center text-muted">
                    <div class="mb-3 text-secondary opacity-50">
                        <i class="fas fa-calendar-check fa-3x"></i>
                    </div>
                    <p class="mb-0">No holidays this month.</p>
                </div>
            @endif
        </div>

        <!-- Leave Tracker -->
        <!-- Leave Tracker -->
        <div class="col-md-6">
            <h5 class="fw-bold mb-3 d-flex align-items-center">
                <i class="fas fa-chart-pie text-secondary me-2"></i> Leave Tracker
            </h5>
            <div class="glass-card p-4 h-100">
                <div class="d-flex flex-column gap-4">
                    @foreach($leaveBalances as $type => $balance)
                        @if($balance['total'] > 0)
                            @php
                                $percentage = ($balance['used'] / $balance['total']) * 100;
                                $color = $percentage > 80 ? 'danger' : ($percentage > 50 ? 'warning' : 'success');
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold text-dark">{{ $type }} Leave</span>
                                    <span class="small fw-bold text-muted">{{ $balance['used'] }} / {{ $balance['total'] }} Days</span>
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 20px;">
                                    <div class="progress-bar bg-{{ $color }} progress-bar-striped progress-bar-animated" role="progressbar" 
                                         style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @else
                             <!-- Unpaid or Unlimited -->
                        @endif
                    @endforeach
                     <div class="text-center mt-2">
                        <a href="{{ route('employee.leaves.create') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-bold">
                            <i class="fas fa-plus me-1"></i> Apply Leave
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Records -->
    <div class="glass-card p-4 animate__animated animate__fadeInUp delay-300">
        <h5 class="fw-bold mb-3">Recent Attendance</h5>
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(Auth::user()->attendances()->latest()->take(5)->get() as $att)
                        <tr>
                            <td class="ps-3 fw-medium">{{ $att->date }}</td>
                            <td>{{ $att->check_in }}</td>
                            <td>{{ $att->check_out ?? '-' }}</td>
                            <td>
                                @if($att->status == 'present' || ($att->check_in && $att->check_out))
                                    <span class="badge badge-success">Present</span>
                                @else
                                    <span class="badge badge-warning">{{ ucfirst($att->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- The original live-clock script was here. It's now replaced by the new structure and script. --}}
        </div>
    </div>

    {{-- Check-in Reminder Script --}}
    @if(!$hasCheckedIn)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })

                // Update live clock every second
                setInterval(() => {
                    const now = new Date();
                    document.getElementById('live-clock').innerText = now.toLocaleTimeString();
                }, 1000);

                // Check every minute for reminder
                setInterval(checkTimeForReminder, 30000);
            });

            function checkTimeForReminder() {
                const now = new Date();
                const hours = now.getHours();
                const minutes = now.getMinutes();
                const isWeekday = now.getDay() !== 0 && now.getDay() !== 6;

                // Logic: 08:55 AM (Production)
                if (isWeekday && hours === 8 && minutes === 55) {
                    
                    // Don't show if already snoozed or visible
                    const snoozeBtn = document.getElementById('snooze-btn');
                    if(Swal.isVisible() || !snoozeBtn.classList.contains('d-none')) return;

                    fireCheckInReminder();
                }
            }

            function fireCheckInReminder() {
                Swal.fire({
                    title: '<span class="fw-bold">Good Morning! ☀️</span>',
                    html: '<p class="text-muted mb-0">It is <b>08:55 AM</b>.<br>Don\'t forget to mark your attendance!</p>',
                    icon: 'info',
                    iconColor: '#6366f1',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-fingerprint me-2"></i>Check In Now',
                    cancelButtonText: 'I will do it later',
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#9ca3af',
                    backdrop: `rgba(0,0,123,0.1) left top no-repeat`,
                    customClass: {
                        popup: 'glass-card border-0 shadow-lg rounded-4',
                        title: 'text-dark',
                        confirmButton: 'btn btn-primary rounded-pill px-4 py-2 shadow-sm',
                        cancelButton: 'btn btn-light rounded-pill px-4 py-2 text-muted'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    const snoozeBtn = document.getElementById('snooze-btn');
                    
                    if (result.isConfirmed) {
                        // User Agreed: Hide snooze, verify action
                        snoozeBtn.classList.add('d-none');
                        document.querySelector('.fa-fingerprint').scrollIntoView({ behavior: 'smooth' });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // User Clicked Later: Show snooze icon
                        snoozeBtn.classList.remove('d-none');
                        
                        // Optional: Tiny toast to confirm snooze
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Reminder snoozed'
                        });
                    }
                });
            }
        </script>
    @endif
@endsection