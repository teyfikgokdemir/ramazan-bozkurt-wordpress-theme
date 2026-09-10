<?php
/**
 * Client-manageable commercial settings.
 */
defined('ABSPATH') || exit;

function rb_theme_setting($key, $default = '') {
    $settings = get_option('rb_theme_settings', []);
    return array_key_exists($key, $settings) && $settings[$key] !== '' ? $settings[$key] : $default;
}

function rb_sanitize_theme_settings($input) {
    $input = is_array($input) ? $input : [];
    return [
        'phone' => sanitize_text_field($input['phone'] ?? '+90 539 824 82 95'),
        'whatsapp' => preg_replace('/\D+/', '', (string)($input['whatsapp'] ?? '905398248295')),
        'free_shipping_threshold' => max(0, (float)($input['free_shipping_threshold'] ?? 4000)),
        'shipping_cost' => max(0, (float)($input['shipping_cost'] ?? 180)),
        'b2b_title' => sanitize_text_field($input['b2b_title'] ?? 'Kurumsal Tedarik'),
        'b2b_message' => sanitize_textarea_field($input['b2b_message'] ?? 'Zincir market, şarküteri, HORECA ve düzenli alım yapan işletmeler için doğrudan teklif süreci.'),
    ];
}

function rb_register_theme_settings() {
    register_setting('rb_theme_settings_group', 'rb_theme_settings', [
        'type' => 'array',
        'sanitize_callback' => 'rb_sanitize_theme_settings',
        'default' => [],
    ]);
}
add_action('admin_init', 'rb_register_theme_settings');

function rb_theme_settings_menu() {
    add_theme_page(
        'Ramazan Bozkurt Ayarları',
        'Tema Ayarları',
        'manage_options',
        'rb-theme-settings',
        'rb_render_theme_settings_page'
    );
}
add_action('admin_menu', 'rb_theme_settings_menu');

function rb_render_theme_settings_page() {
    if (!current_user_can('manage_options')) { return; }
    $s = get_option('rb_theme_settings', []);
    $phone = $s['phone'] ?? '+90 539 824 82 95';
    $whatsapp = $s['whatsapp'] ?? '905398248295';
    $threshold = $s['free_shipping_threshold'] ?? 4000;
    $shipping = $s['shipping_cost'] ?? 180;
    $b2b_title = $s['b2b_title'] ?? 'Kurumsal Tedarik';
    $b2b_message = $s['b2b_message'] ?? 'Zincir market, şarküteri, HORECA ve düzenli alım yapan işletmeler için doğrudan teklif süreci.';
    ?>
    <div class="wrap">
      <h1>Ramazan Bozkurt Tema Ayarları</h1>
      <p>Telefon, WhatsApp, kargo ve kurumsal satış alanlarında kullanılan temel değerleri buradan yönetin.</p>
      <form method="post" action="options.php">
        <?php settings_fields('rb_theme_settings_group'); ?>
        <table class="form-table" role="presentation">
          <tr><th scope="row"><label for="rb-phone">Telefon</label></th><td><input class="regular-text" id="rb-phone" name="rb_theme_settings[phone]" value="<?php echo esc_attr($phone); ?>"></td></tr>
          <tr><th scope="row"><label for="rb-wa">WhatsApp</label></th><td><input class="regular-text" id="rb-wa" name="rb_theme_settings[whatsapp]" value="<?php echo esc_attr($whatsapp); ?>"><p class="description">Ülke koduyla, boşluksuz: 905398248295</p></td></tr>
          <tr><th scope="row"><label for="rb-threshold">Ücretsiz kargo limiti</label></th><td><input type="number" min="0" step="1" id="rb-threshold" name="rb_theme_settings[free_shipping_threshold]" value="<?php echo esc_attr($threshold); ?>"> TL</td></tr>
          <tr><th scope="row"><label for="rb-shipping">Standart kargo ücreti</label></th><td><input type="number" min="0" step="1" id="rb-shipping" name="rb_theme_settings[shipping_cost]" value="<?php echo esc_attr($shipping); ?>"> TL</td></tr>
          <tr><th scope="row"><label for="rb-b2b-title">B2B başlığı</label></th><td><input class="regular-text" id="rb-b2b-title" name="rb_theme_settings[b2b_title]" value="<?php echo esc_attr($b2b_title); ?>"></td></tr>
          <tr><th scope="row"><label for="rb-b2b-message">B2B kısa mesajı</label></th><td><textarea class="large-text" rows="4" id="rb-b2b-message" name="rb_theme_settings[b2b_message]"><?php echo esc_textarea($b2b_message); ?></textarea></td></tr>
        </table>
        <?php submit_button('Ayarları Kaydet'); ?>
      </form>
    </div>
    <?php
}
