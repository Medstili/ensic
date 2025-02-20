@extends('layouts.app')
@section('content')

<div class="patient-profile">
    <!-- Header Section -->
    <div class="profile-header">
        <img src="https://i.pravatar.cc/150" width="100" alt="Patient" class="patient-avatar">
        <div class="header-info">
            <h1 class="patient-name">{{ $patient->full_name }}</h1>
            <div class="patient-meta">
                <span><i class="fas fa-birthday-cake"></i> {{ $patient->age }} years</span>
                <span>
                    @if ($patient->gender == "M")
                        Male <i class="bi bi-gender-male"></i>
                    @elseif ($patient->gender == "F")
                        Female <i class="bi bi-gender-female"></i>
                    @endif
                </span>
                <span><i class="fas fa-file-medical"></i> ID: {{ $patient->id }}</span>
                <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient->id }}">
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <!-- <div class="mt-6 p-4">
        <div id="calendar"></div>
    </div> -->
    <div class="calendar-container">
        <div id="calendar"></div>
    </div>

    <!-- Coach Availability Section -->
    <div class="availability-section">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="filter-card">
                <label class="filter-label  text-2xl mb-2">
                   Select Specialty
                </label>
                <select class="glass-select" id="specialtySelect">
                    <option value="">All Specialties</option>
                    @foreach($specialities as $specialty)
                        <option value="{{ $specialty->name }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button class="availability-btn" id="checkAvailability" onclick="checkAvailableCoaches()">
                    <i class="fas fa-search mr-2"></i>Check Available Coaches
                </button>
            </div>
        </div>
        <div id="availableCoaches"></div>
    </div>
</div>


<style>
    /* Overall Container */
    .patient-profile {
        /* Main-content background is set by the layout (#ecf0f1) */
        margin: 20px;
    }

    /* Profile Header */
    .profile-header {
        display: flex;
        align-items: center;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .patient-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-right: 20px;
        object-fit: cover;
        border: 3px solid #3498db;
    }
    .header-info h1.patient-name {
        font-size: 1.8rem;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    .patient-meta span {
        display: inline-block;
        margin-right: 15px;
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    /* Calendar Styling */
    /* #calendar {
        background-color: #2c3e50;
        border-radius: 8px;
        padding: 20px;
        color: #ecf0f1;
    } */

    .calendar-container {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
       }
    
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
                .fc-event.priority1 {
            background: rgba(255, 167, 38, 0.15) !important;
            color:rgb(255, 38, 38) !important;
        }
        .fc-event.priority1::before {
            background:rgb(255, 38, 38);
        }

        /* Passed - Emerald */
        .fc-event.priority2 {
            background: rgba(102, 187, 106, 0.15) !important;
            color:rgb(231, 140, 2) !important;
        }
        .fc-event.priority2::before {
            background: rgb(231, 140, 2);
        }

        /* Canceled - Rose */
        .fc-event.priority3 {
            background: rgba(239, 83, 80, 0.15) !important;
            color:rgb(16, 236, 0) !important;
        }
        .fc-event.priority3::before {
            background: rgb(16, 236, 0) ;
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

        /* Today Highlight */
        /* .fc-day-today {
            background: rgba(255, 255, 255, 0.03) !important;
        }

        /* Header Styling */
        /* .fc-toolbar {
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .fc-toolbar-title {
            font-size: 1.5em;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        } */ */

        
        /* Button Styling */
        /* .fc-button {
            background: rgba(255,255,255,0.1) !important;
            border: none !important;
            border-radius: 8px !important;
            color: white !important;
            margin-left: 10px;
            transition: all 0.3s ease !important;
        }

        .fc-button:hover {
            background: var(--primary-color) !important;
            transform: translateY(-2px);
        } */

    /* Availability Section */
    .availability-section {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px;
        margin-top: 20px;
    }
    .filter-card {
        margin-bottom: 0.5rem;
    }
    .filter-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #2c3e50;
    }
    .glass-select {
        width: 100%;
        background-color: #ecf0f1;
        border: 1px solid #bdc3c7;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        color: #2c3e50;
    }
    .availability-btn {
        width: 100%;
        background-color: #3498db;
        color: #fff;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        transition: all 0.3s;
        cursor: pointer;
    }
    .availability-btn:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Dynamically Added Coach Cards */
    /* .glass-card {
        background-color: #2c3e50;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        color: #ecf0f1;
    } */
    .glass-card:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 6px 12px rgba(0,0,0,0.25);
    }
    .patient-card-action-btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        color: #fff;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    .patient-card-action-btn.bg-indigo-600 {
        background-color: #5a67d8;
    }
    .patient-card-action-btn.bg-indigo-600:hover {
        background-color: #4c51bf;
    }
    .patient-card-action-btn.bg-emerald-600 {
        background-color: #48bb78;
    }
    .patient-card-action-btn.bg-emerald-600:hover {
        background-color: #38a169;
    }

    
/* Grid Container for Coach Cards */
#availableCoaches .grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
}

/* Smaller Coach Cards */
.glass-card {
    background-color: #2c3e50;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 10px; /* Reduced padding for a smaller card */
    transition: transform 0.3s, box-shadow 0.3s;
    color: #ecf0f1;
    font-size: 0.9rem; /* Slightly smaller text */
}

    
</style>

<script>

    const bookButtons = document.querySelectorAll('#book-btn');
    function switchTable(table) {
    }
    function checkAvailableCoaches() {
        // ... [keep existing code until .then(result => { ... })]
        const patientId = document.getElementById('patient_id').value;
        const speciality = document.getElementById('specialtySelect').value;
                            
        if (!speciality) {
            alert('Please select a speciality first');
            return;
        }

        // Create the payload
        const data = {
            patient_id: patientId,
            speciality: speciality
        };

        // Send the POST request to the route
        fetch("{{ route('coach-availability') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(result => {
            const container = document.getElementById("availableCoaches");
            container.innerHTML = ""; // Clear previous results

            if (result.success) {
                console.log(result);
                
                // Create section heading
                const heading = document.createElement("h3");
                heading.className = "text-black text-xl font-bold mt-4 mb-4";
                heading.innerHTML = `<i class="fas fa-users mr-2"></i>Available Coaches (${result.priorityUsed})`;
                container.appendChild(heading);

                // Create coaches grid
                const grid = document.createElement("div");
                grid.className = "grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4";
                
                result.available_coaches.forEach(item => {
                    console.log(item);
                    
                    // item.coach.forEach(coach => {
                        
                    // });
                    const card = document.createElement("div");
                    card.className = "glass-card p-4 rounded-xl hover:transform hover:scale-105 transition-all";
                    card.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center  mb-4">
                            <img src="${'https://i.pravatar.cc/150?u=' + item['coach'].id}" 
                                class="w-12 h-12 rounded-circle border-2 border-white/20">
                            <div>
                                <h4 class="text-white font-semibold">${item['coach'].full_name}</h4>
                                <p class="text-indigo-200 text-sm">${item['coach'].speciality}</p>
                                <p class="text-indigo-200 text-sm">${item['date']}</p>
                                <p class="text-indigo-200 text-sm"> starts at : ${item['free_interval'].startTime}</p>
                                <p class="text-indigo-200 text-sm"> ends at : ${item['free_interval'].endTime}</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/user/${item['coach'].id}" 
                            class="patient-card-action-btn bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-info-circle mr-2"></i>Details
                            </a>
                            <button class="patient-card-action-btn bg-emerald-600 hover:bg-emerald-700 book-btn"
                            id="book-btn"
                                        data-coach-id="${item['coach'].id}"
                                        data-appointment-date="${item['date']}"
                                        data-start-time="${item['free_interval'].startTime}"
                                        data-end-time="${item['free_interval'].endTime}"
                                        data-patient-id="${patientId}"
                                        data-speciality="${item['coach'].speciality}">
                                <i class="fas fa-calendar-check mr-2"></i>Book
                            </button>
                            <input type='hidden' name='coach-id' value='${item['coach'].id}'/>
                            <input type='hidden' name='patient-id' value='${patientId}'/>
                            
                        </div>
                    `;
                    grid.appendChild(card);
                });

                container.appendChild(grid);
                
                // Add event listeners for book buttons
                document.querySelectorAll('.book-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        console.log('clicked');
                        const coachId = this.getAttribute('data-coach-id');
                        const appointmentDate = this.getAttribute('data-appointment-date');
                        const startTime = this.getAttribute('data-start-time');
                        const endTime = this.getAttribute('data-end-time');
                        const patientId = this.getAttribute('data-patient-id');
                        const speciality = this.getAttribute('data-speciality');
                        bookAppointment(coachId, appointmentDate, startTime, endTime, patientId, speciality);
                    });
                });
                
            } else {
                container.innerHTML = `
                    <div class="glass-card p-4 text-center text-white/70">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        ${result.message}
                    </div>
                `;
            }
        })
    }
    function bookAppointment(coachId, appointmentDate, startTime, endTime, patientId, speciality) {

        const planning = {};
        planning[appointmentDate] = {
            startTime: startTime,
            endTime: endTime
        };
        const data = {
            coach_id: coachId,
            planning: planning,
            patient_id: patientId,
            speciality: speciality
        };

        const bookingAppointmentsUrl = " {{ route('appointment.store') }}";
        // Send the POST request to your Laravel store route.
        fetch( bookingAppointmentsUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(result => {
            console.log('Appointment stored successfully:', result);
            alert('Appointment booked successfully!');
        })
        .catch(error => {
            console.error('Error storing appointment:', error);
            alert('There was an error booking the appointment.');
        });
        
    }
    document.addEventListener('DOMContentLoaded', function() {
        var events = <?php
            $allEvents = [];
            $p_priority = json_decode($patient->priorities, true);
            foreach ($p_priority as $priority => $date) {
                $statusClass = '';
                switch ($priority) {
                    case 'priority 1':
                        $statusClass = 'priority1';
                        break;
                    case 'priority 2':
                        $statusClass = 'priority2';
                        break;
                    case 'priority 3':
                        $statusClass = 'priority3';
                        break;
                }
                foreach ($date as $time) {
                    $day=  key($date);
                    $startTime = $time['startTime'].":00";
                    $endTime = $time['endTime'].":00";
                    $allEvents[] = [
                        'title' => $priority,
                        'date'=>   $day,
                        'start' => $day .'T'.$startTime,
                        'end' =>  $day .'T'.$endTime,
                        'className' => $statusClass,
                        'extendedProps' => [
                            'priority' => '{{ $priority}}'
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
                return {
                    html: `<div class="fc-event-inner">
                            <span class="fc-event-time">${arg.timeText}</span>
                            <span class="fc-event-title">${arg.event.title}</span>
                        </div>`
                };
            },
            eventDidMount: function(arg) {
                // Add hover effect
                arg.el.addEventListener('mouseenter', function() {
                    arg.el.style.zIndex = '999';
                });
                arg.el.addEventListener('mouseleave', function() {
                    arg.el.style.zIndex = 'auto';
                });
            },
        });
        calendar.render();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
