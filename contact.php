<?php
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/chuch';
$pageTitle = 'ADEPR Nyanza | Contact';
$pageDescription = 'Contact ADEPR Nyanza for church services, events, prayer requests, and community ministry information.';
$pageUrl = $baseUrl . '/contact.php';
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
  <main class="section-gap section-light page-offset">
    <div class="container">
      <h1 class="section-title text-center mb-4">Contact Us</h1>
      <div class="row g-4 reveal">
        <div class="col-lg-5"><div class="notice-card h-100"><i class="fa-solid fa-location-dot"></i><h5>Location</h5><p>ADEPR Nyanza Busasamana, Nyanza District, Rwanda</p><p><i class="fa-solid fa-phone me-2"></i>+250 700 000 000</p><p><i class="fa-solid fa-envelope me-2"></i>info@adeprnyanza.org</p></div></div>
        <div class="col-lg-7">
          <form id="contactForm" class="contact-form p-4 rounded-4 shadow-sm bg-white">
            <div class="mb-3"><label class="form-label">Name</label><input type="text" id="name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" id="email" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Message</label><textarea id="message" rows="5" class="form-control" required></textarea></div>
            <button class="btn btn-church" type="submit">Send Message</button>
            <p id="formMessage" class="mt-3 mb-0"></p>
          </form>
        </div>
      </div>
    </div>
  </main>
  <button id="scrollTopBtn" class="scroll-top-btn" aria-label="Scroll to top"><i class="fa-solid fa-arrow-up"></i></button>
  <div id="site-footer"></div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/components.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
