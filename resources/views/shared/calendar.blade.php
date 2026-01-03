@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
        <h2 class="fw-bold mb-0">My Calendar</h2>
        <div>
            <span class="badge badge-success me-2">● Present</span>
            <span class="badge badge-primary me-2">● Approved Leave</span>
            <span class="badge badge-warning">● Pending Leave</span>
        </div>
    </div>

    <div class="glass-card p-4 animate__animated animate__fadeInUp">
        <div id='calendar'></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: '{{ route("calendar.events") }}',
                height: 'auto',
                contentHeight: 600,
            });
            calendar.render();
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

        /* Buttons */
        .fc-button {
            background: white !important;
            border: 1px solid #e2e8f0 !important;
            color: #64748b !important;
            font-weight: 600 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            padding: 0.6rem 1.2rem !important;
            border-radius: 8px !important;
            text-transform: capitalize !important;
            transition: all 0.2s ease;
        }

        .fc-button:hover {
            background: #f8fafc !important;
            color: #334155 !important;
            transform: translateY(-1px);
        }

        .fc-button-active {
            background: #f1f5f9 !important;
            color: #0f172a !important;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06) !important;
        }

        .fc-button-primary:not(:disabled).fc-button-active:focus,
        .fc-button-primary:not(:disabled):active:focus {
            box-shadow: none !important;
        }

        /* Grid & Cells */
        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: var(--fc-border-color) !important;
        }

        .fc-col-header-cell {
            padding: 1rem 0 !important;
            background: rgba(248, 250, 252, 0.5);
        }

        .fc-col-header-cell-cushion {
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            text-decoration: none !important;
        }

        .fc-daygrid-day-top {
            flex-direction: row !important;
            padding: 0.5rem !important;
        }

        .fc-daygrid-day-number {
            font-weight: 600;
            color: #334155;
            text-decoration: none !important;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 0.9rem;
        }

        .fc-day-today .fc-daygrid-day-number {
            background: #ef4444;
            /* Apple style red for today */
            color: white !important;
        }

        .fc-day-today {
            background: white !important;
            /* Clean white background */
        }

        /* Events */
        .fc-event {
            border: none !important;
            border-radius: 6px !important;
            padding: 2px 4px !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            margin-bottom: 2px !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .fc-event:hover {
            transform: scale(1.02);
            z-index: 5;
        }

        .fc-daygrid-dot-event:hover {
            background: rgba(0, 0, 0, 0.02) !important;
        }

        .fc-daygrid-event-dot {
            border-width: 5px !important;
            margin-right: 6px !important;
        }

        /* Specific Event Colors Override for "cleaner" look */
        .fc-event-main {
            padding: 2px 4px;
        }
    </style>
@endsection