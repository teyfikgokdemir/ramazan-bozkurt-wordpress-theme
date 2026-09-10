<?php
get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post();
        $content = trim((string) get_post_field('post_content', get_the_ID()));
        if ($content !== '') {
            echo '<div class="rb-editable-home">';
            the_content();
            echo '</div>';
            get_footer();
            return;
        }
    }
    rewind_posts();
}

$hero = rb_media_first(['ramazan-bozkurt-kayseri-aile-sofrasi-banner','ramazan-bozkurt-kayseri-geleneksel-lezzet-banner']);
$products = [
  ['title'=>'Pastırma','slug'=>'pastirma','text'=>'Kayseri geleneğini taşıyan pastırma seçkisini; farklı sunum ve dilim alternatifleriyle keşfedin.','image'=>rb_media_first(['ramazan-bozkurt-kayseri-pastirmasi-premium-sunum','ramazan-bozkurt-kayseri-pastirmasi-dilimli-sunum'])],
  ['title'=>'Sucuk','slug'=>'sucuk','text'=>'Geleneksel Kayseri sucuklarını, ürün detaylarını ve güncel satış seçeneklerini inceleyin.','image'=>rb_media_first(['ramazan-bozkurt-kayseri-sucugu-premium-sunum','ramazan-bozkurt-kayseri-sucugu-urun-gorseli'])],
  ['title'=>'Kavurma','slug'=>'kavurma','text'=>'Kavurma ürün grubunu; servis, blok ve kesit sunumlarıyla yakından tanıyın.','image'=>rb_media_first(['ramazan-bozkurt-kayseri-kavurma-premium-sunum','ramazan-bozkurt-kayseri-kavurma-urun-sunumu'])],
  ['title'=>'Mantı','slug'=>'manti','text'=>'Kayseri mutfağının simge lezzetlerinden mantıyı ve farklı paket seçeneklerini keşfedin.','image'=>rb_media_first(['ramazan-bozkurt-kayseri-mantisi-geleneksel','ramazan-bozkurt-kayseri-mantisi-paket'])],
];
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/urunler');
$wholesale_url = add_query_arg('talep', 'toptan', home_url('/iletisim'));
?>
<section class="rb-hero<?php echo $hero ? ' has-media' : ''; ?>" aria-label="Ramazan Bozkurt Et ve Et Mamulleri">
  <?php if ($hero) : ?><div class="rb-hero__media"><img src="<?php echo esc_url($hero); ?>" alt="Ramazan Bozkurt Et ve Et Mamulleri Kayseri aile sofrası, pastırma, sucuk, kavurma ve mantı" fetchpriority="high"></div><?php endif; ?>
  <div class="rb-hero__overlay"></div>
  <div class="rb-container rb-hero__content"><p>Pastırma, sucuk, kavurma ve mantıda seçkin ürünler. Perakende alışverişin yanında işletmelere özel toptan satış.</p><div class="rb-actions"><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($shop_url); ?>">Ürünleri İncele</a><a class="rb-btn rb-btn--ghost" href="<?php echo esc_url($wholesale_url); ?>">Toptan Teklif Al</a></div></div>
</section>
<section class="rb-trust-strip" aria-label="Teslimat ve satış avantajları"><div class="rb-container rb-trust-strip__grid"><div><strong>4.000 TL üzeri</strong><span>Ücretsiz kargo</span></div><div><strong>Türkiye geneli</strong><span>Güvenli gönderim</span></div><div><strong>İşletmelere özel</strong><span>Toptan fiyat teklifi</span></div><div><strong>Kayseri’den</strong><span>Doğrudan satış</span></div></div></section>
<section id="urunler" class="rb-section rb-section--surface"><div class="rb-container"><div class="rb-section__head rb-section__head--split"><div><div class="rb-eyebrow">Ramazan Bozkurt Et ve Et Mamulleri</div><h1>Kayseri’nin geleneksel et ve et mamulleri ile mantı seçkisi</h1></div><p>Pastırma, sucuk, kavurma ve mantıyı tek marka dili altında; ürün odaklı, sade ve güven veren bir alışveriş deneyimiyle sunuyoruz.</p></div><div class="rb-grid rb-product-grid"><?php foreach($products as $product): ?><article class="rb-card rb-product-card"><a href="<?php echo esc_url(rb_product_category_url($product['slug'])); ?>"><?php if($product['image']): ?><div class="rb-card__media"><img src="<?php echo esc_url($product['image']); ?>" alt="Ramazan Bozkurt <?php echo esc_attr($product['title']); ?>" loading="lazy"></div><?php endif; ?><div class="rb-card__body"><h2><?php echo esc_html($product['title']); ?></h2><p><?php echo esc_html($product['text']); ?></p><span class="rb-card__link">Ürünleri keşfet <span>→</span></span></div></a></article><?php endforeach; ?></div></div></section>
<section class="rb-wholesale-home" aria-labelledby="rb-wholesale-title"><div class="rb-container rb-wholesale-home__grid"><div class="rb-wholesale-home__copy"><div class="rb-eyebrow">Toptan Satış</div><h2 id="rb-wholesale-title">Restoran, şarküteri, market ve işletmelere özel teklif.</h2><p>Düzenli alım yapan işletmeler için pastırma, sucuk, kavurma ve mantıda sipariş miktarına göre özel fiyatlandırma ve satış desteği sunuyoruz.</p><div class="rb-wholesale-points"><span>Toplu siparişe özel fiyat</span><span>Düzenli tedarik görüşmesi</span><span>Hızlı teklif ve doğrudan iletişim</span></div><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($wholesale_url); ?>">Toptan Teklif İste</a></div><div class="rb-wholesale-home__visual"><?php $wholesale_visual=rb_media_first(['ramazan-bozkurt-kayseri-pastirma-sucuk-manti-kavurma','ramazan-bozkurt-kayseri-geleneksel-lezzet-banner']); if($wholesale_visual): ?><img src="<?php echo esc_url($wholesale_visual); ?>" alt="Ramazan Bozkurt toptan pastırma sucuk kavurma ve mantı ürünleri" loading="lazy"><?php endif; ?></div></div></section>
<section class="rb-story"><div class="rb-container rb-story__inner"><div class="rb-story__copy"><div class="rb-eyebrow">Kayseri’den sofralarınıza</div><h2>Bir ürün değil, bir memleket geleneği.</h2><p>Kayseri’nin pastırma, sucuk, kavurma ve mantı kültürü; ürünün kendisinden daha geniş bir sofra hafızasını temsil eder. Ramazan Bozkurt Et ve Et Mamulleri, bu geleneği çağdaş sunum ve erişilebilir alışveriş deneyimiyle buluşturur.</p><a class="rb-text-link" href="<?php echo esc_url(home_url('/hakkimizda')); ?>">Marka hikâyesini keşfedin →</a></div><div class="rb-story__visual"><?php $story=rb_media_first(['ramazan-bozkurt-kayseri-aile-manti-banner','ramazan-bozkurt-kayseri-aile-sofrasi-banner']); if($story): ?><img src="<?php echo esc_url($story); ?>" alt="Kayseri sofrasında mantı ve et mamulleri hazırlayan aile" loading="lazy"><?php endif; ?></div></div></section>
<section class="rb-proof" aria-labelledby="rb-proof-title"><div class="rb-container"><div class="rb-section__head rb-section__head--split"><div><div class="rb-eyebrow">Neden Ramazan Bozkurt?</div><h2 id="rb-proof-title">Lezzetin yanında güven veren bir alışveriş deneyimi.</h2></div><p>Ürün bilgisine hızlı erişim, sade kategori yapısı ve hem perakende hem toptan taleplere uygun iletişim akışı.</p></div><div class="rb-proof-grid"><article><span>01</span><h3>Kayseri odaklı ürün seçkisi</h3><p>Pastırma, sucuk, kavurma ve mantı aynı geleneksel mutfak ekseninde bir araya gelir.</p></article><article><span>02</span><h3>Şeffaf ürün bilgisi</h3><p>Ürün sayfalarında içerik, gramaj, saklama, teslimat ve kullanım bilgileri net biçimde sunulur.</p></article><article><span>03</span><h3>Perakende ve toptan erişim</h3><p>Bireysel siparişlerin yanında işletme talepleri için ayrı ve doğrudan iletişim akışı bulunur.</p></article></div></div></section>
<?php if(class_exists('WooCommerce')): ?><section class="rb-section rb-section--featured"><div class="rb-container"><div class="rb-section__head rb-section__head--split"><div><div class="rb-eyebrow">Mağazadan seçilenler</div><h2>Öne çıkan ürünler</h2></div><p>Yeni eklenen ve öne çıkarılan ürünleri doğrudan mağazadan inceleyin. İşletme alımlarında ürün sayfasındaki toptan teklif bağlantısını kullanabilirsiniz.</p></div><?php echo do_shortcode('[products limit="3" columns="3" visibility="featured"]'); ?></div></section><?php endif; ?>
<section class="rb-cta rb-cta--wholesale"><div class="rb-container rb-cta__inner"><div><div class="rb-eyebrow">Toptan sipariş mi planlıyorsunuz?</div><h2>İşletmenize uygun miktar ve fiyat için doğrudan teklif alın.</h2></div><div class="rb-actions"><a class="rb-btn rb-btn--primary" href="<?php echo esc_url($wholesale_url); ?>">Toptan Teklif Al</a><a class="rb-btn rb-btn--ghost" href="<?php echo esc_url($shop_url); ?>">Perakende Mağaza</a></div></div></section>
<?php get_footer(); ?>