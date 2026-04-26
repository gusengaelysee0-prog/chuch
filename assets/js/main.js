const initMain = () => {
  const createPreloader = () => {
    const preloaderEl = document.createElement('div');
    preloaderEl.id = 'preloader';
    preloaderEl.className = 'preloader';
    preloaderEl.setAttribute('aria-hidden', 'true');
    preloaderEl.innerHTML = `
      <div class="spinner-border text-light" role="status" aria-hidden="true"></div>
      <span class="visually-hidden">Loading...</span>
    `;
    document.body.prepend(preloaderEl);
    return preloaderEl;
  };

  const preloader = document.getElementById('preloader') || createPreloader();
  let preloaderHidden = false;

  const hidePreloader = () => {
    if (preloaderHidden) return;
    preloaderHidden = true;
    preloader.classList.add('hide');
    window.setTimeout(() => preloader.remove(), 500);
  };

  const revealPage = () => {
    document.body.classList.add('page-ready');
    hidePreloader();
  };

  const maxLoaderTimeout = window.setTimeout(revealPage, 2000);
  if (document.readyState === 'interactive' || document.readyState === 'complete') {
    window.clearTimeout(maxLoaderTimeout);
    revealPage();
  } else {
    window.addEventListener('DOMContentLoaded', () => {
      window.clearTimeout(maxLoaderTimeout);
      revealPage();
    }, { once: true });
  }

  setTimeout(() => document.body.classList.add('page-ready'), 450);

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) entry.target.classList.add('show');
    });
  }, { threshold: 0.14 });
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      if (!href || href === '#') return;
      const target = document.querySelector(href);
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth' });
    });
  });

  const scrollTopBtn = document.getElementById('scrollTopBtn');
  const toggleScrollBtn = () => {
    if (!scrollTopBtn) return;
    scrollTopBtn.classList.toggle('show', window.scrollY > 450);
  };
  window.addEventListener('scroll', toggleScrollBtn, { passive: true });
  toggleScrollBtn();
  scrollTopBtn?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

  const heroSlider = (() => {
    const hero = document.getElementById('home');
    if (!hero) return null;

    const heroPrev = document.getElementById('heroPrevBtn');
    const heroNext = document.getElementById('heroNextBtn');
    const layerA = hero.querySelector('.hero-bg-layer--one');
    const layerB = hero.querySelector('.hero-bg-layer--two');
    const imageSources = ['images/1.jpg', 'images/2.jpg', 'images/3.jpg'];
    let validImages = [];
    let currentIndex = 0;
    let activeLayer = layerA;
    let nextLayer = layerB;
    let intervalId = null;
    let isPaused = false;

    const preloadImages = () => {
      const promises = imageSources.map((src) => new Promise((resolve) => {
        const img = new Image();
        img.onload = () => resolve({ src, success: true });
        img.onerror = () => resolve({ src, success: false });
        img.src = src;
      }));
      return Promise.allSettled(promises).then((results) => results
        .map((item) => item.status === 'fulfilled' && item.value.success ? item.value.src : null)
        .filter(Boolean));
    };

    const setImage = (layer, src) => {
      if (!layer || !src) return;
      layer.style.backgroundImage = `url('${src}')`;
    };

    const showSlide = (index) => {
      if (!validImages.length || !activeLayer || !nextLayer) return;
      const nextImage = validImages[index];
      if (!nextImage) return;
      setImage(nextLayer, nextImage);
      nextLayer.classList.add('active');
      activeLayer.classList.remove('active');
      [activeLayer, nextLayer] = [nextLayer, activeLayer];
    };

    const nextSlide = () => {
      if (!validImages.length) return;
      currentIndex = (currentIndex + 1) % validImages.length;
      showSlide(currentIndex);
    };

    const prevSlide = () => {
      if (!validImages.length) return;
      currentIndex = (currentIndex - 1 + validImages.length) % validImages.length;
      showSlide(currentIndex);
    };

    const resetInterval = () => {
      if (intervalId) clearInterval(intervalId);
      intervalId = window.setInterval(() => {
        if (!isPaused) nextSlide();
      }, 20000);
    };

    const init = async () => {
      validImages = await preloadImages();
      if (!validImages.length) {
        console.warn('Hero slider: no local images found in /images, using fallback image. Add images/1.jpg, images/2.jpg, images/3.jpg.');
        validImages = ['image/choir1.png'];
      }
      setImage(activeLayer, validImages[0]);
      if (validImages.length < 2) {
        heroPrev?.classList.add('hero-nav--hidden');
        heroNext?.classList.add('hero-nav--hidden');
        return;
      }
      if (hero) {
        hero.addEventListener('mouseenter', () => { isPaused = true; });
        hero.addEventListener('mouseleave', () => { isPaused = false; });
      }
      heroPrev?.addEventListener('click', () => { prevSlide(); resetInterval(); });
      heroNext?.addEventListener('click', () => { nextSlide(); resetInterval(); });
      resetInterval();
    };

    init();
    return {
      next: nextSlide,
      prev: prevSlide,
    };
  })();

  const floatingIcons = ['fa-book-bible', 'fa-microphone-lines', 'fa-cross'];
  for (let i = 0; i < 9; i += 1) {
    const icon = document.createElement('i');
    icon.className = `decor-icon fa-solid ${floatingIcons[i % floatingIcons.length]}`;
    icon.style.left = `${Math.random() * 95}%`;
    icon.style.top = `${10 + Math.random() * 80}%`;
    icon.style.animationDuration = `${16 + Math.random() * 18}s`;
    icon.style.animationDelay = `${Math.random() * 4}s`;
    document.body.appendChild(icon);
  }

  const canvas = document.getElementById('rainCanvas');
  if (canvas) {
    const ctx = canvas.getContext('2d');
    const drops = [];
    const makeDrops = (count) => {
      drops.length = 0;
      for (let i = 0; i < count; i += 1) {
        drops.push({
          x: Math.random() * canvas.width,
          y: Math.random() * canvas.height,
          l: 10 + Math.random() * 16,
          v: 1.4 + Math.random() * 2.4,
          drift: 0.2 + Math.random() * 0.8
        });
      }
    };

    const resize = () => {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
      makeDrops(Math.max(40, Math.floor(window.innerWidth / 30)));
    };

    const drawRain = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.strokeStyle = 'rgba(0, 53, 102, 0.22)';
      ctx.lineWidth = 1.1;
      drops.forEach((d) => {
        ctx.beginPath();
        ctx.moveTo(d.x, d.y);
        ctx.lineTo(d.x + d.drift, d.y + d.l);
        ctx.stroke();
        d.y += d.v;
        d.x += d.drift * 0.14;
        if (d.y > canvas.height) {
          d.y = -10;
          d.x = Math.random() * canvas.width;
        }
      });
      requestAnimationFrame(drawRain);
    };

    resize();
    drawRain();
    window.addEventListener('resize', resize);
  }

  window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    document.querySelectorAll('.parallax-bg').forEach((el) => {
      el.style.backgroundPositionY = `${scrolled * 0.35}px`;
    });
  }, { passive: true });

  const videoData = {
    choirVideos: [
      { id: 'TgRNbkHKnBk', title: 'Choir Praise Session', desc: 'A joyful choir worship moment.' },
      { id: '8VI24ZMHvoY', title: 'Sunday Choir Worship', desc: 'Live praise and thanksgiving songs.' }
    ],
    priestVideos: [
      { id: 'PDwtvWHrbUo', title: 'Priest Sermon', desc: 'Weekly word and spiritual guidance.' },
      { id: 'KDO2hbmZHKA', title: 'Leadership Message', desc: 'Encouragement for families and youth.' }
    ],
    eventVideos: [
      { id: 'L_jWHffIx5E', title: 'Youth Event Highlights', desc: 'Special event moments and testimonies.' },
      { id: 'kXYiU_JCYtU', title: 'Church Celebration', desc: 'Praise moments during celebration service.' }
    ]
  };

  Object.entries(videoData).forEach(([containerId, videos]) => {
    const container = document.getElementById(containerId);
    if (!container) return;
    container.innerHTML = videos.map(v => `
      <div class="col-md-6 col-lg-4 reveal">
        <div class="activity-card h-100">
          <div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/${v.id}" title="${v.title}" allowfullscreen></iframe></div>
          <h6 class="mt-3 mb-1">${v.title}</h6>
          <p class="mb-0 text-muted">${v.desc}</p>
        </div>
      </div>
    `).join('');
    container.querySelectorAll('.reveal').forEach(el => observer.observe(el));
  });

  const galleryItems = [...document.querySelectorAll('.gallery-item')];
  const modalEl = document.getElementById('galleryModal');
  if (modalEl && galleryItems.length && window.bootstrap) {
    const modal = new bootstrap.Modal(modalEl);
    const modalImage = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalCaption');
    const modalDescription = document.getElementById('modalDescription');
    const downloadImage = document.getElementById('downloadImage');
    const shareImage = document.getElementById('shareImage');
    const shareMessage = document.getElementById('shareMessage');
    let currentIndex = 0;

    const showImage = (index) => {
      const item = galleryItems[index];
      const img = item.querySelector('img');
      const fullImage = item.dataset.full || img.src;
      modalImage.style.opacity = '0.3';
      setTimeout(() => {
        modalImage.src = fullImage;
        modalCaption.textContent = item.dataset.title || 'Church photo';
        modalDescription.textContent = item.dataset.desc || 'Memories from ADEPR Nyanza.';
        if (downloadImage) {
          downloadImage.href = fullImage;
          downloadImage.setAttribute('download', `${(item.dataset.title || 'adepr-image').replace(/\s+/g, '-').toLowerCase()}.png`);
        }
        modalImage.style.opacity = '1';
      }, 120);
      currentIndex = index;
      if (shareMessage) shareMessage.textContent = '';
    };

    galleryItems.forEach((item, index) => {
      item.addEventListener('click', () => {
        showImage(index);
        modal.show();
      });
    });

    document.getElementById('nextImage')?.addEventListener('click', () => showImage((currentIndex + 1) % galleryItems.length));
    document.getElementById('prevImage')?.addEventListener('click', () => showImage((currentIndex - 1 + galleryItems.length) % galleryItems.length));

    shareImage?.addEventListener('click', async () => {
      const text = `${modalCaption.textContent} - ${window.location.href}`;
      try {
        await navigator.clipboard.writeText(text);
        shareMessage.textContent = 'Share link copied to clipboard.';
      } catch (err) {
        shareMessage.textContent = 'Unable to copy automatically. Please copy from address bar.';
      }
    });
  }

  const form = document.getElementById('contactForm');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const message = document.getElementById('message').value.trim();
      const msg = document.getElementById('formMessage');
      const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!name || !email || !message || !validEmail.test(email)) {
        msg.textContent = 'Please provide a valid name, email, and message.';
        msg.className = 'mt-3 mb-0 text-danger';
        return;
      }

      try {
        const response = await fetch('backend/api/public/contact-submit.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, email, message }),
        });
        const result = await response.json();
        if (!result.success) {
          msg.textContent = result.message || 'Could not send your message.';
          msg.className = 'mt-3 mb-0 text-danger';
          return;
        }
        msg.textContent = 'Message sent successfully. We will contact you soon.';
        msg.className = 'mt-3 mb-0 text-success';
        form.reset();
      } catch (error) {
        msg.textContent = 'Server error. Please try again later.';
        msg.className = 'mt-3 mb-0 text-danger';
      }
    });
  }

  const siteLoginForm = document.getElementById('siteLoginForm');
  if (siteLoginForm) {
    siteLoginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const username = document.getElementById('siteUsername');
      const password = document.getElementById('sitePassword');
      const msg = document.getElementById('siteLoginMessage');
      const u = username.value.trim();
      const p = password.value.trim();
      [username, password].forEach((input) => input.classList.toggle('is-invalid', !input.value.trim()));

      if (!u || !p) {
        msg.textContent = 'Please fill username and password.';
        msg.className = 'small mt-3 mb-0 text-danger text-center';
        return;
      }

      msg.textContent = 'Signing in...';
      msg.className = 'small mt-3 mb-0 text-muted text-center';

      try {
        const response = await fetch('backend/api/admin/auth-login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username: u, password: p }),
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
          msg.textContent = result.message || 'Invalid username or password.';
          msg.className = 'small mt-3 mb-0 text-danger text-center';
          return;
        }

        msg.textContent = 'Login successful. Redirecting to dashboard...';
        msg.className = 'small mt-3 mb-0 text-success text-center';
        setTimeout(() => {
          document.body.classList.remove('page-ready');
          window.location.href = 'admin/dashboard.php';
        }, 420);
      } catch (error) {
        msg.textContent = 'Unable to reach the server. Please try again.';
        msg.className = 'small mt-3 mb-0 text-danger text-center';
      }
    });
  }

  document.querySelectorAll('img.lazy-fade').forEach((img) => {
    if (!img.hasAttribute('loading')) img.setAttribute('loading', 'lazy');
    if (!img.hasAttribute('decoding')) img.setAttribute('decoding', 'async');
    const markLoaded = () => img.classList.add('loaded');
    if (img.complete) markLoaded();
    else img.addEventListener('load', markLoaded, { once: true });
  });

  const navLinks = [...document.querySelectorAll('a[href]')];
  navLinks.forEach((link) => {
    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('http') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
    link.addEventListener('click', () => document.body.classList.remove('page-ready'));
  });

  const noticeModalEl = document.getElementById('noticeModal');
  const noticeList = document.getElementById('notificationList');
  if (noticeModalEl && noticeList && window.bootstrap) {
    const noticeModal = new bootstrap.Modal(noticeModalEl);
    const addNoticeBtn = document.getElementById('addNoticeBtn');
    const saveNoticeBtn = document.getElementById('saveNoticeBtn');
    const modalTitle = document.getElementById('noticeModalTitle');
    const titleInput = document.getElementById('noticeTitle');
    const dateInput = document.getElementById('noticeDate');
    const descInput = document.getElementById('noticeDescription');
    const formMsg = document.getElementById('noticeFormMessage');
    let editingCard = null;

    const openAddMode = () => {
      editingCard = null;
      modalTitle.textContent = 'Add Notification';
      formMsg.textContent = '';
      titleInput.value = '';
      dateInput.value = '';
      descInput.value = '';
      noticeModal.show();
    };

    const openEditMode = (card) => {
      editingCard = card;
      modalTitle.textContent = 'Edit Notification';
      formMsg.textContent = '';
      titleInput.value = card.dataset.title || '';
      dateInput.value = card.dataset.date || '';
      descInput.value = card.dataset.description || '';
      noticeModal.show();
    };

    addNoticeBtn?.addEventListener('click', openAddMode);
    const bindNoticeActions = (scope = noticeList) => {
      scope.querySelectorAll('.notice-edit-btn').forEach((btn) => {
        btn.addEventListener('click', () => openEditMode(btn.closest('.notice-card')));
      });
      scope.querySelectorAll('.notice-delete-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
          const wrapper = btn.closest('.col-md-6, .col-lg-4, .col');
          wrapper?.remove();
        });
      });
    };
    bindNoticeActions();

    saveNoticeBtn?.addEventListener('click', () => {
      const title = titleInput.value.trim();
      const date = dateInput.value.trim();
      const description = descInput.value.trim();
      if (!title || !date || !description) {
        formMsg.textContent = 'Please fill all fields.';
        formMsg.className = 'small text-danger mb-0';
        return;
      }

      if (editingCard) {
        editingCard.dataset.title = title;
        editingCard.dataset.date = date;
        editingCard.dataset.description = description;
        editingCard.querySelector('h5').textContent = title;
        editingCard.querySelector('small').textContent = date;
        editingCard.querySelector('p').textContent = description;
      } else {
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-4';
        col.innerHTML = `
          <div class="notice-card" data-title="${title}" data-description="${description}" data-date="${date}">
            <i class="fa-solid fa-bell"></i><h5>${title}</h5><small>${date}</small>
            <p>${description}</p>
            <div class="notice-actions">
              <button class="btn btn-sm btn-primary notice-edit-btn"><i class="fa-solid fa-pen me-1"></i>Edit</button>
              <button class="btn btn-sm btn-danger notice-delete-btn"><i class="fa-solid fa-trash me-1"></i>Delete</button>
            </div>
          </div>
        `;
        noticeList.prepend(col);
        bindNoticeActions(col);
      }

      noticeModal.hide();
    });
  }
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initMain, { once: true });
} else {
  initMain();
}
