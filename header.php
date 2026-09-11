<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
  <?php
  $rb_favicon = rb_media_first(['ramazan-bozkurt-et-urunleri-kayseri-logo','ramazan-bozkurt-et-urunleri-kayseri-logo-1']);
  if ($rb_favicon) : ?>
    <link rel="icon" href="<?php echo esc_url($rb_favicon); ?>" type="image/png">
    <link rel="shortcut icon" href="<?php echo esc_url($rb_favicon); ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?php echo esc_url($rb_favicon); ?>">
  <?php endif; ?>
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/wholesale.css?v=' . wp_get_theme()->get('Version')); ?>">
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/wholesale-landing.css?v=' . wp_get_theme()->get('Version')); ?>">
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/product-card-fix.css?v=' . wp_get_theme()->get('Version')); ?>">
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/commerce.css?v=' . wp_get_theme()->get('Version')); ?>">
  <?php $rb_nav_css = get_template_directory() . '/assets/css/navigation.css'; $rb_nav_ver = file_exists($rb_nav_css) ? filemtime($rb_nav_css) : wp_get_theme()->get('Version'); ?>
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/navigation.css?v=' . $rb_nav_ver); ?>">
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/visual-effects.css?v=' . wp_get_theme()->get('Version')); ?>">
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/home-compact.css?v=' . wp_get_theme()->get('Version')); ?>">
  <?php $rb_blog_css = get_template_directory() . '/assets/css/blog.css'; $rb_blog_ver = file_exists($rb_blog_css) ? filemtime($rb_blog_css) : wp_get_theme()->get('Version'); ?>
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/blog.css?v=' . $rb_blog_ver); ?>">
  <?php $rb_blog_detail_css = get_template_directory() . '/assets/css/blog-detail-fix.css'; $rb_blog_detail_ver = file_exists($rb_blog_detail_css) ? filemtime($rb_blog_detail_css) : wp_get_theme()->get('Version'); ?>
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/blog-detail-fix.css?v=' . $rb_blog_detail_ver); ?>">
  <script defer src="<?php echo esc_url(get_template_directory_uri() . '/assets/js/cart-progress.js?v=' . wp_get_theme()->get('Version')); ?>"></script>
  <style>
    @media(max-width:880px){
      .site-nav .menu-item-has-children>.sub-menu{display:none!important;opacity:1!important;visibility:visible!important;transform:none!important;position:static!important}
      .site-nav .menu-item-has-children.is-submenu-open>.sub-menu{display:block!important}
      .site-nav .menu-item-has-children>a{cursor:pointer}
    }
    @media(max-width:700px){
      .rb-scroll-top{right:14px!important;bottom:calc(86px + env(safe-area-inset-bottom,0px))!important;z-index:9999!important}
      .rb-scroll-top.is-visible{opacity:1!important;visibility:visible!important;transform:none!important;pointer-events:auto!important}
    }
  </style>
  <script>
  document.addEventListener('DOMContentLoaded',function(){
    var nav=document.getElementById('site-nav');
    if(nav){
      nav.querySelectorAll('.menu-item-has-children').forEach(function(item){
        var link=item.querySelector(':scope > a');
        var submenu=item.querySelector(':scope > .sub-menu');
        if(!link||!submenu)return;
        link.addEventListener('click',function(e){
          if(window.innerWidth>880)return;
          e.preventDefault();
          e.stopPropagation();
          var willOpen=!item.classList.contains('is-submenu-open');
          nav.querySelectorAll('.menu-item-has-children.is-submenu-open').forEach(function(other){if(other!==item)other.classList.remove('is-submenu-open');});
          item.classList.toggle('is-submenu-open',willOpen);
        },true);
      });
    }
    var btn=document.querySelector('[data-scroll-top]');
    if(btn){
      var timer;
      var sync=function(){
        var y=window.pageYOffset||document.documentElement.scrollTop||0;
        if(y>220){
          btn.classList.add('is-visible');
          clearTimeout(timer);
          timer=setTimeout(function(){btn.classList.remove('is-visible');},1800);
        }else{
          btn.classList.remove('is-visible');
        }
      };
      window.addEventListener('scroll',sync,{passive:true});
      window.addEventListener('touchmove',sync,{passive:true});
      btn.addEventListener('click',function(){window.scrollTo({top:0,behavior:'smooth'});});
      sync();
    }
  });
  </script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
$content_hub = get_template_directory() . '/inc/content-hub.php';
if (file_exists($content_hub)) {
    require_once $content_hub;
    if (function_exists('rb_seed_content_hub_v1') && !get_option('rb_content_hub_v1')) {
        rb_seed_content_hub_v1();
    }
}

$nav_setup = get_template_directory() . '/inc/navigation-setup.php';
if (file_exists($nav_setup)) {
    require_once $nav_setup;
    if (function_exists('rb_navigation_setup_v1') && !get_option('rb_navigation_setup_v1')) {
        rb_navigation_setup_v1();
    }
    if (function_exists('rb_navigation_content_hub_v2') && !get_option('rb_navigation_content_hub_v2')) {
        rb_navigation_content_hub_v2();
    }
}

$cemensiz_setup = get_template_directory() . '/inc/cemensiz-image.php';
if (file_exists($cemensiz_setup)) {
    require_once $cemensiz_setup;
    if (function_exists('rb_assign_cemensiz_product_image_v1') && !get_option('rb_cemensiz_product_image_v1')) {
        rb_assign_cemensiz_product_image_v1();
    }
}
?>
<header class="site-header" data-site-header>
  <div class="rb-container site-header__inner">
    <a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Ramazan Bozkurt Et ve Et Mamulleri ana sayfa">
      <?php
      if (has_custom_logo()) {
          the_custom_logo();
      } else {
          $logo = rb_media_first(['ramazan-bozkurt-et-urunleri-kayseri-logo','ramazan-bozkurt-et-urunleri-kayseri-logo-1']);
          if ($logo) {
              echo '<img src="'.esc_url($logo).'" alt="Ramazan Bozkurt Et ve Et Mamulleri Kayseri" width="140" height="86">';
          } else {
              echo '<span>Ramazan Bozkurt Et ve Et Mamulleri</span>';
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
          'menu_class' => 'menu',
          'items_wrap' => '<ul class="menu">%3$s</ul>',
          'fallback_cb' => false,
          'depth' => 2,
        ]);
      } else {
        $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/urunler');
        echo '<ul class="menu">';
        echo '<li><a href="'.esc_url(home_url('/')).'">Ana Sayfa</a></li>';
        echo '<li><a href="'.esc_url($shop_url).'">Ürünler</a></li>';
        echo '<li><a href="'.esc_url(home_url('/toptan-satis')).'">Toptan Satış</a></li>';
        echo '<li><a href="'.esc_url(home_url('/yemek-tarifleri')).'">Yemek Tarifleri</a></li>';
        echo '<li><a href="'.esc_url(home_url('/blog')).'">Blog</a></li>';
        echo '<li><a href="'.esc_url(home_url('/hakkimizda')).'">Hakkımızda</a></li>';
        echo '<li><a href="'.esc_url(home_url('/iletisim')).'">İletişim</a></li>';
        echo '</ul>';
      }
      ?>
    </nav>

    <?php if (class_exists('WooCommerce')) : ?>
      <div class="site-tools">
        <a class="site-tool" href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" aria-label="Hesabım"><span class="site-tool__label">Hesabım</span></a>
        <a class="site-tool site-tool--cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="Sepet"><span>Sepet</span><span class="rb-cart-count"><?php echo esc_html(rb_cart_count()); ?></span></a>
      </div>
    <?php endif; ?>
  </div>
</header>
<main id="content">
