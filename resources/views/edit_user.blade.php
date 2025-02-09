<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Update Coach Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
        function toggleDayTime(day) {
            if (!day) return;
            var container = document.getElementById('dayTimingsContainer');
            if (!document.getElementById(day + '-time')) {
                var section = document.createElement('div');
                section.id = day + '-time';
                section.className = 'mb-2';
                section.innerHTML = `
                    <label>${day} Timing</label>
                    <div class="d-flex gap-2">
                        <input type="time" class="form-control" placeholder="Start Time">
                        <input type="time" class="form-control" placeholder="End Time">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeDay('${day}')">&times;</button>
                    </div>
                `;
                container.appendChild(section);
            }
        }
        function removeDay(day) {
            var section = document.getElementById(day + '-time');
            if (section) {
                section.remove();
            }
            updatePlanning();
        }
    
      // Pre-fill the schedule when the page loads
      document.addEventListener('DOMContentLoaded', function () {
            let schedule = @json(json_decode($user->planning, true));
            for (let day in schedule) {
                toggleDayTime(day);
                let section = document.getElementById(day + '-time');
                if (section) {
                    section.querySelector("input:nth-child(1)").value = schedule[day].startTime;
                    section.querySelector("input:nth-child(2)").value = schedule[day].endTime;
                }
            }});
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Update Coach</h2>
        <form action="{{route('coach.update',$user->id)}}" onsubmit="updatePlanning()" method="POST">
            @csrf
            @method('PUT')
    
            
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullname" value="{{$user->full_name}}"required>
            </div>
            <div class="mb-3">
                <label for="Email" class="form-label">Email </label>
                <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}"required>
            </div>

            <div class="mb-3">
                <label for="Password" class="form-label">Password </label>
                <input type="password" class="form-control" id="Password" name="password"value="{{$user->password}}"required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="tel" value="{{$user->tel}}" required>
            </div>
            
            <div class="mb-3">
                <label for="specialist" class="form-label">Specialist</label>
                <select class="form-select" id="specialist" name="specialist" required>
                    <option value="">Select Specialization</option>
                    @foreach ($specialities as $speciality )
                    <option value="{{$speciality}}" {{$user->speciality == $speciality ? 'selected':''}}>{{$speciality}}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Is Available</label>
                <select class="form-select" id="isAvailable" name="isAvailable" required>
                    <option value=""> is available  </option>
                    <option value="yes"  {{$user->is_available ? 'selected':''}}>Yes</option>
                    <option value="no" {{$user->is_available ? '':'selected'}}>No</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Week Timing</label>
                <select class="form-select" id="weekTiming" onchange="toggleDayTime(this.value)">
                    <option value="">Select Day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>
            
            <div id="dayTimingsContainer"></div>
            
            <input type="hidden" id="planning" name="planning">
            <button type="submit" class="btn btn-primary" >Submit</button>
        </form>
    </div>
</body>
</html> -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Coach Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .form-container {
            background: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .time-section {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .time-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .btn-custom:hover {
            color: white;
            opacity: 0.9;
        }
    </style>
    <script>
        function toggleDayTime(day) {
            if (!day) return;
            var container = document.getElementById('dayTimingsContainer');
            if (!document.getElementById(day + '-time')) {
                var section = document.createElement('div');
                section.id = day + '-time';
                section.className = 'time-section';
                section.innerHTML = `
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-primary"><i class="fas fa-clock me-2"></i>${day} Schedule</h6>
                        <button type="button" class="btn btn-sm btn-link text-danger" onclick="removeDay('${day}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <input type="time" class="form-control" placeholder="Start Time">
                        <input type="time" class="form-control" placeholder="End Time">
                    </div>
                `;
                container.appendChild(section);
            }
        }
        function removeDay(day) {
            var section = document.getElementById(day + '-time');
            if (section) {
                section.remove();
            }
            updatePlanning();
        }
        function updatePlanning() {
    let schedule = {};
    document.querySelectorAll("#dayTimingsContainer div").forEach(div => {
        let day = div.id.replace("-time", "");
        let inputs = div.querySelectorAll("input");
        let startTime = inputs[0] ? inputs[0].value : "";
        let endTime = inputs[1] ? inputs[1].value : "";

        console.log("Day:", day);
        console.log("Start Time:", startTime);
        console.log("End Time:", endTime);

        if (startTime && endTime && day.trim() !== "") {
            schedule[day] = { startTime, endTime };
        }
    });

    console.log("Final Schedule:", schedule);
    document.getElementById("planning").value = Object.keys(schedule).length > 0 ? JSON.stringify(schedule) : "{}";
}
    </script>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="form-container">
            <div class="gradient-header p-4">
                <h2 class="mb-0"><i class="fas fa-user-edit me-2"></i>Update Coach Profile</h2>
            </div>
            
            <form action="{{route('coach.update',$user->id)}}" onsubmit="updatePlanning()" method="POST" class="p-4">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="fullName" class="form-label"><i class="fas fa-user me-2"></i>Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="fullname" value="{{$user->full_name}}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="Email" class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}" required>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="Password" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                        <input type="password" class="form-control" id="Password" name="password" value="{{$user->password}}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label"><i class="fas fa-phone me-2"></i>Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="tel" value="{{$user->tel}}" required>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="specialist" class="form-label"><i class="fas fa-certificate me-2"></i>Specialization</label>
                        <select class="form-select" id="specialist" name="specialist" required>
                            <option value="">Select Specialization</option>
                            @foreach ($specialities as $speciality )
                            <option value="{{$speciality}}" {{$user->speciality == $speciality ? 'selected':''}}>{{$speciality}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-calendar-check me-2"></i>Availability</label>
                        <select class="form-select" id="isAvailable" name="isAvailable" required>
                            <option value="">Select Availability</option>
                            <option value="yes" {{$user->is_available ? 'selected':''}}>Available</option>
                            <option value="no" {{$user->is_available ? '':'selected'}}>Not Available</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-muted mb-3"><i class="fas fa-calendar-alt me-2"></i>Schedule Management</h5>
                            <div class="mb-3">
                                <label class="form-label">Add Working Day</label>
                                <div class="input-group">
                                    <select class="form-select" id="weekTiming" onchange="toggleDayTime(this.value)">
                                        <option value="">Select Day</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" onclick="toggleDayTime(document.getElementById('weekTiming').value)">
                                        <i class="fas fa-plus"></i> Add Day
                                    </button>
                                </div>
                            </div>
                            <div id="dayTimingsContainer" class="mt-3">
                                <!-- Dynamic content will be added here -->
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="planning" name="planning">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-custom btn-lg">
                        <i class="fas fa-save me-2"></i>Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize existing schedule when page loads
        document.addEventListener('DOMContentLoaded', function () {
            let schedule = @json(json_decode($user->planning, true));
            for (let day in schedule) {
                toggleDayTime(day);
                let section = document.getElementById(day + '-time');
                if (section) {
                    section.querySelector("input:nth-child(1)").value = schedule[day].startTime;
                    section.querySelector("input:nth-child(2)").value = schedule[day].endTime;
                }
            }
        });
    </script>
</body>
</html>