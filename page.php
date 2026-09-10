<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
<section class="rb-page-hero">
  <div class="rb-container">
    <div class="rb-eyebrow">Ramazan Bozkurt Et Ürünleri</div>
    <h1><?php the_title(); ?></h1>
  </div>
</section>
<section class="rb-page-copy">
  <div class="rb-container">
    <?php the_content(); ?>
  </div>
</section>
<?php endwhile; ?>
<?php get_footer(); ?>
