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
$b2b_visual = rb_media_first(['ramazan-bozkurt-kayseri-geleneksel-lezzet-banner','ramazan-bozkurt-kayseri-aile-manti-banner']);
?>
<section class="rb-hero<?php echo $hero ? ' has-media' : ''; ?>" aria-label="Ramazan Bozkurt Et ve Et Mamulleri">
  <?php if ($hero) : ?><div class="rb-hero__media"><img src="<?php echo esc_url($hero); ?>" alt="Ramazan Bozkurt Et ve Et Mamulleri Kayseri pastırma sucuk kavurma ve mantı" fetchpriority="high"></div><?php endif; ?>
  <div class="rb-hero__overlay"></div>
  <div class="rb-container rb-hero__content rb-hero__content--premium">
    <div class="rb-hero-promo">
      <span class="rb-hero-promo__eyebrow">Kayseri’den sofranıza</span>
      <strong>Pastırma • Sucuk • Kavurma • Mantı</strong>
      <span class="rb-hero-promo__sub">Perakende sipariş ve kurumsal tedarik</span>
      <div class="rb-actions"><a class="rb-btn rb-btn--primary" href="#urunler">Ürünleri İncele</a><a class="rb-btn rb-btn--ghost" href="<?php echo esc_url($wholesale_url); ?>">Kurumsal Teklif</a></div>
    </div>
  </div>
</section>

<section class="rb-ticker" aria-label="Alışveriş ve kurumsal satış avantajları">
  <div class="rb-ticker__track">
    <?php for($i=0;$i<2;$i++): ?><div class="rb-ticker__group" aria-hidden="<?php echo $i ? 'true' : 'false'; ?>">
      <span>4.000 TL ve üzeri ücretsiz kargo</span><i>◆</i>
      <span>250 g • 500 g • 1 kg seçenekleri</span><i>◆</i>
      <span>Kayseri’den Türkiye geneli gönderim</span><i>◆</i>
      <span>B2B & kurumsal tedarik</span><i>◆</i>
      <span>WhatsApp sipariş ve ürün desteği</span><i>◆</i>
    </div><?php endfor; ?>
  </div>
</section>

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

<section class="rb-b2b-premium" aria-labelledby="rb-b2b-title">
  <div class="rb-container">
    <div class="rb-b2b-premium__intro">
      <div><div class="rb-eyebrow">Kurumsal Tedarik</div><h2 id="rb-b2b-title">Profesyonel satın alma için net, düzenli ve ölçeklenebilir tedarik.</h2></div>
      <p>Perakende satışın yanında, düzenli ve hacimli alım yapan işletmeler için ürün grubu, gramaj, sipariş hacmi ve teslimat planı üzerinden doğrudan teklif süreci yürütüyoruz.</p>
    </div>
    <div class="rb-b2b-premium__stage">
      <div class="rb-b2b-premium__visual">
        <?php if($b2b_visual): ?><img src="<?php echo esc_url($b2b_visual); ?>" alt="Ramazan Bozkurt kurumsal tedarik ürün seçkisi" loading="lazy"><?php endif; ?>
        <div class="rb-b2b-premium__seal"><span>B2B</span><small>Kayseri’den kurumsal tedarik</small></div>
      </div>
      <div class="rb-b2b-premium__content">
        <div class="rb-b2b-premium__line"><span class="rb-b2b-premium__kicker">Perakende Kanalı</span><h3>Market & Şarküteri</h3><p>Raf yapısına, ürün grubuna ve sipariş hacmine göre sürdürülebilir tedarik planı.</p></div>
        <div class="rb-b2b-premium__line"><span class="rb-b2b-premium__kicker">Profesyonel Mutfak</span><h3>HORECA</h3><p>Restoran, otel ve profesyonel mutfaklar için düzenli alım ve ürün planlaması.</p></div>
        <div class="rb-b2b-premium__line"><span class="rb-b2b-premium__kicker">Ticari İş Birliği</span><h3>Kurumsal & Bayi</h3><p>Tekrar eden veya yüksek hacimli siparişlerde doğrudan ticari görüşme ve tekliflendirme.</p></div>
        <div class="rb-b2b-premium__action"><div><strong>Kurumsal satın alma talebinizi paylaşın.</strong><span>Ürün, tahmini miktar ve teslimat şehri bilgisi teklif sürecini başlatmak için yeterli.</span></div><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($wholesale_url); ?>">Kurumsal Teklif Al</a></div>
      </div>
    </div>
  </div>
</section>

<section class="rb-wholesale-home" aria-labelledby="rb-wholesale-title"><div class="rb-container rb-wholesale-home__grid"><div class="rb-wholesale-home__copy"><div class="rb-eyebrow">Toptan Satış</div><h2 id="rb-wholesale-title">Düzenli ve yüksek hacimli alımlarda özel fiyat alın.</h2><p>Zincir market, yerel market, şarküteri, restoran, otel, kafe ve diğer işletmeler için pastırma, sucuk, kavurma ve mantıda sipariş miktarına göre teklif hazırlıyoruz.</p><div class="rb-wholesale-points"><span>B2B tekliflendirme</span><span>Düzenli tedarik görüşmesi</span><span>Kurumsal satın alma desteği</span><span>Toplu siparişe özel fiyat</span></div><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($wholesale_url); ?>">Kurumsal Teklif İste</a></div><div class="rb-wholesale-home__visual"><?php $wholesale_visual=rb_media_first(['ramazan-bozkurt-kayseri-pastirma-sucuk-manti-kavurma','ramazan-bozkurt-kayseri-pastirmasi-sunum-2']); if($wholesale_visual): ?><img src="<?php echo esc_url($wholesale_visual); ?>" alt="Ramazan Bozkurt kurumsal ve toptan pastırma sucuk kavurma ve mantı tedariki" loading="lazy"><?php endif; ?></div></div></section>

<section class="rb-proof"><div class="rb-container"><div class="rb-proof-grid"><article><h3>Gramajını seç</h3><p>Her ana ürün grubunda 250 g, 500 g ve 1 kg seçeneklerinden ihtiyacınıza uygun olanı seçin.</p></article><article><h3>Sepetini tamamla</h3><p>Sepetiniz 4.000 TL’nin altındaysa 180 TL kargo uygulanır; eksik tutar dinamik olarak gösterilir.</p></article><article><h3>Toptan alımda teklif iste</h3><p>Düzenli veya yüksek adetli alımlarda ürün sayfaları ve Toptan Satış sayfasından doğrudan teklif isteyin.</p></article></div></div></section>

<section class="rb-cta rb-cta--wholesale"><div class="rb-container rb-cta__inner"><div><div class="rb-eyebrow">Perakende + B2B</div><h2>Tek mağazada bireysel sipariş; ayrı kanalda kurumsal tedarik.</h2></div><div class="rb-actions"><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($shop_url); ?>">Mağazaya Git</a><a class="rb-btn rb-btn--ghost" href="<?php echo esc_url($wholesale_url); ?>">B2B Teklif Al</a></div></div></section>
<?php get_footer(); ?>