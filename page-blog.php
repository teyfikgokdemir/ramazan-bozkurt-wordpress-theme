<?php get_header(); ?>
<?php
$cat = get_category_by_slug('rehber');
$query = new WP_Query([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'cat' => $cat ? (int) $cat->term_id : 0,
]);
?>
<section class="rb-blog-hero"><div class="rb-container rb-blog-hero__inner"><div><span class="rb-eyebrow">Blog & Rehber</span><h1>Kayseri lezzetleri hakkında detaylı rehberler.</h1></div><p>Pastırma, sucuk, kavurma ve mantı hakkında saklama, servis, pişirme ve kullanım önerileri.</p></div></section>
<section class="rb-blog-archive"><div class="rb-container"><div class="rb-blog-grid">
<?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
<article <?php post_class('rb-blog-card'); ?>>
<a class="rb-blog-card__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>"><?php if (has_post_thumbnail()) { the_post_thumbnail('large', ['loading'=>'lazy']); } else { $fallback=rb_media_first(['ramazan-bozkurt-kayseri-geleneksel-lezzet-banner','ramazan-bozkurt-kayseri-pastirma-sucuk-manti-kavurma']); if($fallback){ echo '<img src="'.esc_url($fallback).'" alt="'.esc_attr(get_the_title()).'" loading="lazy">'; } } ?></a>
<div class="rb-blog-card__body"><div class="rb-blog-card__meta">Rehber</div><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html(get_the_excerpt()); ?></p><a class="rb-text-link" href="<?php the_permalink(); ?>">Yazıyı Oku →</a></div>
</article>
<?php endwhile; wp_reset_postdata(); else : ?><p>Henüz yayınlanmış blog yazısı bulunmuyor.</p><?php endif; ?>
</div></div></section>
<?php get_footer(); ?>
