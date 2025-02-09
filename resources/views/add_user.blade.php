<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Add Coach Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
            function toggleDayTime(day) {
    if (!day || day.trim() === "") {
        console.error("Invalid day value:", day); // Debug: Log invalid days
        return;
    }

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
        function updatePlanning() {
            let schedule = {};
            document.querySelectorAll("#dayTimingsContainer div").forEach(div => {
                let day = div.id.replace("-time", "");
                let startTime = div.querySelector("input:nth-child(1)").value;
                let endTime = div.querySelector("input:nth-child(2)").value;

                console.log("Day:", day);
                console.log("Start Time:", startTime); 
                console.log("End Time:", endTime); 

                if (startTime && endTime && day.trim() !== "") {
                    schedule[day] = { startTime, endTime };
                }
            });
            console.log("Final Schedule:", schedule); 
            document.getElementById("planning").value = JSON.stringify(schedule);
        }
            
        

        
        function removeDay(day) {
            var section = document.getElementById(day + '-time');
            if (section) {
                section.remove();
                updatePlanning();
            }
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Add Coach</h2>

                <!-- Add error messages -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('coach.store') }}" onsubmit="updatePlanning()" method="post">
            @csrf
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullname" value=""required>
            </div>
            <div class="mb-3">
                <label for="Email" class="form-label">Email </label>
                <input type="email" class="form-control" id="email"  name="email"value=""required>
            </div>

            <div class="mb-3">
                <label for="Password" class="form-label" >Password </label>
                <input type="password" class="form-control" id="Password" name="password"value=""required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label" >Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="tel" value="" required>
            </div>
            
            <div class="mb-3">
                <label for="specialist" class="form-label">Specialist</label>
                <select class="form-select" id="specialist" name="specialist" required>
                    <option value="">Select Specialization</option>
                    @foreach ($specialities as $speciality )
                        <option value="{{$speciality}}" >{{$speciality}}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Is Available</label>
                <select class="form-select" id="isAvailable" name="isAvailable" required>
                    <option value=""> is available  </option>
                    <option value="yes">Yes</option>
                    <option value="no" >No</option>
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
</html>
