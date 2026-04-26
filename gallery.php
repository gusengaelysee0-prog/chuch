<?php
require_once __DIR__ . '/backend/config/bootstrap.php';
$pdo = getDbConnection();
$stmt = $pdo->query('SELECT id, title, description, category, image_path, created_at FROM images ORDER BY created_at DESC');
$images = $stmt->fetchAll();
$categories = ['Choir', 'Leaders', 'Events', 'Others'];
$groupedImages = ['All' => $images];
foreach ($categories as $cat) {
    $groupedImages[$cat] = array_values(array_filter($images, fn($image) => $image['category'] === $cat));
}
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/chuch';
$pageTitle = 'ADEPR Nyanza | Gallery';
$pageDescription = 'ADEPR Nyanza church gallery featuring choir, leaders, ministry outreach, and worship moments in Nyanza.';
$pageUrl = $baseUrl . '/gallery.php';
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
  <div id="preloader" class="preloader"><div class="spinner-border text-light"></div></div>
  <div id="site-header"></div>

  <main class="section-gap page-offset">
    <div class="container">
      <h1 class="section-title text-center mb-4">Church Gallery</h1>
      <ul class="nav nav-pills justify-content-center mb-4 gallery-tabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#all" type="button">All</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#choir" type="button">Choir</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#leaders" type="button">Leaders</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#events" type="button">Events</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#others" type="button">Others</button></li>
      </ul>

      <div class="tab-content fade-tab reveal">
        <?php foreach (['All', 'Choir', 'Leaders', 'Events', 'Others'] as $index => $tab): ?>
          <div class="tab-pane fade<?= $index === 0 ? ' show active' : '' ?>" id="<?= strtolower($tab === 'All' ? 'all' : $tab) ?>">
            <div class="row g-3 gallery-grid">
              <?php if (empty($groupedImages[$tab])): ?>
                <div class="col-12"><p class="text-center text-muted">No images available yet.</p></div>
              <?php else: ?>
                <?php foreach ($groupedImages[$tab] as $image): ?>
                  <div class="col-6 col-md-4">
                    <div class="gallery-item" data-title="<?= htmlspecialchars($image['title'], ENT_QUOTES, 'UTF-8') ?>" data-desc="<?= htmlspecialchars($image['description'], ENT_QUOTES, 'UTF-8') ?>" data-full="<?= htmlspecialchars($image['image_path'], ENT_QUOTES, 'UTF-8') ?>">
                      <div class="gallery-thumb">
                        <img class="lazy-fade" loading="lazy" decoding="async" src="<?= htmlspecialchars($image['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($image['title'], ENT_QUOTES, 'UTF-8') ?>">
                        <div class="gallery-caption">
                          <span class="gallery-category"><?= htmlspecialchars($image['category'], ENT_QUOTES, 'UTF-8') ?></span>
                          <p class="gallery-desc"><?= htmlspecialchars($image['description'], ENT_QUOTES, 'UTF-8') ?></p>
                          <h5 class="gallery-title"><?= htmlspecialchars($image['title'], ENT_QUOTES, 'UTF-8') ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

  <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content gallery-modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title">Image Viewer</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body d-flex flex-column align-items-center justify-content-center">
          <button id="prevImage" class="gallery-arrow left" aria-label="Previous image"><i class="fa-solid fa-chevron-left"></i></button>
          <img id="modalImage" src="" alt="Preview" class="img-fluid modal-main-image">
          <button id="nextImage" class="gallery-arrow right" aria-label="Next image"><i class="fa-solid fa-chevron-right"></i></button>
          <div class="gallery-meta text-center mt-3">
            <h5 id="modalCaption" class="mb-1"></h5>
            <p id="modalDescription" class="mb-3 text-light-emphasis"></p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
              <a id="downloadImage" class="btn btn-church" download><i class="fa-solid fa-download me-2"></i>Download</a>
              <button id="shareImage" class="btn btn-church-outline"><i class="fa-solid fa-share-nodes me-2"></i>Share</button>
            </div>
            <p id="shareMessage" class="small mt-2 mb-0"></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <button id="scrollTopBtn" class="scroll-top-btn" aria-label="Scroll to top"><i class="fa-solid fa-arrow-up"></i></button>
  <div id="site-footer"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/components.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
