document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('[data-site-header]');
  const toggle = document.querySelector('[data-nav-toggle]');
  const nav = document.querySelector('[data-primary-nav]');

  if (header && toggle && nav) {
    const closeMenu = () => {
      nav.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
    };

    toggle.addEventListener('click', () => {
      const isOpen = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    nav.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', closeMenu);
    });
  }

  const root = document.querySelector('[data-media-root]');
  if (root) {
    const buttons = Array.from(root.querySelectorAll('[data-media-tab]'));
    const panels = Array.from(root.querySelectorAll('[data-media-panel]'));
    const heroTitle = document.querySelector('[data-media-title]');
    const heroDescription = document.querySelector('[data-media-description]');

    const activate = (tab, syncUrl = false) => {
      buttons.forEach((button) => {
        const active = button.dataset.mediaTab === tab;
        button.classList.toggle('active', active);
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
      });

      panels.forEach((panel) => {
        panel.hidden = panel.dataset.mediaPanel !== tab;
      });

      const activeButton = buttons.find((button) => button.dataset.mediaTab === tab);
      if (activeButton && heroTitle && heroDescription) {
        heroTitle.textContent = activeButton.dataset.mediaTitle || heroTitle.textContent;
        heroDescription.textContent = activeButton.dataset.mediaDescription || heroDescription.textContent;
      }

      if (syncUrl) {
        const url = new URL(window.location.href);
        url.searchParams.set('media', tab);
        window.history.replaceState({}, '', url);
      }
    };

    buttons.forEach((button) => {
      button.addEventListener('click', () => activate(button.dataset.mediaTab, true));
    });

    const initialTab = new URLSearchParams(window.location.search).get('media') || 'gallery';
    activate(buttons.some((button) => button.dataset.mediaTab === initialTab) ? initialTab : 'gallery');

    const videoDialog = root.querySelector('[data-video-dialog]');
    const videoFrame = root.querySelector('[data-video-dialog-frame]');
    const closeVideo = root.querySelector('[data-video-close]');
    let lastFocusedVideo = null;

    if (videoDialog && videoFrame && closeVideo) {
      const getEmbedUrl = (value) => {
        try {
          const url = new URL(value);
          if (url.hostname.includes('youtu.be')) {
            return 'https://www.youtube.com/embed/' + url.pathname.slice(1) + '?autoplay=1';
          }
          if (url.hostname.includes('youtube.com')) {
            const id = url.searchParams.get('v');
            return id ? 'https://www.youtube.com/embed/' + id + '?autoplay=1' : value;
          }
          if (url.hostname.includes('vimeo.com')) {
            const id = url.pathname.split('/').filter(Boolean).pop();
            return id ? 'https://player.vimeo.com/video/' + id + '?autoplay=1' : value;
          }
        } catch {
          return value;
        }
        return value;
      };

      const openVideo = (item) => {
        lastFocusedVideo = item;
        videoFrame.src = getEmbedUrl(item.dataset.videoLink);
        videoDialog.showModal();
        closeVideo.focus();
      };

      const dismissVideo = () => {
        videoDialog.close();
        videoFrame.src = '';
        lastFocusedVideo?.focus();
      };

      root.querySelectorAll('[data-video-item]').forEach((item) => {
        item.addEventListener('click', () => openVideo(item));
        item.addEventListener('keydown', (event) => {
          if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            openVideo(item);
          }
        });
      });

      closeVideo.addEventListener('click', dismissVideo);
      videoDialog.addEventListener('click', (event) => {
        if (event.target === videoDialog) dismissVideo();
      });
      videoDialog.addEventListener('close', () => {
        videoFrame.src = '';
        lastFocusedVideo?.focus();
      });
    }

    const dialog = root.querySelector('[data-gallery-dialog]');
    const dialogImage = root.querySelector('[data-gallery-dialog-image]');
    const closeDialog = root.querySelector('[data-gallery-close]');
    let lastFocusedItem = null;

    if (dialog && dialogImage && closeDialog) {
      const openDialog = (item) => {
        lastFocusedItem = item;
        dialogImage.src = item.dataset.galleryImage;
        dialogImage.alt = item.dataset.galleryTitle;
        dialog.showModal();
        closeDialog.focus();
      };

      const dismissDialog = () => {
        dialog.close();
        dialogImage.src = '';
        lastFocusedItem?.focus();
      };

      root.querySelectorAll('[data-gallery-item]').forEach((item) => {
        item.addEventListener('click', () => openDialog(item));
        item.addEventListener('keydown', (event) => {
          if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            openDialog(item);
          }
        });
      });

      closeDialog.addEventListener('click', dismissDialog);
      dialog.addEventListener('click', (event) => {
        if (event.target === dialog) dismissDialog();
      });
      dialog.addEventListener('close', () => {
        dialogImage.src = '';
        lastFocusedItem?.focus();
      });
    }
  }
});
