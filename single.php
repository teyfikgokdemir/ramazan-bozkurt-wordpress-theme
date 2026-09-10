<?php
get_header();
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
<article <?php post_class('rb-article'); ?>>
  <header class="rb-article__hero">
    <div class="rb-container rb-article__hero-inner">
      <div class="rb-article__intro">
        <span class="rb-eyebrow"><?php echo $primary_cat ? esc_html($primary_cat->name) : 'Blog'; ?></span>
        <h1><?php the_title(); ?></h1>
        <?php if (has_excerpt()) : ?><p><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
        <div class="rb-article__meta"><?php echo esc_html(get_the_date('d F Y')); ?></div>
      </div>
      <div class="rb-article__cover">
        <?php if (has_post_thumbnail()) : ?>
          <?php the_post_thumbnail('full', ['loading'=>'eager','fetchpriority'=>'high']); ?>
        <?php elseif ($fallback_image) : ?>
          <img src="<?php echo esc_url($fallback_image); ?>" alt="<?php the_title_attribute(); ?>" fetchpriority="high">
        <?php endif; ?>
      </div>
    </div>
  </header>

  <div class="rb-container rb-article__layout">
    <div class="rb-article__content"><?php the_content(); ?></div>
    <aside class="rb-article__aside">
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
          'post_type'=>'post',
          'post_status'=>'publish',
          'posts_per_page'=>3,
          'post__not_in'=>[$current_id],
          'orderby'=>'date',
          'order'=>'DESC',
          'ignore_sticky_posts'=>true,
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
          <a class="rb-blog-card__media" href="<?php the_permalink(); ?>">
            <?php if ($thumb) : ?><img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy"><?php endif; ?>
          </a>
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
