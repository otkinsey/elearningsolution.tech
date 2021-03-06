<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$settings = hb_settings();
?>
<!-- Email Sender Options block -->
<h3><?php _e( 'Email Sender', 'wp-hotel-booking' ); ?></h3>
<p class="description"><?php _e( 'The name and email address of the sender displays in email', 'wp-hotel-booking' ); ?></p>
<table class="form-table">
    <tr>
        <th><?php _e( 'From Name', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('email_general_from_name') ); ?>" value="<?php echo esc_attr( $settings->get('email_general_from_name') ); ?>" placeholder="<?php _e( 'E.g: John Smith', 'wp-hotel-booking' ); ?>" />
        </td>
    </tr>
    <tr>
        <th><?php _e( 'From Email', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="email" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('email_general_from_email') ); ?>" value="<?php echo esc_attr( $settings->get('email_general_from_email') ); ?>" placeholder="<?php _e( 'E.g: yourmail@yourdomain.com', 'wp-hotel-booking' ); ?>" />
        </td>
    </tr>
</table>