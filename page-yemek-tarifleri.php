<?php get_header(); ?>
<?php
$cat = get_category_by_slug('yemek-tarifleri');
$query = new WP_Query([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'cat' => $cat ? (int) $cat->term_id : 0,
]);
?>
<section class="rb-blog-hero"><div class="rb-container rb-blog-hero__inner"><div><span class="rb-eyebrow">Yemek Tarifleri</span><h1>Kayseri lezzetleriyle sofraya yakışan tarifler.</h1></div><p>Pastırma, sucuk, kavurma ve mantıyı evde daha iyi değerlendirmek için pratik ve anlaşılır tarifler.</p></div></section>
<section class="rb-blog-archive"><div class="rb-container"><div class="rb-blog-grid">
<?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
<?php
try {
    $post_id = get_the_ID();
    $post_obj = get_post($post_id);
    $title = $post_obj ? $post_obj->post_title : get_the_title();
    $permalink = get_permalink($post_id);
    $raw_excerpt = $post_obj ? trim((string) $post_obj->post_excerpt) : '';
    if ($raw_excerpt === '' && $post_obj) {
        $raw_excerpt = wp_trim_words(wp_strip_all_tags(strip_shortcodes($post_obj->post_content)), 24, '…');
    }
    $thumb = get_the_post_thumbnail_url($post_id, 'large');
    if (!$thumb) {
        $thumb = rb_media_first(['ramazan-bozkurt-kayseri-aile-manti-banner','ramazan-bozkurt-kayseri-mantisi-geleneksel','ramazan-bozkurt-kayseri-pastirma-sucuk-manti-kavurma']);
    }
?>
<article class="rb-blog-card">
<a class="rb-blog-card__media" href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($title); ?>"><?php if ($thumb) : ?><img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy"><?php endif; ?></a>
<div class="rb-blog-card__body"><div class="rb-blog-card__meta">Yemek Tarifi</div><h2><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h2><p><?php echo esc_html($raw_excerpt); ?></p><a class="rb-text-link" href="<?php echo esc_url($permalink); ?>">Tarifi Aç →</a></div>
</article>
<?php } catch (Throwable $e) { error_log('RB recipe archive card error for post '.get_the_ID().': '.$e->getMessage()); } ?>
<?php endwhile; wp_reset_postdata(); else : ?><p>Henüz yayınlanmış yemek tarifi bulunmuyor.</p><?php endif; ?>
</div></div></section>
<?php get_footer(); ?>
