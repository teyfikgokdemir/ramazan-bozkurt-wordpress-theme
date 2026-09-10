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

  const scrollTopButton = document.querySelector('[data-scroll-top]');
  if (scrollTopButton) {
    let scrollHideTimer = null;

    const hideScrollButton = function () {
      scrollTopButton.classList.remove('is-visible');
    };

    const showScrollButton = function () {
      if (window.scrollY < 260) {
        hideScrollButton();
        return;
      }

      scrollTopButton.classList.add('is-visible');
      window.clearTimeout(scrollHideTimer);
      scrollHideTimer = window.setTimeout(hideScrollButton, 900);
    };

    scrollTopButton.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
      hideScrollButton();
    });

    window.addEventListener('scroll', showScrollButton, { passive: true });
    hideScrollButton();
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

  const footer = document.querySelector('.site-footer');
  if (footer && !footer.querySelector('.rb-agency-credit')) {
    const credit = document.createElement('div');
    credit.className = 'rb-agency-credit';
    credit.innerHTML = '<span>Web tasarım ve geliştirme</span><a href="https://olivon.com.tr/" target="_blank" rel="noopener noreferrer">Olivon</a>';
    credit.style.cssText = 'width:min(calc(100% - 40px),1240px);margin:0 auto;padding:14px 0 2px;border-top:1px solid rgba(203,166,94,.12);display:flex;justify-content:center;align-items:center;gap:7px;color:#766b62;font-size:10px;letter-spacing:.04em;text-align:center;';
    const link = credit.querySelector('a');
    if (link) link.style.cssText = 'color:#bda46f;font-weight:800;letter-spacing:.08em;text-transform:uppercase;';
    footer.appendChild(credit);
  }
});
