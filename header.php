<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
  <div class="rb-container site-header__inner">
    <a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php bloginfo('name'); ?>">
      <?php
      if (has_custom_logo()) {
          the_custom_logo();
      } else {
          $logo = rb_media('ramazan-bozkurt-et-urunleri-kayseri-logo');
          if ($logo) {
              echo '<img src="'.esc_url($logo).'" alt="Ramazan Bozkurt Et Ürünleri Kayseri">';
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
        echo '<a href="'.esc_url(home_url('/')).'">Ana Sayfa</a>';
        echo '<a href="'.esc_url(home_url('/urunler')).'">Ürünler</a>';
        echo '<a href="'.esc_url(home_url('/hakkimizda')).'">Hakkımızda</a>';
        echo '<a href="'.esc_url(home_url('/iletisim')).'">İletişim</a>';
      }
      ?>
    </nav>
  </div>
</header>
<main id="content">
