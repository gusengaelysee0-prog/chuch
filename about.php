<?php
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/chuch';
$pageTitle = 'ADEPR Nyanza | About';
$pageDescription = 'About ADEPR Nyanza Busasamana church, its mission, vision, leadership, and ministries in Nyanza, Rwanda.';
$pageUrl = $baseUrl . '/about.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="index, follow">
  <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="author" content="ADEPR Nyanza">
  <link rel="canonical" href="<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url" content="<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image" content="<?= htmlspecialchars($baseUrl . '/image/logo.png', ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($baseUrl . '/image/logo.png', ENT_QUOTES, 'UTF-8') ?>">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="icon" type="image/png" href="image/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <canvas id="rainCanvas"></canvas>
  <div id="site-header"></div>
  <main class="section-gap page-offset">
    <div class="container">
      <h1 class="section-title text-center mb-4">About ADEPR Nyanza Busasamana</h1>
      <section class="reveal mb-5">
        <p class="text-center mx-auto" style="max-width:900px;">ADEPR Nyanza has grown from a small fellowship into a vibrant church community in Busasamana. Through worship, teaching, evangelism, and practical service, the church has become a spiritual home for many families across Nyanza District.</p>
      </section>

      <div class="row g-4 reveal mb-5">
        <div class="col-md-6"><div class="activity-card h-100"><i class="fa-solid fa-bullseye"></i><h4>Mission</h4><p>To preach Christ, disciple believers, and transform the community through faith, love, and practical ministry.</p></div></div>
        <div class="col-md-6"><div class="activity-card h-100"><i class="fa-solid fa-eye"></i><h4>Vision</h4><p>To be a spiritually mature, united, and impactful church serving Nyanza and surrounding communities.</p></div></div>
      </div>

      <section class="reveal mb-5">
        <h2 class="section-title text-center mb-4">Leadership</h2>
        <div class="row g-4">
          <div class="col-md-4"><div class="activity-card text-center"><i class="fa-solid fa-user-tie"></i><h5>Senior Pastor</h5><p>Provides spiritual oversight, teaching, and pastoral care.</p></div></div>
          <div class="col-md-4"><div class="activity-card text-center"><i class="fa-solid fa-people-group"></i><h5>Ministry Leaders</h5><p>Coordinate youth, women, men, and discipleship ministries.</p></div></div>
          <div class="col-md-4"><div class="activity-card text-center"><i class="fa-solid fa-microphone-lines"></i><h5>Choir Team</h5><p>Leads worship services and special praise gatherings.</p></div></div>
        </div>
      </section>

      <section class="reveal mb-5">
        <h2 class="section-title text-center mb-4">Ministries, Choirs & Activities</h2>
        <div class="row g-4">
          <div class="col-md-6 col-lg-3"><div class="activity-card"><img src="image/optimized/choir1.jpg" class="preview-img mb-2 lazy-fade" loading="lazy" decoding="async" alt="Choir"><h6>Choirs</h6><p>Adult, youth, and children choirs for worship excellence.</p></div></div>
          <div class="col-md-6 col-lg-3"><div class="activity-card"><img src="image/optimized/choir4.jpg" class="preview-img mb-2 lazy-fade" loading="lazy" decoding="async" alt="Ministry"><h6>Prayer Ministry</h6><p>Weekly prayer sessions and intercession for families.</p></div></div>
          <div class="col-md-6 col-lg-3"><div class="activity-card"><img src="image/optimized/choir7.jpg" class="preview-img mb-2 lazy-fade" loading="lazy" decoding="async" alt="Youth"><h6>Youth Ministry</h6><p>Mentorship, worship nights, and discipleship programs.</p></div></div>
          <div class="col-md-6 col-lg-3"><div class="activity-card"><img src="image/optimized/choir10.jpg" class="preview-img mb-2 lazy-fade" loading="lazy" decoding="async" alt="Outreach"><h6>Outreach</h6><p>Evangelism and social support for local communities.</p></div></div>
        </div>
      </section>

      <section class="reveal">
        <h2 class="section-title text-center mb-4">Location & Contact</h2>
        <div class="row g-4">
          <div class="col-lg-6"><div class="notice-card h-100"><h5><i class="fa-solid fa-location-dot me-2"></i>Church Location</h5><p>Country: Rwanda</p><p>District: Nyanza</p><p>Sector: Busasamana</p><div class="activity-card mt-3 text-center"><i class="fa-solid fa-map"></i><p class="mb-0">Map Placeholder (Google Map integration ready)</p></div></div></div>
          <div class="col-lg-6"><div class="notice-card h-100"><h5><i class="fa-solid fa-address-book me-2"></i>Contact Details</h5><p><i class="fa-solid fa-phone me-2"></i>+250 700 000 000</p><p><i class="fa-solid fa-envelope me-2"></i>info@adeprnyanza.org</p><p><i class="fa-solid fa-clock me-2"></i>Sunday Service: 9:00 AM</p></div></div>
        </div>
      </section>
    </div>
  </main>
  <button id="scrollTopBtn" class="scroll-top-btn" aria-label="Scroll to top"><i class="fa-solid fa-arrow-up"></i></button>
  <div id="site-footer"></div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/components.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
