<?php
/**
 * Admin new order email
 * @since 1.0.3 or less
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$booking_id = $booking->id;
$customer_id = get_post_meta( $booking_id, '_hb_customer_id', true );
$title = hb_get_title_by_slug( get_post_meta( $customer_id, '_hb_title', true ) );
$first_name = get_post_meta( $customer_id, '_hb_first_name', true );
$last_name = get_post_meta( $customer_id, '_hb_last_name', true );
$customer_name = sprintf( '%s %s %s', $title ? $title : 'Cus.', $first_name, $last_name );

$currency = hb_get_currency_symbol( get_post_meta( $booking_id, '_hb_currency', true ) );
$_rooms = get_post_meta( $booking_id, '_hb_room_id' );
$rooms = array();
foreach( $_rooms as $id ){
    if( empty( $rooms[ $id ] ) ){
        $rooms[ $id ] = 0;
    }
    $rooms[ $id ] ++;
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
            <td colspan="6">
                <h3><?php printf( __( 'Booking Details %s', 'wp-hotel-booking' ), hb_format_order_number( $booking_id ) ); ?></h3>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr class="booking-table-row">
            <td class="bold-text">
                <?php _e( 'Customer Name', 'wp-hotel-booking' ); ?>
            </td>
            <td colspan="5" ><?php echo esc_html( $customer_name ); ?></td>
        </tr>
        <tr class="booking-table-row">
            <td class="bold-text"><?php _e( 'Total Nights', 'wp-hotel-booking' ); ?></td>
            <td colspan="5"><?php echo get_post_meta( $booking_id, '_hb_total_nights', true ) ; ?></td>
        </tr>
        <tr class="booking-table-row">
            <td class="bold-text"><?php _e( 'Total Rooms', 'wp-hotel-booking' ); ?></td>
            <td colspan="5"><?php echo count($_rooms); ?></td>
        </tr>
        <tr class="booking-table-head">
            <td colspan="6">
                <h3><?php _e( 'Booking Rooms', 'wp-hotel-booking' ) ; ?></h3>
            </td>
        </tr>
        <tr class="booking-table-row">
            <td class="bold-text"><?php _e( 'Room type', 'wp-hotel-booking' ); ?></td>
            <td class="text-align-right bold-text"><?php _e( 'Number of rooms', 'wp-hotel-booking' ); ?></td>
            <td class="text-align-right bold-text"><?php _e( 'Capacity', 'wp-hotel-booking' ); ?></td>
            <td class="bold-text"><?php _e( 'Check In Date', 'wp-hotel-booking' ); ?></td>
            <td class="bold-text"><?php _e( 'Check Out Date', 'wp-hotel-booking' ); ?></td>
            <td class="text-align-right bold-text"><?php _e( 'Total', 'wp-hotel-booking' ); ?></td>
        </tr>
        <?php $booking_rooms_params = get_post_meta( $booking_id, '_hb_booking_params', true ); ?>
        <?php if( $booking_rooms_params ): ?>
            <?php foreach ($booking_rooms_params as $search_key => $rooms): ?>
                    <?php foreach ($rooms as $id => $room_param) : ?>
                        <tr style="background-color: #FFFFFF;">
                            <td>
                                <?php
                                    $room = HB_Room::instance( $id, $room_param );
                                    echo get_the_title( $id );
                                    $terms = wp_get_post_terms( $id, 'hb_room_type' );
                                    $room_types = array();
                                    foreach ($terms as $key => $term) {
                                        $room_types[] = $term->name;
                                    }
                                    if( $terms ) echo " (", implode(', ', $room_types), ")";
                                ?>
                            </td>
                            <td style="text-align: right;"><?php echo esc_html( $room->quantity ); ?></td>
                            <td style="text-align: right;">
                                <?php
                                    $cap_id = get_post_meta( $id, '_hb_room_capacity', true );
                                    $term = get_term( $cap_id, 'hb_room_capacity' );
                                    $qty = get_term_meta( $cap_id, 'hb_max_number_of_adults', true );
                                    if ( ! $qty ) {
                                        $qty = get_option( 'hb_taxonomy_capacity_' . $cap_id );
                                    }
                                    if( $term ){
                                        printf( '%s (%d)', $term->name, $qty );
                                    }
                                ?>
                            </td>
                            <td style="text-align: right;"><?php echo date_i18n( hb_get_date_format(), strtotime( $room->check_in_date ) ); ?></td>
                            <td style="text-align: right;"><?php echo date_i18n( hb_get_date_format(), strtotime( $room->check_out_date ) ); ?></td>
                            <td style="text-align: right;">
                                <?php
                                    echo hb_format_price( $room->get_total( $room->check_in_date, $room->check_out_date, $room->quantity, false ), $currency );
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

            <?php endforeach; ?>
        <?php endif; ?>
        <tr class="booking-table-row">
            <td colspan="5" class="bold-text"><?php _e( 'Sub Total', 'wp-hotel-booking' ); ?></td>
            <td class="text-align-right"><?php echo hb_format_price( get_post_meta( $booking_id, '_hb_sub_total', true ), $currency ); ?></td>
        </tr>
        <tr class="booking-table-row">
            <td colspan="5" class="bold-text"><?php _e( 'Tax', 'wp-hotel-booking' ); ?></td>
            <td class="text-align-right">
                <?php
                    $html = '';
                    if( $tax = get_post_meta( $booking_id, '_hb_tax', true ) )
                    {
                        if( is_string($tax) )
                            $html = get_post_meta( $booking_id, '_hb_tax', true ) * 100 .'%' ;
                    }
                    echo sprintf( '%s', apply_filters( 'hotel_booking_admin_book_details', $html, $booking_id ) );
                ?>
            </td>
        </tr>
        <tr class="booking-table-row">
            <td colspan="5" class="bold-text"><?php _e( 'Grand Total', 'wp-hotel-booking' ); ?></td>
            <td class="text-align-right"><?php echo hb_format_price( get_post_meta( $booking_id, '_hb_total', true ), $currency ); ?></td>
        </tr>
    </tbody>
</table>