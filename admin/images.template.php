<!doctype html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>ADEPR Admin | Images</title>
<link rel="icon" type="image/png" href="../image/logo.png"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"><link rel="stylesheet" href="assets/css/admin.css"></head>
<body data-page="images"><div class="admin-wrapper">
<aside class="sidebar" id="sidebar"><a class="brand" href="dashboard.php"><img src="../image/logo.png" alt="logo"><span>ADEPR Admin</span></a><nav class="side-menu nav flex-column">
<a class="nav-link" data-page="dashboard" href="dashboard.php"><i class="fa-solid fa-chart-line"></i>Dashboard</a>
<a class="nav-link" data-page="images" href="images.php"><i class="fa-solid fa-image"></i>Images</a>
<a class="nav-link" data-page="videos" href="videos.php"><i class="fa-solid fa-video"></i>Videos</a>
<a class="nav-link" data-page="updates" href="updates.php"><i class="fa-solid fa-newspaper"></i>Updates</a>
<a class="nav-link" data-page="notifications" href="notifications.php"><i class="fa-solid fa-bell"></i>Notifications</a>
<a class="nav-link" data-page="admins" href="admins.php"><i class="fa-solid fa-users-gear"></i>Admins</a></nav></aside>
<main class="main-panel"><div class="topbar d-flex justify-content-between align-items-center"><button id="sidebarToggle" class="btn btn-outline-secondary d-lg-none"><i class="fa-solid fa-bars"></i></button><h1 class="h5 mb-0">Manage Images</h1><button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#imageModal"><i class="fa-solid fa-plus me-1"></i>Add Image</button></div>
<section class="content">
  <div class="card card-soft p-3 mb-3">
    <div class="row g-3 align-items-end">
      <div class="col-md-4"><label class="form-label small">Search</label><input id="imageSearch" class="form-control" placeholder="Search by title/category"></div>
      <div class="col-md-4"><label class="form-label small">Sort</label><select id="imageSort" class="form-select"><option value="newest">Newest</option><option value="oldest">Oldest</option><option value="category">Category</option></select></div>
      <div class="col-md-4"><label class="form-label small">Category View</label><div id="imageCategoryTabs" class="btn-group w-100 flex-wrap"><button class="btn btn-outline-primary active" data-filter="All">All</button><button class="btn btn-outline-primary" data-filter="Featured">Featured</button><button class="btn btn-outline-primary" data-filter="Choir">Choir</button><button class="btn btn-outline-primary" data-filter="Leaders">Leaders</button><button class="btn btn-outline-primary" data-filter="Events">Events</button><button class="btn btn-outline-primary" data-filter="Others">Others</button></div></div>
    </div>
  </div>
  <div id="imageGrid" class="row g-3"></div>
</section></main></div>

<div class="modal fade" id="imageModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Add New Image</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
  <div class="mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input id="imageTitle" class="form-control" placeholder="Image title"></div>
  <div class="mb-2"><label class="form-label">Category <span class="text-danger">*</span></label><select id="imageCategory" class="form-select"><option value="">Choose category</option><option>Featured</option><option>Choir</option><option>Leaders</option><option>Events</option><option>Others</option></select></div>
  <div class="mb-2"><label class="form-label">Description <span class="text-danger">*</span></label><textarea id="imageDescription" class="form-control" rows="3" placeholder="Image description"></textarea></div>
  <div id="dropzone" class="upload-dropzone mb-3"><i class="fa-solid fa-cloud-arrow-up fs-4 mb-2"></i><p class="mb-2">Drag & drop image here</p><input id="imageFile" type="file" class="form-control" accept="image/*"></div>
  <img id="imagePreview" class="preview-box" alt="Preview">
  <div class="progress mt-3"><div id="fakeProgressBar" class="progress-bar bg-primary" style="width:0%"></div></div>
  <p id="imageFormMessage" class="small mb-0 mt-2"></p>
</div>
<div class="modal-footer"><button class="btn btn-gradient" id="saveImageBtn">Save Image</button></div>
</div></div></div>
<div class="toast-stack" id="toastStack"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="assets/js/admin.js"></script>
</body></html>

