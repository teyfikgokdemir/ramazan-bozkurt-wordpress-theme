document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.querySelector('.site-menu-toggle');
  const nav = document.querySelector('.site-nav');
  const header = document.querySelector('[data-site-header]');

  function closeMenu() {
    if (!toggle || !nav) return;
    nav.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  }

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      const open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    nav.querySelectorAll('.menu-item-has-children > a').forEach(function (link) {
      link.addEventListener('click', function (event) {
        if (window.innerWidth > 880) return;
        const parent = link.parentElement;
        if (!parent.classList.contains('is-submenu-open')) {
          event.preventDefault();
          parent.classList.add('is-submenu-open');
        }
      });
    });

    nav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        if (window.innerWidth > 880 || !link.parentElement.classList.contains('menu-item-has-children')) closeMenu();
      });
    });

    document.addEventListener('click', function (event) {
      if (!nav.classList.contains('is-open')) return;
      if (!nav.contains(event.target) && !toggle.contains(event.target)) closeMenu();
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') closeMenu();
    });
  }

  if (header) {
    const syncHeader = function () {
      header.classList.toggle('is-scrolled', window.scrollY > 18);
    };
    syncHeader();
    window.addEventListener('scroll', syncHeader, { passive: true });
  }

  const cookieBanner = document.querySelector('[data-cookie-banner]');
  if (cookieBanner) {
    const consent = localStorage.getItem('rb_cookie_consent');
    if (!consent) cookieBanner.hidden = false;

    const accept = cookieBanner.querySelector('[data-cookie-accept]');
    const reject = cookieBanner.querySelector('[data-cookie-reject]');

    if (accept) accept.addEventListener('click', function () {
      localStorage.setItem('rb_cookie_consent', 'accepted');
      cookieBanner.hidden = true;
      document.dispatchEvent(new CustomEvent('rbCookieConsent', { detail: 'accepted' }));
    });

    if (reject) reject.addEventListener('click', function () {
      localStorage.setItem('rb_cookie_consent', 'rejected');
      cookieBanner.hidden = true;
      document.dispatchEvent(new CustomEvent('rbCookieConsent', { detail: 'rejected' }));
    });
  }
});
