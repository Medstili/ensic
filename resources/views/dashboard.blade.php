<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Dashboard</title>
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
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Coach Dashboard</h2>
        <form  action="{{route('coach.create')}}" method="get">
            @csrf
            <button class="btn btn-primary mb-3" >Add Coach</button>
        </form>
        
        
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>id</th>
                    <th>Full Name</th>
                    <th>Speciality</th>
                    <th>Availability</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
             @foreach ( $users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->speciality }}</td>
                    <td>{{ $user->is_available ? 'Yes' : 'No' }}</td>
                    <td>{{ $user->is_admin ?' Admin' : 'Coach' }}</td>
                    <td>
                        <a href="{{ route('coach.show', $user->id) }}" class="btn btn-primary btn-sm">details</a>
                        <form action="{{ route('coach.destroy', $user->id) }}" method="POST" style="display: inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                </tr>
             
             @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Coach Modal -->
    <div class="modal fade" width="300px" id="addCoachModal" tabindex="-1" aria-labelledby="addCoachModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCoachModalLabel">Add Coach</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('user.store')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" required>
                        </div>
                        <div class="mb-3">
                            <label for="Email" class="form-label">Email </label>
                            <input type="email" class="form-control" id="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="Password" class="form-label">Password </label>
                            <input type="password" class="form-control" id="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="specialist" class="form-label">Specialist</label>
                            <select class="form-select" id="specialist" required>
                                <option value="">Select Specialization</option>
                                <option value="Fitness">Fitness</option>
                                <option value="Yoga">Yoga</option>
                                <option value="Nutrition">Nutrition</option>
                                <option value="Mental Health">Mental Health</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Is Available</label>
                            <select class="form-select" id="isAvailable" name="isAvailable">
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Is Admin</label>
                            <select class="form-select" id="isAdmin" name="isAdmin">
                                <option value="0">Yes</option>
                                <option value="1">No</option>
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
                        
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
