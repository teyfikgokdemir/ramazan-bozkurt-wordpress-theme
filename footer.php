<?php
$legal_setup = get_template_directory() . '/inc/legal-pages.php';
if (file_exists($legal_setup)) {
    require_once $legal_setup;
    if (function_exists('rb_seed_legal_pages_v1') && !get_option('rb_legal_pages_v1')) {
        rb_seed_legal_pages_v1();
    }
}
?>
</main>
<footer class="site-footer">
  <div class="rb-container site-footer__grid">
    <div class="site-footer__brand">
      <strong><?php bloginfo('name'); ?></strong>
      <p>Kayseri’nin geleneksel pastırma, sucuk, kavurma ve mantı lezzetlerini perakende ve toptan satışla buluşturuyoruz.</p>
      <p class="rb-footer-company">Ramazan Bozkurt<br>Süleymanlı Mah. 6952. Sk. No: 47 Talas / Kayseri<br>Erciyes Vergi Dairesi · VKN 1850818784<br><a href="tel:+905398248295">+90 539 824 82 95</a></p>
    </div>
    <div>
      <h2>Blog</h2>
      <a href="<?php echo esc_url(home_url('/blog')); ?>">Tüm Yazılar</a>
      <a href="<?php echo esc_url(home_url('/yemek-tarifleri')); ?>">Yemek Tarifleri</a>
    </div>
    <div>
      <h2>Yasal</h2>
      <a href="<?php echo esc_url(home_url('/kvkk-aydinlatma-metni')); ?>">KVKK Aydınlatma Metni</a>
      <a href="<?php echo esc_url(home_url('/gizlilik-politikasi')); ?>">Gizlilik Politikası</a>
      <a href="<?php echo esc_url(home_url('/cerez-politikasi')); ?>">Çerez Politikası</a>
      <a href="<?php echo esc_url(home_url('/mesafeli-satis-sozlesmesi')); ?>">Mesafeli Satış Sözleşmesi</a>
      <a href="<?php echo esc_url(home_url('/on-bilgilendirme-formu')); ?>">Ön Bilgilendirme Formu</a>
      <a href="<?php echo esc_url(home_url('/iade-iptal-politikasi')); ?>">İade ve İptal Politikası</a>
    </div>
    <div>
      <h2>Satış</h2>
      <?php if (class_exists('WooCommerce')) : ?>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Mağaza</a>
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>">Sepet</a>
      <?php endif; ?>
      <a href="<?php echo esc_url(home_url('/toptan-satis')); ?>">Toptan Satış</a>
      <a href="<?php echo esc_url(home_url('/iletisim')); ?>">İletişim</a>
      <a href="tel:+905398248295">Telefon: +90 539 824 82 95</a>
      <a href="https://wa.me/905398248295" target="_blank" rel="noopener noreferrer">WhatsApp</a>
    </div>
  </div>
  <div class="rb-container site-footer__bottom">
    <span>© <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. Tüm hakları saklıdır.</span>
    <span>Kayseri • Türkiye</span>
  </div>
</footer>

<a class="rb-whatsapp" href="https://wa.me/905398248295" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp ile +90 539 824 82 95 numarasından iletişime geç">
  <svg viewBox="0 0 32 32" aria-hidden="true" focusable="false">
    <path fill="currentColor" d="M16.04 3C9.42 3 4.05 8.27 4.05 14.77c0 2.28.67 4.5 1.93 6.4L3.9 28.9l8-2.04a12.2 12.2 0 0 0 4.13.72h.01c6.62 0 12-5.27 12-11.77C28.04 8.27 22.66 3 16.04 3Zm0 21.98h-.01a9.63 9.63 0 0 1-3.68-.72l-.59-.24-4.75 1.21 1.27-4.53-.27-.61a9.1 9.1 0 0 1-1.35-4.79c0-5.02 4.2-9.1 9.38-9.1 5.17 0 9.38 4.08 9.38 9.1 0 5.02-4.21 9.1-9.38 9.1Zm5.15-6.81c-.28-.14-1.66-.8-1.92-.89-.26-.09-.45-.14-.64.14-.19.27-.73.89-.9 1.08-.17.18-.33.2-.61.07-.28-.14-1.19-.43-2.26-1.36a8.67 8.67 0 0 1-1.57-1.91c-.16-.27-.02-.42.12-.56.13-.13.28-.34.42-.5.14-.16.19-.27.28-.45.09-.18.05-.34-.02-.48-.07-.14-.64-1.51-.88-2.06-.23-.56-.47-.48-.64-.49h-.55c-.19 0-.5.07-.76.34-.26.27-1  .96-1 2.34 0 1.37 1.03 2.7 1.17 2.88.14.18 2.02 3.01 4.9 4.22.68.29 1.22.46 1.63.59.68.21 1.31.18 1.8.11.55-.08 1.66-.66 1.9-1.3.24-.64.24-1.19.17-1.3-.07-.12-.26-.19-.54-.33Z"/>
  </svg>
</a>

<button class="rb-scroll-top" type="button" data-scroll-top aria-label="Sayfanın başına dön">
  <span aria-hidden="true">↑</span>
</button>

<div class="rb-cookie" data-cookie-banner hidden>
  <div class="rb-cookie__inner">
    <div>
      <strong>Çerez tercihleri</strong>
      <p>Site işlevleri için zorunlu çerezler kullanılır. Analitik ve pazarlama çerezleri yalnızca onay verirseniz etkinleştirilir. Ayrıntılar için <a href="<?php echo esc_url(home_url('/cerez-politikasi')); ?>">Çerez Politikası</a> sayfasını inceleyebilirsiniz.</p>
    </div>
    <div class="rb-cookie__actions">
      <button type="button" class="rb-btn rb-btn--ghost" data-cookie-reject>Reddet</button>
      <button type="button" class="rb-btn rb-btn--primary" data-cookie-accept>Kabul Et</button>
    </div>
  </div>
</div>

<style>
.rb-footer-company{font-size:12px;line-height:1.7;color:#a89788;margin-top:18px}.rb-footer-company a{display:inline!important;color:var(--rb-gold-soft)}
.rb-whatsapp{position:fixed;left:22px;bottom:22px;z-index:1400;width:56px;height:56px;display:grid;place-items:center;border:1px solid rgba(255,255,255,.14);border-radius:50%;background:#25D366;color:#fff;box-shadow:0 16px 42px rgba(0,0,0,.38),0 0 0 0 rgba(37,211,102,.34);transition:transform .22s ease,box-shadow .22s ease,filter .22s ease;animation:rbWhatsappPulse 3.4s ease-out infinite}.rb-whatsapp svg{width:29px;height:29px;display:block}.rb-whatsapp:hover{transform:translateY(-3px) scale(1.04);filter:brightness(1.04);box-shadow:0 20px 48px rgba(0,0,0,.44),0 0 0 8px rgba(37,211,102,.10)}.rb-whatsapp:focus-visible{outline:2px solid var(--rb-gold-soft);outline-offset:4px}@keyframes rbWhatsappPulse{0%,68%,100%{box-shadow:0 16px 42px rgba(0,0,0,.38),0 0 0 0 rgba(37,211,102,.30)}78%{box-shadow:0 16px 42px rgba(0,0,0,.38),0 0 0 12px rgba(37,211,102,0)}}
.rb-scroll-top{position:fixed;right:22px;bottom:22px;z-index:1400;width:48px;height:48px;display:grid;place-items:center;border:1px solid var(--rb-border-strong);border-radius:999px;background:#17100d;color:var(--rb-gold-soft);box-shadow:0 18px 45px rgba(0,0,0,.35);font-size:22px;line-height:1;cursor:pointer;opacity:0;visibility:hidden;transform:translateY(10px);pointer-events:none;transition:opacity .2s ease,visibility .2s ease,transform .2s ease,background .2s ease}.rb-scroll-top.is-visible{opacity:1;visibility:visible;transform:translateY(0);pointer-events:auto}.rb-scroll-top:hover{background:#211711}
.rb-cookie{position:fixed;left:20px;right:20px;bottom:20px;z-index:2000}.rb-cookie__inner{width:min(100%,980px);margin:auto;padding:22px 24px;display:flex;align-items:center;justify-content:space-between;gap:26px;border:1px solid var(--rb-border-strong);border-radius:20px;background:rgba(18,13,10,.98);box-shadow:0 30px 80px rgba(0,0,0,.5);backdrop-filter:blur(16px)}.rb-cookie strong{color:var(--rb-cream);font-size:16px}.rb-cookie p{margin:5px 0 0;color:var(--rb-muted);font-size:13px;max-width:680px}.rb-cookie p a{color:var(--rb-gold-soft);text-decoration:underline}.rb-cookie__actions{display:flex;gap:10px;flex:0 0 auto}
@media(prefers-reduced-motion:reduce){.rb-whatsapp{animation:none}.rb-whatsapp,.rb-scroll-top{transition:none}}
@media(max-width:700px){.rb-cookie{left:12px;right:12px;bottom:12px}.rb-cookie__inner{display:grid;padding:18px}.rb-cookie__actions{width:100%}.rb-cookie__actions .rb-btn{flex:1}.rb-whatsapp{left:14px;bottom:14px;width:52px;height:52px}.rb-whatsapp svg{width:27px;height:27px}.rb-scroll-top{right:14px;bottom:14px}.site-footer__grid{grid-template-columns:1fr}}
</style>

<?php wp_footer(); ?>
</body>
</html>
