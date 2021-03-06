<?php
/**
 * Template Customer
 * @since  1.1
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

?>
<table class="hb-booking-table customer-information hb-table-width30">
    <thead>
        <th colspan="2">
            <h3><?php _e( 'Customer Information', 'wp-hotel-booking') ?></h3>
        </th>
    </thead>
    <tbody>
        <tr>
            <th><?php _e( 'Name', 'wp-hotel-booking' ) ?> </th>
            <td>
                <?php
                    $title = hb_get_title_by_slug( $customer->get( '_hb_title' ) );
                    $first_name = $customer->get( '_hb_first_name' );
                    $last_name = $customer->get( '_hb_last_name' );
                    printf( '%s %s %s', $title ? $title : 'Cus.', $first_name, $last_name );
                ?>
            </td>
        </tr>
        <tr>
            <th> <?php _e( 'Address ', 'wp-hotel-booking'); ?> </th>
            <td><?php echo esc_html( $customer->get( '_hb_address' ) ); ?></td>
        </tr>
        <tr>
            <th> <?php _e( 'City ', 'wp-hotel-booking' ); ?> </th>
            <td><?php echo esc_html( $customer->get( '_hb_city' ) ) ?></td>
        </tr>
        <tr>
            <th><?php _e( 'State ', 'wp-hotel-booking' ); ?></th>
            <td><?php echo esc_html( $customer->get( '_hb_state' ) ) ?></td>
        </tr>
        <tr>
            <th><?php _e( 'Country ', 'wp-hotel-booking' ); ?></th>
            <td><?php echo esc_html( $customer->get( '_hb_country' ) ) ?></td>
        </tr>
        <tr>
            <th><?php _e( 'Zip/ Post Code ','wp-hotel-booking' ); ?></th>
            <td><?php echo esc_html( $customer->get( '_hb_postal_code' ) ) ?></td>
        </tr>
        <tr>
            <th><?php _e( 'Phone ', 'wp-hotel-booking' ); ?></th>
            <td><?php echo esc_html( $customer->get( '_hb_phone' ) ) ?></td>
        </tr>
        <tr>
            <th><?php _e( 'Fax ', 'wp-hotel-booking' ); ?></th>
            <td><?php echo esc_html( $customer->get( '_hb_fax' ) ) ?></td>
        </tr>
        <tr>
            <th><?php _e( 'Email ', 'wp-hotel-booking' ); ?></th>
            <td><?php echo esc_html( $customer->get( '_hb_email' ) ); ?></td>
        </tr>
    </tbody>
</table>