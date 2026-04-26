<!doctype html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>ADEPR Admin | Updates</title>
<link rel="icon" type="image/png" href="../image/logo.png"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"><link rel="stylesheet" href="assets/css/admin.css"></head>
<body data-page="updates"><div class="admin-wrapper"><aside class="sidebar" id="sidebar"><a class="brand" href="dashboard.php"><img src="../image/logo.png" alt="logo"><span>ADEPR Admin</span></a><nav class="side-menu nav flex-column">
<a class="nav-link" data-page="dashboard" href="dashboard.php"><i class="fa-solid fa-chart-line"></i>Dashboard</a><a class="nav-link" data-page="images" href="images.php"><i class="fa-solid fa-image"></i>Images</a><a class="nav-link" data-page="videos" href="videos.php"><i class="fa-solid fa-video"></i>Videos</a><a class="nav-link" data-page="updates" href="updates.php"><i class="fa-solid fa-newspaper"></i>Updates</a><a class="nav-link" data-page="notifications" href="notifications.php"><i class="fa-solid fa-bell"></i>Notifications</a><a class="nav-link" data-page="admins" href="admins.php"><i class="fa-solid fa-users-gear"></i>Admins</a></nav></aside>
<main class="main-panel"><div class="topbar d-flex justify-content-between align-items-center"><button id="sidebarToggle" class="btn btn-outline-secondary d-lg-none"><i class="fa-solid fa-bars"></i></button><h1 class="h5 mb-0">Manage Updates</h1></div>
<section class="content"><div class="card card-soft p-3 mb-4" id="updatesSection"><h2 class="h6">Create Update</h2><div class="row g-3">
<div class="col-md-4"><label class="form-label small">Title</label><input id="updateTitle" class="form-control" placeholder="Enter update title"></div>
<div class="col-md-4"><label class="form-label small">Photo</label><input id="updateImage" type="file" accept="image/*" class="form-control"></div>
<div class="col-md-4"><label class="form-label small">Description</label><textarea id="updateContent" class="form-control" rows="1" placeholder="Enter update description"></textarea></div>
<div class="col-12 d-flex flex-column flex-sm-row align-items-start align-items-sm-end gap-3 mt-2"><button id="saveUpdateBtn" class="btn btn-gradient">Save Update</button><span id="updateFormMessage" class="small text-muted">Choose a photo to upload with the update.</span></div>
<div class="col-12"><img id="updateImagePreview" src="" alt="Preview" class="img-fluid rounded mt-3" style="display:none; max-height:240px; object-fit:cover;"></div>
</div></div>
<div class="card card-soft p-3"><div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h6 mb-0">Updates List</h2><p class="small text-muted mb-0">Uploaded updates appear here.</p></div><div id="updatesList" class="list-group list-group-flush"></div></div>
</section></main></div><script src="assets/js/admin.js"></script></body></html>

