document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.querySelector('.site-menu-toggle');
  const nav = document.querySelector('.site-nav');
  const header = document.querySelector('[data-site-header]');

  function closeMenu() {
    if (!toggle || !nav) return;
    nav.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    nav.querySelectorAll('.menu-item-has-children.is-submenu-open').forEach(function (item) {
      item.classList.remove('is-submenu-open');
      const button = item.querySelector(':scope > .rb-submenu-toggle');
      if (button) {
        button.setAttribute('aria-expanded', 'false');
        const parentLink = item.querySelector(':scope > a');
        if (parentLink) button.setAttribute('aria-label', parentLink.textContent.trim() + ' alt menüsünü aç');
      }
    });
  }

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      const open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    nav.querySelectorAll('.menu-item-has-children').forEach(function (item, index) {
      const link = item.querySelector(':scope > a');
      const submenu = item.querySelector(':scope > .sub-menu');
      if (!link || !submenu) return;

      if (!submenu.id) submenu.id = 'rb-submenu-' + index;

      let submenuToggle = item.querySelector(':scope > .rb-submenu-toggle');
      if (!submenuToggle) {
        submenuToggle = document.createElement('button');
        submenuToggle.type = 'button';
        submenuToggle.className = 'rb-submenu-toggle';
        submenuToggle.setAttribute('aria-label', link.textContent.trim() + ' alt menüsünü aç');
        submenuToggle.setAttribute('aria-controls', submenu.id);
        submenuToggle.setAttribute('aria-expanded', 'false');
        submenuToggle.innerHTML = '<span aria-hidden="true"></span>';
        link.insertAdjacentElement('afterend', submenuToggle);
      }

      const setOpen = function (open) {
        item.classList.toggle('is-submenu-open', open);
        submenuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        submenuToggle.setAttribute('aria-label', link.textContent.trim() + (open ? ' alt menüsünü kapat' : ' alt menüsünü aç'));
      };

      submenuToggle.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (window.innerWidth > 880) return;
        setOpen(!item.classList.contains('is-submenu-open'));
      });

      link.addEventListener('click', function (event) {
        if (window.innerWidth > 880) return;
        event.preventDefault();
        setOpen(!item.classList.contains('is-submenu-open'));
      });
    });

    nav.querySelectorAll('.sub-menu a, .menu > li:not(.menu-item-has-children) > a').forEach(function (link) {
      link.addEventListener('click', closeMenu);
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
    const syncHeader = function () { header.classList.toggle('is-scrolled', window.scrollY > 18); };
    syncHeader();
    window.addEventListener('scroll', syncHeader, { passive: true });
  }

  const scrollTopButton = document.querySelector('[data-scroll-top]');
  if (scrollTopButton) {
    let scrollHideTimer = null;
    let lastScrollY = window.scrollY;
    const hideScrollButton = function () { scrollTopButton.classList.remove('is-visible'); };
    const showScrollButton = function () {
      const currentY = window.scrollY || document.documentElement.scrollTop || 0;
      if (currentY < 220) {
        hideScrollButton();
        lastScrollY = currentY;
        return;
      }
      if (Math.abs(currentY - lastScrollY) >= 2) {
        scrollTopButton.classList.add('is-visible');
        window.clearTimeout(scrollHideTimer);
        scrollHideTimer = window.setTimeout(hideScrollButton, 1800);
      }
      lastScrollY = currentY;
    };
    scrollTopButton.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
      hideScrollButton();
    });
    window.addEventListener('scroll', showScrollButton, { passive: true });
    window.addEventListener('touchmove', showScrollButton, { passive: true });
    hideScrollButton();
  }

  const cookieBanner = document.querySelector('[data-cookie-banner]');
  if (cookieBanner) {
    const consent = localStorage.getItem('rb_cookie_consent');
    if (!consent) cookieBanner.hidden = false;
    const accept = cookieBanner.querySelector('[data-cookie-accept]');
    const reject = cookieBanner.querySelector('[data-cookie-reject]');
    if (accept) accept.addEventListener('click', function () {
      localStorage.setItem('rb_cookie_consent', 'accepted'); cookieBanner.hidden = true;
      document.dispatchEvent(new CustomEvent('rbCookieConsent', { detail: 'accepted' }));
    });
    if (reject) reject.addEventListener('click', function () {
      localStorage.setItem('rb_cookie_consent', 'rejected'); cookieBanner.hidden = true;
      document.dispatchEvent(new CustomEvent('rbCookieConsent', { detail: 'rejected' }));
    });
  }

  document.querySelectorAll('.single-product form.variations_form table.variations select').forEach(function (select) {
    if (select.dataset.rbEnhanced === '1') return;
    select.dataset.rbEnhanced = '1';
    const wrap = document.createElement('div');
    wrap.className = 'rb-variation-pills';
    wrap.setAttribute('role', 'group');
    wrap.setAttribute('aria-label', 'Gramaj seçimi');
    Array.from(select.options).forEach(function (option) {
      if (!option.value) return;
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'rb-variation-pill';
      button.textContent = option.textContent.trim();
      button.dataset.value = option.value;
      if (option.disabled) button.disabled = true;
      if (option.selected) button.classList.add('is-active');
      button.addEventListener('click', function () {
        select.value = button.dataset.value;
        select.dispatchEvent(new Event('change', { bubbles: true }));
      });
      wrap.appendChild(button);
    });
    select.insertAdjacentElement('afterend', wrap);
    select.classList.add('rb-native-variation-select');
    const sync = function () {
      wrap.querySelectorAll('.rb-variation-pill').forEach(function (button) {
        button.classList.toggle('is-active', button.dataset.value === select.value);
      });
    };
    select.addEventListener('change', sync);
    sync();
  });

  const footerBottom = document.querySelector('.site-footer__bottom');
  if (footerBottom && !footerBottom.querySelector('.rb-agency-credit')) {
    const credit = document.createElement('span');
    credit.className = 'rb-agency-credit';
    credit.innerHTML = '<span>Web tasarım ve geliştirme</span> <a href="https://olivon.com.tr/" target="_blank" rel="noopener noreferrer">Olivon</a>';
    credit.style.cssText = 'color:#766b62;font-size:10px;letter-spacing:.04em;text-align:center;white-space:nowrap;';
    const link = credit.querySelector('a');
    if (link) link.style.cssText = 'display:inline;color:#bda46f;font-weight:800;letter-spacing:.08em;text-transform:uppercase;';
    const location = footerBottom.lastElementChild;
    if (location) footerBottom.insertBefore(credit, location); else footerBottom.appendChild(credit);
  }

  if (!document.getElementById('rb-footer-balance-fix')) {
    const footerStyle = document.createElement('style');
    footerStyle.id = 'rb-footer-balance-fix';
    footerStyle.textContent = [
      '.site-footer{padding-bottom:0!important}',
      '.rb-payment-strip{margin-bottom:0!important}',
      '.site-footer__bottom{margin-top:0!important;padding-top:22px!important;padding-bottom:22px!important;border-top:0!important;display:grid!important;grid-template-columns:1fr auto 1fr!important;align-items:center!important;gap:24px!important}',
      '.site-footer__bottom>span:first-child{text-align:left!important}',
      '.site-footer__bottom>span:last-child{text-align:right!important}',
      '.rb-agency-credit{justify-self:center!important}',
      '@media(max-width:700px){.site-footer__bottom{grid-template-columns:1fr!important;gap:10px!important;text-align:center!important;padding-top:18px!important;padding-bottom:18px!important}.site-footer__bottom>span:first-child,.site-footer__bottom>span:last-child{text-align:center!important}.rb-agency-credit{justify-self:center!important}}'
    ].join('');
    document.head.appendChild(footerStyle);
  }
});