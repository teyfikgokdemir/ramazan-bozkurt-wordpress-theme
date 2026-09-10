<?php get_header(); ?>
<section class="rb-section">
  <div class="rb-container">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <article <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </article>
    <?php endwhile; else : ?>
      <p>İçerik bulunamadı.</p>
    <?php endif; ?>
  </div>
</section>
<?php get_footer(); ?>
