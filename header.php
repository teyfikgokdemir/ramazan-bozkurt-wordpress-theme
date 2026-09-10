<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/wholesale.css?v=' . wp_get_theme()->get('Version')); ?>">
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header" data-site-header>
  <div class="rb-container site-header__inner">
    <a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Ramazan Bozkurt Et Ürünleri ana sayfa">
      <?php
      if (has_custom_logo()) {
          the_custom_logo();
      } else {
          $logo = rb_media_first(['ramazan-bozkurt-et-urunleri-kayseri-logo','ramazan-bozkurt-et-urunleri-kayseri-logo-1']);
          if ($logo) {
              echo '<img src="'.esc_url($logo).'" alt="Ramazan Bozkurt Et Ürünleri Kayseri" width="140" height="86">';
          } else {
              echo '<span>Ramazan Bozkurt Et Ürünleri</span>';
          }
      }
      ?>
    </a>

    <button class="site-menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Menüyü aç">
      <span></span><span></span><span></span>
    </button>

    <nav id="site-nav" class="site-nav" aria-label="Ana menü">
      <?php
      if (has_nav_menu('primary')) {
        wp_nav_menu([
          'theme_location' => 'primary',
          'container' => false,
          'items_wrap' => '%3$s',
          'fallback_cb' => false,
        ]);
      } else {
        $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/urunler');
        echo '<a href="'.esc_url(home_url('/')).'">Ana Sayfa</a>';
        echo '<a href="'.esc_url($shop_url).'">Ürünler</a>';
        echo '<a href="'.esc_url(home_url('/hakkimizda')).'">Hakkımızda</a>';
        echo '<a href="'.esc_url(add_query_arg('talep','toptan',home_url('/iletisim'))).'">Toptan Satış</a>';
        echo '<a href="'.esc_url(home_url('/iletisim')).'">İletişim</a>';
      }
      ?>
    </nav>

    <?php if (class_exists('WooCommerce')) : ?>
      <div class="site-tools">
        <a class="site-tool" href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" aria-label="Hesabım">
          <span class="site-tool__label">Hesabım</span>
        </a>
        <a class="site-tool site-tool--cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="Sepet">
          <span>Sepet</span><span class="rb-cart-count"><?php echo esc_html(rb_cart_count()); ?></span>
        </a>
      </div>
    <?php endif; ?>
  </div>
</header>
<main id="content">
