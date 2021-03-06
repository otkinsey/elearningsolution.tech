<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$settings = hb_settings();
?>
<table class="form-table">
    <tr>
        <th><?php _e( 'Hotel Name', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('hotel_name') ); ?>" value="<?php echo esc_attr( $settings->get('hotel_name') ); ?>" />
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Address', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('hotel_address') ); ?>" value="<?php echo esc_attr( $settings->get('hotel_address') ); ?>" />
        </td>
    </tr>
    <tr>
        <th><?php _e( 'City', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('hotel_city') ); ?>" value="<?php echo esc_attr( $settings->get('hotel_city') ); ?>" />
        </td>
    </tr>
    <tr>
        <th><?php _e( 'State', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('hotel_state') ); ?>" value="<?php echo esc_attr( $settings->get('hotel_state') ); ?>" />
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Country', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('hotel_country') ); ?>" value="<?php echo esc_attr( $settings->get('hotel_country') ); ?>" />
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Zip / Postal Code', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('hotel_zip_code') ); ?>" value="<?php echo esc_attr( $settings->get('hotel_zip_code') ); ?>" />
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Phone Number', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('hotel_phone_number') ); ?>" value="<?php echo esc_attr( $settings->get('hotel_phone_number') ); ?>" />
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Fax', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('hotel_fax_number') ); ?>" value="<?php echo esc_attr( $settings->get('hotel_fax_number') ); ?>" />
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Email', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="email" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('hotel_email_address') ); ?>" value="<?php echo esc_attr( $settings->get('hotel_email_address') ); ?>" />
        </td>
    </tr>
</table>