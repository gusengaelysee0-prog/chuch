<?php
declare(strict_types=1);
require_once __DIR__ . '/backend/config/bootstrap.php';

$pdo = getDbConnection();
$stmt = $pdo->query('SELECT id, title, description, publish_date, created_at FROM notifications ORDER BY publish_date DESC, created_at DESC');
$notifications = $stmt->fetchAll();

function escapeHtml(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<?php
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/chuch';
$pageTitle = 'ADEPR Nyanza | Notifications';
$pageDescription = 'Latest announcements and notifications from ADEPR Nyanza church in Nyanza, Rwanda.';
$pageUrl = $baseUrl . '/notifications.php';
?>
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
<body class="page-transition">
  <canvas id="rainCanvas"></canvas>
  <div id="site-header"></div>
  <main class="section-gap page-offset">
    <div class="container">
      <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
        <div>
          <h1 class="section-title mb-0">Notifications</h1>
          <p class="text-muted mb-0">Latest announcements from ADEPR Nyanza.</p>
        </div>
      </div>

      <?php if (empty($notifications)): ?>
        <div class="alert alert-info rounded-4 shadow-sm">
          <strong>No notifications yet.</strong> Please check back later for updates.
        </div>
      <?php else: ?>
        <div id="notificationList" class="row g-4 reveal">
          <?php foreach ($notifications as $notification): ?>
            <div class="col-md-6 col-lg-4">
              <article class="notice-card h-100">
                <i class="fa-solid fa-bell"></i>
                <h5><?= escapeHtml((string) $notification['title']) ?></h5>
                <small><?= escapeHtml(date('F j, Y', strtotime((string) ($notification['publish_date'] ?: $notification['created_at'])))) ?></small>
                <p><?= nl2br(escapeHtml((string) $notification['description'])) ?></p>
              </article>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <button id="scrollTopBtn" class="scroll-top-btn" aria-label="Scroll to top"><i class="fa-solid fa-arrow-up"></i></button>
  <div id="site-footer"></div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/components.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
