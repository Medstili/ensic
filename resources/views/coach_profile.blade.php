<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <!-- Profile Card -->
    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <!-- Profile Picture & Basic Info -->
        <div class="flex items-center space-x-6">
            <img id="profile-pic" src="https://i.pravatar.cc/150" class="w-24 h-24 rounded-full border-2 border-blue-500">
            <div>
                <h2 id="full-name" class="text-2xl font-bold text-gray-800"> {{$coach->full_name}}</h2>
                <p class="text-gray-600"><i class="fas fa-envelope"></i> <span id="email">{{$coach->email}}</span></p>
                <p class="text-gray-600"><i class="fas fa-phone"></i> <span id="phone">{{$coach->tel}}</span></p>
                <span id="admin-badge" class="hidden bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">{{$coach->is_admin?'Admin':"Coach"}}</span>
            </div>
        </div>

        <!-- Speciality & Availability -->
        <div class="mt-4">
            <h3 class="text-lg font-semibold text-gray-700"></h3>
            <p id="speciality" class="text-gray-800">{{$coach->speciality}}</p>
            <p class="mt-2">
                <span class="font-semibold">Availability:</span>
                <span id="availability" class="text-green-600 font-bold">{{$coach->is_available ?'avialable':'not available'}}</span>
            </p>
        </div>

        <!-- Weekly Schedule -->
        <div class="mt-4">
            <h3 class="text-lg font-semibold text-gray-700">Schedule</h3>
            <ul id="schedule" class="list-disc pl-4 text-gray-800">
                @php
                    $schedule = json_decode($coach->planning, true);
                @endphp
                @if (is_array($schedule))
                    @foreach ($schedule as $day => $time)
                        <li><strong>{{ $day }}:</strong> {{ $time['startTime'] }} - {{ $time['endTime'] }}</li>
                    @endforeach
                @else
                    <li>No schedule available.</li>
                @endif
            </ul>
        </div>

        <!-- Edit Profile Button -->
        <div class="mt-6 text-center">
        <form action="{{route('coach.edit',$coach->id)}}" method="get">
            @csrf
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit Profile
            </button>

        </form>
          
        </div>
    </div>

    <!-- <script>
        // User Data (Simulated JSON)
        // const user = {
        //     full_name: "John Doe",
        //     email: "johndoe@email.com",
        //     phone: "0612345678",
        //     speciality: "Fitness Trainer",
        //     isAdmin: true, 
        //     isAvailable: true,
        //     week_planning: {
        //         "Monday": "8:30 - 10:30",
        //         "Wednesday": "14:00 - 16:00"
        //     }
        // };

        // // Load User Data into the Profile
        // document.getElementById("full-name").textContent = user.full_name;
        // document.getElementById("email").textContent = user.email;
        // document.getElementById("phone").textContent = user.phone;
        // document.getElementById("speciality").textContent = user.speciality;
        // document.getElementById("availability").textContent = user.isAvailable ? "Available" : "Not Available";
        // document.getElementById("availability").classList.add(user.isAvailable ? "text-green-600" : "text-red-600");

        // Show Admin Badge if User is Admin
        // if (user.isAdmin) {
        //     document.getElementById("admin-badge").classList.remove("hidden");
        // }

        // Load Weekly Schedule
        // const scheduleList = document.getElementById("schedule");
        // for (const [day, time] of Object.entries(user.week_planning)) {
        //     const listItem = document.createElement("li");
        //     listItem.innerHTML = `<strong>${day}:</strong> ${time}`;
        //     scheduleList.appendChild(listItem);
        // }

        // Edit Profile Function (For Future Use)
        // function editProfile() {
        //     alert("Edit Profile Feature Coming Soon!");
        // }
    </script> -->

</body>
</html>
