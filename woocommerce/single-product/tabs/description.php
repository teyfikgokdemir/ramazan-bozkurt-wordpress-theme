<?php
/**
 * Rich, text-first product descriptions.
 * WooCommerce template override: keeps description areas free of decorative images
 * and adds product-specific, search-friendly information without unverified
 * production, facility, ingredient or shelf-life claims.
 */
defined('ABSPATH') || exit;

global $product;
if (!$product) { return; }

$slug = $product->get_slug();
$shipping_threshold = function_exists('rb_free_shipping_threshold') ? rb_free_shipping_threshold() : 4000;
$shipping_label = function_exists('wc_price') ? wp_strip_all_tags(wc_price($shipping_threshold, ['decimals' => 0])) : number_format_i18n($shipping_threshold, 0) . ' TL';

$common_note = '<div class="rb-product-copy__note"><strong>Ürün etiketi önceliklidir:</strong> İçindekiler, alerjenler, saklama sıcaklığı, son tüketim tarihi ve ambalaj açıldıktan sonraki kullanım süresi için teslim aldığınız ürünün etiketindeki bilgileri esas alınız.</div>';

$descriptions = [
    'kayseri-pastirmasi-250-g' => [
        'title' => 'Sırt Pastırma',
        'lead' => 'Sırt pastırma, Kayseri pastırması arayanların en sık karşılaştığı klasik ürün çeşitlerinden biridir. Sırt kesimi genel olarak düzenli lif yapısı ve daha dengeli yağ oranıyla bilinir; bu nedenle ince dilim servislerde, kahvaltı sofralarında ve şarküteri tabaklarında tercih edilen pastırma tipleri arasında yer alır.',
        'body' => '<p>Ramazan Bozkurt Sırt Pastırma; 250 g, 400 g, 700 g ve 1 kg gramaj seçenekleriyle sunulur. Küçük gramajlar ürünü ilk kez denemek veya az kişilik sofralar için pratik bir seçim oluştururken, 700 g ve 1 kg seçenekleri daha sık tüketim yapan aileler ve kalabalık sofralar için değerlendirilebilir.</p><p>Sırt pastırmayı servis ederken çok kalın dilimler yerine ince dilimler tercih etmek ürünün dokusunu ve aromasını daha dengeli hissettirir. Kahvaltıda yumurta, peynir ve ekmek eşliğinde sade olarak sunulabilir; pastırmalı yumurta, kuru fasulye, paçanga böreği, sandviç ve sıcak başlangıçlarda da kullanılabilir. Pastırmayı sıcak tariflerde kullanacaksanız uzun süre yüksek ısıda tutmak yerine yemeğin son aşamalarında eklemek daha kontrollü bir sonuç verir.</p><p>“Sırt pastırma nedir?”, “Kayseri sırt pastırma nasıl yenir?” ve “pastırma hangi gramajda alınmalı?” gibi soruların cevabı kullanım alışkanlığına göre değişir. Tek öğün veya kahvaltılık kullanım için küçük paketler, düzenli tüketim için daha yüksek gramajlar daha uygun olabilir.</p>',
        'faq' => '<h3>Sırt pastırma nasıl servis edilir?</h3><p>İnce dilim olarak kahvaltıda, şarküteri tabağında veya sıcak tariflerde kullanılabilir. Oda sıcaklığında uzun süre bekletmek yerine servis edeceğiniz miktarı ambalajdan ayırmanız daha doğru olur.</p><h3>Sırt pastırma ile antrikot pastırma arasındaki fark nedir?</h3><p>Farkın temelinde kullanılan et kesimi bulunur. Antrikot kesimi genellikle daha belirgin yağ damarlarıyla; sırt kesimi ise daha düzenli ve nispeten daha dengeli yapısıyla bilinir. Nihai doku ve tat ürüne göre değişebileceğinden seçim yaparken damak zevkinizi esas alın.</p>',
    ],
    'antrikot-pastirma' => [
        'title' => 'Antrikot Pastırma',
        'lead' => 'Antrikot pastırma, pastırma çeşitleri içinde etin doğal yağ damarlarının daha belirgin hissedilebildiği seçeneklerden biridir. Antrikot kesimi genel olarak yumuşak dokusu ve mermerimsi yağ dağılımıyla tanınır; bu özellik, ince dilim pastırma sevenler için onu ayrı bir seçenek haline getirir.',
        'body' => '<p>Ramazan Bozkurt Antrikot Pastırma 250 g, 400 g, 700 g ve 1 kg gramaj seçenekleriyle satışa sunulur. Farklı gramajlar sayesinde ürünü kahvaltı, misafir sunumu, günlük tüketim veya daha kalabalık sofralar için ihtiyacınıza göre seçebilirsiniz.</p><p>Antrikot pastırma; kahvaltıda sade şekilde, yumurtayla birlikte, şarküteri tabağında, sandviçte veya paçanga böreği gibi sıcak tariflerde kullanılabilir. İnce dilim servis, etin dokusunun daha rahat algılanmasını sağlar. Tavada kullanırken pastırmanın kendi yapısını korumak için kısa süreli ısıtma çoğu tarifte yeterlidir.</p><p>Online pastırma alışverişinde yalnızca fiyat değil; doğru gramaj, ürün tipi ve kullanım amacı da önemlidir. Antrikot pastırma özellikle daha yumuşak ve yağ damarları daha belirgin bir pastırma deneyimi arayan tüketicilerin değerlendirebileceği seçeneklerden biridir. Daha sade veya farklı dokuda bir ürün arıyorsanız sırt, tütünlük ya da çemensiz pastırma seçeneklerini de karşılaştırabilirsiniz.</p>',
        'faq' => '<h3>Antrikot pastırma nasıl yenir?</h3><p>En pratik kullanım şekli ince dilim olarak doğrudan servis etmektir. Kahvaltı, şarküteri tabağı, yumurtalı tarifler, börek ve sandviçlerde kullanılabilir.</p><h3>Hangi gramajı seçmeliyim?</h3><p>250 g ve 400 g seçenekleri daha küçük tüketimler için; 700 g ve 1 kg seçenekleri ise düzenli tüketim veya kalabalık sofralar için tercih edilebilir.</p>',
    ],
    'tutunluk-pastirma' => [
        'title' => 'Tütünlük Pastırma',
        'lead' => 'Tütünlük pastırma, Kayseri pastırma kültüründe ayrı adıyla anılan ve pastırma çeşitlerini karşılaştırmak isteyen kullanıcıların sık araştırdığı seçeneklerden biridir. Pastırmada kullanılan et parçası, yağ dağılımı ve dilim yapısı ürünün ağız hissini etkilediği için tütünlük, sırt ve antrikot gibi çeşitler birbirinden ayrı değerlendirilir.',
        'body' => '<p>Ramazan Bozkurt Tütünlük Pastırma; 250 g, 400 g, 700 g ve 1 kg seçenekleriyle sunulur. Bu gramaj yapısı, hem ürünü denemek isteyen bireysel müşterilere hem de daha düzenli tüketim yapan hanelere seçim kolaylığı sağlar.</p><p>Tütünlük pastırmayı ince dilim olarak kahvaltıda veya şarküteri tabağında servis edebilir, sandviç ve sıcak atıştırmalıklarda değerlendirebilirsiniz. Pastırmalı yumurta, kuru fasulye, börek ve benzeri tariflerde ürünün aromasını yemeğe taşımak için kontrollü ısı kullanmak tercih edilebilir.</p><p>Pastırma seçerken “hangisi daha iyi?” sorusundan çok hangi dokuyu ve kullanım biçimini istediğinizi düşünmek daha faydalıdır. Sırt, antrikot, tütünlük ve çemensiz pastırma aynı kategori içinde farklı damak beklentilerine hitap eder. Bu nedenle alışveriş sırasında yalnızca fiyatı değil, gramaj ve ürün çeşidini birlikte değerlendirmeniz önerilir.</p>',
        'faq' => '<h3>Tütünlük pastırma nerelerde kullanılır?</h3><p>Kahvaltı, şarküteri tabağı, yumurtalı tarifler, börek, sandviç ve sıcak yemeklerde kullanılabilir.</p><h3>Tütünlük pastırmayı nasıl saklamalıyım?</h3><p>Kesin saklama sıcaklığı ve açıldıktan sonraki tüketim süresi ürün etiketinde yer alır. Ambalaj üzerindeki üretici/tedarikçi saklama talimatlarını uygulayın.</p>',
    ],
    'cemensiz-pastirma' => [
        'title' => 'Çemensiz Pastırma',
        'lead' => 'Çemensiz pastırma, çemen aromasını ve kokusunu daha az tercih eden tüketicilerin araştırdığı pastırma seçeneklerinden biridir. Çemen tabakasının olmadığı ya da servis formunda çemensiz sunulan pastırmalarda etin kendi aroması daha doğrudan hissedilir; bu nedenle daha sade bir pastırma deneyimi isteyenler için alternatif oluşturur.',
        'body' => '<p>Ramazan Bozkurt Çemensiz Pastırma 250 g, 400 g, 700 g ve 1 kg gramaj seçenekleriyle sunulur. Farklı paket büyüklükleri sayesinde kahvaltılık küçük porsiyondan daha düzenli tüketime kadar ihtiyaca göre seçim yapılabilir.</p><p>Çemensiz pastırma; kahvaltı, sandviç, şarküteri tabağı ve sıcak tariflerde kullanılabilir. Çemenin baskın baharat karakterini istemeyen kişiler için sade sunumlarda özellikle avantajlıdır. İnce dilim servis, dokunun daha dengeli hissedilmesine yardımcı olur.</p><p>“Çemensiz pastırma nedir?” sorusunun pratik cevabı, tüketici açısından çemen aroması daha geri planda olan veya çemensiz sunulan bir pastırma seçeneğidir. Ancak üretim ve hazırlama yöntemi markaya göre değişebileceğinden ürünün kesin üretim biçimi konusunda ambalaj üzerindeki bilgi esas alınmalıdır. Ürün seçerken çemenli/çemensiz tercihinizin yanında gramaj ve kullanım amacınızı da değerlendirin.</p>',
        'faq' => '<h3>Çemensiz pastırma kimler için uygundur?</h3><p>Çemenin yoğun aromasını tercih etmeyen veya daha sade pastırma tadı arayan tüketiciler tarafından tercih edilebilir.</p><h3>Çemensiz pastırma sıcak tariflerde kullanılabilir mi?</h3><p>Evet. Yumurtalı tarifler, börek, sandviç ve çeşitli sıcak yemeklerde kullanılabilir. Ürünü gereğinden uzun süre yüksek ısıda tutmamak genellikle daha iyi bir doku sağlar.</p>',
    ],
    'kayseri-sucugu-500-g' => [
        'title' => 'Kayseri Sucuğu',
        'lead' => 'Kayseri sucuğu, kahvaltıdan ızgaraya kadar geniş kullanım alanı nedeniyle Türkiye’de en çok aranan geleneksel şarküteri ürünlerinden biridir. Sucuğun baharat profili, yağ dengesi, kesim kalınlığı ve pişirme yöntemi lezzet deneyimini doğrudan etkiler.',
        'body' => '<p>Ramazan Bozkurt Kayseri Sucuğu 500 g ve 1 kg gramaj seçenekleriyle sunulur. 500 g paket günlük veya küçük haneler için daha pratik bir seçimken, 1 kg seçenek daha sık tüketim yapanlar ve kalabalık sofralar için değerlendirilebilir.</p><p>Kayseri sucuğunu tavada, ızgarada, tostta, yumurtayla veya çeşitli sıcak yemeklerde kullanabilirsiniz. Sucuk dilimlerini çok yüksek ateşte uzun süre tutmak yerine kontrollü ısıda pişirmek dış yüzeyin aşırı sertleşmesini önlemeye yardımcı olur. Yumurtalı sucukta önce sucukları kısa süre pişirip ardından yumurtayı eklemek yaygın kullanılan yöntemlerden biridir.</p><p>Online sucuk siparişi verirken gramaj kadar ürünü hangi amaçla kullanacağınız da önemlidir. Kahvaltı ve tost için daha ince; ızgara ve sıcak yemeklerde daha kalın dilimler tercih edilebilir. İçindekiler, baharat bileşimi, alerjen bilgisi ve kesin saklama koşulları ise ürüne özgü olduğundan teslim aldığınız ambalaj etiketinden kontrol edilmelidir.</p>',
        'faq' => '<h3>Kayseri sucuğu nasıl pişirilir?</h3><p>Tava veya ızgarada kontrollü ısı kullanabilirsiniz. Çok uzun pişirme, ürünün kurumasına neden olabilir. Kesin hazırlama önerisi varsa ürün ambalajındaki talimatı esas alın.</p><h3>500 g mı 1 kg mı tercih etmeliyim?</h3><p>Tüketim sıklığınız düşükse 500 g; daha sık tüketim veya kalabalık sofralar için 1 kg daha uygun olabilir.</p>',
    ],
    'kayseri-kavurmasi-250-g' => [
        'title' => 'Kayseri Kavurması',
        'lead' => 'Kavurma, hızlı servis edilebilmesi ve farklı öğünlere kolayca uyarlanabilmesi sayesinde geleneksel et ürünleri arasında pratik kullanım alanı en geniş seçeneklerden biridir. Kahvaltı, yumurta, pilav ve sıcak yemeklerle birlikte tüketilebilir.',
        'body' => '<p>Ramazan Bozkurt Kayseri Kavurması 250 g, 500 g, 750 g ve 1 kg gramaj seçenekleriyle sunulur. 250 g küçük porsiyonlar ve deneme alışverişleri için pratikken, 500 g ve üzeri seçenekler daha düzenli kullanım veya kalabalık sofralar için tercih edilebilir.</p><p>Kavurmayı servis ederken ihtiyacınız kadar ürünü tavaya alıp kontrollü biçimde ısıtabilirsiniz. Yumurtalı kavurma, kavurmalı pilav, sandviç ve sıcak kahvaltı tabakları en bilinen kullanım örnekleri arasındadır. Ürünün dokusunu korumak için gereğinden uzun süre yüksek ısıda tutmamak faydalıdır.</p><p>Kavurma satın alırken yalnızca paket ağırlığını değil, kaç kişilik servis planladığınızı ve ürünü hangi tarifte kullanacağınızı da hesaba katın. İçindekiler, yağ oranı, üretim yöntemi, saklama sıcaklığı ve tüketim süresi markaya ve ürüne göre değişebileceği için bu ayrıntılarda ambalaj etiketi esas alınmalıdır.</p>',
        'faq' => '<h3>Kavurma nasıl servis edilir?</h3><p>Tavada kısa süre ısıtılarak sade, yumurtayla, pilav üzerinde veya sandviç içinde servis edilebilir.</p><h3>Kavurma açıldıktan sonra ne kadar dayanır?</h3><p>Bu süre ürünün ambalajlama ve saklama koşullarına bağlıdır. Kesin süre için ambalaj üzerindeki açıldıktan sonra tüketim ve saklama talimatlarını uygulayın.</p>',
    ],
    'kayseri-mantisi-500-g' => [
        'title' => 'Kayseri Mantısı 500 g',
        'lead' => 'Kayseri mantısı, küçük hamur parçalarının iç harçla kapatılması ve yoğurtlu/soslu servis biçimiyle tanınan Kayseri mutfağının en bilinen yemeklerinden biridir. Pişirme süresi, hamurun kalınlığına ve ürünün taze ya da donuk olmasına göre değişebileceği için ürün ambalajındaki hazırlama talimatı her zaman önceliklidir.',
        'body' => '<p>Ramazan Bozkurt Kayseri Mantısı 500 g tek paket seçeneğiyle sunulur. 500 gramlık paket, ana öğün veya paylaşmalı sofra için pratik bir ölçüdür; porsiyon sayısı hazırlama şekline ve servis büyüklüğüne göre değişir.</p><p>Kayseri mantısı geleneksel olarak haşlandıktan sonra yoğurt ve tereyağlı sosla servis edilir; pul biber, nane veya sumak gibi eşlikçiler kişisel tercihe göre eklenebilir. Suyun miktarı ve pişirme süresi ürüne göre değişeceğinden genel tariflerden çok ambalajdaki üretici/tedarikçi önerisini uygulamak daha doğru olur.</p><p>Mantı satın alırken net gramaj, saklama şekli ve hazırlama talimatlarını birlikte değerlendirin. Ürünün taze veya donuk oluşu, saklama sıcaklığı ve raf ömrü konusunda internet üzerindeki genel bilgiler yerine teslim aldığınız ürün etiketini esas alın. Böylece hem gıda güvenliği hem de istenen doku açısından daha doğru sonuç elde edersiniz.</p>',
        'faq' => '<h3>Kayseri mantısı nasıl servis edilir?</h3><p>En bilinen servis biçimi yoğurt ve tereyağlı sosla sunmaktır. Üzerine damak zevkinize göre nane, sumak veya pul biber ekleyebilirsiniz.</p><h3>500 g mantı kaç kişiliktir?</h3><p>Porsiyon büyüklüğü kişiden kişiye değiştiği için sabit bir kişi sayısı vermek doğru olmaz. Ana yemek veya yan ürün olarak servis etmenize göre porsiyon sayısını ayarlayabilirsiniz.</p>',
    ],
];

if (!isset($descriptions[$slug])) {
    echo wp_kses_post(wpautop($product->get_description()));
    return;
}

$data = $descriptions[$slug];
?>
<div class="rb-product-copy">
  <h2><?php echo esc_html($data['title']); ?> Hakkında</h2>
  <p class="rb-product-copy__lead"><?php echo esc_html($data['lead']); ?></p>
  <?php echo wp_kses_post($data['body']); ?>

  <h3>Gramaj ve sipariş seçenekleri</h3>
  <p>Güncel gramaj ve fiyat seçeneklerini ürün sayfasındaki seçim alanından görebilirsiniz. <?php echo esc_html($shipping_label); ?> ve üzeri perakende siparişlerde ücretsiz kargo uygulanır. Gönderim ayrıntıları için <a href="<?php echo esc_url(home_url('/kargo-ve-teslimat/')); ?>">Kargo ve Teslimat</a> sayfasını inceleyebilirsiniz.</p>

  <h3>Saklama ve ürün güvenliği</h3>
  <?php echo wp_kses_post($common_note); ?>

  <div class="rb-product-copy__faq">
    <h2>Sık Sorulan Sorular</h2>
    <?php echo wp_kses_post($data['faq']); ?>
  </div>

  <h3>Sipariş öncesi destek</h3>
  <p>Ürün, gramaj veya kullanım hakkında karar veremiyorsanız <a href="<?php echo esc_url(home_url('/iletisim/')); ?>">iletişim sayfasından</a> ya da WhatsApp üzerinden bize ulaşabilirsiniz. Toptan ve düzenli alımlar için <a href="<?php echo esc_url(home_url('/toptan-satis/')); ?>">kurumsal teklif</a> talebi oluşturabilirsiniz.</p>
</div>
