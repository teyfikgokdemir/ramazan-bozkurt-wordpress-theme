<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
<article>
  <header class="rb-page-hero">
    <div class="rb-container">
      <div class="rb-eyebrow">Ramazan Bozkurt • Kayseri Lezzet Rehberi</div>
      <h1><?php the_title(); ?></h1>
      <p><?php echo esc_html(get_the_date('d.m.Y')); ?></p>
    </div>
  </header>
  <div class="rb-page-copy">
    <div class="rb-container">
      <?php if (has_post_thumbnail()) : ?><div class="rb-article-cover"><?php the_post_thumbnail('large'); ?></div><?php endif; ?>
      <?php the_content(); ?>
    </div>
  </div>
</article>
<?php endwhile; ?>
<?php get_footer(); ?>
