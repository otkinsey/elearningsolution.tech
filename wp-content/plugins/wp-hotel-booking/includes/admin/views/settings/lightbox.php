<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$settings = HB_Settings::instance();
$field_name = $settings->get_field_name('lightbox');
$lightbox = $settings->get('lightbox');
$lightbox = wp_parse_args(
    $lightbox,
    array(
        'lightbox'    => ''
    )
);
$lightboxs = hb_get_support_lightboxs();
?>
<table class="form-table">
    <tr>
        <th><?php _e( 'Lightbox', 'wp-hotel-booking' ); ?></th>
        <td>
            <select name="<?php echo esc_attr( $field_name ); ?>[lightbox]">
                <option value=""><?php _e( 'None', 'wp-hotel-booking' ); ?></option>
                <?php if( $lightboxs ): foreach( $lightboxs as $slug => $name ){?>
                <option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $slug == $lightbox['lightbox']); ?>><?php echo esc_html( $name ); ?></option>
                <?php } endif; ?>
            </select>
        </td>
    </tr>
</table>