<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

$title                = '';
$first_name           = '';
$last_name            = '';
$address              = '';
$city                 = '';
$state                = '';
$postal_code          = '';
$country              = '';
$phone                = '';
$fax                  = '';
$email                = '';
$addition_information = '';

// if ( $email = get_transient( 'hotel_booking_customer_email_' . HB_BLOG_ID, $email ) ) {
if ( $email = TP_Hotel_Booking::instance()->cart->customer_email ) {
	$query_args = array(
		'post_type'  => 'hb_customer',
		'meta_query' => array(
			array(
				'key'     => '_hb_email',
				'value'   => $email,
				'compare' => 'EQUALS'
			),
		)
	);
	// set_transient( 'hotel_booking_customer_email_' . HB_BLOG_ID, $email, DAY_IN_SECONDS );
	TP_Hotel_Booking::instance()->cart->set_customer( 'customer_email', $email );
	if ( $posts = get_posts( $query_args ) ) {
		$customer       = $posts[0];
		$customer->data = array();
		$data           = get_post_meta( $customer->ID );
		foreach ( $data as $k => $v ) {
			$k = preg_replace( '!^_hb_!', '', $k );
			$customer->data[$k] = $v[0];
		}
		extract( $customer->data );
	} else {
		$customer = null;
	}
}

?>
<div class="hb-order-new-customer" id="hb-order-new-customer">
	<div class="hb-col-padding hb-col-border">
		<h4><?php _e( 'New Customer', 'wp-hotel-booking' ); ?></h4>
		<ul class="hb-form-table col-2">
			<li class="hb-form-field">
				<label class="hb-form-field-label"><?php _e( 'Title', 'wp-hotel-booking' ); ?>
					<span class="hb-required">*</span> </label>

				<div class="hb-form-field-input">
					<?php hb_dropdown_titles( array( 'selected' => $title ) ); ?>
				</div>
			</li>
			<li class="hb-form-field">
				<label class="hb-form-field-label"><?php _e( 'Name', 'wp-hotel-booking' ); ?>
					<span class="hb-required">*</span></label>

				<div class="hb-form-field-input">
					<input type="text" name="first_name" value="<?php echo esc_attr( $first_name ); ?>" placeholder="<?php _e( 'First name', 'wp-hotel-booking' ); ?>" />
					<input type="text" name="last_name" value="<?php echo esc_attr( $last_name ); ?>" placeholder="<?php _e( 'Last name', 'wp-hotel-booking' ); ?>" />
				</div>
			</li>
			<li class="hb-form-field">
				<label class="hb-form-field-label"><?php _e( 'Address', 'wp-hotel-booking' ); ?>
					<span class="hb-required">*</span></label>

				<div class="hb-form-field-input">
					<input type="text" name="address" value="<?php echo esc_attr( $address ); ?>" placeholder="<?php _e( 'Address', 'wp-hotel-booking' ); ?>" />
				</div>
			</li>
			<li class="hb-form-field">
				<label class="hb-form-field-label"><?php _e( 'City', 'wp-hotel-booking' ); ?>
					<span class="hb-required">*</span></label>

				<div class="hb-form-field-input">
					<input type="text" name="city" value="<?php echo esc_attr( $city ); ?>" placeholder="<?php _e( 'City', 'wp-hotel-booking' ); ?>" />
				</div>
			</li>
			<li class="hb-form-field">
				<label class="hb-form-field-label"><?php _e( 'State', 'wp-hotel-booking' ); ?>
					<span class="hb-required">*</span></label>

				<div class="hb-form-field-input">
					<input type="text" name="state" value="<?php echo esc_attr( $state ); ?>" placeholder="<?php _e( 'State', 'wp-hotel-booking' ); ?>" />
				</div>
			</li>
		</ul>
		<ul class="hb-form-table col-2">
			<li class="hb-form-field">
				<label class="hb-form-field-label"><?php _e( 'Postal Code', 'wp-hotel-booking' ); ?>
					<span class="hb-required">*</span></label>

				<div class="hb-form-field-input">
					<input type="text" name="postal_code" value="<?php echo esc_attr( $postal_code ); ?>" placeholder="<?php _e( 'Postal code', 'wp-hotel-booking' ); ?>" />
				</div>
			</li>
			<li class="hb-form-field">
				<label class="hb-form-field-label"><?php _e( 'Country', 'wp-hotel-booking' ); ?>
					<span class="hb-required">*</span></label>

				<div class="hb-form-field-input">
					<?php hb_dropdown_countries( array( 'name' => 'country', 'show_option_none' => __( 'Country', 'wp-hotel-booking' ), 'selected' => $country ) ); ?>
				</div>
			</li>
			<li class="hb-form-field">
				<label class="hb-form-field-label"><?php _e( 'Phone', 'wp-hotel-booking' ); ?>
					<span class="hb-required">*</span></label>

				<div class="hb-form-field-input">
					<input type="text" name="phone" value="<?php echo esc_attr( $phone ); ?>" placeholder="<?php _e( 'Phone Number', 'wp-hotel-booking' ); ?>" />
				</div>
			</li>
			<li class="hb-form-field">
				<label class="hb-form-field-label"><?php _e( 'Email', 'wp-hotel-booking' ); ?>
					<span class="hb-required">*</span></label>

				<div class="hb-form-field-input">
					<input type="email" name="email" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php _e( 'Email address', 'wp-hotel-booking' ); ?>" />
				</div>
			</li>
			<li class="hb-form-field">
				<label class="hb-form-field-label"><?php _e( 'Fax', 'wp-hotel-booking' ); ?></label>

				<div class="hb-form-field-input">
					<input type="text" name="fax" value="<?php echo esc_attr( $fax ); ?>" placeholder="<?php _e( 'Fax', 'wp-hotel-booking' ); ?>" />
				</div>
			</li>
		</ul>
		<input type="hidden" name="existing-customer-id" value="" />
	</div>
</div>