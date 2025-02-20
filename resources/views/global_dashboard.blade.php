<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Analytics Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #4f46e5;
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-color: #a5b4fc;
            --text-dark: #1e293b;
            --text-light: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f1f5f9;
            min-height: 100vh;
        }

        /* Glowing Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: var(--gradient-bg);
            padding: 30px 20px;
            box-shadow: 4px 0 15px rgba(99, 102, 241, 0.2);
            border-radius: 0 20px 20px 0;
            z-index: 1000;
        }

        .logo {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 50px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .menu-item {
            padding: 15px 20px;
            margin: 12px 0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 15px;
            backdrop-filter: blur(5px);
            background: rgba(255, 255, 255, 0.1);
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(10px);
        }

        /* Top Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0px;
            right: 0;
            height: 80px;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0 40px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            z-index: 999;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-dark);
        }

        .profile-img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--gradient-bg);
            display: grid;
            place-items: center;
            color: white;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            margin-top: 80px;
            padding: 40px;
            width: calc(100% - 280px);
        }

        /* Neon Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gradient-bg);
        }

        .stat-value {
            color: var(--primary-color);
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        /* Glassmorphic Charts */
        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 40px;
        }

        .chart-box {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        /* Holographic Table */
        .appointment-table {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            background: var(--accent-color);
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <!-- Holographic Sidebar 
    <div class="sidebar">
        <div class="logo">HealthMetrics</div>
        <div class="menu-item">📅 Appointments</div>
        <div class="menu-item">👨🏫 Coaches</div>
        <div class="menu-item">👥 Patients</div>
    </div>

    <!-- Top Navbar
    <nav class="navbar">
        <div class="profile">
            <div class="profile-img">AP</div>
            <div>
                <h4>Admin Profile</h4>
                <p>System Administrator</p>
            </div>
        </div>
    </nav> -->
 @extends('layouts.app')
    <!-- Main Content -->
    @section('content')
    
    <style>

    /* Main Content */
    /* .main-content {
      width: calc(100%-250px);
      /* margin:  auto; */
      /* background-color: red; */
      /* margin-left: 250px; */
    /* } */
    
    /* Stats Cards */
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }
    .stat-card {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }
    .stat-card:hover {
      transform: translateY(-5px);
    }
    .stat-value {
      color: #2c3e50;
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 8px;
    }
    .stat-trend {
      color: #777;
      font-size: 0.9rem;
    }
    
    /* Charts */
    .charts-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }
    .chart-box {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .chart-header {
      margin-bottom: 20px;
    }
    .chart-header h3 {
      margin: 0;
      font-size: 1.2rem;
      color: #2c3e50;
    }
    
    /* Appointment Table */
    .appointment-table {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .appointment-table h3 {
      margin-bottom: 15px;
      font-size: 1.2rem;
      color: #2c3e50;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th {
      background: #2c3e50;
      color: #fff;
      padding: 12px 15px;
      text-align: left;
    }
    td {
      padding: 12px 15px;
      border-bottom: 1px solid #e2e8f0;
      color: #333;
    }
    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
      background: #e0e7ff;
      color: #4f46e5;
      display: inline-block;
    }
  </style>

        <!-- Stats Cards -->
        <div class="stats-container">
        <div class="stat-card">
            <div class="stat-value">24</div>
            <p>Active Coaches</p>
            <div class="stat-trend">↑ 12% from last month</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">158</div>
            <p>Registered Patients</p>
            <div class="stat-trend">↑ 8% from last week</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">56</div>
            <p>Appointments</p>
            <div class="stat-trend">↓ 3% cancellations</div>
        </div>
        </div>

        <!-- Interactive Charts -->
        <div class="charts-container">
        <div class="chart-box">
            <div class="chart-header">
            <h3>Specialty Distribution</h3>
            </div>
            <canvas id="specialtyChart"></canvas>
        </div>
        <div class="chart-box">
            <div class="chart-header">
            <h3>Appointment Status</h3>
            </div>
            <canvas id="statusChart"></canvas>
        </div>
        </div>

        <!-- Appointment Table -->
        <div class="appointment-table">
        <h3>Recent Appointments</h3>
        <table>
            <tr>
            <th>Patient</th>
            <th>Coach</th>
            <th>Date</th>
            <th>Status</th>
            </tr>
            <tr>
            <td>John Doe</td>
            <td>Dr. Smith</td>
            <td>2023-08-15</td>
            <td><span class="status-badge">Pending</span></td>
            </tr>
            <tr>
            <td>Sarah Johnson</td>
            <td>Coach Wilson</td>
            <td>2023-08-16</td>
            <td><span class="status-badge">Completed</span></td>
            </tr>
            <tr>
            <td>Mike Peters</td>
            <td>Dr. Anderson</td>
            <td>2023-08-17</td>
            <td><span class="status-badge">Canceled</span></td>
            </tr>
        </table>
        </div>


  <!-- Charts Script -->
    <script>
    // Specialty Chart (Bar Chart)
    new Chart(document.getElementById('specialtyChart'), {
      type: 'bar',
      data: {
        labels: ['Nutrition', 'Fitness', 'Mental', 'Rehab', 'Yoga'],
        datasets: [{
          label: 'Appointments',
          data: [35, 28, 20, 12, 5],
          backgroundColor: [
            'rgba(44, 62, 80, 0.8)',
            'rgba(52, 73, 94, 0.8)',
            'rgba(74, 101, 114, 0.8)',
            'rgba(127, 140, 141, 0.8)',
            'rgba(189, 195, 199, 0.8)'
          ],
          borderColor: '#fff',
          borderWidth: 2,
          borderRadius: 12
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        }
      }
    });

    // Status Chart (Doughnut)
    new Chart(document.getElementById('statusChart'), {
      type: 'doughnut',
      data: {
        labels: ['Completed', 'Pending', 'Canceled'],
        datasets: [{
          data: [60, 25, 15],
          backgroundColor: [
            'rgba(44, 62, 80, 0.8)',
            'rgba(52, 73, 94, 0.8)',
            'rgba(74, 101, 114, 0.8)'
          ],
          borderColor: '#fff',
          borderWidth: 3
        }]
      },
      options: {
        cutout: '70%',
        plugins: {
          legend: { position: 'bottom' }
        }
      }
    });
    </script>
    
    @endsection
  






    <!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Healthcare Analytics Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
</body>
</html> -->
