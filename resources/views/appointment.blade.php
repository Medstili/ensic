@extends('layouts.app')
@section('content')
    <!-- <div class="container py-5"> -->
        <!-- Header -->
        <div class="dashboard-header glass-card">
            <h1 class="display-4 mb-3">Appointment List</h1>
            <!-- <form action="{{ route('appointment.create') }}" method="get">
                @csrf
                <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                    <i class="fas fa-plus me-2"></i>Add New Appointment
                </button>
            </form> -->
        </div>

            <!-- Calendar Section -->
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>
        <!-- Search and Filter Form -->
        <form action="{{ route('appointment.index') }}" method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Coach or Patient" class="form-control">
                </div>
                <div class="col-md-4">
                    <select name="speciality" class="form-select">
                        <option value="">All Specialities</option>
                        <option value="Life Coaching" {{ request('speciality')=='Life Coaching' ? 'selected' : '' }}>Life Coaching</option>
                        <option value="Career Coaching" {{ request('speciality')=='Career Coaching' ? 'selected' : '' }}>Career Coaching</option>
                        <option value="Health and Wellness Coaching" {{ request('speciality')=='Health and Wellness Coaching' ? 'selected' : '' }}>Health and Wellness Coaching</option>
                        <option value="Executive Coaching" {{ request('speciality')=='Executive Coaching' ? 'selected' : '' }}>Executive Coaching</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </div>
        </form>

        <!-- Appointment Table -->
        <div class="glass-card p-2 mb-4">
            <table class="table table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th><i class="fas fa-id-badge text-center"></i> ID</th>
                        <th><i class="fas fa-user text-center"></i> Patient Name</th>
                        <th><i class="fas fa-date text-center"></i> Date </th>
                        <th><i class="fas fa-user text-center"></i> Coach</th>
                        <th><i class="fas fa-star text-center"></i> Specialty</th>
                        <th><i class="fas fa-clock text-center"></i> Status</th>
                        <th><i class="fas fa-cogs text-center"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                 @php
          
                 @endphp
                    @foreach($appointments as $appointment)

                 
                    
                    <tr class="coach-card">
                        <td>{{ $appointment->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40 me-3">
                                    <img src="https://i.pravatar.cc/150" 
                                         class="rounded-circle" 
                                         width="40" 
                                         alt="Coach Avatar">
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $appointment->patient->full_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $schedule = json_decode($appointment->appointment_planning, true);
                            @endphp
                            @if (is_array($schedule))
                                @foreach ($schedule as $day => $time)
                                    <ul class="list-unstyled mb-0">
                                        <li class="fw-bold">{{ $day }}</li>
                                        @foreach ($time as $slot)
                                            <li>{{ $slot }}</li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            @else
                                <div class="schedule-item">No schedule available</div>
                            @endif
                        </td>
                        <td>
                           
                            <span class="status-badge">
                                {{ $appointment->coachs->full_name }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge text-black">
                                {{ $appointment->choosen_speciality }}
                            </span>
                        </td>
                        <td>
                            @php
                                $color = ''; // Default color
                                if ($appointment->status == 'pending') {
                                    $color = 'badge bg-warning';
                                } elseif ($appointment->status == 'passed') {
                                    $color = 'badge bg-success';
                                } elseif ($appointment->status == 'cancel') {
                                    $color = 'badge bg-danger';
                                }
                            @endphp
                            <span class="{{ $color }}">
                                {{ $appointment->status }}
                            </span>
                        </td>
                        <td>
                            <div class="row row-cols-2 g-2 text-center">
                                @if ($appointment->report_path)
                                    <div class="col">
                                        <form action="{{ route('appointments.downloadReport', $appointment->id) }}" method="GET">
                                            @csrf
                                            <button class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-cloud-download-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                <div class="col">
                                    <form action="{{ route('appointment.show', $appointment->id) }}" method="GET">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </form>
                                </div>
                                @if ($appointment->status == "pending")
                                    <div class="col">
                                        <form action="{{ route('appointment.edit',$appointment->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col">
                                        <form action="{{ route('appointments.update-appointment-status', [$appointment->id, 'passed']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    <style>
        /* Main container (content) styling to account for fixed sidebar */
        .container.py-5 {
            margin-left: 250px; /* Width of the sidebar */
            background-color: #ecf0f1;
            min-height: calc(100vh - 60px);
            padding: 40px;
        }

        /* Glass Card styling for headers and panels */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
        }

        /* Dashboard Header Text */
        .dashboard-header h1 {
            color: #2c3e50;
            font-weight: bold;
        }

        /* Form Controls & Selects */
        .form-control, .form-select {
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        /* Table Styling */
        .table {
            background: rgba(255, 255, 255, 0.95);
        }
        .table thead {
            background-color: #2c3e50;
            color: #ecf0f1;
        }
        .table thead th {
            border-bottom: 2px solid #34495e;
        }
        .table tbody tr {
            transition: background-color 0.3s;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Coach Card styling inside table rows (using transparent background to blend with table) */
        .coach-card {
            background: transparent;
        }

        /* Badge adjustments */
        .badge {
            font-size: 0.875rem;
            padding: 0.5em 0.75em;
        }

        /* Responsive adjustments for smaller screens */
        @media (max-width: 768px) {
            .container.py-5 {
                margin-left: 0;
                padding: 20px;
            }
        }


          /* Calendar Container */
    .calendar-container {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
       /* Event Styling with Status Colors */
    .fc-event {
        border: none !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        margin: 4px !important;
        font-weight: 500 !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 3px 6px rgba(0,0,0,0.16) !important;
        position: relative;
        overflow: hidden;
    }

    .fc-event::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    /* Pending - Amber */
    .fc-event.pending {
        background: rgba(255, 167, 38, 0.15) !important;
        color: #FFA726 !important;
    }
    .fc-event.pending::before {
        background: #FFA726;
    }

    /* Passed - Emerald */
    .fc-event.passed {
        background: rgba(102, 187, 106, 0.15) !important;
        color: #66BB6A !important;
    }
    .fc-event.passed::before {
        background: #66BB6A;
    }

    /* Canceled - Rose */
    .fc-event.cancel {
        background: rgba(239, 83, 80, 0.15) !important;
        color: #EF5350 !important;
    }
    .fc-event.cancel::before {
        background: #EF5350;
    }

    /* Hover Effects */
    .fc-event:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 6px 12px rgba(0,0,0,0.25) !important;
    }

    /* Time Styling */
    .fc-event-time {
        font-weight: 300;
        opacity: 0.8;
        margin-right: 8px;
    }
    </style>
    <script>

document.addEventListener('DOMContentLoaded', function() {
    var events = <?php
        $allEvents = [];
        foreach ($appointments as $app) {
            // dd($app);
            $statusClass = '';
            switch ($app->status) {
                case 'passed':
                    $statusClass = 'passed';
                    break;
                case 'pending':
                    $statusClass = 'pending';
                    break;
                case 'cancel':
                    $statusClass = 'cancel';
                    break;
            }
            $appointment_date = json_decode($app->appointment_planning, true);
            foreach ($appointment_date as $date => $time) {
                $startTime = $time['startTime'] .":". "00";
                $endTime = $time['endTime'] . ":"."00";
                // dd($app->choosen_speciality);
                $allEvents[] = [
                    'id'    => $app->id,
                    'title' => $app->patient->full_name,
                    'start' => $date . 'T' . $startTime,
                    'end'   => $date . 'T' . $endTime,
                    'className' => $statusClass,
                    'extendedProps' => [
                        'status' => '{{ $app->status }}'
                    ]
                ];
            }
        }
        echo json_encode($allEvents);
    ?>;
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: events,
        eventContent: function(arg) {
            console.log(arg.event.speciality);
            
            return {
                html: `<div class="fc-event-inner">
                        <p class="fc-event-title">${arg.event.title}</p>
                        <p class="fc-event-time">${arg.timeText}</p>
                       </div>`
            };
        },
        eventDidMount: function(arg) {
            arg.el.addEventListener('mouseenter', function() {
                arg.el.style.zIndex = '999';
            });
            arg.el.addEventListener('mouseleave', function() {
                arg.el.style.zIndex = 'auto';
            });
        },
        eventClick: function(info) {
            var detailUrl = "{{ route('appointment.show', ':id') }}";
            detailUrl = detailUrl.replace(':id', info.event.id);
            window.location.href = detailUrl;
        }
    });
    calendar.render();
});


    </script>
@endsection
