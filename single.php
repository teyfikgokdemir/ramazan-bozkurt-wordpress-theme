<?php
get_header();
?>
<style id="rb-single-hard-fix">
html,body.single-post,body.single-post #content,body.single-post main#content{background:#0d0907!important;color:#f3eadf!important;width:100%!important;max-width:none!important}
body.single-post .rb-single-hero{background:linear-gradient(180deg,#150d09 0%,#0c0806 100%)!important;border-bottom:1px solid rgba(203,166,94,.18)!important;padding:72px 0 56px!important}
body.single-post .rb-single-hero__inner{width:min(calc(100% - 40px),1120px)!important;margin:0 auto!important;display:grid!important;grid-template-columns:minmax(0,1.1fr) minmax(320px,.9fr)!important;gap:48px!important;align-items:center!important}
body.single-post .rb-single-copy{min-width:0!important}
body.single-post .rb-single-kicker{display:block!important;color:#cba65e!important;font-size:11px!important;font-weight:800!important;letter-spacing:.18em!important;text-transform:uppercase!important;margin:0 0 16px!important}
body.single-post .rb-single-title{margin:0!important;color:#f3eadf!important;font-family:Georgia,'Times New Roman',serif!important;font-size:clamp(38px,4vw,58px)!important;line-height:1.02!important;letter-spacing:-.035em!important;overflow-wrap:break-word!important;word-break:normal!important;max-width:720px!important}
body.single-post .rb-single-excerpt{margin:20px 0 0!important;color:#cdbfb3!important;font-size:16px!important;line-height:1.7!important;max-width:680px!important}
body.single-post .rb-single-date{margin-top:16px!important;color:#8f8175!important;font-size:12px!important}
body.single-post .rb-single-cover{width:100%!important;max-width:430px!important;justify-self:end!important;aspect-ratio:4/3!important;border-radius:22px!important;overflow:hidden!important;background:#17100c!important;border:1px solid rgba(203,166,94,.15)!important}
body.single-post .rb-single-cover img{display:block!important;width:100%!important;height:100%!important;object-fit:cover!important}
body.single-post .rb-single-body{width:min(calc(100% - 40px),1040px)!important;margin:0 auto!important;display:grid!important;grid-template-columns:minmax(0,720px) 260px!important;gap:56px!important;align-items:start!important;padding:64px 0 84px!important}
body.single-post .rb-single-content{min-width:0!important;color:#d8cec5!important;font-size:17px!important;line-height:1.9!important}
body.single-post .rb-single-content p,body.single-post .rb-single-content li,body.single-post .rb-single-content div,body.single-post .rb-single-content span{color:#d8cec5!important}
body.single-post .rb-single-content h2,body.single-post .rb-single-content h3,body.single-post .rb-single-content h4{color:#f3eadf!important;font-family:Georgia,'Times New Roman',serif!important;line-height:1.12!important}
body.single-post .rb-single-content h2{font-size:34px!important;margin:42px 0 14px!important}
body.single-post .rb-single-content h3{font-size:25px!important;margin:30px 0 12px!important}
body.single-post .rb-single-content a{color:#e2c98f!important;text-decoration:underline!important;text-underline-offset:3px!important}
body.single-post .rb-single-aside{position:sticky!important;top:108px!important;padding:22px!important;border:1px solid rgba(203,166,94,.2)!important;border-radius:18px!important;background:#15100d!important;color:#f3eadf!important}
body.single-post .rb-single-aside strong,body.single-post .rb-single-aside span,body.single-post .rb-single-aside a{display:block!important}
body.single-post .rb-single-aside strong{font-family:Georgia,'Times New Roman',serif!important;font-size:21px!important;color:#f3eadf!important}
body.single-post .rb-single-aside span{margin:8px 0 16px!important;color:#bcaea1!important;font-size:12px!important;line-height:1.6!important}
body.single-post .rb-single-aside a{padding:10px 0!important;border-top:1px solid rgba(203,166,94,.18)!important;color:#e2c98f!important;font-size:12px!important}
body.single-post .rb-article-more{background:#0c0806!important;border-top:1px solid rgba(203,166,94,.16)!important}
@media(max-width:900px){body.single-post .rb-single-hero__inner,body.single-post .rb-single-body{grid-template-columns:1fr!important}body.single-post .rb-single-cover{justify-self:start!important;max-width:680px!important}body.single-post .rb-single-aside{position:static!important}body.single-post .rb-single-title{font-size:clamp(38px,7vw,54px)!important;max-width:100%!important}}
@media(max-width:640px){body.single-post .rb-single-hero{padding:52px 0 36px!important}body.single-post .rb-single-hero__inner,body.single-post .rb-single-body{width:min(calc(100% - 28px),1120px)!important}body.single-post .rb-single-title{font-size:clamp(34px,10.5vw,46px)!important}body.single-post .rb-single-body{padding:42px 0 60px!important;gap:28px!important}body.single-post .rb-single-content{font-size:16px!important}body.single-post .rb-single-content h2{font-size:29px!important}}
</style>
<?php
while (have_posts()) : the_post();
    $current_id = get_the_ID();
    $cats = get_the_category();
    $primary_cat = $cats ? $cats[0] : null;
    $is_recipe = $primary_cat && $primary_cat->slug === 'yemek-tarifleri';
    $fallback_slugs = $is_recipe
        ? ['ramazan-bozkurt-kayseri-mantisi-geleneksel','ramazan-bozkurt-kayseri-mantisi-paket','ramazan-bozkurt-kayseri-aile-manti-banner']
        : ['ramazan-bozkurt-kayseri-pastirmasi-premium-sunum','ramazan-bozkurt-kayseri-sucugu-premium-sunum','ramazan-bozkurt-kayseri-geleneksel-lezzet-banner'];
    $fallback_image = rb_media_first($fallback_slugs);
?>
<article <?php post_class('rb-article rb-single-article'); ?>>
  <header class="rb-single-hero">
    <div class="rb-single-hero__inner">
      <div class="rb-single-copy">
        <span class="rb-single-kicker"><?php echo $primary_cat ? esc_html($primary_cat->name) : 'Blog'; ?></span>
        <h1 class="rb-single-title"><?php the_title(); ?></h1>
        <?php if (has_excerpt()) : ?><p class="rb-single-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
        <div class="rb-single-date"><?php echo esc_html(get_the_date('d F Y')); ?></div>
      </div>
      <div class="rb-single-cover">
        <?php if (has_post_thumbnail()) : ?>
          <?php the_post_thumbnail('full', ['loading'=>'eager','fetchpriority'=>'high']); ?>
        <?php elseif ($fallback_image) : ?>
          <img src="<?php echo esc_url($fallback_image); ?>" alt="<?php the_title_attribute(); ?>" fetchpriority="high">
        <?php endif; ?>
      </div>
    </div>
  </header>

  <div class="rb-single-body">
    <div class="rb-single-content"><?php the_content(); ?></div>
    <aside class="rb-single-aside">
      <strong>Ramazan Bozkurt</strong>
      <span>Kayseri pastırması, sucuk, kavurma ve mantı seçeneklerini inceleyin.</span>
      <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/urunler')); ?>">Online Mağaza →</a>
      <a href="<?php echo esc_url(home_url('/toptan-satis')); ?>">Kurumsal Tedarik →</a>
    </aside>
  </div>
</article>

<section class="rb-article-more">
  <div class="rb-container">
    <div class="rb-section__head"><span class="rb-eyebrow">Okumaya devam edin</span><h2><?php echo $is_recipe ? 'Diğer tarifler' : 'Diğer yazılar'; ?></h2></div>
    <div class="rb-blog-grid rb-blog-grid--compact">
      <?php
      $query_args = [
          'post_type'=>'post','post_status'=>'publish','posts_per_page'=>3,
          'post__not_in'=>[$current_id],'orderby'=>'date','order'=>'DESC','ignore_sticky_posts'=>true,
      ];
      if ($primary_cat) { $query_args['cat'] = (int) $primary_cat->term_id; }
      $more = new WP_Query($query_args);
      if ($more->have_posts()) :
          while ($more->have_posts()) : $more->the_post();
              $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
              if (!$thumb) {
                  $related_cats = get_the_category();
                  $related_recipe = $related_cats && $related_cats[0]->slug === 'yemek-tarifleri';
                  $thumb = rb_media_first($related_recipe
                      ? ['ramazan-bozkurt-kayseri-mantisi-paket','ramazan-bozkurt-kayseri-mantisi-el-yapimi','ramazan-bozkurt-kayseri-aile-manti-banner']
                      : ['ramazan-bozkurt-kayseri-pastirmasi-dilimli-sunum','ramazan-bozkurt-kayseri-sucugu-premium-sunum','ramazan-bozkurt-kayseri-geleneksel-lezzet-banner']);
              }
      ?>
        <article class="rb-blog-card">
          <a class="rb-blog-card__media" href="<?php the_permalink(); ?>"><?php if ($thumb) : ?><img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy"><?php endif; ?></a>
          <div class="rb-blog-card__body">
            <div class="rb-blog-card__meta"><?php echo esc_html(get_the_date('d F Y')); ?></div>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 18, '…')); ?></p>
            <a class="rb-text-link" href="<?php the_permalink(); ?>">Oku →</a>
          </div>
        </article>
      <?php endwhile; else : ?>
        <p class="rb-article-more__empty">Bu bölümde henüz başka içerik bulunmuyor.</p>
      <?php endif; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endwhile; get_footer(); ?>
