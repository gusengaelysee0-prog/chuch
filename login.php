<?php
require_once __DIR__ . '/backend/config/bootstrap.php';
if (!empty($_SESSION['admin'])) {
    header('Location: /chuch/admin/dashboard.php');
    exit;
}
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/chuch';
$pageTitle = 'ADEPR Nyanza | Login';
$pageDescription = 'Admin login for ADEPR Nyanza dashboard access.';
$pageUrl = $baseUrl . '/login.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="author" content="ADEPR Nyanza">
  <link rel="canonical" href="<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="icon" type="image/png" href="image/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="page-transition">
  <canvas id="rainCanvas"></canvas>
  <div id="site-header"></div>

  <main class="section-gap page-offset d-flex align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
          <div class="activity-card p-4 login-box reveal">
            <h1 class="section-title text-center mb-3">Sign In</h1>
            <p class="text-center text-muted mb-4">Access your ADEPR Nyanza account. Use <strong>pastor</strong> for both username and password.</p>
            <form id="siteLoginForm" novalidate>
              <div class="mb-3 input-icon-group">
                <i class="fa-solid fa-user"></i>
                <input id="siteUsername" class="form-control" type="text" placeholder="pastor" required>
              </div>
              <div class="mb-3 input-icon-group">
                <i class="fa-solid fa-lock"></i>
                <input id="sitePassword" class="form-control" type="password" placeholder="pastor" required>
              </div>
              <button class="btn btn-church w-100" type="submit">Sign In</button>
              <p id="siteLoginMessage" class="small mt-3 mb-0 text-center"></p>
            </form>
          </div>
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
