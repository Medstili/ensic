
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Profile Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #4f46e5;
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            background: var(--gradient-bg);
            min-height: 100vh;
            color: #fff;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .coach-profile {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .coach-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            box-shadow: 0 4px 15px rgba(58, 134, 255, 0.3);
        }

        .schedule-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
        }

        .status-badge {
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .passed { background: rgba(72, 187, 120, 0.2); color: #48bb78; }
        .upcoming { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
        .cancelled { background: rgba(245, 101, 101, 0.2); color: #f56565; }

        .table-switcher .active {
            background: var(--secondary-color) !important;
            border-color: var(--primary-color);
        }

        .action-btn {
            transition: all 0.3s ease;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .action-btn:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="coach-profile glass-card">
        <!-- Coach Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 pb-8 border-b border-white/10">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <img src="https://i.pravatar.cc/150" alt="Coach" class="coach-avatar">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold">{{$coach->full_name}}</h1>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full bg-white/10 text-sm">{{$coach->speciality}} Coach</span>
                        <span class="px-3 py-1 rounded-full {{$coach->is_available ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'}}">
                            {{$coach->is_available == 1 ? 'Available' :'Busy'}}
                        </span>
                    </div>
                    <div class="space-y-1 text-white/80">
                        <p><i class="fas fa-envelope mr-2"></i>{{$coach->email}}</p>
                        <p><i class="fas fa-phone mr-2"></i>{{$coach->tel}}</p>
                    </div>
                </div>
            </div>
                <button class="action-btn px-6 py-2" data-bs-toggle="modal" data-bs-target="#addCoachModal">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </button>
        </div>

        <!-- Schedule -->
        <div class="my-8">
            <h2 class="text-xl font-bold mb-4"><i class="fas fa-calendar-alt mr-2"></i>Weekly Schedule</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @php
                    $schedule = json_decode($coach->planning, true);
                @endphp
                @if (is_array($schedule))
                    @foreach ($schedule as $day => $time)
                        <div class="schedule-item">
                            <h3 class="font-bold text-white/90">{{ $day }}</h3>
                            <p class="text-white/70">
                                <i class="fas fa-clock mr-2"></i>
                                {{ $time['startTime'] }} - {{ $time['endTime'] }}
                            </p>
                        </div>
                    @endforeach
                @else
                    <div class="schedule-item">No schedule available</div>
                @endif
            </div>
        </div>

        <!-- Table Switcher -->
        <div class="table-switcher flex gap-2 my-8">
            <button class="action-btn px-6 py-2 active" onclick="showTable('appointments')">
                <i class="fas fa-calendar-check mr-2"></i>Appointments
            </button>
            <button class="action-btn px-6 py-2" onclick="showTable('patients')">
                <i class="fas fa-users mr-2"></i>Patients
            </button>
        </div>

        <!-- Appointments Table -->
        <div id="appointments">
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full">
                    <thead class="bg-white/10">
                        <tr>
                            <th class="px-6 py-4 text-left">Patient</th>
                            <th class="px-6 py-4 text-left">Date & Time</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <tr>
                            <td class="px-6 py-4">Sarah Johnson</td>
                            <td class="px-6 py-4">Mon, 15 July 2024 - 2:00 PM</td>
                            <td class="px-6 py-4"><span class="status-badge passed">Completed</span></td>
                            <td class="px-6 py-4 flex gap-2">
                                <button class="action-btn px-4 py-2">
                                    <i class="fas fa-eye mr-2"></i>Details
                                </button>
                                <a href="#" class="action-btn px-4 py-2">
                                    <i class="fas fa-download mr-2"></i>Report
                                </a>
                            </td>
                        </tr>
                        <!-- Add more rows -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Patients Table -->
        <div id="patients" style="display: none;">
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full">
                    <thead class="bg-white/10">
                        <tr>
                            <th class="px-6 py-4 text-left">Patient</th>
                            <th class="px-6 py-4 text-left">Contact</th>
                            <th class="px-6 py-4 text-left">Progress</th>
                            <th class="px-6 py-4 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <tr>
                            <td class="px-6 py-4">John Doe</td>
                            <td class="px-6 py-4">+1 (555) 678-9012</td>
                            <td class="px-6 py-4">
                                <div class="w-full bg-white/10 rounded h-2">
                                    <div class="bg-green-400 h-2 rounded w-3/4"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4"><span class="status-badge upcoming">Active</span></td>
                        </tr>
                        <!-- Add more rows -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- Enhanced Add Coach Modal -->
  <div class="modal fade" id="addCoachModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content glass-card">
                <div class="modal-header border-0">
                    <h3 class="modal-title">🌟 Update Coach Profile</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.update', $coach->id) }}" onsubmit="updatePlanning()" method="POST" class="row g-4">
                        @csrf
                        @method('PUT')

                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-transparent text-white" 
                                       id="fullName" name="full_name" required value="{{$coach->full_name}}">
                                <label for="fullName"><i class="fas fa-user me-2"></i>Full Name</label>
                            </div>

                            <div class="form-floating">
                                <input type="number" class="form-control bg-transparent text-white mt-2" 
                                       id="tel" name="tel" required value="{{$coach->tel}}">
                                <label for="tel"><i class="fas fa-phone me-2"></i>Phone Number</label>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="email" class="form-control bg-transparent text-white" 
                                       id="email" name="email" required value="{{$coach->email}}">
                                <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="password" class="form-control bg-transparent text-white" 
                                       id="password" name="password" required value="{{$coach->password}}">
                                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="card bg-transparent mb-3">
                                <div class="card-body">
                                    <h5 class="card-title text-white"><i class="fas fa-dumbbell me-2"></i>Specialization</h5>
            

                                    <select class="form-select bg-transparent text-white" id="specialist" name="specialist" required>
                                        <option value="">Select Specialization</option>
                                        @foreach ($specialities as $speciality )
                                            <option value="{{$speciality}}" class="text-black" {{$coach->speciality == $speciality ? 'selected' : ''}}>{{$speciality}}</option>
                                        @endforeach
                                    </select> 
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="card bg-transparent">
                                        <div class="card-body">
                                            <h5 class="card-title text-white"><i class="fas fa-calendar-check me-2 "></i>Availability</h5>
                                            <select class="form-select bg-transparent text-white" name="is_available">
                                                <option value="1" class="text-black" {{$coach->is_available == 1 ? 'selected' : ''}}>Available</option>
                                                <option value="0" class="text-black"  {{$coach->is_available == 0 ? 'selected' : ''}}>Unavailable</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-6">
                                    <div class="card bg-transparent">
                                        <div class="card-body">
                                            <h5 class="card-title text-white"><i class="fas fa-shield-alt me-2"></i>Role</h5>
                                            <select class="form-select bg-transparent text-white" name="is_admin">
                                                <option value="0" class="text-black">Coach</option>
                                                <option value="1" class="text-black">Admin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>

                        <!-- Schedule Section -->
                        <div class="col-12">
                            <div class="card bg-transparent">
                                <div class="card-body">
                                    <h5 class="card-title text-white"><i class="fas fa-clock me-2"></i>Schedule</h5>
                                    <div class="row g-3" id="dayTimingsContainer">
                                        <!-- Dynamic timing cards will be added here -->
                                    </div>
                                    <div class="mt-3">
                                        <select class="form-select bg-transparent text-white" 
                                                id="weekTiming" 
                                                onchange="toggleDayTime(this.value)">
                                            <option value="" class="text-black">➕ Add Working Day</option>
                                            <option value="Monday" class="text-black">Monday</option>
                                            <option value="Tuesday" class="text-black">Tuesday</option>
                                            <option value="Wednesday" class="text-black">Wednesday</option>
                                            <option value="Thursday" class="text-black">Thursday</option>
                                            <option value="Friday" class="text-black">Friday</option>
                                            <option value="Saturday" class="text-black">Saturday</option>
                                            <option value="Sunday" class="text-black">Sunday</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <input type="hidden" id="planning" name="planning">
                            
                            <button type="submit" class="btn btn-light btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    
        function showTable(tableId) {
            const tables = ['appointments', 'patients'];
            tables.forEach(id => {
                document.getElementById(id).style.display = id === tableId ? 'block' : 'none';
            });
            
            document.querySelectorAll('.table-switcher button').forEach(btn => {
                btn.classList.toggle('active', btn.onclick.toString().includes(tableId));
            });
        }

        function toggleDayTime(day) {
            if (!day) return;
            const container = document.getElementById('dayTimingsContainer');
            
            if (!document.getElementById(day + '-time')) {
                const card = document.createElement('div');
                card.className = 'col-md-6';
                card.innerHTML = `
                    <div class="timing-card" id="${day}-time">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-white">${day}</h6>
                            <button type="button" class="btn btn-link text-danger" 
                                    onclick="removeDay('${day}')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="row g-2 mt-2" id="timing-inputs">
                            <div class="col-6">
                                <input type="time" class="form-control bg-transparent text-white" 
                                       placeholder="Start Time">
                            </div>
                            <div class="col-6">
                                <input type="time" class="form-control bg-transparent text-white" 
                                       placeholder="End Time">
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            }
        }

        function removeDay(day) {
            const section = document.getElementById(day + '-time');
            if (section) {
                section.parentElement.remove();
            }
        }

        function updatePlanning() {
            let schedule = {};
            const dayTimings = document.querySelectorAll('.timing-card');
            const dayInputs =document.querySelectorAll('#timing-inputs');
            dayTimings.forEach((card, index) => {
                const day = card.id.split('-')[0];
                const inputs = dayInputs[index].querySelectorAll('input');
                schedule[day] = {
                    startTime: inputs[0].value,
                    endTime: inputs[1].value
                };
                });
            document.getElementById('planning').value = JSON.stringify(schedule);
        

        }
        document.addEventListener('DOMContentLoaded', function () {
    let schedule = @json(json_decode($coach->planning, true));

    for (let day in schedule) {
        // Call toggleDayTime to create the timing card
        toggleDayTime(day);

        // Find the generated section
        let section = document.getElementById(day + '-time');
        if (section) {
            let inputs = section.querySelectorAll("input[type='time']");
            if (inputs.length === 2) {
                inputs[0].value = schedule[day].startTime; // Set Start Time
                inputs[1].value = schedule[day].endTime;   // Set End Time
            }
        }
    }
});
    </script>
</body>
</html>