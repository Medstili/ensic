<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
.coach-profile {
  font-family: 'Segoe UI', sans-serif;
  max-width: 1000px;
  margin: 2rem auto;
  background: #f9fbfd;
  border-radius: 15px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  padding: 2rem;
}

.coach-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 2rem;
  border-bottom: 2px solid #eee;
}

.coach-avatar {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #3a86ff;
}

.coach-info h1 {
  color: #1a237e;
  margin: 0;
  font-size: 2.2rem;
}

.specialty {
  color: #4a5568;
  font-size: 1.1rem;
  margin: 0.5rem 0;
}

.badge-experience {
  background: #3a86ff;
  color: white;
  padding: 0.3rem 1rem;
  border-radius: 20px;
  display: inline-block;
  font-weight: 500;
}

.coach-meta p {
  margin: 0.5rem 0;
  color: #666;
}

.edit-btn {
  background: #ff9800;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  cursor: pointer;
  transition: transform 0.2s;
}

.edit-btn:hover {
  transform: translateY(-2px);
}

.appointments-table , .patients-table{
  margin-top: 2rem;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
}

th, td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #eee;
}

th {
  background: #3a86ff;
  color: white;
}

.status-badge {
  display: inline-block;
  padding: 0.3rem 0.8rem;
  border-radius: 15px;
  font-size: 0.9rem;
}

.status-badge.passed {
  background: #c6f6d5;
  color: #22543d;
}

.status-badge.upcoming {
  background: #bee3f8;
  color: #2a4365;
}

.status-badge.cancelled {
  background: #fed7d7;
  color: #742a2a;
}

.details-btn, .report-btn {
  background: #3a86ff;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
  transition: transform 0.2s;
}

.details-btn:hover, .report-btn:hover {
  transform: translateY(-2px);
}

.report-link {
  color: #3a86ff;
  text-decoration: none;
  font-weight: 500;
}

.report-pending {
  color: #a0aec0;
  font-style: italic;
}
.table-switcher {
  margin-top: 2rem;
}
.switch-btn {
  background: #3a86ff;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  cursor: pointer;
  margin-right: 10px;
}

.switch-btn:hover {
  background: #2a6ad4;
}
</style>
<body class="coach-profile">
  <!-- Coach Header -->
  <div class="coach-header">
    <img src="https://i.pravatar.cc/150" alt="Coach image profile" class="coach-avatar">
    <div class="coach-info">
      <h1>{{$coach->full_name}}</h1>
      <p class="specialty">{{$coach->speciality}} Coach</p>      
      <div class="coach-meta">
        <p>📧 {{$coach->email}}</p>
        <p>📱 {{$coach->tel}}</p>
        <p> {{$coach->is_available == 1 ? 'Available' :'Not Available'}}</p>
        <div class="schedule">
            <h3>📅 Schedule:</h3>
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
      </div>
    </div>
    <form action="{{ route('coach.edit', $coach->id) }}" method="GET">
        @csrf
        <button class="edit-btn">✏️ Edit</button>
    </form>
  </div>

   <!-- Buttons for switching tables -->
   <div class="table-switcher">
    <button class="switch-btn" onclick="showTable('appointments')">Appointments</button>
    <button class="switch-btn" onclick="showTable('patients')">Patients</button>
  </div>
  <!-- Appointments Table -->
  <div class="appointments-table" id="appointments">
    
    <table>
      <thead>
        <tr>
          <th>Patient Name</th>
          <th>Appointment Day</th>
          <th>Status</th>
          <th>Details</th>
          <th>Session Report</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Sarah Johnson</td>
          <td>Mon, 15 July 2024 - 2:00 PM</td>
          <td><span class="status-badge passed">Completed</span></td>
          <td><button class="details-btn">View Details</button></td>
          <td><a href="reports/sarah_johnson_150724.pdf" class="report-btn" download>📄 Download PDF</a></td>
        </tr>
        <tr>
          <td>James Wilson</td>
          <td>Tue, 16 July 2024 - 10:00 AM</td>
          <td><span class="status-badge upcoming">Scheduled</span></td>
          <td><button class="details-btn">View Details</button></td>
          <td><span class="report-pending">Pending</span></td>
        </tr>
        <tr>
          <td>Emma Roberts</td>
          <td>Wed, 17 July 2024 - 4:30 PM</td>
          <td><span class="status-badge cancelled">Cancelled</span></td>
          <td><button class="details-btn">View Details</button></td>
          <td><a href="#" class="report-link">View Notes</a></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="patients-table" id="patients" style="display:none;">
  
    <table>
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Phone</th>
          <th>Reason for Visit</th>
          <th>Treatment Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>John Doe</td>
          <td>+1 (555) 678-9012</td>
          <td>Stress Management</td>
          <td>Ongoing</td>
        </tr>
        <tr>
          <td>Jane Smith</td>
          <td>+1 (555) 234-5678</td>
          <td>Career Coaching</td>
          <td>Completed</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

</body>

<script>

function showTable(tableId) {
  document.getElementById('appointments').style.display = (tableId === 'appointments') ? 'block' : 'none';
  document.getElementById('patients').style.display = (tableId === 'patients') ? 'block' : 'none';
  console.log(tableId);
  
}
</script>
</html>


