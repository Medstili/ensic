



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Healthcare Analytics Dashboard</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
  <!-- <link rel="stylesheet" type="text/css" href="{{ url('/css/style.css') }}" /> -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-.min.css"/>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.js"></script>

  <!-- Custom Styles -->
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #ecf0f1;
      margin: 0;
    }
    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 250px;
      background-color: #2c3e50;
      color: #ecf0f1;
      display: flex;
      flex-direction: column;
      padding: 20px;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }
    .sidebar .logo {
      font-size: 1.8rem;
      font-weight: bold;
      margin-bottom: 30px;
      text-align: center;
    }
    .sidebar form {
      margin-bottom: 10px;
    }
    .sidebar .menu-item {
      width: 100%;
      background: transparent;
      border: none;
      color: #ecf0f1;
      text-align: left;
      padding: 10px 15px;
      font-size: 1rem;
      border-radius: 4px;
      transition: background 0.3s;
    }
    .sidebar .menu-item:hover {
      background: rgba(236, 240, 241, 0.15);
      cursor: pointer;
    }

    /* Top Navbar */
    .navbar {
      margin-left: 250px;
      height: 60px;
      background-color: #fff;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      padding: 0 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 900;
    }
    .navbar .profile {
      display: flex;
      align-items: center;
    }
    .navbar .profile-img {
      width: 40px;
      height: 40px;
      background-color: #3498db;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: #fff;
      margin-right: 10px;
    }
    .navbar h4 {
      margin: 0;
      font-size: 1rem;
      color: #333;
    }
    .navbar p {
      margin: 0;
      font-size: 0.8rem;
      color: #777;
    }

    /* Main Content */
    .main-content {
      margin-left: 250px;
      padding: 20px;
      min-height: calc(100vh - 60px);
      background-color: #ecf0f1;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">HealthMetrics</div>
    <form action="{{ route('global') }}" method="get">
      <button class="menu-item"> Dashboard</button>
    </form>
    <form action="{{ route('appointment.index') }}" method="get">
      <button class="menu-item">Appointments</button>
    </form>
    <form action="{{ route('user.index') }}" method="get">
      <button class="menu-item">Coaches</button>
    </form>
    <form action="{{ route('patient.index') }}" method="get">
      <button class="menu-item">Patients</button>
    </form>
  </div>

  <!-- Top Navbar -->
  <nav class="navbar">
    <div class="profile">
      <div class="profile-img">AP</div>
      <div>
        <h4>Admin Profile</h4>
        <p>System Administrator</p>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
