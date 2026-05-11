(() => {
  'use strict';

  const body = document.body;
  const header = document.querySelector('[data-header]');
  const menuButton = document.querySelector('.js-menu-button');
  const navLinks = document.querySelectorAll('.site-nav a, .header-actions a');
  const contactForm = document.querySelector('.js-contact-form');
  const submitButton = contactForm?.querySelector('button[type="submit"]');

  // ── Mobile menu ─────────────────────────────────────
  const closeMenu = () => {
    body.classList.remove('nav-open');
    menuButton?.classList.remove('is-open');
    menuButton?.setAttribute('aria-expanded', 'false');
  };

  menuButton?.addEventListener('click', () => {
    const open = body.classList.toggle('nav-open');
    menuButton.classList.toggle('is-open', open);
    menuButton.setAttribute('aria-expanded', String(open));
  });

  navLinks.forEach((link) => link.addEventListener('click', closeMenu));

  window.addEventListener('resize', () => {
    if (window.innerWidth > 880) closeMenu();
  });

  // ── Scroll-aware header ─────────────────────────────
  const updateHeader = () => {
    if (!header) return;
    header.classList.toggle('is-scrolled', window.scrollY > 8);
  };
  updateHeader();
  window.addEventListener('scroll', updateHeader, { passive: true });

  // ── Reveal-on-scroll animation ──────────────────────
  const targets = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        obs.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    targets.forEach((el) => io.observe(el));
  } else {
    targets.forEach((el) => el.classList.add('is-visible'));
  }

  // ── Form submit guard ───────────────────────────────
  if (contactForm && submitButton) {
    contactForm.addEventListener('submit', () => {
      submitButton.disabled = true;
      const labelEl = submitButton.querySelector('span');
      if (labelEl) labelEl.textContent = '送信中...';
    });
  }

  // ── FAQ: close siblings when one opens (single-open) ──
  const details = document.querySelectorAll('.faq-item');
  details.forEach((d) => {
    d.addEventListener('toggle', () => {
      if (!d.open) return;
      details.forEach((other) => { if (other !== d) other.open = false; });
    });
  });
})();
