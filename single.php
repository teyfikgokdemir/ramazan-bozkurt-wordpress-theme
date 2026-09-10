<?php
get_header();
while (have_posts()) : the_post();
?>
<article <?php post_class('rb-article'); ?>>
  <header class="rb-article__hero">
    <div class="rb-container rb-article__hero-inner">
      <div class="rb-article__intro">
        <span class="rb-eyebrow"><?php $cats=get_the_category(); echo $cats ? esc_html($cats[0]->name) : 'Blog'; ?></span>
        <h1><?php the_title(); ?></h1>
        <?php if (has_excerpt()) : ?><p><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
        <div class="rb-article__meta"><?php echo esc_html(get_the_date('d F Y')); ?></div>
      </div>
      <?php if (has_post_thumbnail()) : ?><div class="rb-article__cover"><?php the_post_thumbnail('full'); ?></div><?php endif; ?>
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
    <div class="rb-section__head"><span class="rb-eyebrow">Okumaya devam edin</span><h2>Diğer yazılar</h2></div>
    <div class="rb-blog-grid rb-blog-grid--compact">
      <?php
      $more = new WP_Query(['post_type'=>'post','post_status'=>'publish','posts_per_page'=>3,'post__not_in'=>[get_the_ID()],'orderby'=>'date','order'=>'DESC']);
      while ($more->have_posts()) : $more->the_post(); ?>
        <article class="rb-blog-card">
          <a class="rb-blog-card__media" href="<?php the_permalink(); ?>"><?php if (has_post_thumbnail()) the_post_thumbnail('medium_large', ['loading'=>'lazy']); ?></a>
          <div class="rb-blog-card__body"><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><a class="rb-text-link" href="<?php the_permalink(); ?>">Oku →</a></div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endwhile; get_footer(); ?>
