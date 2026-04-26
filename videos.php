<?php
require_once __DIR__ . '/backend/config/bootstrap.php';
$pdo = getDbConnection();
$stmt = $pdo->query('SELECT id, title, description, category, youtube_url, created_at FROM videos ORDER BY created_at DESC');
$videos = $stmt->fetchAll();
$groupedVideos = ['Choir' => [], 'Priest' => [], 'Events' => []];
foreach ($videos as $video) {
    if (isset($groupedVideos[$video['category']])) {
        $groupedVideos[$video['category']][] = $video;
    }
}
function getEmbedUrl(string $url): string {
    if (preg_match('/(?:v=|youtu\.be\/|embed\/)([\w\-]{11})/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }
    return 'https://www.youtube.com/embed/jfKfPfyJRdk';
}
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/chuch';
$pageTitle = 'ADEPR Nyanza | Videos';
$pageDescription = 'Watch videos from ADEPR Nyanza including worship highlights, choir ministry, and church events in Nyanza.';
$pageUrl = $baseUrl . '/videos.php';
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
      <h1 class="section-title text-center mb-4">Church Videos</h1>
      <ul class="nav nav-tabs justify-content-center mb-4 video-tabs">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#choir-videos">Choir Videos</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#priest-videos">Priest Videos</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#event-videos">Events</button></li>
      </ul>
      <div class="tab-content reveal">
        <div class="tab-pane fade show active" id="choir-videos">
          <div class="row g-4 video-grid">
            <?php if (empty($groupedVideos['Choir'])): ?>
              <div class="col-12"><p class="text-center text-muted">No choir videos have been uploaded yet.</p></div>
            <?php else: ?>
              <?php foreach ($groupedVideos['Choir'] as $video): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="activity-card h-100">
                    <div class="ratio ratio-16x9"><iframe src="<?= htmlspecialchars(getEmbedUrl($video['youtube_url']), ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?>" allowfullscreen></iframe></div>
                    <h6 class="mt-3 mb-1"><?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?></h6>
                    <p class="mb-0 text-muted"><?= htmlspecialchars($video['description'], ENT_QUOTES, 'UTF-8') ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="tab-pane fade" id="priest-videos">
          <div class="row g-4 video-grid">
            <?php if (empty($groupedVideos['Priest'])): ?>
              <div class="col-12"><p class="text-center text-muted">No priest videos have been uploaded yet.</p></div>
            <?php else: ?>
              <?php foreach ($groupedVideos['Priest'] as $video): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="activity-card h-100">
                    <div class="ratio ratio-16x9"><iframe src="<?= htmlspecialchars(getEmbedUrl($video['youtube_url']), ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?>" allowfullscreen></iframe></div>
                    <h6 class="mt-3 mb-1"><?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?></h6>
                    <p class="mb-0 text-muted"><?= htmlspecialchars($video['description'], ENT_QUOTES, 'UTF-8') ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="tab-pane fade" id="event-videos">
          <div class="row g-4 video-grid">
            <?php if (empty($groupedVideos['Events'])): ?>
              <div class="col-12"><p class="text-center text-muted">No event videos have been uploaded yet.</p></div>
            <?php else: ?>
              <?php foreach ($groupedVideos['Events'] as $video): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="activity-card h-100">
                    <div class="ratio ratio-16x9"><iframe src="<?= htmlspecialchars(getEmbedUrl($video['youtube_url']), ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?>" allowfullscreen></iframe></div>
                    <h6 class="mt-3 mb-1"><?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?></h6>
                    <p class="mb-0 text-muted"><?= htmlspecialchars($video['description'], ENT_QUOTES, 'UTF-8') ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
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
