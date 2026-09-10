<?php
get_header();
$hero = rb_media('ramazan-bozkurt-kayseri-geleneksel-lezzet-banner');
$products = [
  [
    'title' => 'Pastırma',
    'text'  => 'Kayseri geleneğini taşıyan seçkin pastırma çeşitleri.',
    'image' => rb_media('ramazan-bozkurt-kayseri-pastirmasi-premium-sunum'),
  ],
  [
    'title' => 'Sucuk',
    'text'  => 'Yoğun aromalı, geleneksel Kayseri sucukları.',
    'image' => rb_media('ramazan-bozkurt-kayseri-sucugu-premium-sunum'),
  ],
  [
    'title' => 'Kavurma',
    'text'  => 'Özenle hazırlanan, dengeli dokulu kavurma çeşitleri.',
    'image' => rb_media('ramazan-bozkurt-kayseri-kavurma-premium-sunum'),
  ],
  [
    'title' => 'Mantı',
    'text'  => 'Kayseri mutfağının vazgeçilmez geleneksel mantısı.',
    'image' => rb_media('ramazan-bozkurt-kayseri-mantisi-geleneksel'),
  ],
];
?>
<section class="rb-hero<?php echo $hero ? ' has-media' : ''; ?>">
  <?php if ($hero) : ?>
    <div class="rb-hero__media"><img src="<?php echo esc_url($hero); ?>" alt="Ramazan Bozkurt Et Ürünleri Kayseri geleneksel lezzetleri"></div>
  <?php endif; ?>
  <div class="rb-hero__overlay"></div>
  <div class="rb-container rb-hero__content">
    <div class="rb-eyebrow">Kayseri • Geleneksel Üretim</div>
    <h1>Kayseri’nin Geleneksel Lezzetleri Sofralarınızda</h1>
    <p>Pastırma, sucuk, kavurma ve mantıda seçkin ürünler. Perakende ve toptan satış.</p>
    <div class="rb-actions">
      <a class="rb-btn rb-btn--primary" href="#urunler">Ürünleri İncele</a>
      <a class="rb-btn rb-btn--ghost" href="<?php echo esc_url(home_url('/iletisim')); ?>">Toptan Satış</a>
    </div>
  </div>
</section>

<section id="urunler" class="rb-section rb-section--surface">
  <div class="rb-container">
    <div class="rb-section__head rb-section__head--split">
      <div>
        <div class="rb-eyebrow">Ramazan Bozkurt Et Ürünleri</div>
        <h2>Kayseri’nin dört temel lezzeti</h2>
      </div>
      <p>Geleneksel üretim anlayışını modern sunum ve güvenilir ürün standardıyla buluşturuyoruz.</p>
    </div>
    <div class="rb-grid rb-product-grid">
      <?php foreach ($products as $product) : ?>
        <article class="rb-card rb-product-card">
          <?php if ($product['image']) : ?>
            <div class="rb-card__media"><img src="<?php echo esc_url($product['image']); ?>" alt="Ramazan Bozkurt <?php echo esc_attr($product['title']); ?>"></div>
          <?php endif; ?>
          <div class="rb-card__body">
            <h3><?php echo esc_html($product['title']); ?></h3>
            <p><?php echo esc_html($product['text']); ?></p>
            <a class="rb-card__link" href="<?php echo esc_url(home_url('/urunler')); ?>">Ürünü keşfet <span>→</span></a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="rb-story">
  <div class="rb-container rb-story__inner">
    <div class="rb-story__copy">
      <div class="rb-eyebrow">Kayseri’den sofralarınıza</div>
      <h2>Bir ürün değil, bir memleket geleneği.</h2>
      <p>Pastırmadan sucuğa, kavurmadan mantıya uzanan bu mutfak; ustalık, sabır ve doğru ham maddeyle anlam kazanır.</p>
      <a class="rb-text-link" href="<?php echo esc_url(home_url('/hakkimizda')); ?>">Hikâyemizi keşfedin →</a>
    </div>
    <div class="rb-story__visual">
      <?php $story = rb_media('ramazan-bozkurt-kayseri-aile-manti-banner'); ?>
      <?php if ($story) : ?><img src="<?php echo esc_url($story); ?>" alt="Ramazan Bozkurt Kayseri aile sofrası"><?php endif; ?>
    </div>
  </div>
</section>

<section class="rb-cta">
  <div class="rb-container rb-cta__inner">
    <div>
      <div class="rb-eyebrow">Perakende & Toptan</div>
      <h2>Kayseri lezzetlerini sofranıza taşıyalım.</h2>
    </div>
    <a class="rb-btn rb-btn--primary" href="<?php echo esc_url(home_url('/iletisim')); ?>">İletişime Geç</a>
  </div>
</section>

<?php get_footer(); ?>
