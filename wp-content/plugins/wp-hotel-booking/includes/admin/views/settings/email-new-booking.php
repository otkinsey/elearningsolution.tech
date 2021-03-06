<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$settings = hb_settings();
?>
<!-- New Booking block -->
<h3><?php _e( 'New Booking', 'wp-hotel-booking' ); ?></h3>
<p class="description"><?php _e( 'New booking emails are sent when a booking is received.', 'wp-hotel-booking'); ?></p>
<table class="form-table">
    <tr>
        <th><?php _e( 'Enable', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="hidden" name="<?php echo esc_attr( $settings->get_field_name('email_new_booking_enable') ); ?>" value="<?php echo sprintf( '%s', $settings->get('email_new_booking_enable') ? 1 : 0 ); ?>" />
            <input type="checkbox" name="<?php echo esc_attr( $settings->get_field_name('email_new_booking_enable') ); ?>" <?php checked( $settings->get('email_new_booking_enable') ? true : false, true ); ?> value="1" />
        </td>
    </tr>
    <tr class="<?php echo esc_attr( $settings->get_field_name('email_new_booking_enable') ); ?>">
        <th><?php _e( 'Recipient(s)', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('email_new_booking_recipients') ); ?>" value="<?php echo esc_attr( $settings->get('email_new_booking_recipients') ); ?>" />
            <p class="description"><?php printf( __( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', 'wp-hotel-booking' ), get_option( 'admin_email' ) ); ?></p>
        </td>
    </tr>
    <tr class="<?php echo esc_attr( $settings->get_field_name('email_new_booking_enable') ); ?>">
        <th><?php _e( 'Subject', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('email_new_booking_subject') ); ?>" value="<?php echo esc_attr( $settings->get('email_new_booking_subject') ); ?>" />
            <p class="description"><?php _e( 'Subject for email. Leave blank to use the default: <code>[{site_title}] New customer booking ({booking_number}) - {booking_date}</code>.', 'wp-hotel-booking' ); ?></p>
        </td>
    </tr>
    <tr class="<?php echo esc_attr( $settings->get_field_name('email_new_booking_enable') ); ?>">
        <th><?php _e( 'Email Heading', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo esc_attr( $settings->get_field_name('email_new_booking_heading') ); ?>" value="<?php echo esc_attr( $settings->get('email_new_booking_heading') ); ?>" />
            <p class="description"><?php _e( 'The main heading displays in the top of email. Default heading: <code>New customer booking</code>.', 'wp-hotel-booking' ); ?></p>
        </td>
    </tr>
    <tr class="<?php echo esc_attr( $settings->get_field_name('email_new_booking_enable') ); ?>">
        <th><?php _e( 'Email Format', 'wp-hotel-booking' ); ?></th>
        <td>
            <?php
            $template_formats = array(
                'plain'     => __( 'Plain Text', 'wp-hotel-booking' ),
                'html'      => __( 'HTML', 'wp-hotel-booking' )
            );
            ?>
            <select name="<?php echo esc_attr( $settings->get_field_name('email_new_booking_format') ); ?>">
                <?php foreach( $template_formats as $k => $v ){?>
                <option value="<?php echo esc_attr( $k ); ?>" <?php selected( $k == $settings->get('email_new_booking_format') ); ?>><?php echo esc_html( $v ); ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
</table>
