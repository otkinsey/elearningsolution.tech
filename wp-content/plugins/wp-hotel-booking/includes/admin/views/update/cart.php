<?php
/**
 * Template Cart Params
 * @since  1.1
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$cart_params = apply_filters( 'hotel_booking_admin_cart_params', $cart_params );

$rooms = array();
$child = array();
foreach ( $cart_params as $key => $cart_item ) {
    if ( $cart_item->product_data->post && $cart_item->product_data->post->post_type === 'hb_room' ) {
        $rooms[ $key ] = $cart_item->product_data;
    }

    if ( isset( $cart_item->parent_id ) ) {
        if ( ! array_key_exists( $cart_item->parent_id, $child ) ) {
            $child[ $cart_item->parent_id ] = array();
        }
        $child[ $cart_item->parent_id ][] = $key;
    }
}

?>

<table class="booking-details hb-booking-table hb-table-width70">
    <thead>
        <th colspan="28">
            <h3><?php _e( 'Booking Details', 'wp-hotel-booking') ?></h3>
        </th>
    </thead>
    <thead>
        <th colspan="1">
            <h3><?php _e( 'Room', 'wp-hotel-booking') ?></h3>
        </th>
        <th colspan="1">
            <h3><?php _e( 'Capacity', 'wp-hotel-booking') ?></h3>
        </th>
        <th colspan="1">
            <h3><?php _e( 'Quantity', 'wp-hotel-booking') ?></h3>
        </th>
        <th colspan="1">
            <h3><?php _e( 'Check - in', 'wp-hotel-booking') ?></h3>
        </th>
        <th colspan="1">
            <h3><?php _e( 'Check - out', 'wp-hotel-booking') ?></h3>
        </th>
        <th colspan="1">
            <h3><?php _e( 'Night', 'wp-hotel-booking') ?></h3>
        </th>
        <th colspan="1">
            <h3><?php _e( 'Gross Total', 'wp-hotel-booking') ?></h3>
        </th>
    </thead>
    <tbody>
        <!--Cart item-->
        <?php foreach( $rooms as $cart_id => $room ) : ?>

            <tr>
                <td class="hb_room_type" colspan="1" rowspan="<?php echo array_key_exists( $cart_id, $child ) ? count( $child[ $cart_id ] ) + 2 : 1 ?>">
                    <a href="<?php echo get_edit_post_link( $room->ID ); ?>"><?php echo esc_html( $room->name ); ?><?php printf( '%s', $room->capacity_title ? ' ('.$room->capacity_title.')' : '' ); ?></a>
                </td>
                <td class="hb_capacity" colspan="1"><?php echo sprintf( _n( '%d adult', '%d adults', $room->capacity, 'wp-hotel-booking' ), $room->capacity ); ?> </td>
                <td class="hb_quantity" colspan="1" style="text-align: center;"><?php echo esc_html( $room->quantity ); ?></td>
                <td class="hb_check_in" colspan="1"><?php echo date_i18n( hb_get_date_format(), strtotime( $room->get_data( 'check_in_date' ) ) ) ?></td>
                <td class="hb_check_out" colspan="1"><?php echo date_i18n( hb_get_date_format(), strtotime( $room->get_data( 'check_out_date' ) ) ) ?></td>
                <td class="hb_night" colspan="1"><?php echo hb_count_nights_two_dates( $room->get_data( 'check_out_date' ), $room->get_data( 'check_in_date' ) ) ?></td>
                <td class="hb_gross_total" colspan="1">
                    <?php echo sprintf( '%s', hb_format_price( $rooms[ $cart_id ]->amount_singular_exclude_tax, hb_get_currency_symbol( $booking->currency ) ) ); ?>
                </td>
            </tr>

            <?php do_action( 'hotel_booking_admin_cart_after_item', $cart_params, $cart_id, $booking ); ?>
        <?php endforeach; ?>
        <!--Coupon-->
        <?php if ( $booking->coupon ) : ?>

            <tr class="hb_coupon">
                <td class="hb_coupon_remove" colspan="28">
                    <span class="hb-remove-coupon_code"><?php printf( __( 'Coupon applied: %s', 'wp-hotel-booking' ), $booking->coupon['code'] ); ?></span>
                    <span class="hb-align-right">
                        -<?php echo hb_format_price( $booking->coupon['value'], hb_get_currency_symbol( $booking->currency ) ); ?>
                    </span>
                </td>
            </tr>

        <?php endif; ?>
        <!--Subtotal-->
        <tr class="hb_sub_total">
            <td colspan="6">
                <?php _e( 'Sub Total', 'wp-hotel-booking' ); ?>
            </td>
            <td colspan="1">
                <?php echo hb_format_price( $booking->sub_total, hb_get_currency_symbol( $booking->currency ) ); ?>
            </td>
        </tr>
        <!--Tax-->
        <?php if ( $booking->tax ) : ?>
            <tr class="hb_advance_tax">
                <td colspan="6">
                    <?php _e( 'Tax', 'wp-hotel-booking' ); ?>
                    <?php if( $booking->tax < 0 ) { ?>
                        <span><?php printf( __( '(price including tax)', 'wp-hotel-booking' ) ); ?></span>
                    <?php } ?>
                </td>
                <td colspan="1">
                    <?php echo apply_filters( 'hotel_booking_admin_book_details', abs( $booking->tax * 100 ) . '%', $booking ); ?>
                </td>
            </tr>
        <?php endif; ?>
        <!--Total-->
        <tr class="hb_advance_grand_total">
            <td colspan="6">
                <?php _e( 'Grand Total', 'wp-hotel-booking' ); ?>
            </td>
            <td colspan="1">
                <?php echo hb_format_price( $booking->total, hb_get_currency_symbol( $booking->currency ) ) ?>
            </td>
        </tr>
    </tbody>
</table>