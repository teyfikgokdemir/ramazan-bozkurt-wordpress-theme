<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
<?php
$visual = rb_media_first([
    'ramazan-bozkurt-kayseri-pastirma-sucuk-manti-kavurma',
    'ramazan-bozkurt-kayseri-geleneksel-lezzet-banner',
    'ramazan-bozkurt-kayseri-aile-manti-banner'
]);
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/urunler');
$wa_number = function_exists('rb_theme_setting') ? rb_theme_setting('whatsapp', '905398248295') : '905398248295';
$b2b_title = function_exists('rb_theme_setting') ? rb_theme_setting('b2b_title', 'Kurumsal Tedarik') : 'Kurumsal Tedarik';
$b2b_message = function_exists('rb_theme_setting') ? rb_theme_setting('b2b_message', 'Zincir market, şarküteri, HORECA ve düzenli alım yapan işletmeler için doğrudan teklif süreci.') : 'Zincir market, şarküteri, HORECA ve düzenli alım yapan işletmeler için doğrudan teklif süreci.';
$whatsapp = 'https://wa.me/' . rawurlencode($wa_number) . '?text=' . rawurlencode('Merhaba, Ramazan Bozkurt Et ve Et Mamulleri web sitesinden ulaşıyorum. Kurumsal tedarik ve toptan alım için teklif almak istiyorum.');
?>
<section class="rb-b2b-hero">
  <div class="rb-b2b-hero__media"><?php if ($visual) : ?><img src="<?php echo esc_url($visual); ?>" alt="Ramazan Bozkurt kurumsal tedarik ve toptan satış" fetchpriority="high"><?php endif; ?></div>
  <div class="rb-b2b-hero__veil"></div>
  <div class="rb-container rb-b2b-hero__content">
    <div class="rb-eyebrow">B2B • <?php echo esc_html($b2b_title); ?> • Kayseri</div>
    <h1>Düzenli alım yapan işletmelere güçlü ve doğrudan tedarik kanalı.</h1>
    <p><?php echo esc_html($b2b_message); ?></p>
    <div class="rb-actions"><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($whatsapp); ?>" target="_blank" rel="noopener noreferrer">WhatsApp’tan Teklif Al</a><a class="rb-btn rb-btn--ghost" href="#tedarik-modeli">Tedarik Modelini İncele</a></div>
    <div class="rb-b2b-hero__facts"><span><strong>4 ana ürün grubu</strong>Pastırma • Sucuk • Kavurma • Mantı</span><span><strong>Hacme göre teklif</strong>Düzenli ve toplu siparişlerde ticari görüşme</span><span><strong>Kayseri merkezli</strong>Türkiye geneli kurumsal tedarik görüşmesi</span></div>
  </div>
</section>

<section id="tedarik-modeli" class="rb-b2b-page-section"><div class="rb-container">
  <div class="rb-b2b-page-head"><div><div class="rb-eyebrow">Tedarik Modeli</div><h2>İşletme tipine göre tek bir satış dili değil, doğru ticari akış.</h2></div><p>Kurumsal alımda fiyat kadar sipariş sıklığı, ürün karması, gramaj, teslimat planı ve operasyon düzeni önemlidir. Teklif sürecini bu bilgiler üzerinden netleştiriyoruz.</p></div>
  <div class="rb-b2b-page-grid rb-b2b-page-grid--3">
    <article><span>Market & Şarküteri</span><h3>Raf düzenine uygun ürün planı</h3><p>Yerel ve zincir marketler ile şarküteriler için ürün grubu, satış hacmi ve tekrar eden sipariş yapısına göre tedarik görüşmesi.</p></article>
    <article><span>HORECA</span><h3>Profesyonel mutfak tedariki</h3><p>Restoran, otel, kafe ve toplu tüketim noktalarında düzenli kullanım için ürün ve miktar planlaması.</p></article>
    <article><span>Kurumsal & Bayi</span><h3>Yüksek hacimli ticari alım</h3><p>Tekrar eden veya yüksek miktarlı siparişlerde doğrudan tekliflendirme ve teslimat planı üzerinden ticari süreç.</p></article>
  </div>
</div></section>

<section class="rb-b2b-page-section rb-b2b-page-section--dark"><div class="rb-container rb-b2b-page-split">
  <div><div class="rb-eyebrow">Ürün Portföyü</div><h2>Perakende ürün seçkisi, kurumsal alım için ayrı görüşme.</h2><p>Pastırmada sırt, antrikot, tütünlük ve çemensiz seçenekleri; sucuk, kavurma ve Kayseri mantısı ile birlikte kurumsal taleplere göre değerlendirilir.</p><a class="rb-btn" href="<?php echo esc_url($shop_url); ?>">Perakende Ürünleri İncele</a></div>
  <div class="rb-b2b-product-list"><span>Sırt Pastırma</span><span>Antrikot Pastırma</span><span>Tütünlük Pastırma</span><span>Çemensiz Pastırma</span><span>Kayseri Sucuğu</span><span>Kayseri Kavurması</span><span>Kayseri Mantısı</span></div>
</div></section>

<section class="rb-b2b-page-section"><div class="rb-container">
  <div class="rb-b2b-page-head"><div><div class="rb-eyebrow">Nasıl İlerliyoruz?</div><h2>Teklif sürecini kısa ve net tutuyoruz.</h2></div></div>
  <div class="rb-b2b-page-grid rb-b2b-page-grid--3">
    <article><h3>İhtiyacınızı paylaşın</h3><p>Ürün grubu, yaklaşık miktar, sipariş sıklığı ve teslimat şehrini iletin.</p></article>
    <article><h3>Tedarik yapısını netleştirelim</h3><p>Gramaj, ürün karması ve teslimat planı üzerinden uygun ticari çerçeveyi belirleyelim.</p></article>
    <article><h3>Teklif ve operasyon</h3><p>Uygunluk durumuna göre fiyat, sipariş planı ve sevkiyat detaylarını doğrudan görüşelim.</p></article>
  </div>
</div></section>

<section class="rb-b2b-cta"><div class="rb-container rb-b2b-cta__inner"><div><div class="rb-eyebrow">Kurumsal Satın Alma</div><h2>Ürün ve yaklaşık miktarı yazmanız teklif görüşmesini başlatmak için yeterli.</h2><p>WhatsApp üzerinden doğrudan satış ekibine ulaşın.</p></div><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($whatsapp); ?>" target="_blank" rel="noopener noreferrer">Kurumsal Teklif İste</a></div></section>

<?php $editor_content = trim((string) get_post_field('post_content', get_the_ID())); if ($editor_content !== '') : ?>
<section class="rb-b2b-editor-content"><div class="rb-container"><?php the_content(); ?></div></section>
<?php endif; ?>
<?php endwhile; ?>
<?php get_footer(); ?>
