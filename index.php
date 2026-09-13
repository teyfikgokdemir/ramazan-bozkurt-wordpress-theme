<?php get_header(); ?>
<section class="rb-section">
  <div class="rb-container">
    <?php if (is_singular()) : ?>
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
          <h1><?php the_title(); ?></h1>
          <?php the_content(); ?>
        </article>
      <?php endwhile; endif; ?>
    <?php else : ?>
      <header class="rb-section__head">
        <h1><?php echo esc_html(wp_strip_all_tags(get_the_archive_title() ?: 'İçerikler')); ?></h1>
      </header>
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <?php the_excerpt(); ?>
        </article>
      <?php endwhile; else : ?>
        <p>İçerik bulunamadı.</p>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<?php get_footer(); ?>
