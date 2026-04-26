(function () {
  const menu = [
    { href: 'index.php#home', icon: 'fa-house', label: 'Home' },
    { href: 'gallery.php', icon: 'fa-images', label: 'Gallery' },
    { href: 'videos.php', icon: 'fa-video', label: 'Videos' },
    { href: 'notifications.php', icon: 'fa-bell', label: 'Notifications' },
    { href: 'updates.php', icon: 'fa-newspaper', label: 'Updates' },
    { href: 'about.php', icon: 'fa-circle-info', label: 'About' },
    { href: 'contact.php', icon: 'fa-envelope', label: 'Contact' }
  ];

  const path = window.location.pathname.split('/').pop() || 'index.php';

  const navItems = menu.map(item => {
    const itemPath = item.href.split('#')[0];
    const isHomeHash = path === 'index.php' && item.href.startsWith('index.php#');
    const active = itemPath === path || isHomeHash ? 'active' : '';
    return `<li class="nav-item"><a class="nav-link ${active}" href="${item.href}"><i class="fa-solid ${item.icon} me-1"></i>${item.label}</a></li>`;
  }).join('');

  const header = `
    <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark fixed-top church-navbar">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php#home">
          <span>ADEPR Nyanza</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-label="Toggle menu">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
          <ul class="navbar-nav mx-auto mb-2 mb-lg-0">${navItems}</ul>
          <a href="login.php" class="btn btn-signin"><i class="fa-solid fa-right-to-bracket me-2"></i>Sign In</a>
        </div>
      </div>
    </nav>
  `;

  const footer = `
    <footer class="church-footer">
      <div class="container">
        <div class="row g-3 align-items-center text-center text-md-start">
          <div class="col-md-5">
            <h5 class="mb-1">ADEPR Nyanza Busasamana</h5>
            <p class="mb-0">Nyanza District, Rwanda</p>
          </div>
          <div class="col-md-4">
            <p class="mb-1"><i class="fa-solid fa-phone me-2"></i>+250 700 000 000</p>
            <p class="mb-0"><i class="fa-solid fa-envelope me-2"></i>info@adeprnyanza.org</p>
          </div>
          <div class="col-md-3 text-md-end">
            <div class="social-links mb-2">
              <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
              <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
              <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
            </div>
            <small>&copy; 2026 ADEPR Nyanza</small>
          </div>
        </div>
      </div>
    </footer>
  `;

  document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('site-header')?.insertAdjacentHTML('beforeend', header);
    document.getElementById('site-footer')?.insertAdjacentHTML('beforeend', footer);
  });
})();
