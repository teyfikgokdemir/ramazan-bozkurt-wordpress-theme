<?php
get_header();
$hero = rb_media_first(['ramazan-bozkurt-kayseri-aile-sofrasi-banner','ramazan-bozkurt-kayseri-geleneksel-lezzet-banner']);
$products = [
  [
    'title' => 'Pastırma',
    'slug'  => 'pastirma',
    'text'  => 'Kayseri geleneğini taşıyan pastırma seçkisini; farklı sunum ve dilim alternatifleriyle keşfedin.',
    'image' => rb_media_first(['ramazan-bozkurt-kayseri-pastirmasi-premium-sunum','ramazan-bozkurt-kayseri-pastirmasi-dilimli-sunum']),
  ],
  [
    'title' => 'Sucuk',
    'slug'  => 'sucuk',
    'text'  => 'Geleneksel Kayseri sucuklarını, ürün detaylarını ve güncel satış seçeneklerini inceleyin.',
    'image' => rb_media_first(['ramazan-bozkurt-kayseri-sucugu-premium-sunum','ramazan-bozkurt-kayseri-sucugu-urun-gorseli']),
  ],
  [
    'title' => 'Kavurma',
    'slug'  => 'kavurma',
    'text'  => 'Kavurma ürün grubunu; servis, blok ve kesit sunumlarıyla yakından tanıyın.',
    'image' => rb_media_first(['ramazan-bozkurt-kayseri-kavurma-premium-sunum','ramazan-bozkurt-kayseri-kavurma-urun-sunumu']),
  ],
  [
    'title' => 'Mantı',
    'slug'  => 'manti',
    'text'  => 'Kayseri mutfağının simge lezzetlerinden mantıyı ve farklı paket seçeneklerini keşfedin.',
    'image' => rb_media_first(['ramazan-bozkurt-kayseri-mantisi-geleneksel','ramazan-bozkurt-kayseri-mantisi-paket']),
  ],
];
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/urunler');
?>
<section class="rb-hero<?php echo $hero ? ' has-media' : ''; ?>" aria-label="Ramazan Bozkurt Et Ürünleri">
  <?php if ($hero) : ?>
    <div class="rb-hero__media"><img src="<?php echo esc_url($hero); ?>" alt="Ramazan Bozkurt Et Ürünleri Kayseri aile sofrası, pastırma, sucuk, kavurma ve mantı" fetchpriority="high"></div>
  <?php endif; ?>
  <div class="rb-hero__overlay"></div>
  <div class="rb-container rb-hero__content">
    <p>Pastırma, sucuk, kavurma ve mantıda seçkin ürünler. Perakende ve toptan satış.</p>
    <div class="rb-actions">
      <a class="rb-btn rb-btn--primary" href="<?php echo esc_url($shop_url); ?>">Ürünleri İncele</a>
      <a class="rb-btn rb-btn--ghost" href="<?php echo esc_url(home_url('/iletisim')); ?>">Toptan Satış</a>
    </div>
  </div>
</section>

<section id="urunler" class="rb-section rb-section--surface">
  <div class="rb-container">
    <div class="rb-section__head rb-section__head--split">
      <div>
        <div class="rb-eyebrow">Ramazan Bozkurt Et Ürünleri</div>
        <h1>Kayseri’nin geleneksel et ürünleri ve mantı seçkisi</h1>
      </div>
      <p>Pastırma, sucuk, kavurma ve mantıyı tek marka dili altında; ürün odaklı, sade ve güven veren bir alışveriş deneyimiyle sunuyoruz.</p>
    </div>
    <div class="rb-grid rb-product-grid">
      <?php foreach ($products as $product) : ?>
        <article class="rb-card rb-product-card">
          <a href="<?php echo esc_url(rb_product_category_url($product['slug'])); ?>" aria-label="<?php echo esc_attr($product['title']); ?> ürünlerini incele">
            <?php if ($product['image']) : ?>
              <div class="rb-card__media"><img src="<?php echo esc_url($product['image']); ?>" alt="Ramazan Bozkurt <?php echo esc_attr($product['title']); ?>" loading="lazy"></div>
            <?php endif; ?>
            <div class="rb-card__body">
              <h2><?php echo esc_html($product['title']); ?></h2>
              <p><?php echo esc_html($product['text']); ?></p>
              <span class="rb-card__link">Ürünleri keşfet <span>→</span></span>
            </div>
          </a>
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
      <p>Kayseri’nin pastırma, sucuk, kavurma ve mantı kültürü; ürünün kendisinden daha geniş bir sofra hafızasını temsil eder. Ramazan Bozkurt Et Ürünleri, bu geleneği çağdaş sunum ve erişilebilir alışveriş deneyimiyle buluşturur.</p>
      <a class="rb-text-link" href="<?php echo esc_url(home_url('/hakkimizda')); ?>">Marka hikâyesini keşfedin →</a>
    </div>
    <div class="rb-story__visual">
      <?php $story = rb_media_first(['ramazan-bozkurt-kayseri-aile-manti-banner','ramazan-bozkurt-kayseri-aile-sofrasi-banner']); ?>
      <?php if ($story) : ?><img src="<?php echo esc_url($story); ?>" alt="Kayseri sofrasında mantı ve et ürünleri hazırlayan aile" loading="lazy"><?php endif; ?>
    </div>
  </div>
</section>

<section class="rb-proof" aria-labelledby="rb-proof-title">
  <div class="rb-container">
    <div class="rb-section__head rb-section__head--split">
      <div>
        <div class="rb-eyebrow">Neden Ramazan Bozkurt?</div>
        <h2 id="rb-proof-title">Lezzetin yanında güven veren bir alışveriş deneyimi.</h2>
      </div>
      <p>Ürün bilgisine hızlı erişim, sade kategori yapısı ve hem perakende hem toptan taleplere uygun iletişim akışı.</p>
    </div>
    <div class="rb-proof-grid">
      <article><span>01</span><h3>Kayseri odaklı ürün seçkisi</h3><p>Pastırma, sucuk, kavurma ve mantı aynı geleneksel mutfak ekseninde bir araya gelir.</p></article>
      <article><span>02</span><h3>Şeffaf ürün bilgisi</h3><p>Ürün sayfalarında içerik, gramaj, saklama, teslimat ve kullanım bilgileri net biçimde sunulur.</p></article>
      <article><span>03</span><h3>Perakende ve toptan erişim</h3><p>Bireysel siparişlerin yanında işletme talepleri için ayrı ve doğrudan iletişim akışı bulunur.</p></article>
    </div>
  </div>
</section>

<?php if (class_exists('WooCommerce')) : ?>
<section class="rb-section rb-section--featured">
  <div class="rb-container">
    <div class="rb-section__head rb-section__head--split">
      <div><div class="rb-eyebrow">Mağazadan seçilenler</div><h2>Öne çıkan ürünler</h2></div>
      <p>Yeni eklenen ve öne çıkarılan ürünleri doğrudan mağazadan inceleyin.</p>
    </div>
    <?php echo do_shortcode('[products limit="3" columns="3" visibility="featured"]'); ?>
  </div>
</section>
<?php endif; ?>

<section class="rb-cta">
  <div class="rb-container rb-cta__inner">
    <div>
      <div class="rb-eyebrow">Perakende & Toptan</div>
      <h2>Kayseri lezzetlerini sofranıza taşıyalım.</h2>
    </div>
    <div class="rb-actions">
      <a class="rb-btn rb-btn--primary" href="<?php echo esc_url($shop_url); ?>">Mağazaya Git</a>
      <a class="rb-btn rb-btn--ghost" href="<?php echo esc_url(home_url('/iletisim')); ?>">İletişime Geç</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
