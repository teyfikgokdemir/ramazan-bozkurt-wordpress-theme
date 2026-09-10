</main>
<footer class="site-footer">
  <div class="rb-container site-footer__grid">
    <div class="site-footer__brand">
      <strong><?php bloginfo('name'); ?></strong>
      <p>Kayseri’nin pastırma, sucuk, kavurma ve mantı geleneğini modern alışveriş deneyimiyle buluşturan seçkin ürün markası.</p>
    </div>
    <div>
      <h2>Ürünler</h2>
      <a href="<?php echo esc_url(rb_product_category_url('pastirma')); ?>">Pastırma</a>
      <a href="<?php echo esc_url(rb_product_category_url('sucuk')); ?>">Sucuk</a>
      <a href="<?php echo esc_url(rb_product_category_url('kavurma')); ?>">Kavurma</a>
      <a href="<?php echo esc_url(rb_product_category_url('manti')); ?>">Mantı</a>
    </div>
    <div>
      <h2>Ramazan Bozkurt</h2>
      <a href="<?php echo esc_url(home_url('/hakkimizda')); ?>">Hakkımızda</a>
      <a href="<?php echo esc_url(home_url('/iletisim')); ?>">İletişim</a>
      <?php if (class_exists('WooCommerce')) : ?>
        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">Hesabım</a>
      <?php endif; ?>
    </div>
    <div>
      <h2>Alışveriş</h2>
      <?php if (class_exists('WooCommerce')) : ?>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Mağaza</a>
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>">Sepet</a>
        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>">Ödeme</a>
      <?php endif; ?>
      <a href="<?php echo esc_url(home_url('/iletisim')); ?>">Toptan Satış</a>
    </div>
  </div>
  <div class="rb-container site-footer__bottom">
    <span>© <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. Tüm hakları saklıdır.</span>
    <span>Kayseri • Türkiye</span>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
