```
@extends('layouts.app')

@section('content')
    <x-employee-header title="My Attendance" description="Track your daily check-ins and working hours.">
        <x-slot:actions>
            <div class="btn-group shadow-sm" role="group">
                <button type="button" class="btn btn-light active" onclick="switchView('list')" id="btn-list"
                    data-bs-toggle="tooltip" title="List View"><i class="fas fa-list"></i></button>
                <button type="button" class="btn btn-light" onclick="switchView('grid')" id="btn-grid"
                    data-bs-toggle="tooltip" title="Grid View"><i class="fas fa-th-large"></i></button>
                <button type="button" class="btn btn-light" onclick="switchView('timeline')" id="btn-timeline"
                    data-bs-toggle="tooltip" title="Timeline View"><i class="fas fa-stream"></i></button>
                <button type="button" class="btn btn-light" onclick="switchView('calendar')" id="btn-calendar"
                    data-bs-toggle="tooltip" title="Calendar View"><i class="fas fa-calendar-alt"></i></button>
            </div>
        </x-slot:actions>
    </x-employee-header>

    <!-- List View -->
    <div id="view-list" class="view-section animate__animated animate__fadeIn">
        <div class="glass-card table-responsive">
            <table class="table table-hover align-middle mb-0 custom-table">
                <thead class="bg-light text-uppercase text-secondary small">
                    <tr>
                        <th class="ps-4 py-3">Date</th>
                        <th>First In</th>
                        <th>Last Out</th>
                        <th>Total Hours</th>
                        <th>Payable</th>
                        <th>Overtime</th>
                        <th>Status</th>
                        <th>Shift(s)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $att)
                        @php
                            $in = \Carbon\Carbon::parse($att->check_in);
                            $out = $att->check_out ? \Carbon\Carbon::parse($att->check_out) : null;
                            $formattedTotal = '-';
                            $overtimeLabel = '-';

                            // Re-evaluate status: If we have check-in/out, it is PRESENT, overriding absent
                            $displayStatus = $att->status;
                            if ($att->check_in && $att->check_out && $att->status === 'absent') {
                                $displayStatus = 'present';
                            }

                            if ($out) {
                                // Total Duration
                                $formattedTotal = $out->diff($in)->format('%H:%I');

                                // Overtime Logic: Check if worked past 18:00 (6 PM)
                                // We can also calculate extra hours
                                $endOfShift = \Carbon\Carbon::parse($att->date . ' 18:00:00');
                                if ($out->gt($endOfShift)) {
                                    $otDuration = $out->diff($endOfShift)->format('%H:%I');
                                    $overtimeLabel = '<span class="badge bg-success text-white"><i class="fas fa-clock me-1"></i>+' . $otDuration . '</span>';
                                }
                            }

                            $badgeClass = match ($displayStatus) {
                                'present' => 'bg-success-subtle text-success',
                                'absent' => 'bg-danger-subtle text-danger',
                                'weekend' => 'bg-warning-subtle text-warning',
                                'holiday' => 'bg-info-subtle text-info',
                                default => 'bg-secondary-subtle text-secondary'
                            };
                        @endphp
                        <tr>
                            <td class="ps-4 fw-medium">{{ \Carbon\Carbon::parse($att->date)->format('D, d-M-Y') }}</td>
                            <td>{{ $in->format('h:i A') }}</td>
                            <td>{{ $out ? $out->format('h:i A') : '-' }}</td>
                            <td>{{ $formattedTotal }}</td>
                            <td>09:00</td>
                            <td>{!! $overtimeLabel !!}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ ucfirst($displayStatus) }}</span></td>
                            <td>General</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-3">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>

    <!-- Grid View -->
    <div id="view-grid" class="view-section d-none animate__animated animate__fadeIn">
        <div class="row g-4">
            @foreach($attendances as $att)
                <div class="col-md-6 col-lg-4">
                    <div class="glass-card p-4 h-100 position-relative">
                        <div class="d-flex justify-content-between mb-3">
                            <span
                                class="badge {{ $att->status == 'present' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($att->status) }}</span>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($att->date)->format('d M, Y') }}</small>
                        </div>
                        <div class="text-center mb-3">
                            <h3 class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($att->check_in)->format('h:i A') }}</h3>
                            <small class="text-secondary text-uppercase fw-bold">Check In</small>
                        </div>
                        @if($att->check_out)
                            <div class="d-flex justify-content-between border-top pt-3">
                                <div>
                                    <small class="d-block text-muted">Check Out</small>
                                    <span class="fw-bold">{{ \Carbon\Carbon::parse($att->check_out)->format('h:i A') }}</span>
                                </div>
                                <div class="text-end">
                                    <small class="d-block text-muted">Total Hrs</small>
                                    <span
                                        class="fw-bold">{{ \Carbon\Carbon::parse($att->check_out)->diff(\Carbon\Carbon::parse($att->check_in))->format('%H:%I') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center border-top pt-3 text-warning">
                                <i class="fas fa-clock me-1"></i> Working...
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    </div>

    <!-- Calendar View -->
    <div id="view-calendar" class="view-section d-none animate__animated animate__fadeIn">
        <div class="glass-card p-4">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Timeline View (Gantt Style) -->
    <div id="view-timeline" class="view-section d-none animate__animated animate__fadeIn">
        <div class="glass-card p-4">
            <!-- Timeline Header -->
            <div class="d-flex justify-content-between text-muted text-uppercase small fw-bold border-bottom pb-2 mb-3">
                <div style="width: 15%">Date</div>
                <div class="flex-grow-1 position-relative">
                    <div class="d-flex justify-content-between">
                        <span>09 AM</span>
                        <span>12 PM</span>
                        <span>03 PM</span>
                        <span>06 PM</span>
                    </div>
                </div>
                <div style="width: 15%" class="text-end">Total Hours</div>
            </div>

            <!-- Timeline Rows -->
            <div class="d-flex flex-column gap-3">
                @foreach($attendances as $att)
                    @php
                        $in = \Carbon\Carbon::parse($att->check_in);
                        $out = $att->check_out ? \Carbon\Carbon::parse($att->check_out) : null;

                        // Calculate percentage position (09:00 to 18:00 = 9 hours)
                        // If outside range, clamp or adjust. Let's assume standard 09-18 view but flexible.
                        $startHour = 9;
                        $totalViewHours = 9; // 9am to 6pm

                        $startPercent = 0;
                        $widthPercent = 0;

                        if ($att->status == 'present') {
                            $inFloat = $in->hour + ($in->minute / 60);
                            // Offset from 9am
                            $startOffset = $inFloat - $startHour;
                            $startPercent = ($startOffset / $totalViewHours) * 100;

                            // Duration
                            if ($out) {
                                $outFloat = $out->hour + ($out->minute / 60);
                                $duration = $outFloat - $inFloat;
                                $widthPercent = ($duration / $totalViewHours) * 100;
                            } else {
                                // Currently working, maybe show till now or max?
                                $nowFloat = now()->hour + (now()->minute / 60);
                                $duration = min($nowFloat, 18) - $inFloat; // Cap at 6pm visual
                                $widthPercent = max(0, ($duration / $totalViewHours) * 100);
                            }
                        } else {
                            // Full width for status (Holiday/Weekend)
                            $startPercent = 0;
                            $widthPercent = 100;
                        }

                        $barColor = match ($att->status) {
                            'present' => 'bg-warning', // As per screenshot (orange/yellow)
                            'weekend' => 'bg-warning-subtle',
                            'holiday' => 'bg-info-subtle',
                            'absent' => 'bg-danger-subtle',
                            default => 'bg-secondary-subtle'
                        };

                        $textColor = match ($att->status) {
                            'present' => 'text-dark',
                            default => 'text-muted'
                        };
                    @endphp

                    <div class="d-flex align-items-center">
                        <!-- Date Column -->
                        <div style="width: 15%" class="d-flex flex-column">
                            <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($att->date)->format('D') }}</span>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($att->date)->format('d') }}</small>
                        </div>

                        <!-- Timeline Bar Column -->
                        <div class="flex-grow-1 position-relative" style="height: 40px;">
                            <!-- Background Track line -->
                            <div class="position-absolute top-50 start-0 w-100 border-top border-2 border-light"></div>

                            <!-- Start Dot/Line -->
                            <div class="position-absolute top-50 translate-middle-y"
                                style="left: 0%; width: 5px; height: 5px; border-radius: 50%; background: #e0e0e0;"></div>
                            <div class="position-absolute top-50 translate-middle-y"
                                style="right: 0%; width: 5px; height: 5px; border-radius: 50%; background: #e0e0e0;"></div>

                            <!-- The Bar -->
                            @if($att->status == 'present')
                                <div class="position-absolute top-50 translate-middle-y h-50 rounded-pill {{ $barColor }}"
                                    style="left: {{ max(0, min(100, $startPercent)) }}%; width: {{ max(5, min(100, $widthPercent)) }}%; min-width: 10px;">
                                </div>
                                <!-- Status Text Centered on Bar (or Middle of track if not present) -->
                            @else
                                <!-- Center Badge for Non-Working Days -->
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <span class="badge border {{ $barColor }} {{ $textColor }} fw-normal px-3 py-1 rounded-pill">
                                        {{ $att->status == 'holiday' ? 'Holiday' : ucfirst($att->status) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Hours Column -->
                        <div style="width: 15%" class="text-end">
                            <span class="d-block fw-bold text-dark">
                                {{ $att->status == 'present' && $out ? $out->diff($in)->format('%H:%I') : '00:00' }}
                            </span>
                            <small class="text-muted">Hrs worked</small>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="border-top mt-4 pt-3 d-flex justify-content-between text-muted small">
                <div>Payable Days: <span
                        class="fw-bold text-dark">{{ $attendances->where('status', '!=', 'absent')->count() }} Days</span>
                </div>
                <div>Present: <span class="fw-bold text-dark">{{ $attendances->where('status', 'present')->count() }}
                        Days</span></div>
            </div>
        </div>
    </div>

    <script>
        function switchView(viewName) {
            // Hide all
            document.querySelectorAll('.view-section').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.btn-group .btn').forEach(el => el.classList.remove('active', 'btn-secondary'));
            document.querySelectorAll('.btn-group .btn').forEach(el => el.classList.add('btn-outline-secondary'));

            // Show selected
            document.getElementById('view-' + viewName).classList.remove('d-none');
            document.getElementById('btn-' + viewName).classList.add('active', 'btn-secondary');
            document.getElementById('btn-' + viewName).classList.remove('btn-outline-secondary');

            // Render calendar if needed
            if (viewName === 'calendar') {
                setTimeout(() => {
                    calendar.render();
                }, 100);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: '{{ route('calendar.events') }}', // Reuse existing event source
                themeSystem: 'bootstrap5',
                height: 'auto'
            });
            window.calendar = calendar; // Global for access
        });
    </script>
    <style>
        /* Apple Calendar Style Overrides */
        :root {
            --fc-border-color: rgba(0, 0, 0, 0.05);
            --fc-today-bg-color: rgba(99, 102, 241, 0.05);
            --fc-neutral-bg-color: rgba(255, 255, 255, 0.5);
            --fc-list-event-hover-bg-color: #f1f5f9;
        }

        #calendar {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Header Toolbar */
        .fc-header-toolbar {
            margin-bottom: 2rem !important;
            padding: 0 1rem;
        }

        .fc-toolbar-title {
            font-weight: 800 !important;
            font-size: 1.75rem !important;
            letter-spacing: -0.03em;
            color: #1e293b;
        }

        .fc-button-primary {
            background-color: white !important;
            border: 1px solid #e2e8f0 !important;
            color: #475569 !important;
            font-weight: 600 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.75rem !important;
            transition: all 0.2s ease !important;
        }

        .fc-button-primary:hover {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            transform: translateY(-1px);
        }

        .fc-button-active {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
        }

        .fc-today-button {
            background-color: #6366f1 !important;
            color: white !important;
            border: none !important;
        }

        .fc-today-button:hover {
            background-color: #4f46e5 !important;
        }

        /* Grid Styling */
        .fc-scrollgrid {
            border: none !important;
        }

        .fc-col-header-cell {
            padding: 1rem 0 !important;
            background-color: #f8fafc;
            border: none !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .fc-col-header-cell-cushion {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 700;
            color: #64748b;
            text-decoration: none !important;
        }

        .fc-daygrid-day {
            border: 1px solid #f1f5f9 !important;
        }

        .fc-daygrid-day-top {
            justify-content: center !important;
            padding-top: 0.5rem !important;
        }

        .fc-daygrid-day-number {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            text-decoration: none !important;
        }

        .fc-day-today .fc-daygrid-day-number {
            background-color: #6366f1;
            color: white;
        }

        /* Events Styles */
        .fc-event {
            border: none !important;
            border-radius: 6px !important;
            padding: 2px 4px !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            margin-top: 2px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: transform 0.1s;
            cursor: pointer;
        }

        .fc-event:hover {
            transform: scale(1.02);
            z-index: 5;
        }

        /* Holiday Specific */
        .holiday-event {
            background-color: #fce7f3 !important;
            border-left: 3px solid #ec4899 !important;
        }

        .holiday-event .fc-event-title {
            color: #be185d !important;
        }
    </style>
@endsection
```