<?php
/**
 * Admin new order email
 * @since 1.0.3 or less
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$customer = HB_Customer::instance( $booking->customer_id );
$customer_name = sprintf( '%s %s %s', $customer->title ? $customer->title : 'Cus.', $customer->first_name, $customer->last_name );
$cart_params = $booking->booking_cart_params;
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

<?php do_action( 'hb_email_header', $email_heading ); ?>

<?php do_action( 'hb_email_before_booking_table', $booking, true, false ); ?>

<h2>
    <a class="link" href="<?php echo admin_url( 'post.php?post=' . $booking->id . '&action=edit' ); ?>"><?php printf( __( 'Booking %s', 'wp-hotel-booking'), $booking->get_booking_number() ); ?></a>
    (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $booking->order_date ) ), date_i18n( hb_date_format(), strtotime( $booking->order_date ) ) ); ?>)
</h2>

<table class="booking-table" cellpadding="5" cellspacing="1">
    <thead>
        <tr class="booking-table-head">
            <td colspan="7">
                <h3><?php printf( __( 'Booking Details %s', 'wp-hotel-booking' ), hb_format_order_number( $booking->id ) ); ?></h3>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr class="booking-table-row">
            <td class="bold-text">
                <?php _e( 'Customer Name', 'wp-hotel-booking' ); ?>
            </td>
            <td colspan="6" ><?php echo esc_html( $customer_name ); ?></td>
        </tr>
        <tr class="booking-table-head">
            <td colspan="7">
                <h3><?php _e( 'Booking Rooms', 'wp-hotel-booking' ) ; ?></h3>
            </td>
        </tr>
        <tr class="booking-table-row">
            <td class="bold-text"><?php _e( 'Room', 'wp-hotel-booking' ); ?></td>
            <td class="text-align-right bold-text"><?php _e( 'Quantity', 'wp-hotel-booking' ); ?></td>
            <td class="text-align-right bold-text"><?php _e( 'Capacity', 'wp-hotel-booking' ); ?></td>
            <td class="text-align-right bold-text"><?php _e( 'Check In Date', 'wp-hotel-booking' ); ?></td>
            <td class="text-align-right bold-text"><?php _e( 'Check Out Date', 'wp-hotel-booking' ); ?></td>
	        <td class="text-align-right bold-text"><?php _e( 'Night', 'wp-hotel-booking') ?></td>
            <td class="text-align-right bold-text"><?php _e( 'Total', 'wp-hotel-booking' ); ?></td>
        </tr>
        <!--Cart item-->
        <?php foreach( $rooms as $cart_id => $room ) : ?>

            <tr style="background-color: #FFFFFF;">
                <td class="bold-text" rowspan="<?php echo array_key_exists( $cart_id, $child ) ? count( $child[ $cart_id ] ) + 2 : 1 ?>">
                    <a href="<?php echo get_edit_post_link( $room->ID ); ?>"><?php echo esc_html( $room->name ); ?><?php printf( '%s', $room->capacity_title ? ' ('.$room->capacity_title.')' : '' ); ?></a>
                </td>
                <td style="text-align: right;"><?php echo esc_html( $room->quantity ); ?></td>
                <td style="text-align: right;"><?php echo sprintf( _n( '%d adult', '%d adults', $room->capacity, 'wp-hotel-booking' ), $room->capacity ); ?> </td>
                <td style="text-align: right;"><?php echo date_i18n( hb_date_format(), strtotime( $room->get_data( 'check_in_date' ) ) ) ?></td>
                <td style="text-align: right;"><?php echo date_i18n( hb_date_format(), strtotime( $room->get_data( 'check_out_date' ) ) ) ?></td>
                <td style="text-align: right;"><?php echo hb_count_nights_two_dates( $room->get_data( 'check_out_date' ), $room->get_data( 'check_in_date' ) ) ?></td>
                <td style="text-align: right;">
                    <?php echo hb_format_price( $rooms[ $cart_id ]->amount_singular_exclude_tax, hb_get_currency_symbol( $booking->currency ) ); ?>
                </td>
            </tr>

            <?php do_action( 'hotel_booking_admin_cart_after_item', $cart_params, $cart_id, $booking ); ?>

        <?php endforeach; ?>
        <!--Coupon-->
        <?php if ( $booking->coupon ) : ?>

            <tr class="booking-table-row">
                <td class="bold-text">
                    <span class="bold-text"><?php printf( __( 'Coupon applied: %s', 'wp-hotel-booking' ), $booking->coupon['code'] ); ?></span>
                    <span class="text-align-right bold-text">
                        -<?php echo hb_format_price( $booking->coupon['value'], hb_get_currency_symbol( $booking->currency ) ); ?>
                    </span>
                </td>
            </tr>

        <?php endif; ?>
        <!--Subtotal-->
        <tr class="booking-table-row">
            <td colspan="6" class="bold-text">
                <?php _e( 'Sub Total', 'wp-hotel-booking' ); ?>
            </td>
            <td style="text-align: right;">
                <?php echo hb_format_price( $booking->sub_total, hb_get_currency_symbol( $booking->currency ) ); ?>
            </td>
        </tr>
        <!--Tax-->
        <?php if ( $booking->tax ) : ?>
            <tr class="booking-table-row">
                <td colspan="6">
                    <?php _e( 'Tax', 'wp-hotel-booking' ); ?>
                    <?php if( $booking->tax < 0 ) { ?>
                        <span><?php printf( __( '(price including tax)', 'wp-hotel-booking' ) ); ?></span>
                    <?php } ?>
                </td>
                <td style="text-align: right;">
                    <?php echo apply_filters( 'hotel_booking_admin_book_details', abs( $booking->tax * 100 ) . '%', $booking ); ?>
                </td>
            </tr>
        <?php endif; ?>
        <!--Total-->
        <tr class="booking-table-row">
            <td colspan="6">
                <?php _e( 'Grand Total', 'wp-hotel-booking' ); ?>
            </td>
            <td colspan="1" style="text-align: right;">
                <?php echo hb_format_price( $booking->total, hb_get_currency_symbol( $booking->currency ) ) ?>
            </td>
        </tr>
    </tbody>
</table>
