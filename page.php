<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
<section class="rb-page-hero">
  <div class="rb-container">
    <div class="rb-eyebrow">Ramazan Bozkurt Et Ürünleri</div>
    <h1><?php the_title(); ?></h1>
    <?php if (has_excerpt()) : ?><p><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
  </div>
</section>
<section class="rb-page-copy">
  <div class="rb-container">
    <article <?php post_class('rb-entry'); ?>>
      <?php the_content(); ?>
    </article>
  </div>
</section>
<?php endwhile; ?>
<?php get_footer(); ?>
