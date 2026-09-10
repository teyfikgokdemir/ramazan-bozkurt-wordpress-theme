<?php
/**
 * Ramazan Bozkurt single product layout.
 */
defined('ABSPATH') || exit;

global $product;

do_action('woocommerce_before_single_product');
if (post_password_required()) {
    echo get_the_password_form();
    return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
    <?php do_action('woocommerce_before_single_product_summary'); ?>

    <div class="summary entry-summary">
        <?php do_action('woocommerce_single_product_summary'); ?>

        <aside class="rb-wholesale-box" aria-label="Toptan satış teklifi">
            <div class="rb-wholesale-box__eyebrow">İşletmelere Özel Toptan Satış</div>
            <h3>Bu ürünü düzenli veya toplu mu alıyorsunuz?</h3>
            <p>Restoran, şarküteri, market, otel, kafe ve diğer işletmeler için sipariş miktarına göre özel toptan fiyat teklifi hazırlıyoruz.</p>
            <div class="rb-wholesale-box__actions">
                <a class="rb-btn rb-btn--primary" href="<?php echo esc_url(add_query_arg(['talep'=>'toptan','urun'=>get_the_title()], home_url('/iletisim'))); ?>">Toptan Teklif İste</a>
                <a class="rb-btn" href="<?php echo esc_url(wc_get_cart_url()); ?>">Perakende Alışveriş</a>
            </div>
            <div class="rb-wholesale-mini"><strong>4.000 TL üzeri ücretsiz kargo.</strong> Toptan siparişlerde teslimat ve sevkiyat koşulları sipariş miktarına göre ayrıca planlanır.</div>
        </aside>
    </div>

    <?php do_action('woocommerce_after_single_product_summary'); ?>
</div>
<?php do_action('woocommerce_after_single_product'); ?>
