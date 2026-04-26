<?php
declare(strict_types=1);
require_once __DIR__ . '/backend/config/bootstrap.php';

$pdo = getDbConnection();
$stmt = $pdo->query('SELECT id, title, content, image_path, created_at FROM updates ORDER BY created_at DESC');
$updates = $stmt->fetchAll();

function escapeHtml(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/chuch';
$pageTitle = 'ADEPR Nyanza | Updates';
$pageDescription = 'Church updates from ADEPR Nyanza including community outreach, worship events, and ministry news.';
$pageUrl = $baseUrl . '/updates.php';
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
      <h1 class="section-title text-center mb-4">Church Updates</h1>
      <div class="row g-4 reveal">
        <?php if (empty($updates)): ?>
          <div class="col-12">
            <div class="alert alert-info rounded-4 shadow-sm">
              <strong>No updates yet.</strong> Check back later for the latest news.
            </div>
          </div>
        <?php else: ?>
          <?php foreach ($updates as $update): ?>
            <div class="col-lg-6">
              <article class="update-post h-100 rounded-4 shadow-sm overflow-hidden">
                <?php if (!empty($update['image_path'])): ?>
                  <img class="lazy-fade w-100" loading="lazy" decoding="async" src="<?= escapeHtml($update['image_path']) ?>" alt="<?= escapeHtml($update['title']) ?>">
                <?php endif; ?>
                <div class="p-4">
                  <h4><?= escapeHtml((string) $update['title']) ?></h4>
                  <p class="text-muted small mb-2"><?= escapeHtml(date('F j, Y', strtotime((string) $update['created_at']))) ?></p>
                  <p><?= nl2br(escapeHtml((string) $update['content'])) ?></p>
                </div>
              </article>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
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
