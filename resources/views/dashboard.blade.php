
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Dashboard Pro</title>
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

        .dashboard-header {
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .coach-card {
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .coach-card:hover {
            transform: translateY(-5px);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .available {
            background: rgba(72, 187, 120, 0.2);
            color: #48bb78;
        }

        .unavailable {
            background: rgba(245, 101, 101, 0.2);
            color: #f56565;
        }

        .action-btn {
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .timing-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Header -->
        <div class="dashboard-header glass-card">
            <h1 class="display-4 mb-3">Coach List</h1>
            <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#addCoachModal">
                <i class="fas fa-plus me-2"></i>Add New Coach
            </button>
        </div>

        <!-- Coaches Table -->
        <div class="glass-card p-4 mb-4">
            <table class="table table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th><i class="fas fa-id-badge text-center"></i> ID</th>
                        <th><i class="fas fa-user text-center"></i> Coach</th>
                        <th><i class="fas fa-star text-center"></i> Specialty</th>
                        <th><i class="fas fa-clock text-center"></i> Availability</th>
                        <th><i class="fas fa-shield-alt text-center"></i> Permission Allowness</th>
                        <th><i class="fas fa-cogs text-center"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="coach-card">
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40 me-3">
                                    <img src="https://i.pravatar.cc/150" 
                                         class="rounded-circle" 
                                         width="40" 
                                         alt="Coach Avatar">
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $user->full_name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $user->speciality }}</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $user->is_available ? 'available' : 'unavailable' }}">
                                {{ $user->is_available ? 'Available' : 'Busy' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $user->is_admin ? 'bg-warning' : 'bg-success' }}">
                                {{ $user->is_admin ? 'Allow' : 'Not Allow' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('user.show', $user->id) }}" 
                               class="btn btn-sm action-btn btn-outline-light me-2">
                               <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('user.destroy', $user->id) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm action-btn btn-outline-danger">
                                   <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enhanced Add Coach Modal -->
    <div class="modal fade" id="addCoachModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content glass-card">
                <div class="modal-header border-0">
                    <h3 class="modal-title">🌟 New Coach Profile</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.store') }}" onsubmit="updatePlanning()" method="POST" class="row g-4">
                        @csrf
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-transparent text-white" 
                                       id="fullName" name="full_name" required>
                                <label for="fullName"><i class="fas fa-user me-2"></i>Full Name</label>
                            </div>

                            <div class="form-floating">
                                <input type="number" class="form-control bg-transparent text-white mt-2" 
                                       id="tel" name="tel" required>
                                <label for="tel"><i class="fas fa-phone me-2"></i>Phone Number</label>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="email" class="form-control bg-transparent text-white" 
                                       id="email" name="email" required>
                                <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="password" class="form-control bg-transparent text-white" 
                                       id="password" name="password" required>
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
                                            <option value="{{$speciality}}" class="text-black" >{{$speciality}}</option>
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
                                                <option value="1" class="text-black">Available</option>
                                                <option value="0" class="text-black">Unavailable</option>
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
                                <i class="fas fa-save me-2"></i>Create Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>