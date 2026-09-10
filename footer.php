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
      <p class="rb-footer-company">Ramazan Bozkurt<br>Süleymanlı Mah. 6952. Sk. No: 47 Talas / Kayseri<br>Erciyes Vergi Dairesi · VKN 1850818784</p>
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
    </div>
  </div>
  <div class="rb-container site-footer__bottom">
    <span>© <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. Tüm hakları saklıdır.</span>
    <span>Kayseri • Türkiye</span>
  </div>
</footer>

<a class="rb-whatsapp" href="https://wa.me/905555555555" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp ile iletişime geç">
  <span aria-hidden="true">✆</span><strong>WhatsApp</strong>
</a>

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
.rb-footer-company{font-size:12px;line-height:1.7;color:#a89788;margin-top:18px}.rb-whatsapp{position:fixed;left:22px;bottom:22px;z-index:1400;display:inline-flex;align-items:center;gap:8px;min-height:48px;padding:0 16px;border:1px solid var(--rb-border-strong);border-radius:999px;background:#17100d;color:var(--rb-gold-soft);box-shadow:0 18px 45px rgba(0,0,0,.35);font-size:13px}.rb-whatsapp:hover{background:#211711}.rb-cookie{position:fixed;left:20px;right:20px;bottom:20px;z-index:2000}.rb-cookie__inner{width:min(100%,980px);margin:auto;padding:22px 24px;display:flex;align-items:center;justify-content:space-between;gap:26px;border:1px solid var(--rb-border-strong);border-radius:20px;background:rgba(18,13,10,.98);box-shadow:0 30px 80px rgba(0,0,0,.5);backdrop-filter:blur(16px)}.rb-cookie strong{color:var(--rb-cream);font-size:16px}.rb-cookie p{margin:5px 0 0;color:var(--rb-muted);font-size:13px;max-width:680px}.rb-cookie p a{color:var(--rb-gold-soft);text-decoration:underline}.rb-cookie__actions{display:flex;gap:10px;flex:0 0 auto}@media(max-width:700px){.rb-cookie{left:12px;right:12px;bottom:12px}.rb-cookie__inner{display:grid;padding:18px}.rb-cookie__actions{width:100%}.rb-cookie__actions .rb-btn{flex:1}.rb-whatsapp{left:14px;bottom:14px}.site-footer__grid{grid-template-columns:1fr}}
</style>

<?php wp_footer(); ?>
</body>
</html>
