@extends('layouts.app')

@section('content')
<style>
    /* Hover Shadow Effect for table rows */
    .hover-shadow:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: box-shadow 0.3s ease;
    }
    /* Define common color variables */
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --light-bg: #ecf0f1;
    }
    /* Main container adjusted for fixed sidebar */
    .container.py-5 {
        background-color: var(--light-bg);
        min-height: calc(100vh - 60px);
        padding: 40px;
    }
    /* Glass Card styling for headers, forms, and panels */
    .glass-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: none;
    }
    /* Dashboard Header */
    .dashboard-header {
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
        background-color: #fff;
    }
    .dashboard-header h1 {
        color: var(--secondary-color);
        font-weight: bold;
    }
    /* Form & Input Styling */
    .form-control, .form-select {
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    .form-control.bg-transparent,
    .form-select.bg-transparent {
        background-color: transparent;
        color: var(--secondary-color);
    }
    /* Table Styling */
    .table.table-hover {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .table thead {
        background-color: var(--secondary-color);
        color: #ecf0f1;
    }
    .table thead th {
        border-bottom: 2px solid #34495e;
    }
    .table tbody tr:hover {
        background-color: #f1f1f1;
    }
    /* Badge Styling for Gender */
    .badge.bg-primary {
        background-color: #3498db !important;
    }
    .badge.bg-danger {
        background-color: #e74c3c !important;
    }
    /* Modal adjustments */
    .modal-content.glass-card {
        background-color: #fff;
        color: var(--secondary-color);
    }
    .modal-header {
        border-bottom: none;
    }
</style>

<div class="container py-5">
    <!-- Header -->
    <div class="dashboard-header glass-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 mb-0">Patients List</h1>
            <button class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                <i class="fas fa-plus me-2"></i>Add New Patient
            </button>
        </div>
    </div>

    <!-- Search Form -->
    <form action="{{ route('patient.index') }}" method="GET" class="mb-4 glass-card p-4">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Patient" class="form-control bg-transparent">
            </div>
            <div class="col-md-4">
                <select name="gender" class="form-select bg-transparent">
                    <option value="">All Gender</option>
                    <option value="M" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Search
                </button>
            </div>
        </div>
    </form>

    <!-- Patients Table -->
    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="bg-primary text-white">
                        <th><i class="fas fa-id-badge me-2"></i>ID</th>
                        <th><i class="fas fa-user me-2"></i>Patient</th>
                        <th><i class="fas fa-phone me-2"></i>Phone</th>
                        <th><i class="fas fa-calendar-alt me-2"></i>Age</th>
                        <th><i class="fas fa-venus-mars me-2"></i>Gender</th>
                        <th><i class="fas fa-cogs me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $Patient)
                    <tr class="hover-shadow">
                        <td>{{ $Patient->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40 me-3">
                                    <img src="https://i.pravatar.cc/150" class="rounded-circle" width="40" alt="Patient Avatar">
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $Patient->full_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $Patient->phone }}</td>
                        <td>{{ $Patient->age }}</td>
                        <td>
                            @if ($Patient->gender == 'M')
                                <span class="badge bg-primary">Male <i class="bi bi-gender-male"></i></span>
                            @else
                                <span class="badge bg-danger">Female <i class="bi bi-gender-female"></i></span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('patient.show', $Patient->id) }}" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('patient.destroy', $Patient->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
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
</div>

<!-- Add Patient Modal -->
<div class="modal fade" id="addPatientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content glass-card">
            <div class="modal-header border-0">
                <h3 class="modal-title">🌟 New Patient Profile</h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('patient.store') }}" onsubmit="return PatientUpdatePlanning()" method="POST" class="row g-4">
                    @csrf
                    <!-- Left Column: Full Name & Phone Number -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-transparent" id="fullName" name="full_name" required>
                                <label for="fullName"><i class="fas fa-user me-2"></i>Full Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control bg-transparent mt-2" id="tel" name="phone_number" required>
                                <label for="tel"><i class="fas fa-phone me-2"></i>Phone Number</label>
                            </div>
                        </div>
                        <!-- Right Column: Age & Gender -->
                        <div class="col-md-6">
                            <div class="card bg-transparent mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-dumbbell me-2"></i>Age</h5>
                                    <input type="number" min="1" max="100" name="age" class="w-100 bg-transparent">
                                </div>
                            </div>
                            <div class="card bg-transparent">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i>Gender</h5>
                                    <select class="form-select bg-transparent" name="gender">
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Schedule Section -->
                    <div class="col-12">
                        <div class="card  bg-transparent">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-clock me-2"></i>Priorities</h5>
                                <div class="row g-3" id="dayTimingsContainer">
                                    <!-- Priority 1 -->
                                    <div class="col-md-4">
                                        <h4>Priority 1</h4>
                                        <div class="timing-card">
                                            <input type="date" name="priority_1_day" id="priority_1_day" class="form-select bg-transparent">
                                            <div class="row g-2 mt-2">
                                                <div class="col-6">
                                                    <input type="time" class="form-control bg-transparent" name="priority_1_start_time" id="priority_1_start_time" placeholder="Start Time">
                                                </div>
                                                <div class="col-6">
                                                    <input type="time" class="form-control bg-transparent" name="priority_1_end_time" id="priority_1_end_time" placeholder="End Time">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h4>Priority 2</h4>
                                        <div class="timing-card">
                                            <input type="date" name="priority_2_day" id="priority_2_day" class="form-select bg-transparent">
                                            <div class="row g-2 mt-2">
                                                <div class="col-6">
                                                    <input type="time" class="form-control bg-transparent" name="priority_2_start_time" id="priority_2_start_time" placeholder="Start Time">
                                                </div>
                                                <div class="col-6">
                                                    <input type="time" class="form-control bg-transparent" name="priority_2_end_time" id="priority_2_end_time" placeholder="End Time">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h4>Priority 3</h4>
                                        <div class="timing-card">
                                            <input type="date" name="priority_3_day" id="priority_3_day" class="form-select bg-transparent">
                                            <div class="row g-2 mt-2">
                                                <div class="col-6">
                                                    <input type="time" class="form-control bg-transparent" name="priority_3_start_time" id="priority_3_start_time" placeholder="Start Time">
                                                </div>
                                                <div class="col-6">
                                                    <input type="time" class="form-control bg-transparent" name="priority_3_end_time" id="priority_3_end_time" placeholder="End Time">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Priority 2 and 3 sections omitted for brevity -->
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                    <!-- Submit Button -->
                    <div class="col-12 text-center mt-4">
                        <input type="hidden" id="planning" name="Priorities">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Add Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
  function PatientUpdatePlanning() {
    // Collect data for Priority 1
    const priority1Day = document.getElementById('priority_1_day').value.trim();
    const priority1StartTime = document.getElementById('priority_1_start_time').value.trim();
    const priority1EndTime = document.getElementById('priority_1_end_time').value.trim();
    // Collect data for Priority 2
    const priority2Day = document.getElementById('priority_2_day').value.trim();
    const priority2StartTime = document.getElementById('priority_2_start_time').value.trim();
    const priority2EndTime = document.getElementById('priority_2_end_time').value.trim();
    // Collect data for Priority 3
    const priority3Day = document.getElementById('priority_3_day').value.trim();
    const priority3StartTime = document.getElementById('priority_3_start_time').value.trim();
    const priority3EndTime = document.getElementById('priority_3_end_time').value.trim();

    // Initialize an empty planning object
    let planning = {};

    // Helper function to add valid priority to planning
    function addPriority(priority, day, startTime, endTime) {
        if (day && startTime && endTime) {
            planning[priority] = {
                [day]: {
                    startTime: startTime,
                    endTime: endTime
                }
            };
        }
    }

    // Validate: Priority 1 is mandatory
    if (!priority1Day || !priority1StartTime || !priority1EndTime) {
        alert("Please fill out Priority 1 as it's required.");
        return false; // Prevent form submission
    }

    // Add only filled priorities
    addPriority("priority 1", priority1Day, priority1StartTime, priority1EndTime);
    addPriority("priority 2", priority2Day, priority2StartTime, priority2EndTime);
    addPriority("priority 3", priority3Day, priority3StartTime, priority3EndTime);

    // Convert planning object to JSON
    const planningJSON = JSON.stringify(planning);

    // Set the JSON data in the hidden input field
    document.getElementById('planning').value = planningJSON;

    // Log the JSON for debugging
    console.log("Generated Planning JSON:", planningJSON);

    // Allow form submission
    return true;
  }
</script>
@endsection
