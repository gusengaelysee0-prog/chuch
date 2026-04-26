<?php
declare(strict_types=1);
require_once __DIR__ . '/backend/config/bootstrap.php';

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/chuch';
$pageTitle = 'ADEPR Nyanza | Home';
$pageDescription = 'ADEPR Nyanza Busasamana church in Rwanda. Worship, community service, updates, gallery and videos for the local church family.';
$pageUrl = $baseUrl . '/';

$pdo = getDbConnection();
$imageStmt = $pdo->prepare('SELECT id, title, description, image_path FROM images WHERE category = :category ORDER BY created_at DESC LIMIT 4');
$imageStmt->execute(['category' => 'Featured']);
$featuredImages = $imageStmt->fetchAll();

$videoStmt = $pdo->prepare('SELECT id, title, description, youtube_url FROM videos WHERE category = :category ORDER BY created_at DESC LIMIT 3');
$videoStmt->execute(['category' => 'Featured']);
$featuredVideos = $videoStmt->fetchAll();

function escapeHtml(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function getEmbedUrl(string $url): string
{
    if (preg_match('/(?:youtube\.com\/watch\?(?:.*&)?v=|youtu\.be\/)([A-Za-z0-9_-]{11})/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }
    return $url;
}
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
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "ReligiousOrganization",
    "name": "ADEPR Nyanza",
    "url": "<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>",
    "logo": "<?= htmlspecialchars($baseUrl . '/image/logo.png', ENT_QUOTES, 'UTF-8') ?>",
    "contactPoint": [{
      "@type": "ContactPoint",
      "telephone": "+250700000000",
      "contactType": "customer service",
      "availableLanguage": ["English"]
    }],
    "address": {
      "@type": "PostalAddress",
      "addressCountry": "RW",
      "addressLocality": "Nyanza",
      "streetAddress": "Busasamana"
    }
  }
  </script>
</head>
<body>
  <canvas id="rainCanvas"></canvas>
  <div id="preloader" class="preloader"><div class="spinner-border text-light" role="status"></div></div>
  <div id="site-header"></div>

  <header id="home" class="hero-section section-gap parallax-bg">
    <div class="hero-bg-layer hero-bg-layer--one active"></div>
    <div class="hero-bg-layer hero-bg-layer--two"></div>
    <div class="hero-overlay"></div>
    <div class="container hero-content fade-up text-center">
      <h1>Welcome to ADEPR Nyanza</h1>
      <p class="lead mb-4">Growing in faith, worship, and community service in Nyanza.</p>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="#intro" class="btn btn-church">Explore Our Church</a>
        <a href="contact.php" class="btn btn-church-outline">Join Us This Sunday</a>
      </div>
    </div>
    <button id="heroPrevBtn" class="hero-nav hero-nav--prev" aria-label="Previous slide"><i class="fa-solid fa-chevron-left"></i></button>
    <button id="heroNextBtn" class="hero-nav hero-nav--next" aria-label="Next slide"><i class="fa-solid fa-chevron-right"></i></button>
  </header>

  <section id="intro" class="section-gap section-light reveal">
    <div class="container">
      <h2 class="section-title text-center mb-4">Who We Are</h2>
      <div class="row g-4 align-items-center">
        <div class="col-lg-7">
          <p>ADEPR Nyanza Busasamana is a welcoming church family committed to biblical teaching, prayer, and praise. We gather to grow together and support one another through every season.</p>
          <p>Our mission is to raise disciples, empower youth, strengthen families, and shine the love of Christ in the Nyanza community.</p>
        </div>
        <div class="col-lg-5">
          <div class="icon-features">
            <div><i class="fa-solid fa-cross"></i><span>Faith</span></div>
            <div><i class="fa-solid fa-people-group"></i><span>Community</span></div>
            <div><i class="fa-solid fa-hands-praying"></i><span>Prayer</span></div>
            <div><i class="fa-solid fa-heart"></i><span>Service</span></div>
          </div>
        </div>
      </div>
    </div>
    <div class="section-separator"></div>
  </section>

  <section class="section-gap reveal">
    <div class="container">
      <h2 class="section-title text-center mb-4">Our Worship Journey</h2>
      <div class="row g-4">
        <div class="col-md-6"><div class="activity-card h-100"><h5>Sunday Worship</h5><p>Powerful worship services every Sunday with prayer, teaching, and praise.</p></div></div>
        <div class="col-md-6"><div class="activity-card h-100"><h5>Weekly Fellowships</h5><p>Home cell meetings and prayer gatherings that keep believers connected.</p></div></div>
      </div>
    </div>
    <div class="section-separator"></div>
  </section>

  <section id="activities" class="section-gap section-light reveal parallax-bg">
    <div class="container">
      <h2 class="section-title text-center">Church Activities</h2>
      <div class="row g-4 mt-1">
        <div class="col-md-6 col-lg-3"><div class="activity-card"><i class="fa-solid fa-microphone-lines"></i><h5>Choir Ministry</h5><p>Uplifting worship through music and praise.</p></div></div>
        <div class="col-md-6 col-lg-3"><div class="activity-card"><i class="fa-solid fa-book-bible"></i><h5>Bible Study</h5><p>Weekly teachings for spiritual maturity.</p></div></div>
        <div class="col-md-6 col-lg-3"><div class="activity-card"><i class="fa-solid fa-child-reaching"></i><h5>Youth Fellowship</h5><p>Empowering youth with Christ-centered values.</p></div></div>
        <div class="col-md-6 col-lg-3"><div class="activity-card"><i class="fa-solid fa-handshake-angle"></i><h5>Community Outreach</h5><p>Serving families and supporting local needs.</p></div></div>
      </div>
    </div>
    <div class="section-separator"></div>
  </section>

  <section id="gallery-preview" class="section-gap reveal">
    <div class="container">
      <h2 class="section-title text-center">Featured Images</h2>
      <div class="row g-3 mt-1">
        <?php if (empty($featuredImages)): ?>
          <div class="col-6 col-md-3"><img src="image/optimized/choir1.jpg" class="preview-img lazy-fade" loading="lazy" decoding="async" alt="Choir 1"></div>
          <div class="col-6 col-md-3"><img src="image/optimized/choir2.jpg" class="preview-img lazy-fade" loading="lazy" decoding="async" alt="Choir 2"></div>
          <div class="col-6 col-md-3"><img src="image/optimized/choir3.jpg" class="preview-img lazy-fade" loading="lazy" decoding="async" alt="Choir 3"></div>
          <div class="col-6 col-md-3"><img src="image/optimized/choir4.jpg" class="preview-img lazy-fade" loading="lazy" decoding="async" alt="Choir 4"></div>
        <?php else: ?>
          <?php foreach ($featuredImages as $image): ?>
            <div class="col-6 col-md-3"><img src="<?= escapeHtml((string) $image['image_path']) ?>" class="preview-img lazy-fade" loading="lazy" decoding="async" alt="<?= escapeHtml((string) $image['title']) ?>"></div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="text-center mt-4"><a href="gallery.php" class="btn btn-church">View Full Gallery</a></div>
    </div>
    <div class="section-separator"></div>
  </section>

  <section id="videos-preview" class="section-gap section-light reveal">
    <div class="container">
      <h2 class="section-title text-center">Featured Videos</h2>
      <div class="row g-4 mt-1">
        <?php if (empty($featuredVideos)): ?>
          <div class="col-md-6 col-lg-4"><div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/5qap5aO4i9A" title="Video 1" allowfullscreen></iframe></div><p class="video-title">Sunday Worship Highlights</p></div>
          <div class="col-md-6 col-lg-4"><div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/jfKfPfyJRdk" title="Video 2" allowfullscreen></iframe></div><p class="video-title">Choir Praise Session</p></div>
          <div class="col-md-6 col-lg-4"><div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/DWcJFNfaw9c" title="Video 3" allowfullscreen></iframe></div><p class="video-title">Youth Ministry Event</p></div>
        <?php else: ?>
          <?php foreach ($featuredVideos as $video): ?>
            <div class="col-md-6 col-lg-4"><div class="ratio ratio-16x9"><iframe src="<?= escapeHtml(getEmbedUrl((string) $video['youtube_url'])) ?>" title="<?= escapeHtml((string) $video['title']) ?>" allowfullscreen></iframe></div><p class="video-title"><?= escapeHtml((string) $video['title']) ?></p></div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="section-separator"></div>
  </section>

  <section id="announcements" class="section-gap reveal">
    <div class="container">
      <h2 class="section-title text-center">Latest Notifications</h2>
      <div class="row g-4 mt-1">
        <div class="col-md-4"><div class="notice-card"><i class="fa-solid fa-calendar-days"></i><h6>Weekly Prayer Night</h6><small>April 24, 2026</small><p>Join us every Friday at 6 PM for prayer and worship.</p></div></div>
        <div class="col-md-4"><div class="notice-card"><i class="fa-solid fa-bullhorn"></i><h6>Youth Retreat</h6><small>May 03, 2026</small><p>Registration open for all youth members this Sunday.</p></div></div>
        <div class="col-md-4"><div class="notice-card"><i class="fa-solid fa-church"></i><h6>Special Service</h6><small>May 10, 2026</small><p>Family thanksgiving service led by church leaders.</p></div></div>
      </div>
    </div>
    <div class="section-separator"></div>
  </section>

  <section class="section-gap section-light reveal">
    <div class="container text-center">
      <h2 class="section-title">Community Impact</h2>
      <p class="mx-auto scripture-text mb-4">We serve families through outreach, counseling, discipleship, and practical support across Nyanza.</p>
      <a href="updates.php" class="btn btn-church">Read Church Updates</a>
    </div>
    <div class="section-separator"></div>
  </section>

  <section id="scripture" class="section-gap reveal">
    <div class="container text-center">
      <h2 class="section-title">Word of Encouragement</h2>
      <p class="mx-auto scripture-text">"For where two or three gather in my name, there am I with them." - Matthew 18:20</p>
      <a href="about.php" class="btn btn-church-outline">Learn More About Us</a>
    </div>
  </section>

  <button id="scrollTopBtn" class="scroll-top-btn" aria-label="Scroll to top"><i class="fa-solid fa-arrow-up"></i></button>
  <div id="site-footer"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/components.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
