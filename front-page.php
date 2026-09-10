<?php
get_header();

if (is_page() && (int) get_option('page_on_front') === (int) get_queried_object_id()) {
    $content = trim((string) get_post_field('post_content', get_queried_object_id()));
    if ($content !== '') {
        echo '<div class="rb-editable-home">';
        while (have_posts()) { the_post(); the_content(); }
        echo '</div>';
        get_footer();
        return;
    }
}

$hero = rb_media_first(['ramazan-bozkurt-kayseri-aile-sofrasi-banner','ramazan-bozkurt-kayseri-geleneksel-lezzet-banner']);
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/urunler');
$wholesale_url = home_url('/toptan-satis');
$categories = [
  ['title'=>'Pastırma','slug'=>'pastirma','text'=>'250 g, 500 g ve 1 kg gramaj seçenekleriyle Kayseri pastırması.','image'=>rb_media_first(['ramazan-bozkurt-kayseri-pastirmasi-premium-sunum','ramazan-bozkurt-kayseri-pastirmasi-dilimli-sunum'])],
  ['title'=>'Sucuk','slug'=>'sucuk','text'=>'250 g, 500 g ve 1 kg seçenekleriyle Kayseri sucuğu.','image'=>rb_media_first(['ramazan-bozkurt-kayseri-sucugu-premium-sunum','ramazan-bozkurt-kayseri-sucugu-urun-gorseli'])],
  ['title'=>'Kavurma','slug'=>'kavurma','text'=>'250 g, 500 g ve 1 kg gramaj seçenekleriyle kavurma.','image'=>rb_media_first(['ramazan-bozkurt-kayseri-kavurma-premium-sunum','ramazan-bozkurt-kayseri-kavurma-urun-sunumu'])],
  ['title'=>'Mantı','slug'=>'manti','text'=>'250 g, 500 g ve 1 kg seçenekleriyle Kayseri mantısı.','image'=>rb_media_first(['ramazan-bozkurt-kayseri-mantisi-geleneksel','ramazan-bozkurt-kayseri-mantisi-paket'])],
];
?>
<section class="rb-hero<?php echo $hero ? ' has-media' : ''; ?>" aria-label="Ramazan Bozkurt Et ve Et Mamulleri">
  <?php if ($hero) : ?><div class="rb-hero__media"><img src="<?php echo esc_url($hero); ?>" alt="Ramazan Bozkurt Et ve Et Mamulleri Kayseri pastırma sucuk kavurma ve mantı" fetchpriority="high"></div><?php endif; ?>
  <div class="rb-hero__overlay"></div>
  <div class="rb-container rb-hero__content">
    <p>Kayseri pastırması, sucuk, kavurma ve mantı. Perakende satışın yanında işletmeler, zincir marketler ve profesyonel alıcılar için B2B tedarik görüşmeleri.</p>
    <div class="rb-actions"><a class="rb-btn rb-btn--primary" href="#urunler">Ürünleri İncele</a><a class="rb-btn rb-btn--ghost" href="<?php echo esc_url($wholesale_url); ?>">Kurumsal Teklif Al</a></div>
  </div>
</section>

<section class="rb-trust-strip" aria-label="Alışveriş ve kurumsal satış avantajları"><div class="rb-container rb-trust-strip__grid"><div><strong>4.000 TL ve üzeri</strong><span>Ücretsiz kargo</span></div><div><strong>4.000 TL altı</strong><span>180 TL kargo</span></div><div><strong>250 g • 500 g • 1 kg</strong><span>Gramaj seçenekleri</span></div><div><strong>B2B & Kurumsal</strong><span>Toptan tedarik görüşmesi</span></div></div></section>

<section id="urunler" class="rb-section rb-section--surface rb-home-products"><div class="rb-container">
  <div class="rb-section__head rb-section__head--split"><div><div class="rb-eyebrow">Doğrudan Ürünlere Ulaşın</div><h1>Kayseri’nin geleneksel lezzetlerini gramajını seçerek sipariş edin.</h1></div><p>Pastırma, sucuk, kavurma ve mantıda 250 g, 500 g ve 1 kg seçenekleri. Ürün sayfasından gramaj seçip doğrudan sepete ekleyebilirsiniz.</p></div>
  <div class="rb-grid rb-product-grid"><?php foreach($categories as $item): ?><article class="rb-card rb-product-card"><a href="<?php echo esc_url(rb_product_category_url($item['slug'])); ?>"><?php if($item['image']): ?><div class="rb-card__media"><img src="<?php echo esc_url($item['image']); ?>" alt="Ramazan Bozkurt <?php echo esc_attr($item['title']); ?>" loading="lazy"></div><?php endif; ?><div class="rb-card__body"><h2><?php echo esc_html($item['title']); ?></h2><p><?php echo esc_html($item['text']); ?></p><span class="rb-card__link">Seçenekleri ve fiyatları gör <span>→</span></span></div></a></article><?php endforeach; ?></div>
</div></section>

<?php if(class_exists('WooCommerce')): ?>
<section class="rb-section rb-section--featured rb-home-shop"><div class="rb-container">
  <div class="rb-section__head rb-section__head--split"><div><div class="rb-eyebrow">Online Mağaza</div><h2>Ürünler ve güncel fiyatlar</h2></div><p>Gramaj seçeneğinizi ürün detayında belirleyin. 4.000 TL’ye ulaştığınızda kargo otomatik olarak ücretsiz olur.</p></div>
  <?php echo do_shortcode('[products limit="4" columns="4" orderby="menu_order" order="ASC"]'); ?>
  <div class="rb-home-shop__action"><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($shop_url); ?>">Tüm Ürünleri Gör</a></div>
</div></section>
<?php endif; ?>

<section class="rb-b2b" aria-labelledby="rb-b2b-title"><div class="rb-container">
  <div class="rb-b2b__head"><div><div class="rb-eyebrow">B2B • Kurumsal Tedarik</div><h2 id="rb-b2b-title">Zincir marketten horeca kanalına, profesyonel alıma uygun tedarik görüşmesi.</h2></div><p>Ramazan Bozkurt Et ve Et Mamulleri, perakendenin yanında toptan ticaret odağıyla kurumsal talepleri de karşılamaya yönelik bir satış yapısı kurar. Ürün, gramaj, sipariş hacmi ve teslimat planı üzerinden işletmeye özel teklif hazırlanır.</p></div>
  <div class="rb-b2b__grid">
    <article><span>01</span><h3>Zincir ve yerel marketler</h3><p>Raf planı, ürün grubu, gramaj ve sipariş hacmine göre tedarik görüşmesi ve teklif süreci.</p></article>
    <article><span>02</span><h3>Şarküteri & gurme satış noktaları</h3><p>Pastırma, sucuk, kavurma ve mantı için düzenli alım ve ürün bazlı toplu sipariş değerlendirmesi.</p></article>
    <article><span>03</span><h3>HORECA</h3><p>Restoran, otel, kafe ve profesyonel mutfaklar için ihtiyaca göre ürün ve miktar planlaması.</p></article>
    <article><span>04</span><h3>Bayi & kurumsal alım</h3><p>Düzenli veya yüksek hacimli siparişlerde ticari koşulların doğrudan görüşüldüğü B2B teklif akışı.</p></article>
  </div>
  <div class="rb-b2b__footer"><div><strong>Kurumsal satın alma mı yapıyorsunuz?</strong><span>İhtiyacınız olan ürünleri, tahmini miktarı ve teslimat şehrini paylaşın.</span></div><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($wholesale_url); ?>">B2B Teklif Talebi</a></div>
</div></section>

<section class="rb-wholesale-home" aria-labelledby="rb-wholesale-title"><div class="rb-container rb-wholesale-home__grid"><div class="rb-wholesale-home__copy"><div class="rb-eyebrow">Toptan Satış</div><h2 id="rb-wholesale-title">Düzenli ve yüksek hacimli alımlarda özel fiyat alın.</h2><p>Zincir market, yerel market, şarküteri, restoran, otel, kafe ve diğer işletmeler için pastırma, sucuk, kavurma ve mantıda sipariş miktarına göre teklif hazırlıyoruz.</p><div class="rb-wholesale-points"><span>B2B tekliflendirme</span><span>Düzenli tedarik görüşmesi</span><span>Kurumsal satın alma desteği</span><span>Toplu siparişe özel fiyat</span></div><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($wholesale_url); ?>">Kurumsal Teklif İste</a></div><div class="rb-wholesale-home__visual"><?php $wholesale_visual=rb_media_first(['ramazan-bozkurt-kayseri-pastirma-sucuk-manti-kavurma','ramazan-bozkurt-kayseri-geleneksel-lezzet-banner']); if($wholesale_visual): ?><img src="<?php echo esc_url($wholesale_visual); ?>" alt="Ramazan Bozkurt kurumsal ve toptan pastırma sucuk kavurma ve mantı tedariki" loading="lazy"><?php endif; ?></div></div></section>

<section class="rb-proof"><div class="rb-container"><div class="rb-proof-grid"><article><span>01</span><h3>Gramajını seç</h3><p>Her ana ürün grubunda 250 g, 500 g ve 1 kg seçeneklerinden ihtiyacınıza uygun olanı seçin.</p></article><article><span>02</span><h3>Sepetini tamamla</h3><p>Sepetiniz 4.000 TL’nin altındaysa 180 TL kargo uygulanır; eksik tutar dinamik olarak gösterilir.</p></article><article><span>03</span><h3>Toptan alımda teklif iste</h3><p>Düzenli veya yüksek adetli alımlarda ürün sayfaları ve Toptan Satış sayfasından doğrudan teklif isteyin.</p></article></div></div></section>

<section class="rb-cta rb-cta--wholesale"><div class="rb-container rb-cta__inner"><div><div class="rb-eyebrow">Perakende + B2B</div><h2>Tek mağazada bireysel sipariş; ayrı kanalda kurumsal tedarik.</h2></div><div class="rb-actions"><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($shop_url); ?>">Mağazaya Git</a><a class="rb-btn rb-btn--ghost" href="<?php echo esc_url($wholesale_url); ?>">B2B Teklif Al</a></div></div></section>
<?php get_footer(); ?>