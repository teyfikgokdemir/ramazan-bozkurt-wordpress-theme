<?php
get_header();
$hero = rb_get_media_url_by_slug('ramazan-bozkurt-kayseri-geleneksel-lezzet-banner');
?>
<section class="rb-hero">
  <?php if ($hero) : ?>
    <div class="rb-hero__media"><img src="<?php echo esc_url($hero); ?>" alt="Ramazan Bozkurt Et Ürünleri Kayseri geleneksel lezzetleri"></div>
  <?php endif; ?>
  <div class="rb-hero__overlay"></div>
  <div class="rb-container rb-hero__content">
    <div class="rb-eyebrow">Kayseri • Geleneksel Üretim</div>
    <h1>Kayseri’nin Geleneksel Lezzetleri Sofralarınızda</h1>
    <p>Pastırma, sucuk, kavurma ve mantıda seçkin ürünler. Perakende ve toptan satış.</p>
    <div class="rb-actions">
      <a class="rb-btn rb-btn--primary" href="<?php echo esc_url(home_url('/urunler')); ?>">Ürünleri İncele</a>
      <a class="rb-btn rb-btn--ghost" href="<?php echo esc_url(home_url('/iletisim')); ?>">Toptan Satış</a>
    </div>
  </div>
</section>

<section class="rb-section rb-section--surface">
  <div class="rb-container">
    <div class="rb-section__head">
      <div class="rb-eyebrow">Ramazan Bozkurt Et Ürünleri</div>
      <h2>Kayseri’nin dört temel lezzeti</h2>
      <p>Geleneksel üretim anlayışını modern sunum ve güvenilir ürün standardıyla buluşturuyoruz.</p>
    </div>
    <div class="rb-grid">
      <article class="rb-card"><div class="rb-card__body"><h3>Pastırma</h3><p>Kayseri geleneğini taşıyan seçkin pastırma çeşitleri.</p></div></article>
      <article class="rb-card"><div class="rb-card__body"><h3>Sucuk</h3><p>Yoğun aromalı, geleneksel Kayseri sucukları.</p></div></article>
      <article class="rb-card"><div class="rb-card__body"><h3>Kavurma</h3><p>Özenle hazırlanan, dengeli dokulu kavurma çeşitleri.</p></div></article>
      <article class="rb-card"><div class="rb-card__body"><h3>Mantı</h3><p>Kayseri mutfağının vazgeçilmez geleneksel mantısı.</p></div></article>
    </div>
  </div>
</section>

<?php get_footer(); ?>
