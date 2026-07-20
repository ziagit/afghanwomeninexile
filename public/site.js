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

    const activate = (tab) => {
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
    };

    buttons.forEach((button) => {
      button.addEventListener('click', () => activate(button.dataset.mediaTab));
    });

    activate('gallery');
  }
});
