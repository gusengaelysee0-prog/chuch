<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ADEPR Admin | Dashboard</title>
  <link rel="icon" type="image/png" href="../image/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body data-page="dashboard">
<div class="admin-wrapper">
  <aside class="sidebar" id="sidebar">
    <a class="brand" href="dashboard.php"><img src="../image/logo.png" alt="logo"><span>ADEPR Admin</span></a>
    <nav class="side-menu nav flex-column">
      <a class="nav-link" data-page="dashboard" href="dashboard.php"><i class="fa-solid fa-chart-line"></i>Dashboard</a>
      <a class="nav-link" data-page="images" href="images.php"><i class="fa-solid fa-image"></i>Images</a>
      <a class="nav-link" data-page="videos" href="videos.php"><i class="fa-solid fa-video"></i>Videos</a>
      <a class="nav-link" data-page="updates" href="updates.php"><i class="fa-solid fa-newspaper"></i>Updates</a>
      <a class="nav-link" data-page="notifications" href="notifications.php"><i class="fa-solid fa-bell"></i>Notifications</a>
      <a class="nav-link" data-page="admins" href="admins.php"><i class="fa-solid fa-users-gear"></i>Admins</a>
    </nav>
  </aside>
  <main class="main-panel">
    <div class="topbar d-flex justify-content-between align-items-center">
      <button id="sidebarToggle" class="btn btn-outline-secondary d-lg-none"><i class="fa-solid fa-bars"></i></button>
      <h1 class="h5 mb-0">Dashboard Overview</h1>
      <div class="profile">
        <span class="small">Admin: Super Admin</span>
        <span class="profile-icon"><i class="fa-solid fa-user"></i></span>
        <a class="btn btn-sm btn-gradient" href="logout.php"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
      </div>
    </div>
    <section class="content">
      <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3"><div class="card card-soft p-3"><div class="d-flex justify-content-between"><div><p class="mb-1 text-muted">Total Images</p><h3 class="mb-0" id="statImages">0</h3></div><span class="stat-icon bg-red"><i class="fa-solid fa-image"></i></span></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card card-soft p-3"><div class="d-flex justify-content-between"><div><p class="mb-1 text-muted">Total Videos</p><h3 class="mb-0" id="statVideos">0</h3></div><span class="stat-icon bg-blue"><i class="fa-solid fa-video"></i></span></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card card-soft p-3"><div class="d-flex justify-content-between"><div><p class="mb-1 text-muted">Updates</p><h3 class="mb-0" id="statUpdates">0</h3></div><span class="stat-icon bg-purple"><i class="fa-solid fa-newspaper"></i></span></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card card-soft p-3"><div class="d-flex justify-content-between"><div><p class="mb-1 text-muted">Notifications</p><h3 class="mb-0" id="statNotifications">0</h3></div><span class="stat-icon bg-pink"><i class="fa-solid fa-bell"></i></span></div></div></div>
      </div>

      <div class="card card-soft p-3">
        <h2 class="h6 mb-3">Recent Activity</h2>
        <div class="table-responsive">
          <table class="table table-modern align-middle mb-0">
            <thead><tr><th>Activity</th><th>Section</th><th>Time</th></tr></thead>
            <tbody>
              <tr><td>Uploaded new choir image</td><td>Images</td><td>2 mins ago</td></tr>
              <tr><td>Added Easter celebration video</td><td>Videos</td><td>20 mins ago</td></tr>
              <tr><td>Published youth retreat update</td><td>Updates</td><td>1 hour ago</td></tr>
              <tr><td>Posted prayer meeting notice</td><td>Notifications</td><td>3 hours ago</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>
</div>
<script src="assets/js/admin.js"></script>
</body>
</html>

