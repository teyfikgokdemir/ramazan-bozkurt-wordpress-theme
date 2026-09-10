<?php
/**
 * Editable content hub seed: Blog + Yemek Tarifleri.
 * Runs once and never overwrites later client edits.
 */
defined('ABSPATH') || exit;

function rb_seed_content_hub_v1() {
    if (get_option('rb_content_hub_v1')) { return; }

    $blog_page = get_page_by_path('blog', OBJECT, 'page');
    if (!$blog_page) {
        $blog_id = wp_insert_post([
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'Blog',
            'post_name' => 'blog',
            'post_content' => '<p>Kayseri pastırması, sucuk, kavurma ve mantı hakkında saklama, servis, pişirme ve sofra önerilerini bu bölümde bulabilirsiniz.</p>',
        ]);
    } else {
        $blog_id = $blog_page->ID;
    }

    $recipes_page = get_page_by_path('yemek-tarifleri', OBJECT, 'page');
    if (!$recipes_page) {
        $recipes_id = wp_insert_post([
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'Yemek Tarifleri',
            'post_name' => 'yemek-tarifleri',
            'post_content' => '<h2>Kayseri lezzetleriyle sofraya yakışan tarifler</h2><p>Pastırma, sucuk, kavurma ve mantıyı evde daha keyifli servis etmek için pratik, anlaşılır ve günlük mutfağa uyarlanabilir tarifleri bir araya getiriyoruz.</p>',
        ]);
    } else {
        $recipes_id = $recipes_page->ID;
    }

    $blog_cat = term_exists('Rehber', 'category');
    if (!$blog_cat) { $blog_cat = wp_insert_term('Rehber', 'category', ['slug' => 'rehber']); }
    $recipe_cat = term_exists('Yemek Tarifleri', 'category');
    if (!$recipe_cat) { $recipe_cat = wp_insert_term('Yemek Tarifleri', 'category', ['slug' => 'yemek-tarifleri']); }
    $blog_cat_id = is_array($blog_cat) ? (int) $blog_cat['term_id'] : (int) $blog_cat;
    $recipe_cat_id = is_array($recipe_cat) ? (int) $recipe_cat['term_id'] : (int) $recipe_cat;

    $posts = [
        'kayseri-pastirmasi-nasil-saklanir' => [
            'title' => 'Kayseri Pastırması Nasıl Saklanır? Lezzetini Korumak İçin Pratik Rehber',
            'cat' => $blog_cat_id,
            'excerpt' => 'Pastırmayı açtıktan sonra nasıl saklamalısınız, servis öncesi nelere dikkat etmelisiniz? Ev kullanımı için pratik bir rehber.',
            'content' => '<p>Pastırma güçlü aroması ve ince dilim yapısıyla sofrada az miktarla bile kendini belli eden bir üründür. Bu yüzden saklama sırasında amaç yalnızca ürünü korumak değil; kokusunu, dokusunu ve servis kalitesini mümkün olduğunca dengeli tutmaktır.</p><h2>Pastırma açılmadan önce nasıl saklanır?</h2><p>Öncelikle ambalaj üzerindeki üretici saklama talimatını esas alın. Ürünü doğrudan güneş alan, sıcaklığı sürekli değişen alanlarda bekletmeyin. Ambalaj kapalıyken dahi serin ve stabil koşullar tercih edilmelidir.</p><h2>Paket açıldıktan sonra ne yapmalı?</h2><p>Açılmış pastırmayı hava ile gereksiz temasta bırakmayın. Kullanacağınız miktarı ayırdıktan sonra kalan kısmı yeniden sıkıca kapatın. Burada en önemli nokta, ürünü açık tabakta buzdolabına kaldırmamak; hem yüzeyin kurumasını hem de aromanın diğer yiyeceklere geçmesini azaltmaktır.</p><h2>Servisten ne kadar önce çıkarmalı?</h2><p>Çok soğuk pastırmanın aroması daha kapalı hissedilebilir. Servis edeceğiniz miktarı kısa süre önce çıkarıp tabakta dinlendirmek, dilimlerin yapısını ve kokusunu daha belirgin hale getirir. Ancak özellikle sıcak havalarda ürünü uzun süre oda sıcaklığında bekletmeyin.</p><h2>Dilimleri birbirinden ayırırken</h2><p>İnce dilimleri hızlıca çekiştirmek yerine uç kısmından nazikçe ayırmak daha iyi sonuç verir. Kahvaltıda, sandviçte veya sıcak yemek üzerinde kullanacaksanız porsiyonu önceden ayırmak servisi kolaylaştırır.</p><h2>Ne zaman tüketilmeli?</h2><p>Son tüketim tarihi ve açıldıktan sonraki kullanım süresi için her zaman ürün etiketini esas alın. Koku, renk veya dokuda olağandışı bir değişiklik fark ederseniz tüketmeyin.</p><p><strong>Kısa cevap:</strong> Pastırmayı ambalaj talimatına uygun, serin koşullarda saklayın; açıldıktan sonra hava temasını azaltın ve servis edeceğiniz miktarı ayrı çıkarın.</p>'
        ],
        'pastirma-nasil-servis-edilir' => [
            'title' => 'Pastırma Nasıl Servis Edilir? Kahvaltıdan Sıcak Yemeklere Kullanım Önerileri',
            'cat' => $blog_cat_id,
            'excerpt' => 'Pastırmayı kahvaltıda, yumurtada, sandviçte ve sıcak yemeklerde dengeli biçimde kullanmak için servis önerileri.',
            'content' => '<p>Pastırmayı iyi servis etmek için çok fazla malzemeye ihtiyaç yoktur. Asıl mesele, yoğun aromayı bastırmadan eşlik edecek ürünleri seçmek ve pastırmayı gereğinden fazla pişirmemektir.</p><h2>Kahvaltıda pastırma</h2><p>İnce dilimleri sade bir tabakta, iyi ekmek ve sıcak çay eşliğinde sunabilirsiniz. Peynir, domates ve yumurta gibi klasik kahvaltılıklarla birlikte servis edildiğinde pastırmayı tabağın merkezinde tutmak yeterlidir.</p><h2>Pastırmalı yumurta</h2><p>Tavayı orta ateşte ısıtın. Pastırmayı uzun süre kızartmak yerine kısa süre ısıtıp hemen ardından yumurtayı ekleyin. Pastırmanın zaten yoğun bir aroması olduğu için ayrıca fazla tuz eklememek genellikle daha dengeli sonuç verir.</p><h2>Sandviç ve tostta</h2><p>Pastırmayı kaşar gibi eriyen peynirlerle birlikte kullanırken miktarı ölçülü tutun. İnce birkaç dilim, bütün sandviçin karakterini değiştirmeye yeter. Çok kalın soslar yerine ekmeği ve ana ürünü öne çıkaran sade eşlikçiler tercih edin.</p><h2>Sıcak yemeklerde</h2><p>Pastırmayı kuru fasulye, yumurta, patates veya bazı hamur işlerinde kullanabilirsiniz. Uzun süreli pişirme gerekiyorsa pastırmayı son aşamalarda eklemek dokusunu daha iyi koruyabilir.</p><h2>Sunum dengesi</h2><p>Ahşap servis tahtası, koyu tabaklar ve sade garnitürler pastırmanın rengini öne çıkarır. Gösterişli ama işlevsiz süslemeler yerine ürünü rahatça alabileceğiniz temiz bir düzen daha premium görünür.</p>'
        ],
        'kayseri-sucugu-nasil-pisirilir' => [
            'title' => 'Kayseri Sucuğu Nasıl Pişirilir? Tavada, Yumurtayla ve Izgarada',
            'cat' => $blog_cat_id,
            'excerpt' => 'Sucuğu kurutmadan tavada, yumurtayla veya ızgarada hazırlamak için temel pişirme ipuçları.',
            'content' => '<p>Sucuk pişirirken en sık yapılan hata, yüksek ateşte uzun süre tutup içindeki yağı tamamen çıkarmaktır. İyi sonuç için amaç dış yüzeyi hafifçe kızartırken iç dokuyu gereksiz yere kurutmamaktır.</p><h2>Tavada sucuk</h2><p>Tavayı orta ateşte ısıtın. Sucuğu eşit kalınlıkta dilimleyin ve yağ eklemeden tavaya alın. Bir yüzü renk aldığında çevirin. Çok sık çevirmek yerine kısa ve kontrollü pişirme daha iyi sonuç verir.</p><h2>Sucuklu yumurta</h2><p>Sucukları önce kısa süre tavada çevirin. Ardından yumurtayı ekleyin ve ateşi biraz azaltın. Böylece yumurta pişerken sucuk gereğinden fazla sertleşmez.</p><h2>Izgarada sucuk</h2><p>Izgarada daha kalın dilimler veya ikiye bölünmüş parçalar kullanılabilir. Ateşin çok harlı olmaması önemli; dış yüzeyi hızla yakmadan kontrollü pişirin.</p><h2>Tost ve sandviçte</h2><p>Sucuğu tostun içine çiğ koymak yerine kısa süre ön pişirmek, hem dokuyu hem de yağ dengesini kontrol etmeyi kolaylaştırır. Kaşar peyniriyle birlikte kullanıldığında ekstra sos ihtiyacı azalır.</p><h2>Servis önerisi</h2><p>Sucuğu pişirdikten sonra bekletmeden servis edin. Sıcak servis, kokunun ve baharat profilinin daha belirgin algılanmasını sağlar.</p>'
        ],
        'kavurmali-pilav-tarifi' => [
            'title' => 'Kavurmalı Pilav Tarifi: Doyurucu ve Pratik Bir Akşam Yemeği',
            'cat' => $recipe_cat_id,
            'excerpt' => 'Hazır kavurmayla kısa sürede hazırlanabilen, tane tane pilav ve et lezzetini bir araya getiren pratik tarif.',
            'content' => '<p>Kavurmalı pilav, elinizde iyi bir kavurma varsa fazla uğraştırmadan güçlü bir ana yemek çıkarabileceğiniz tariflerden biri. Burada önemli olan pilavı ağırlaştırmadan kavurmanın lezzetini son aşamada yemeğe taşımak.</p><h2>Malzemeler</h2><ul><li>2 su bardağı pirinç</li><li>Yaklaşık 200-250 g kavurma</li><li>2,5 su bardağı sıcak su veya et suyu</li><li>1 yemek kaşığı tereyağı</li><li>1 yemek kaşığı sıvı yağ</li><li>Tuz</li></ul><h2>Hazırlık</h2><p>Pirinci nişastası gidene kadar birkaç kez yıkayın. Vaktiniz varsa 15-20 dakika ılık suda bekletip iyice süzün. Bu küçük adım pilavın daha tane tane olmasına yardımcı olur.</p><h2>Pişirme</h2><p>Tencereye tereyağı ve sıvı yağı alın. Pirinci birkaç dakika nazikçe kavurun. Sıcak suyu ekleyip tuzunu ayarlayın. Kaynadığında kapağı kapatın ve kısık ateşte suyunu çekene kadar pişirin.</p><p>Kavurmayı ayrı küçük bir tavada birkaç dakika ısıtın. Uzun süre kızartmayın; zaten pişmiş ürünü yalnızca servis sıcaklığına getirmek yeterli. Pilav suyunu çektikten sonra kavurmayı üzerine ekleyin, çok fazla ezmeden karıştırın ve 10 dakika dinlendirin.</p><h2>Servis</h2><p>Yanına cacık, ayran veya mevsim salatası yeterli olur. Kavurmanın aroması güçlü olduğu için sofrayı fazla karmaşıklaştırmadan servis etmek daha dengeli bir sonuç verir.</p>'
        ],
        'kayseri-mantisi-nasil-hazirlanir' => [
            'title' => 'Kayseri Mantısı Nasıl Hazırlanır? Yoğurt ve Sos Dengesiyle Servis',
            'cat' => $recipe_cat_id,
            'excerpt' => 'Kayseri mantısını evde haşlama, yoğurt ve tereyağlı sosla servis etmenin pratik yolu.',
            'content' => '<p>Mantı servisinde sonucu belirleyen üç şey var: hamuru gereğinden fazla haşlamamak, yoğurdu doğru kıvamda hazırlamak ve tereyağlı sosu yakmamak. Bunları kontrol ettiğinizde tabak zaten kendini topluyor.</p><h2>Haşlama</h2><p>Geniş bir tencerede bol su kaynatın ve hafifçe tuzlayın. Mantıyı kaynayan suya ekleyin. Pişirme süresi ürünün boyutuna ve ambalaj talimatına göre değişebileceği için paketteki süreyi esas alın. Mantıyı gereğinden fazla haşlamak hamurun dağılmasına yol açabilir.</p><h2>Sarımsaklı yoğurt</h2><p>Yoğurdu bir kasede pürüzsüz olana kadar karıştırın. Sarımsak kullanacaksanız ezip yoğurda ekleyin. Çok koyuysa bir iki kaşık mantı suyuyla kıvamını açabilirsiniz.</p><h2>Tereyağlı sos</h2><p>Küçük tavada tereyağını eritin. Kırmızı toz biber veya pul biber kullanacaksanız ateşi çok yüksek tutmayın. Baharatı ekledikten sonra birkaç saniye çevirip ocaktan alın; yanmış biber bütün tabağın tadını sertleştirir.</p><h2>Birleştirme</h2><p>Mantıyı süzüp servis tabağına alın. Birkaç dakika ilk hararetinin çıkmasını bekleyin; kaynar mantının üzerine doğrudan soğuk yoğurt dökmek yerine kısa bir denge süresi daha iyi sonuç verir. Yoğurdu ve ardından tereyağlı sosu ekleyin.</p><h2>Son dokunuş</h2><p>İsteğe göre kuru nane, sumak veya pul biber ekleyebilirsiniz. Ancak hepsini aynı anda yoğun kullanmak yerine iki baharatla daha temiz bir lezzet elde edebilirsiniz.</p>'
        ],
        'pastirmali-yumurta-tarifi' => [
            'title' => 'Pastırmalı Yumurta Tarifi: Pastırmayı Kurutmadan Kahvaltıya Hazırlayın',
            'cat' => $recipe_cat_id,
            'excerpt' => 'Pastırmayı sertleştirmeden, yumurtayı da fazla pişirmeden hazırlanan klasik kahvaltı tarifi.',
            'content' => '<p>Pastırmalı yumurta hızlı bir tarif ama birkaç saniyelik fark bile sonucu değiştirebilir. Pastırmayı uzun uzun kızartmak yerine aromasını tavaya bırakacak kadar ısıtmak yeterlidir.</p><h2>Malzemeler</h2><ul><li>2-3 yumurta</li><li>6-8 ince dilim pastırma</li><li>1 tatlı kaşığı tereyağı</li><li>İsteğe göre karabiber</li></ul><h2>Hazırlama</h2><p>Tavayı orta-kısık ateşte ısıtıp tereyağını ekleyin. Pastırma dilimlerini tavaya tek kat yerleştirin ve yaklaşık yarım dakika kadar çevirin. Kenarlar hafifçe hareketlenmeye başladığında yumurtaları kırın.</p><p>Yumurtayı nasıl seviyorsanız o kıvama kadar pişirin. Pastırma tuzlu olabileceğinden tuzu en başta değil, servis öncesi tadına bakarak eklemek daha kontrollü olur.</p><h2>Servis</h2><p>Sıcak ekmek ve çayla hemen servis edin. Tavada uzun süre bekletmek pastırmanın sertleşmesine ve yumurtanın kurumasına neden olabilir.</p>'
        ],
        'sucuklu-yumurta-tarifi' => [
            'title' => 'Sucuklu Yumurta Nasıl Yapılır? Sucuğu Kurutmadan Klasik Tarif',
            'cat' => $recipe_cat_id,
            'excerpt' => 'Sucuklu yumurtada doğru ateş, dilim kalınlığı ve pişirme sırasıyla daha dengeli sonuç alın.',
            'content' => '<p>Sucuklu yumurta için ekstra yağa çoğu zaman gerek yoktur. Sucuk kendi yağını bıraktığı için doğru ateşte kısa bir ön pişirme yapmak yeterlidir.</p><h2>Malzemeler</h2><ul><li>150-200 g sucuk</li><li>3 yumurta</li><li>İsteğe göre çok az tereyağı</li></ul><h2>Sucuğu hazırlayın</h2><p>Sucuğu eşit kalınlıkta dilimleyin. Tavayı orta ateşte ısıtıp dilimleri tek kat halinde yerleştirin. Bir yüzü hafif kızardığında çevirin.</p><h2>Yumurtayı ekleyin</h2><p>Sucuk yağını bırakmaya başladığında yumurtaları tavaya kırın. Ateşi biraz düşürün. Böylece yumurtanın beyazı kontrollü pişerken sucuk sertleşmez.</p><h2>Ne zaman ocaktan alınmalı?</h2><p>Yumurta istediğiniz kıvama geldiğinde bekletmeden ocaktan alın. Döküm veya kalın tabanlı tavalar ısıyı tuttuğu için ocaktan aldıktan sonra da pişirme devam eder.</p><h2>Yanına ne gider?</h2><p>Taze ekmek, domates ve çay klasik ve yeterli bir eşliktir. Sucuğun baharatlı karakteri nedeniyle tabağa çok fazla ek sos eklemek şart değildir.</p>'
        ],
    ];

    foreach ($posts as $slug => $data) {
        if (get_page_by_path($slug, OBJECT, 'post')) { continue; }
        $post_id = wp_insert_post([
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_title' => $data['title'],
            'post_name' => $slug,
            'post_excerpt' => $data['excerpt'],
            'post_content' => $data['content'],
            'post_category' => [$data['cat']],
        ]);
        if ($post_id && !is_wp_error($post_id)) {
            if (strpos($slug, 'pastirma') !== false) $image = rb_media_id_first(['ramazan-bozkurt-kayseri-pastirmasi-sunum-1','ramazan-bozkurt-pastirma-dilim-detay']);
            elseif (strpos($slug, 'sucuk') !== false) $image = rb_media_id_first(['ramazan-bozkurt-sucuk-geleneksel-premium-sunum','ramazan-bozkurt-kayseri-parmak-sucuk']);
            elseif (strpos($slug, 'kavurma') !== false) $image = rb_media_id_first(['ramazan-bozkurt-kavurma-klasik-premium-servis','ramazan-bozkurt-kavurma-kalip-ve-dilim']);
            else $image = rb_media_id_first(['ramazan-bozkurt-kayseri-mantisi-el-yapimi','ramazan-bozkurt-kayseri-mantisi-ambalaj']);
            if ($image) set_post_thumbnail($post_id, $image);
        }
    }

    if ($blog_id && !is_wp_error($blog_id)) {
        update_option('show_on_front', 'page');
        update_option('page_for_posts', (int) $blog_id);
    }

    update_option('rb_content_hub_v1', 1);
}
add_action('admin_init', 'rb_seed_content_hub_v1', 100);
