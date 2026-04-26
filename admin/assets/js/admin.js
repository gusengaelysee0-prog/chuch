(function () {
  const page = document.body.dataset.page || '';
  const navTarget = document.querySelector(`.side-menu .nav-link[data-page='${page}']`);
  if (navTarget) navTarget.classList.add('active');

  const toggleBtn = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  toggleBtn?.addEventListener('click', () => sidebar?.classList.toggle('show'));

  const toastStack = document.getElementById('toastStack');
  const showToast = (message, type = 'success') => {
    if (!toastStack) return;
    const toast = document.createElement('div');
    toast.className = `app-toast ${type === 'error' ? 'error' : ''}`;
    toast.textContent = message;
    toastStack.appendChild(toast);
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-4px)';
    }, 2600);
    setTimeout(() => toast.remove(), 3000);
  };

  const markInvalid = (el, invalid) => {
    if (!el) return;
    el.classList.toggle('input-invalid', invalid);
  };

  const apiRequest = async (url, options = {}) => {
    const response = await fetch(url, options);
    const data = await response.json().catch(() => ({ success: false, message: 'Invalid server response' }));
    if (!response.ok || !data.success) {
      throw new Error(data.message || 'Request failed');
    }
    return data;
  };

  const badgeClass = (category = '') => {
    const key = category.toLowerCase().replace(/\s+/g, '-');
    if (['choir', 'choir-videos'].includes(key)) return 'badge-choir';
    if (['leaders'].includes(key)) return 'badge-leaders';
    if (['events'].includes(key)) return 'badge-events';
    if (['priest', 'priest-videos'].includes(key)) return 'badge-priest-videos';
    return 'badge-others';
  };

  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const username = document.getElementById('username');
      const password = document.getElementById('password');
      const msg = document.getElementById('loginMessage');
      const u = username?.value.trim();
      const p = password?.value.trim();
      markInvalid(username, !u);
      markInvalid(password, !p);
      if (!u || !p) {
        msg.textContent = 'Please fill in username and password.';
        msg.className = 'small text-danger mt-2';
        return;
      }
      try {
        await apiRequest('../backend/api/admin/auth-login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username: u, password: p }),
        });
        msg.textContent = 'Login successful. Redirecting...';
        msg.className = 'small text-success mt-2';
        setTimeout(() => (window.location.href = 'dashboard.php'), 400);
      } catch (error) {
        msg.textContent = error.message || 'Login failed.';
        msg.className = 'small text-danger mt-2';
      }
    });
  }

  const imgInput = document.getElementById('imageFile');
  const imgPreview = document.getElementById('imagePreview');
  const progressBar = document.getElementById('fakeProgressBar');
  const dropzone = document.getElementById('dropzone');
  const renderPreview = (file) => {
    if (!file || !imgPreview) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
      imgPreview.src = ev.target.result;
      imgPreview.style.display = 'block';
    };
    reader.readAsDataURL(file);
    if (progressBar) {
      progressBar.style.width = '0%';
      setTimeout(() => (progressBar.style.width = '32%'), 120);
      setTimeout(() => (progressBar.style.width = '67%'), 360);
      setTimeout(() => (progressBar.style.width = '100%'), 650);
    }
  };
  imgInput?.addEventListener('change', () => renderPreview(imgInput.files[0]));

  if (dropzone && imgInput) {
    ['dragenter', 'dragover'].forEach(evt => {
      dropzone.addEventListener(evt, (e) => {
        e.preventDefault();
        dropzone.classList.add('drag');
      });
    });
    ['dragleave', 'drop'].forEach(evt => {
      dropzone.addEventListener(evt, (e) => {
        e.preventDefault();
        dropzone.classList.remove('drag');
      });
    });
    dropzone.addEventListener('drop', (e) => {
      const file = e.dataTransfer?.files?.[0];
      if (!file) return;
      const dt = new DataTransfer();
      dt.items.add(file);
      imgInput.files = dt.files;
      renderPreview(file);
    });
  }

  const imageGrid = document.getElementById('imageGrid');
  if (imageGrid) {
    const titleInput = document.getElementById('imageTitle');
    const categoryInput = document.getElementById('imageCategory');
    const descInput = document.getElementById('imageDescription');
    const saveBtn = document.getElementById('saveImageBtn');
    const formMsg = document.getElementById('imageFormMessage');
    const searchInput = document.getElementById('imageSearch');
    const sortInput = document.getElementById('imageSort');
    const tabs = document.getElementById('imageCategoryTabs');
    let activeFilter = 'All';

    const renderImages = async () => {
      try {
        const query = new URLSearchParams({
          search: searchInput?.value || '',
          category: activeFilter === 'All' ? '' : activeFilter,
          sort: sortInput?.value || 'newest',
        });
        const result = await apiRequest(`../backend/api/admin/images.php?${query.toString()}`);
        const items = result.data || [];

        imageGrid.innerHTML = items.map(item => `
          <div class="col-sm-6 col-lg-4">
            <div class="card card-soft p-2 h-100">
              <img src="../${item.image_path}" class="img-fluid rounded" alt="${item.title}">
              <div class="d-flex justify-content-between align-items-start mt-2 gap-2">
                <div>
                  <strong>${item.title}</strong><br>
                  <span class="category-badge ${badgeClass(item.category)}">${item.category}</span>
                </div>
                <div class="card-actions">
                  <button class="btn btn-sm btn-outline-danger image-delete" data-id="${item.id}">Delete</button>
                </div>
              </div>
              <small class="text-muted mt-2">${item.description}</small>
            </div>
          </div>
        `).join('');

        imageGrid.querySelectorAll('.image-delete').forEach(btn => {
          btn.addEventListener('click', async () => {
            if (!confirm('Delete this image?')) return;
            try {
              await apiRequest('../backend/api/admin/images.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: Number(btn.dataset.id) }),
              });
              await renderImages();
              showToast('Image deleted successfully.');
            } catch (error) {
              showToast(error.message, 'error');
            }
          });
        });
      } catch (error) {
        showToast(error.message || 'Failed to load images.', 'error');
      }
    };

    saveBtn?.addEventListener('click', async () => {
      const title = titleInput.value.trim();
      const category = categoryInput.value.trim();
      const description = descInput.value.trim();
      const file = imgInput?.files?.[0];
      [titleInput, categoryInput, descInput].forEach(i => markInvalid(i, !i.value.trim()));

      if (!title || !category || !description || !file) {
        formMsg.textContent = 'All fields including image are required.';
        formMsg.className = 'small text-danger mb-0 mt-2';
        showToast('Please complete image form.', 'error');
        return;
      }

      const formData = new FormData();
      formData.append('title', title);
      formData.append('category', category);
      formData.append('description', description);
      formData.append('image', file);

      try {
        await apiRequest('../backend/api/admin/images.php', { method: 'POST', body: formData });
        formMsg.textContent = 'Image uploaded successfully.';
        formMsg.className = 'small text-success mb-0 mt-2';
        showToast('Image uploaded.');
        await renderImages();
      } catch (error) {
        formMsg.textContent = error.message;
        formMsg.className = 'small text-danger mb-0 mt-2';
        showToast(error.message, 'error');
      }
    });

    searchInput?.addEventListener('input', renderImages);
    sortInput?.addEventListener('change', renderImages);
    tabs?.querySelectorAll('button').forEach(btn => {
      btn.addEventListener('click', () => {
        tabs.querySelectorAll('button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeFilter = btn.dataset.filter;
        renderImages();
      });
    });

    renderImages();
  }

  const videoCards = document.getElementById('videoCards');
  if (videoCards) {
    const titleInput = document.getElementById('videoTitle');
    const categoryInput = document.getElementById('videoCategory');
    const linkInput = document.getElementById('videoLink');
    const descInput = document.getElementById('videoDescription');
    const saveBtn = document.getElementById('saveVideoBtn');
    const formMsg = document.getElementById('videoFormMessage');
    const searchInput = document.getElementById('videoSearch');
    const sortInput = document.getElementById('videoSort');
    const categoryFilter = document.getElementById('videoCategoryFilter');
    const tabs = document.getElementById('videoTabs');
    let activeTab = 'All';

    const categories = ['All', 'Featured', 'Choir', 'Priest', 'Events'];
    tabs.innerHTML = categories.map((c, idx) => `<li class="nav-item"><button class="nav-link ${idx===0?'active':''}" data-category="${c}">${c}</button></li>`).join('');
    tabs.querySelectorAll('button').forEach(btn => btn.addEventListener('click', () => {
      tabs.querySelectorAll('button').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      activeTab = btn.dataset.category;
      renderVideos();
    }));

    const ytToEmbed = (url) => {
      const idMatch = url.match(/(?:v=|\.be\/)([\w-]{11})/);
      return idMatch ? `https://www.youtube.com/embed/${idMatch[1]}` : 'https://www.youtube.com/embed/jfKfPfyJRdk';
    };

    const renderVideos = async () => {
      try {
        const categoryValue = categoryFilter?.value === 'All' ? '' : (categoryFilter?.value || '');
        const query = new URLSearchParams({
          search: searchInput?.value || '',
          category: activeTab !== 'All' ? activeTab : categoryValue,
          sort: sortInput?.value || 'newest',
        });
        const result = await apiRequest(`../backend/api/admin/videos.php?${query.toString()}`);
        const items = result.data || [];

        videoCards.innerHTML = items.map(v => `
          <div class="col-md-6 col-xl-4">
            <div class="card card-soft p-3 h-100">
              <div class="ratio ratio-16x9 mb-2"><iframe src="${ytToEmbed(v.youtube_url)}" title="${v.title}" allowfullscreen></iframe></div>
              <h6>${v.title}</h6>
              <span class="category-badge ${badgeClass(v.category)} mb-2">${v.category}</span>
              <p class="small text-muted mb-1">${v.description}</p>
              <div class="card-actions"><button class="btn btn-sm btn-outline-danger video-delete" data-id="${v.id}">Delete</button></div>
            </div>
          </div>
        `).join('');

        videoCards.querySelectorAll('.video-delete').forEach(btn => {
          btn.addEventListener('click', async () => {
            if (!confirm('Delete this video?')) return;
            try {
              await apiRequest('../backend/api/admin/videos.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: Number(btn.dataset.id) }),
              });
              await renderVideos();
              showToast('Video deleted.');
            } catch (error) {
              showToast(error.message, 'error');
            }
          });
        });
      } catch (error) {
        showToast(error.message || 'Failed to load videos.', 'error');
      }
    };

    saveBtn?.addEventListener('click', async () => {
      const title = titleInput.value.trim();
      const category = categoryInput.value.trim();
      const link = linkInput.value.trim();
      const description = descInput.value.trim();
      [titleInput, categoryInput, linkInput, descInput].forEach(i => markInvalid(i, !i.value.trim()));
      if (!title || !category || !link || !description) {
        formMsg.textContent = 'Please fill all required fields.';
        formMsg.className = 'small text-danger mb-0 mt-2';
        showToast('Video form incomplete.', 'error');
        return;
      }
      try {
        await apiRequest('../backend/api/admin/videos.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ title, category, youtube_url: link, description }),
        });
        formMsg.textContent = 'Video saved successfully.';
        formMsg.className = 'small text-success mb-0 mt-2';
        showToast('Video added.');
        await renderVideos();
      } catch (error) {
        formMsg.textContent = error.message;
        formMsg.className = 'small text-danger mb-0 mt-2';
        showToast(error.message, 'error');
      }
    });

    searchInput?.addEventListener('input', renderVideos);
    sortInput?.addEventListener('change', renderVideos);
    categoryFilter?.addEventListener('change', renderVideos);
    renderVideos();
  }

  const updatesSection = document.getElementById('updatesSection');
  if (updatesSection) {
    const updateTitle = document.getElementById('updateTitle');
    const updateImage = document.getElementById('updateImage');
    const updateContent = document.getElementById('updateContent');
    const updateFormMessage = document.getElementById('updateFormMessage');
    const saveUpdateBtn = document.getElementById('saveUpdateBtn');
    const updateImagePreview = document.getElementById('updateImagePreview');
    const updatesList = document.getElementById('updatesList');

    const renderUpdatePreview = (file) => {
      if (!file || !updateImagePreview) return;
      const reader = new FileReader();
      reader.onload = (ev) => {
        updateImagePreview.src = ev.target.result;
        updateImagePreview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    };

    updateImage?.addEventListener('change', () => {
      const file = updateImage.files?.[0];
      if (file) {
        renderUpdatePreview(file);
      }
    });

    const renderUpdates = async () => {
      try {
        const result = await apiRequest('../backend/api/admin/updates.php');
        const items = result.data || [];
        if (!updatesList) return;
        updatesList.innerHTML = items.length ? items.map(item => `
          <div class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div class="d-flex gap-3 align-items-start">
              ${item.image_path ? `<img src="../${item.image_path}" alt="${item.title}" class="rounded" style="width:100px; height:80px; object-fit:cover;">` : ''}
              <div>
                <strong>${item.title}</strong>
                <p class="mb-1 text-muted small">${item.created_at ? new Date(item.created_at).toLocaleString('default', { year: 'numeric', month: 'short', day: 'numeric' }) : ''}</p>
                <p class="mb-0">${item.content}</p>
              </div>
            </div>
            <button class="btn btn-sm btn-outline-danger update-delete" data-id="${item.id}">Delete</button>
          </div>
        `).join('') : '<div class="text-center py-4 text-muted">No updates yet. Upload one to make it live.</div>';

        updatesList.querySelectorAll('.update-delete').forEach((btn) => {
          btn.addEventListener('click', async () => {
            if (!confirm('Delete this update?')) return;
            try {
              await apiRequest('../backend/api/admin/updates.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: Number(btn.dataset.id) }),
              });
              showToast('Update deleted.');
              await renderUpdates();
            } catch (error) {
              showToast(error.message, 'error');
            }
          });
        });
      } catch (error) {
        showToast(error.message || 'Failed to load updates.', 'error');
      }
    };

    saveUpdateBtn?.addEventListener('click', async () => {
      const title = updateTitle?.value.trim();
      const content = updateContent?.value.trim();
      const file = updateImage?.files?.[0];
      [updateTitle, updateContent].forEach((el) => markInvalid(el, !el?.value.trim()));
      markInvalid(updateImage, !file);

      if (!title || !content || !file) {
        if (updateFormMessage) {
          updateFormMessage.textContent = 'Title, description, and a photo are required.';
          updateFormMessage.className = 'small text-danger';
        }
        showToast('Please complete the update form.', 'error');
        return;
      }

      const formData = new FormData();
      formData.append('title', title);
      formData.append('content', content);
      formData.append('image', file);

      try {
        await apiRequest('../backend/api/admin/updates.php', { method: 'POST', body: formData });
        if (updateFormMessage) {
          updateFormMessage.textContent = 'Update uploaded successfully.';
          updateFormMessage.className = 'small text-success';
        }
        if (updateTitle) updateTitle.value = '';
        if (updateContent) updateContent.value = '';
        if (updateImage) updateImage.value = '';
        if (updateImagePreview) updateImagePreview.style.display = 'none';
        await renderUpdates();
        showToast('Update added. It will display on the public updates page.');
      } catch (error) {
        if (updateFormMessage) {
          updateFormMessage.textContent = error.message || 'Unable to upload update.';
          updateFormMessage.className = 'small text-danger';
        }
        showToast(error.message, 'error');
      }
    });

    renderUpdates();
  }

  const adminTableBody = document.getElementById('adminTableBody');
  if (adminTableBody) {
    const usernameInput = document.getElementById('adminUsername');
    const passwordInput = document.getElementById('adminPassword');
    const roleInput = document.getElementById('adminRole');
    const createBtn = document.getElementById('createAdminBtn');
    const msg = document.getElementById('adminFormMessage');
    const modalEl = document.getElementById('newAdminModal');
    const modal = modalEl && window.bootstrap ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;

    const renderAdmins = async () => {
      try {
        const result = await apiRequest('../backend/api/admin/admins.php');
        const admins = result.data || [];
        adminTableBody.innerHTML = admins.map(a => `
          <tr>
            <td>${a.username}</td>
            <td>${a.role}</td>
            <td>${a.is_main_admin == 1 ? '<span class="text-muted small">Protected</span>' : `<button class="btn btn-sm btn-outline-danger delete-admin" data-id="${a.id}">Delete</button>`}</td>
          </tr>
        `).join('');
        adminTableBody.querySelectorAll('.delete-admin').forEach(btn => {
          btn.addEventListener('click', async () => {
            if (!confirm('Delete this admin?')) return;
            try {
              await apiRequest('../backend/api/admin/admins.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: Number(btn.dataset.id) }),
              });
              await renderAdmins();
              showToast('Admin deleted.');
            } catch (error) {
              showToast(error.message, 'error');
            }
          });
        });
      } catch (error) {
        showToast(error.message || 'Failed to load admins.', 'error');
      }
    };

    createBtn?.addEventListener('click', async () => {
      const username = usernameInput.value.trim();
      const password = passwordInput.value.trim();
      const role = roleInput.value.trim();
      [usernameInput, passwordInput, roleInput].forEach(i => markInvalid(i, !i.value.trim()));
      if (!username || !password || !role) {
        msg.textContent = 'Username, password, and role are required.';
        msg.className = 'small text-danger mt-2 mb-0';
        showToast('Please complete admin form.', 'error');
        return;
      }
      try {
        await apiRequest('../backend/api/admin/admins.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username, password, role }),
        });
        msg.textContent = 'Admin created successfully.';
        msg.className = 'small text-success mt-2 mb-0';
        showToast('New admin created.');
        await renderAdmins();
        setTimeout(() => modal?.hide(), 350);
      } catch (error) {
        msg.textContent = error.message;
        msg.className = 'small text-danger mt-2 mb-0';
        showToast(error.message, 'error');
      }
    });

    renderAdmins();
  }

  const messagesTableBody = document.getElementById('messagesTableBody');
  if (messagesTableBody) {
    (async () => {
      try {
        const result = await apiRequest('../backend/api/admin/messages.php');
        messagesTableBody.innerHTML = (result.data || []).map(row => `
          <tr>
            <td>${row.name}</td>
            <td>${row.email}</td>
            <td>${row.message}</td>
            <td>${row.created_at}</td>
          </tr>
        `).join('');
      } catch (error) {
        showToast(error.message || 'Failed to load messages.', 'error');
      }
    })();
  }

  const statImages = document.getElementById('statImages');
  if (statImages) {
    (async () => {
      try {
        const result = await apiRequest('../backend/api/admin/dashboard-stats.php');
        const stats = result.stats || {};
        document.getElementById('statImages').textContent = stats.images ?? 0;
        document.getElementById('statVideos').textContent = stats.videos ?? 0;
        document.getElementById('statUpdates').textContent = stats.updates ?? 0;
        document.getElementById('statNotifications').textContent = stats.notifications ?? 0;
      } catch (error) {
        showToast(error.message || 'Failed to load dashboard stats.', 'error');
      }
    })();
  }

  const notificationTable = document.getElementById('notificationsTable');
  const notificationTitle = document.getElementById('notificationTitle');
  const notificationDate = document.getElementById('notificationDate');
  const notificationDescription = document.getElementById('notificationDescription');
  const notificationFormMessage = document.getElementById('notificationFormMessage');
  const clearNotificationFormBtn = document.getElementById('clearNotificationFormBtn');
  const saveNotificationBtn = document.getElementById('saveNotificationBtn');
  let editingNotificationId = null;

  const resetNotificationForm = () => {
    editingNotificationId = null;
    if (notificationTitle) notificationTitle.value = '';
    if (notificationDate) notificationDate.value = '';
    if (notificationDescription) notificationDescription.value = '';
    if (notificationFormMessage) {
      notificationFormMessage.textContent = 'Only admins can publish or update notifications.';
      notificationFormMessage.className = 'small text-muted mb-0';
    }
  };

  const bindNotificationActions = () => {
    notificationTable?.querySelectorAll('.notification-edit').forEach((btn) => {
      btn.addEventListener('click', () => {
        const row = btn.closest('.list-group-item');
        if (!row) return;
        editingNotificationId = Number(btn.dataset.id || 0);
        if (notificationTitle) notificationTitle.value = row.dataset.title || '';
        if (notificationDate) notificationDate.value = row.dataset.date || '';
        if (notificationDescription) notificationDescription.value = row.dataset.description || '';
        if (notificationFormMessage) {
          notificationFormMessage.textContent = 'Editing notification. Save to update.';
          notificationFormMessage.className = 'small text-primary mb-0';
        }
      });
    });

    notificationTable?.querySelectorAll('.notification-delete').forEach((btn) => {
      btn.addEventListener('click', async () => {
        if (!confirm('Delete this notification?')) return;
        try {
          await apiRequest('../backend/api/admin/notifications.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: Number(btn.dataset.id) }),
          });
          showToast('Notification deleted.');
          window.location.reload();
        } catch (error) {
          showToast(error.message, 'error');
        }
      });
    });
  };

  if (notificationTable) {
    bindNotificationActions();
  }

  saveNotificationBtn?.addEventListener('click', async () => {
    const title = notificationTitle?.value.trim();
    const publishDate = notificationDate?.value.trim();
    const description = notificationDescription?.value.trim();

    if (!title || !publishDate || !description) {
      if (notificationFormMessage) {
        notificationFormMessage.textContent = 'Please fill all notification fields.';
        notificationFormMessage.className = 'small text-danger mb-0';
      }
      return;
    }

    try {
      await apiRequest('../backend/api/admin/notifications.php', {
        method: editingNotificationId ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          id: editingNotificationId,
          title,
          publish_date: publishDate,
          description,
        }),
      });
      showToast(editingNotificationId ? 'Notification updated.' : 'Notification published.');
      resetNotificationForm();
      window.location.reload();
    } catch (error) {
      if (notificationFormMessage) {
        notificationFormMessage.textContent = error.message || 'Unable to save notification.';
        notificationFormMessage.className = 'small text-danger mb-0';
      }
      showToast(error.message, 'error');
    }
  });

  clearNotificationFormBtn?.addEventListener('click', resetNotificationForm);
})();
