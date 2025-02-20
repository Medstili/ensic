@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Dashboard Header -->
    <!-- <div class="dashboard-header glass-card">
        <h1 class="display-4 mb-3">Coach List</h1>
        <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#addCoachModal">
            <i class="fas fa-plus me-2"></i>Add New Coach
        </button>
    </div> -->
    <div class="dashboard-header glass-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 mb-0">Coachs List</h1>
            <button class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#addCoachModal">
                <i class="fas fa-plus me-2"></i>Add New Coach
            </button>
        </div>
    </div>

        <!-- Search Form -->
        <form action="{{ route('user.index') }}" method="GET" class="mb-4 glass-card p-4">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Coach" class="form-control bg-transparent">
            </div>
            <div class="col-md-2">
                <select name="availability" class="form-select bg-transparent">
                    <option value="">All Coaches</option>
                    <option value="1" {{ request('availability') == 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="0" {{ request('availability') == 'Not Available' ? 'selected' : '' }}>Not Available</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="specialities" class="form-select bg-transparent">
                    <option value="">All specialities</option>
                    @foreach($specialities as $speciality)
                    <option value="{{ $speciality->name }}" {{ request('specialities') == $speciality->name ? 'selected' : '' }}>{{ $speciality->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Search
                </button>
            </div>
        </div>
    </form>
    <!-- Coaches Table -->
    <div class="glass-card p-4 mb-4">
        <table class="table table-hover align-middle">
            <thead class="thead-light">
                <tr>
                    <th><i class="fas fa-id-badge text-center"></i> ID</th>
                    <th><i class="fas fa-user text-center"></i> Coach</th>
                    <th><i class="fas fa-star text-center"></i> Specialty</th>
                    <th><i class="fas fa-clock text-center"></i> Availability</th>
                    <!-- <th><i class="fas fa-shield-alt text-center"></i> Permission Allowness</th> -->
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
                    <!-- <td class="text-center">
                        <span class="badge {{ $user->is_admin ? 'bg-warning' : 'bg-success' }}">
                            {{ $user->is_admin ? 'Allow' : 'Not Allow' }}
                        </span>
                    </td> -->
                    <td>
                        <a href="{{ route('user.show', $user->id) }}" class="btn btn-sm btn-outline-secondary me-2">
                           <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger me-2">
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.store') }}" onsubmit="coachUpdatePlanning()" method="POST" class="row g-4">
                    @csrf
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="fullName" name="full_name" required>
                            <label for="fullName"><i class="fas fa-user me-2"></i>Full Name</label>
                        </div>
                        <div class="form-floating mt-2">
                            <input type="number" class="form-control" id="tel" name="tel" required>
                            <label for="tel"><i class="fas fa-phone me-2"></i>Phone Number</label>
                        </div>
                        <div class="form-floating mt-3">
                            <input type="email" class="form-control" id="email" name="email" required>
                            <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                        </div>
                        <div class="form-floating mt-3">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="card border-0 mb-3">
                            <div class="card-body p-0">
                                <h5 class="card-title"><i class="fas fa-dumbbell me-2"></i>Specialization</h5>
                                <select class="form-select" id="specialist" name="specialist" required>
                                    <option value="">Select Specialization</option>
                                    @foreach ($specialities as $speciality)
                                        <option value="{{ $speciality->name }}">{{ $speciality->name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="card border-0">
                                    <div class="card-body p-0">
                                        <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i>Availability</h5>
                                        <select class="form-select" name="is_available">
                                            <option value="1">Available</option>
                                            <option value="0">Unavailable</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Section -->
                    <!-- <div class="col-12">
                        <div class="card border-0">
                            <div class="card-body p-0">
                                <h5 class="card-title"><i class="fas fa-clock me-2"></i>Schedule</h5>
                                <div class="row g-3" id="dayTimingsContainer">
                                    Dynamic timing cards will be added here
                                </div>
                                <div class="mt-3">
                                    Date input for adding schedule
                                    <input type="date" class="form-control" id="datePicker" onchange="coachToggleDayTime(this.value)">
                                </div>
                            </div>
                        </div>
                    </div> -->

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

<!-- Custom Styles to Match Sidebar/Navbar Style -->
<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --light-bg: #ecf0f1;
    }
    body {
        background-color: var(--light-bg);
        color: #333;
    }
    /* Glass Card styling */
    .glass-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: none;
    }
    /* Dashboard Header */
    .dashboard-header {
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
        background-color: #fff;
    }
    /* Table Styling */
    .table.table-hover {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    /* Coach Card row hover effect */
    .coach-card {
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    .coach-card:hover {
        transform: translateY(-2px);
    }
    /* Status Badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    .available {
        background-color: rgba(72, 187, 120, 0.2);
        color: #48bb78;
    }
    .unavailable {
        background-color: rgba(245, 101, 101, 0.2);
        color: #f56565;
    }
    /* Action Button */
    .action-btn {
        transition: all 0.3s ease;
        border-radius: 8px;
    }
    /* Timing Card */
    .timing-card {
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #ddd;
    }
    /* Modal adjustments */
    .modal-content.glass-card {
        background-color: #fff;
        color: #333;
    }
    .modal-header {
        border-bottom: none;
    }
</style>

<script>
    // Helper: Format date string "YYYY-MM-DD" to "D/M/YYYY"
    function formatDate(dateString) {
        const parts = dateString.split('-'); 
        const year = parts[0];
        const month = parseInt(parts[1], 10);
        const day = parseInt(parts[2], 10);
        return day + '/' + month + '/' + year;
    }
    function coachToggleDayTime(dateValue) {
        if (!dateValue) return;
        const container = document.getElementById('dayTimingsContainer');
        if (!document.getElementById(dateValue + '-time')) {
            const card = document.createElement('div');
            card.className = 'col-md-6';
            card.innerHTML = `
                <div class="timing-card" id="${dateValue}-time">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">${dateValue}</h6>
                        <button type="button" class="btn btn-link text-danger" onclick="coachRemoveDay('${dateValue}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <input type="time" class="form-control" name="start_time" placeholder="Start Time">
                        </div>
                        <div class="col-6">
                            <input type="time" class="form-control" name="end_time" placeholder="End Time">
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        }
    }
    function coachRemoveDay(dateValue) {
        const section = document.getElementById(dateValue + '-time');
        if (section) {
            section.parentElement.remove();
        }
        coachUpdatePlanning();
    }
    function coachUpdatePlanning() {
        let schedule = {};
        const dayTimings = document.querySelectorAll('.timing-card');
        dayTimings.forEach((card) => {
            const dateValue = card.id.replace('-time', '');
            const inputs = card.querySelectorAll('input');
            if (inputs[0].value && inputs[1].value) {
                schedule[dateValue] = {
                    startTime: inputs[0].value,
                    endTime: inputs[1].value
                };
            }
        });
        document.getElementById('planning').value = JSON.stringify(schedule);
    }
</script>
@endsection
