<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function hb_get_max_capacity_of_rooms() {
	static $max = null;
	if ( ! is_null( $max ) ) {
		return $max;
	}
	$terms = get_terms( 'hb_room_capacity', array( 'hide_empty' => false ) );
	if ( $terms ) foreach ( $terms as $term ) {
		$cap = get_term_meta( $term->term_id, 'hb_max_number_of_adults', true );
		/**
		 * @since  1.1.2
		 * use term meta
		 */
		if ( ! $cap ) {
			$cap = get_option( "hb_taxonomy_capacity_{$term->term_id}" );
		}
		if ( intval( $cap ) > $max ) {
			$max = $cap;
		}
	}
	return $max;
}

// get array search
function hb_get_capacity_of_rooms() {
	$terms = get_terms( 'hb_room_capacity', array( 'hide_empty' => false ) );
	$return = array();
	if ( $terms ) {
		foreach ( $terms as $term ) {
			$qty = get_term_meta( $term->term_id, 'hb_max_number_of_adults', true );
			/**
			 * @since  1.1.2
			 * use term meta
			 */
			if ( ! $qty ) {
				get_option( 'hb_taxonomy_capacity_' . $term->term_id );
			}
			if ( $qty ) {
				$return[$qty] = array(
						'value'	=> $term->term_id,
						'text'	=> $qty
					);
			}
		}
	}
	ksort( $return );
	return $return;
}

/**
 * List room capacities into dropdown select
 *
 * @param array
 *
 * @return string
 */
function hb_dropdown_room_capacities( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'echo' => true
		)
	);
	ob_start();
	wp_dropdown_categories(
		array_merge( $args,
			array(
				'taxonomy'   => 'hb_room_capacity',
				'hide_empty' => false,
				'name'       => 'hb-room-capacities'
			)
		)
	);

	$output = ob_get_clean();
	if ( $args['echo'] ) {
		echo sprintf( '%s', $output );
	}
	return $output;
}

/**
 * List room types into dropdown select
 *
 * @param array $args
 *
 * @return string
 */
function hb_dropdown_room_types( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'echo' => true
		)
	);
	ob_start();
	wp_dropdown_categories(
		array_merge( $args,
			array(
				'taxonomy'   => 'hb_room_type',
				'hide_empty' => false,
				'name'       => 'hb-room-types',
				'orderby'    => 'term_group',
				'echo'       => true
			)
		)
	);
	$output = ob_get_clean();

	if ( $args['echo'] ) {
		echo sprintf( '%s', $output );
	}
	return $output;
}

/**
 * List room types into dropdown select
 *
 * @param array $args
 *
 * @return string
 */
function hb_dropdown_rooms( $args = array( 'selected' => '' ) ) {
	global $wpdb;
	$posts = $wpdb->get_results( $wpdb->prepare(
		"SELECT ID, post_title FROM {$wpdb->posts} WHERE `post_type` = %s AND `post_status` = %s", 'hb_room', 'publish'
	), OBJECT );

	$output                    = '<select name="hb-room" id="hb-room-select">';
	$emptySelected             = new stdClass;
	$emptySelected->ID         = '';
	$emptySelected->post_title = __( '---Select Room---', 'wp-hotel-booking' );
	$posts                     = array_merge( array( $emptySelected ), $posts );
	foreach ( $posts as $key => $post ) {
		$output .= '<option value="' . $post->ID . '"' . ( $post->ID == $args['selected'] ? ' selected' : '' ) . '>' . $post->post_title . '</option>';
	}
	$output .= '</select>';
	return $output;
}

/**
 * Get room types taxonomy
 *
 * @param array $args
 *
 * @return array
 */
function hb_get_room_types( $args = array() ) {
	$args  = wp_parse_args(
		$args,
		array(
			'taxonomy'   => 'hb_room_type',
			'hide_empty' => 0,
			'orderby'    => 'term_group',
			'map_fields' => null
		)
	);
	$terms = (array) get_terms( "hb_room_type", $args );
	if ( is_array( $args['map_fields'] ) ) {
		$types = array();
		foreach ( $terms as $term ) {
			$type = new stdClass();
			foreach ( $args['map_fields'] as $from => $to ) {
				if ( !empty( $term->{$from} ) ) {
					$type->{$to} = $term->{$from};
				} else {
					$type->{$to} = null;
				}
			}
			$types[] = $type;
		}
	} else {
		$types = $terms;
	}
	return $types;
}

/**
 * Get room capacities taxonomy
 *
 * @param array $args
 *
 * @return array
 */
function hb_get_room_capacities( $args = array() ) {
	$args  = wp_parse_args(
		$args,
		array(
			'taxonomy'   => 'hb_room_capacity',
			'hide_empty' => 0,
			'orderby'    => 'term_group',
			'map_fields' => null
		)
	);
	$terms = (array) get_terms( 'hb_room_capacity', $args );
	if ( is_array( $args['map_fields'] ) ) {
		$types = array();
		foreach ( $terms as $term ) {
			$type = new stdClass();
			foreach ( $args['map_fields'] as $from => $to ) {
				if ( ! empty( $term->{$from} ) ) {
					$type->{$to} = $term->{$from};
				} else {
					$type->{$to} = null;
				}
			}
			$types[] = $type;
		}
	} else {
		$types = $terms;
	}
	return $types;
}

/**
 * Get list of child per each room with all available rooms
 *
 * @return mixed
 */
function hb_get_child_per_room() {
	global $wpdb;
	$query = $wpdb->prepare( "
        SELECT DISTINCT meta_value
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE p.post_type=%s
          AND meta_key=%s
          AND meta_value <> 0
        ORDER BY meta_value ASC
    ", 'hb_room', '_hb_max_child_per_room' );
	return $wpdb->get_col( $query );
}

/**
 * Get list of child per each room with all available rooms
 *
 * @return mixed
 */
function hb_get_max_child_of_rooms() {
	$rows = hb_get_child_per_room();
	if ( $rows ) {
		sort($rows);
		return $rows ? end( $rows ) : - 1;
	}
}

/**
 * List child of room into dropdown select
 *
 * @param array $args
 */
function hb_dropdown_child_per_room( $args = array() ) {
	$args      = wp_parse_args(
		$args,
		array(
			'name'     => '',
			'selected' => ''
		)
	);
	$max_child = hb_get_max_child_of_rooms();
	$output    = '<select name="' . $args['name'] . '">';
	$output .= '<option value="0">' . __( 'Select', 'wp-hotel-booking' ) . '</option>';
	if ( $max_child > 0 ) {
		for ( $i = 1; $i <= $max_child; $i ++ ) {
			$output .= sprintf( '<option value="%1$d"%2$s>%1$d</option>', $i, $args['selected'] == $i ? ' selected="selected"' : '' );
		}
	}
	$output .= '</select>';
	echo sprintf( '%s', $output );
}

/**
 * Get capacity of a room type
 *
 * @param $type_id
 *
 * @return int
 */
function hb_get_room_type_capacities( $type_id ) {
	return intval( get_option( "hb_taxonomy_capacity_{$type_id}" ) );
}

/**
 * Parse a param from request has encoded
 */
function hb_parse_request() {
	$params = hb_get_request( 'hotel-booking-params' );
	if ( $params ) {
		$params = maybe_unserialize( base64_decode( $params ) );
		if ( $params ) {
			foreach ( $params as $k => $v ) {
				$_GET[$k]     = sanitize_text_field( $v );
				$_POST[$k]    = sanitize_text_field( $v );
				$_REQUEST[$k] = sanitize_text_field( $v );
			}
		}
		if ( isset( $_GET['hotel-booking-params'] ) ) unset( $_GET['hotel-booking-params'] );
		if ( isset( $_POST['hotel-booking-params'] ) ) unset( $_POST['hotel-booking-params'] );
		if ( isset( $_REQUEST['hotel-booking-params'] ) ) unset( $_REQUEST['hotel-booking-params'] );
	}
}

add_action( 'init', 'hb_parse_request' );

/**
 * Get the list of common currencies
 *
 * @return mixed
 */
function hb_payment_currencies() {
	$currencies = array(
		'AED' => 'United Arab Emirates Dirham (د.إ)',
		'AUD' => 'Australian Dollars ($)',
		'BDT' => 'Bangladeshi Taka (৳&nbsp;)',
		'BRL' => 'Brazilian Real (R$)',
		'BGN' => 'Bulgarian Lev (лв.)',
		'CAD' => 'Canadian Dollars ($)',
		'CLP' => 'Chilean Peso ($)',
		'CNY' => 'Chinese Yuan (¥)',
		'COP' => 'Colombian Peso ($)',
		'CZK' => 'Czech Koruna (Kč)',
		'DKK' => 'Danish Krone (kr.)',
		'DOP' => 'Dominican Peso (RD$)',
		'EUR' => 'Euros (€)',
		'HKD' => 'Hong Kong Dollar ($)',
		'HRK' => 'Croatia kuna (Kn)',
		'HUF' => 'Hungarian Forint (Ft)',
		'ISK' => 'Icelandic krona (Kr.)',
		'IDR' => 'Indonesia Rupiah (Rp)',
		'INR' => 'Indian Rupee (Rs.)',
		'NPR' => 'Nepali Rupee (Rs.)',
		'ILS' => 'Israeli Shekel (₪)',
		'JPY' => 'Japanese Yen (¥)',
		'KIP' => 'Lao Kip (₭)',
		'KRW' => 'South Korean Won (₩)',
		'MYR' => 'Malaysian Ringgits (RM)',
		'MXN' => 'Mexican Peso ($)',
		'NGN' => 'Nigerian Naira (₦)',
		'NOK' => 'Norwegian Krone (kr)',
		'NZD' => 'New Zealand Dollar ($)',
		'PYG' => 'Paraguayan Guaraní (₲)',
		'PHP' => 'Philippine Pesos (₱)',
		'PLN' => 'Polish Zloty (zł)',
		'GBP' => 'Pounds Sterling (£)',
		'RON' => 'Romanian Leu (lei)',
		'RUB' => 'Russian Ruble (руб.)',
		'SGD' => 'Singapore Dollar ($)',
		'ZAR' => 'South African rand (R)',
		'SEK' => 'Swedish Krona (kr)',
		'CHF' => 'Swiss Franc (CHF)',
		'TWD' => 'Taiwan New Dollars (NT$)',
		'THB' => 'Thai Baht (฿)',
		'TRY' => 'Turkish Lira (₺)',
		'USD' => 'US Dollars ($)',
		'VND' => 'Vietnamese Dong (₫)',
		'EGP' => 'Egyptian Pound (EGP)'
	);

	return apply_filters( 'hb_payment_currencies', $currencies );
}

/**
 * Checks to see if is enable overwrite templates from theme
 *
 * @return bool
 */
function hb_enable_overwrite_template() {
	return HB_Settings::instance()->get( 'overwrite_templates' ) == 'on';
}

/**
 * Get a variable from request
 *
 * @param string
 * @param mixed
 * @param mixed
 *
 * @return mixed
 */
function hb_get_request( $name, $default = null, $var = '' ) {
	$return = $default;
	switch ( strtolower( $var ) ) {
		case 'post':
			$var = $_POST;
			break;
		case 'get':
			$var = $_GET;
			break;
		default:
			$var = $_REQUEST;
	}
	if ( ! empty( $var[$name] ) ) {
		$return = $var[$name];
	}
	if ( is_string( $return ) ) {
		$return = sanitize_text_field( $return );
	}
	return $return;
}

/**
 * Calculate the nights between to dates
 *
 * @param null $end
 * @param      $start
 *
 * @return float
 */
function hb_count_nights_two_dates( $end = null, $start ) {
	if ( !$end ) $end = time();
	else if ( is_string( $end ) ) {
		$end = @strtotime( $end );
	}
	if ( is_string( $start ) ) {
		$start = strtotime( $start );
	}
	$datediff = $end - $start;
	return floor( $datediff / ( 60 * 60 * 24 ) );
}

function hb_date_names() {
	$date_names = array(
		__( 'Sun', 'wp-hotel-booking' ),
		__( 'Mon', 'wp-hotel-booking' ),
		__( 'Tue', 'wp-hotel-booking' ),
		__( 'Web', 'wp-hotel-booking' ),
		__( 'Thu', 'wp-hotel-booking' ),
		__( 'Fri', 'wp-hotel-booking' ),
		__( 'Sat', 'wp-hotel-booking' )
	);
	return apply_filters( 'hb_date_names', $date_names );
}

function hb_date_to_name( $date ) {
	$date_names = hb_date_names();
	return $date_names[$date];
}

function hb_get_common_titles() {
	return apply_filters( 'hb_customer_titles', array(
			'mr'   => __( 'Mr.', 'wp-hotel-booking' ),
			'ms'   => __( 'Ms.', 'wp-hotel-booking' ),
			'mrs'  => __( 'Mrs.', 'wp-hotel-booking' ),
			'miss' => __( 'Miss.', 'wp-hotel-booking' ),
			'dr'   => __( 'Dr.', 'wp-hotel-booking' ),
			'prof' => __( 'Prof.', 'wp-hotel-booking' )
		)
	);
}

function hb_get_title_by_slug( $slug ) {
	$titles = hb_get_common_titles();
	return !empty( $titles[$slug] ) ? $titles[$slug] : '';
}

function hb_dropdown_titles( $args = array() ) {
	$args              = wp_parse_args(
		$args,
		array(
			'name'              => 'title',
			'selected'          => '',
			'show_option_none'  => __( 'Select', 'wp-hotel-booking' ),
			'option_none_value' => - 1,
			'echo'              => true
		)
	);
	$name              = '';
	$selected          = '';
	$echo              = false;
	$show_option_none  = false;
	$option_none_value = - 1;
	extract( $args );
	$titles = hb_get_common_titles();
	$output = '<select name="' . $name . '">';
	if ( $show_option_none ) {
		$output .= sprintf( '<option value="%s">%s</option>', $option_none_value, $show_option_none );
	}
	if ( $titles ) foreach ( $titles as $slug => $title ) {
		$output .= sprintf( '<option value="%s"%s>%s</option>', $slug, $slug == $selected ? ' selected="selected"' : '', $title );
	}
	$output .= '</select>';
	if ( $echo ) {
		echo sprintf( '%s', $output );
	}
	return $output;
}

/**
 * Create an empty object with all fields as a WP_Post object
 *
 * @return stdClass
 */
function hb_create_empty_post( $args = array() ) {
	$posts = get_posts(
		array(
			'post_type'      => 'any',
			'posts_per_page' => 1
		)
	);

	if ( $posts ) {
		foreach ( get_object_vars( $posts[0] ) as $key => $value ) {
			if ( !in_array( $key, $args ) )
				$posts[0]->{$key} = null;
			else
				$posts[0]->{$key} = $args[$key];
		}
		return $posts[0];
	}
	return new stdClass();
}

/**
 * Localize script for front-end
 *
 * @return mixed
 */
function hb_l18n() {
	$translation = array(
		'invalid_email'                  => __( 'Your email address is invalid', 'wp-hotel-booking' ),
		'no_payment_method_selected'     => __( 'Please select your payment method ', 'wp-hotel-booking' ),
		'confirm_tos'                    => __( 'Please accept our Terms and Conditions ', 'wp-hotel-booking' ),
		'no_rooms_selected'              => __( 'Please select at least one the room', 'wp-hotel-booking' ),
		'empty_customer_title'           => __( 'Please select your title', 'wp-hotel-booking' ),
		'empty_customer_first_name'      => __( 'Please enter your first name', 'wp-hotel-booking' ),
		'empty_customer_last_name'       => __( 'Please enter your last name', 'wp-hotel-booking' ),

		'empty_customer_address'         => __( 'Please enter your address', 'wp-hotel-booking' ),
		'empty_customer_city'            => __( 'Please enter your city name', 'wp-hotel-booking' ),
		'empty_customer_state'           => __( 'Please enter your state', 'wp-hotel-booking' ),
		'empty_customer_postal_code'     => __( 'Please enter your postal code', 'wp-hotel-booking' ),
		'empty_customer_country'         => __( 'Please select your country', 'wp-hotel-booking' ),
		'empty_customer_phone'           => __( 'Please enter your phone number', 'wp-hotel-booking' ),
		'customer_email_invalid'         => __( 'Your email is invalid', 'wp-hotel-booking' ),
		'customer_email_not_match'       => __( 'Your email does not match with existing email! Ok to create a new customer information', 'wp-hotel-booking' ),

		'empty_check_in_date'            => __( 'Please select check in date', 'wp-hotel-booking' ),
		'empty_check_out_date'           => __( 'Please select check out date', 'wp-hotel-booking' ),
		'check_in_date_must_be_greater'  => __( 'Check in date must be greater than the current', 'wp-hotel-booking' ),
		'check_out_date_must_be_greater' => __( 'Check out date must be greater than the check in', 'wp-hotel-booking' ),

		'enter_coupon_code'              => __( 'Please enter coupon code', 'wp-hotel-booking' ),
		'review_rating_required'         => __( 'Please select a rating', 'wp-hotel-booking' ),
		'waring'						 => array(
												'room_select'	=> __( 'Please select room number', 'wp-hotel-booking' ),
												'try_again'		=> __( 'Please try again!', 'wp-hotel-booking' )
										),
		'date_time_format'				=> hb_date_time_format_js(),
		'monthNames'					=> hb_month_name_js(),
		'monthNamesShort'				=> hb_month_name_short_js(),
		'dayNames'						=> hb_day_name_js(),
		'dayNamesShort'					=> hb_day_name_short_js(),
		'dayNamesMin '					=> hb_day_name_min_js(),
	);
	return apply_filters( 'hb_l18n', $translation );
}

// date time format
function hb_date_time_format_js() {
	// set detault datetime format datepicker
    $dateFormat = hb_get_date_format();

    switch ( $dateFormat ) {
    	case 'Y-m-d':
    		$return = 'yy-mm-dd';
    		break;

    	//
    	case 'Y/m/d':
    		$return = 'yy/mm/dd';
    		break;

    	case 'd/m/Y':
    		$return = 'dd/mm/yy';
    		break;

    	//
    	case 'd-m-Y':
    		$return = 'dd-mm-yy';
    		break;

    	case 'm/d/Y':
    		$return = 'mm/dd/yy';
    		break;

    	//
    	case 'm-d-Y':
    		$return = 'mm-dd-yy';
    		break;

    	case 'F j, Y':
    		$return = 'MM dd, yy';
    		break;

    	default:
    		$return = 'mm/dd/yy';
    		break;
    }
    return $return;
}

function hb_month_name_js() {
	return apply_filters( 'hotel_booking_month_name_js', array(
			__( 'January', 'wp-hotel-booking' ),
			__( 'February', 'wp-hotel-booking' ),
			__( 'March', 'wp-hotel-booking' ),
			__( 'April', 'wp-hotel-booking' ),
			__( 'May', 'wp-hotel-booking' ),
			__( 'June', 'wp-hotel-booking' ),
			__( 'July', 'wp-hotel-booking' ),
			__( 'August', 'wp-hotel-booking' ),
			__( 'September', 'wp-hotel-booking' ),
			__( 'October', 'wp-hotel-booking' ),
			__( 'November', 'wp-hotel-booking' ),
			__( 'December', 'wp-hotel-booking' )
		) );
}

function hb_month_name_short_js() {
	return apply_filters( 'hotel_booking_month_name_short_js', array(
			__( 'Jan', 'wp-hotel-booking' ),
			__( 'Feb', 'wp-hotel-booking' ),
			__( 'Mar', 'wp-hotel-booking' ),
			__( 'Apr', 'wp-hotel-booking' ),
			__( 'Maj', 'wp-hotel-booking' ),
			__( 'Jun', 'wp-hotel-booking' ),
			__( 'Jul', 'wp-hotel-booking' ),
			__( 'Aug', 'wp-hotel-booking' ),
			__( 'Sep', 'wp-hotel-booking' ),
			__( 'Oct', 'wp-hotel-booking' ),
			__( 'Nov', 'wp-hotel-booking' ),
			__( 'Dec', 'wp-hotel-booking' )
		) );
}

function hb_day_name_js() {
	return apply_filters( 'hotel_booking_day_name_js', array(
			__( 'Sunday', 'wp-hotel-booking' ),
			__( 'Monday', 'wp-hotel-booking' ),
			__( 'Tuesday', 'wp-hotel-booking' ),
			__( 'Wednesday', 'wp-hotel-booking' ),
			__( 'Thursday', 'wp-hotel-booking' ),
			__( 'Friday', 'wp-hotel-booking' ),
			__( 'Saturday', 'wp-hotel-booking' )
		) );
}

function hb_day_name_short_js() {
	return apply_filters( 'hotel_booking_day_name_short_js', hb_date_names() );
}
function hb_day_name_min_js(){
	return apply_filters( 'hotel_booking_day_name_min_js', array(
			__( 'Su', 'wp-hotel-booking' ),
			__( 'Mo', 'wp-hotel-booking' ),
			__( 'Tu', 'wp-hotel-booking' ),
			__( 'We', 'wp-hotel-booking' ),
			__( 'Thu', 'wp-hotel-booking' ),
			__( 'Fr', 'wp-hotel-booking' ),
			__( 'Sa', 'wp-hotel-booking' )
		) );
}
/**
 * Get tax setting
 *
 * @return float|mixed
 */
function hb_get_tax_settings() {
	$settings = HB_Settings::instance();
	if ( $tax = $settings->get( 'tax' ) ) {
		$tax = (float) $settings->get( 'tax' ) / 100;
	}

	if ( hb_price_including_tax() ) {
		$tax = $tax;
	}
	return $tax;
}

function hb_price_including_tax( $cart = false ) {
	$settings = HB_Settings::instance();
	return apply_filters( 'hb_price_including_tax', $settings->get( 'price_including_tax' ), $cart );
}

function hb_dropdown_numbers( $args = array() ) {
	$args              = wp_parse_args(
		$args,
		array(
			'min'               => 0,
			'max'               => 100,
			'selected'          => 0,
			'name'              => '',
			'class'             => '',
			'echo'              => true,
			'show_option_none'  => '',
			'option_none_value' => '',
			'options'			=> array()
		)
	);
	$min               = 0;
	$max               = 100;
	$selected          = 0;
	$name              = '';
	$id                = '';
	$class             = '';
	$echo              = true;
	$show_option_none  = false;
	$option_none_value = '';

	extract( $args );

	$id     = ! empty( $id ) ? $id : '';
	$output = '<select name="' . $name . '" ' . ( $id ? 'id="' . $id . '"' : '' ) . '' . ( $class ? ' class="' . $class . '"' : '' ) . '>';
	if ( $show_option_none ) {
		$output .= '<option value="' . $option_none_value . '">' . $show_option_none . '</option>';
	}

	if ( empty( $options ) ) {
		for ( $i = $min; $i <= $max; $i ++ ) {
			$output .= sprintf( '<option value="%1$d"%2$s>%1$d</option>', $i, $selected == $i ? ' selected="selected"' : '' );
		}
	} else {
		foreach ( $options as $option ) {
			$output .= sprintf( '<option value="%1$d"%2$s>%3$d</option>', $option['value'], $selected == $option['value'] ? ' selected="selected"' : '', $option['text'] );
		}
	}

	$output .= '</select>';
	if ( $echo ) {
		echo sprintf( '%s', $output );
	}
	return $output;
}

/**
 * @param $data
 */
function hb_send_json( $data ) {
	echo '<!-- HB_AJAX_START -->';
	@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
	echo wp_json_encode( $data );
	echo '<!-- HB_AJAX_END -->';
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		wp_die();
	else
		die;
}

function hb_is_ajax() {
	return defined( 'DOING_AJAX' ) && DOING_AJAX;
}

function hb_update_customer_info( $data ) {
	$is_new = false;

	$customer_id = false;
	if ( ! empty( $data['ID'] ) ) {
		$customer_id = absint( $data['ID'] );
		$customer    = get_post( $customer_id );
		if ( ! $customer || ( get_post_meta( $customer_id, '_hb_email', true ) != $data['email'] ) ) {
			$customer_id = 0;
		}
	}

	if ( ! $customer_id ) {
		$cus_id = hb_get_post_id_meta( '_hb_email', $data['email'] );
		if ( get_post( $cus_id ) ) {
			$customer_id = $cus_id;
		} else {
			$is_new      = true;
			$customer_id = wp_insert_post(
				array(
					'post_type'   => 'hb_customer',
					'post_status' => 'publish',
					'post_title'  => __( 'Hotel Booking Customer', 'wp-hotel-booking' )
				)
			);
		}
	}

	if ( $customer_id ) {
		foreach ( $data as $k => $v ) {
			if ( $k == 'ID' ) continue;
			if ( ! $is_new && $k == 'email' ) continue;
			update_post_meta( $customer_id, "_hb_{$k}", $v );
		}
	}
	return $customer_id;
}

function hb_get_customer( $customer_id ) {
	if ( is_string( $customer_id ) && intval( $customer_id ) == 0 ) {
		global $wpdb;
		$query       = $wpdb->prepare( "
            SELECT post_id
            FROM {$wpdb->postmeta}
            WHERE meta_key = %s
            AND meta_value = %s
        ", '_hb_email', $customer_id );
		$customer_id = $wpdb->get_var( $query );
	}
	$customer = get_post( $customer_id );
	if ( $customer && $customer->post_type == 'hb_customer' ) {
		$customer->data = array();
		$data           = get_post_meta( $customer->ID );
		foreach ( $data as $k => $v ) {
			$key_name                  = preg_replace( '!^_hb_!', '', $k );
			$customer->data[$key_name] = $v[0];
		}
	} else {
		$customer = null;
	}
	return $customer;
}

/**
 * Place order for a booking
 *
 * @throws Exception
 */
function hb_customer_place_order() {
	HB_Checkout::instance()->process_checkout();
	exit();
}

function hb_get_current_user() {
	return wp_get_current_user();
}

//add_action( 'init', 'hb_customer_place_order' );

function hb_get_currency() {
	$currencies     = hb_payment_currencies();
	$currency_codes = array_keys( $currencies );
	$currency       = reset( $currency_codes );

	return apply_filters( 'hb_currency', HB_Settings::instance()->get( 'currency', $currency ) );
}

function hb_get_currency_symbol( $currency = '' ) {
	if ( ! $currency ) {
		$currency = hb_get_currency();
	}

	switch ( $currency ) {
		case 'AED' :
			$currency_symbol = 'د.إ';
			break;
		case 'AUD' :
		case 'CAD' :
		case 'CLP' :
		case 'COP' :
		case 'HKD' :
		case 'MXN' :
		case 'NZD' :
		case 'SGD' :
		case 'USD' :
			$currency_symbol = '&#36;';
			break;
		case 'BDT':
			$currency_symbol = '&#2547;&nbsp;';
			break;
		case 'BGN' :
			$currency_symbol = '&#1083;&#1074;.';
			break;
		case 'BRL' :
			$currency_symbol = '&#82;&#36;';
			break;
		case 'CHF' :
			$currency_symbol = '&#67;&#72;&#70;';
			break;
		case 'CNY' :
		case 'JPY' :
		case 'RMB' :
			$currency_symbol = '&yen;';
			break;
		case 'CZK' :
			$currency_symbol = '&#75;&#269;';
			break;
		case 'DKK' :
			$currency_symbol = 'kr.';
			break;
		case 'DOP' :
			$currency_symbol = 'RD&#36;';
			break;
		case 'EGP' :
			$currency_symbol = 'EGP';
			break;
		case 'EUR' :
			$currency_symbol = '&euro;';
			break;
		case 'GBP' :
			$currency_symbol = '&pound;';
			break;
		case 'HRK' :
			$currency_symbol = 'Kn';
			break;
		case 'HUF' :
			$currency_symbol = '&#70;&#116;';
			break;
		case 'IDR' :
			$currency_symbol = 'Rp';
			break;
		case 'ILS' :
			$currency_symbol = '&#8362;';
			break;
		case 'INR' :
			$currency_symbol = 'Rs.';
			break;
		case 'ISK' :
			$currency_symbol = 'Kr.';
			break;
		case 'KIP' :
			$currency_symbol = '&#8365;';
			break;
		case 'KRW' :
			$currency_symbol = '&#8361;';
			break;
		case 'MYR' :
			$currency_symbol = '&#82;&#77;';
			break;
		case 'NGN' :
			$currency_symbol = '&#8358;';
			break;
		case 'NOK' :
			$currency_symbol = '&#107;&#114;';
			break;
		case 'NPR' :
			$currency_symbol = 'Rs.';
			break;
		case 'PHP' :
			$currency_symbol = '&#8369;';
			break;
		case 'PLN' :
			$currency_symbol = '&#122;&#322;';
			break;
		case 'PYG' :
			$currency_symbol = '&#8370;';
			break;
		case 'RON' :
			$currency_symbol = 'lei';
			break;
		case 'RUB' :
			$currency_symbol = '&#1088;&#1091;&#1073;.';
			break;
		case 'SEK' :
			$currency_symbol = '&#107;&#114;';
			break;
		case 'THB' :
			$currency_symbol = '&#3647;';
			break;
		case 'TRY' :
			$currency_symbol = '&#8378;';
			break;
		case 'TWD' :
			$currency_symbol = '&#78;&#84;&#36;';
			break;
		case 'UAH' :
			$currency_symbol = '&#8372;';
			break;
		case 'VND' :
			$currency_symbol = '&#8363;';
			break;
		case 'ZAR' :
			$currency_symbol = '&#82;';
			break;
		default :
			$currency_symbol = $currency;
			break;
	}

	return apply_filters( 'hb_currency_symbol', $currency_symbol, $currency );
}

function hb_format_price( $price, $with_currency = true ) {
	$settings                  = HB_Settings::instance();
	$position                  = $settings->get( 'price_currency_position' );
	$price_thousands_separator = $settings->get( 'price_thousands_separator' );
	$price_decimals_separator  = $settings->get( 'price_decimals_separator' );
	$price_number_of_decimal   = $settings->get( 'price_number_of_decimal' );
	if ( ! is_numeric( $price ) )
		$price = 0;

	$price  = apply_filters( 'tp_hotel_booking_price_switcher', $price );
	$before = $after = '';
	if ( $with_currency ) {
		if ( gettype( $with_currency ) != 'string' ) {
			$currency = hb_get_currency_symbol();
		} else {
			$currency = $with_currency;
		}

		switch ( $position ) {
			default:
				$before = $currency;
				break;
			case 'left_with_space':
				$before = $currency . ' ';
				break;
			case 'right':
				$after = $currency;
				break;
			case 'right_with_space':
				$after = ' ' . $currency;
		}
	}

	$price_format =
		$before
		. number_format(
			$price,
			$price_number_of_decimal,
			$price_decimals_separator,
			$price_thousands_separator
		) . $after;

	return apply_filters( 'hb_price_format', $price_format, $price, $with_currency );
}

function hb_search_rooms( $args = array() ) {
    global $wpdb;
    $adults_term = hb_get_request( 'adults', 0 );
    $adults = $adults_term ? get_term_meta( $adults_term, 'hb_max_number_of_adults', true) : 0;
    if ( ! $adults ) {
    	$adults = $adults_term ? (int)get_option( 'hb_taxonomy_capacity_' . $adults_term ) : 0;
    }
    $max_child = hb_get_request( 'max_child', 0 );

    $args = wp_parse_args(
        $args,
        array(
            'check_in_date'     => date( 'm/d/Y' ),
            'check_out_date'    => date( 'm/d/Y' ),
            'adults'            => $adults,
            'max_child'         => 0
        )
    );

    $check_in_time = strtotime( $args['check_in_date'] );
    $check_out_time = strtotime( $args['check_out_date'] );
    $check_in_date_to_time = mktime( 0, 0, 0, date( 'm', $check_in_time ), date( 'd', $check_in_time ), date( 'Y', $check_in_time ) );
    $check_out_date_to_time = mktime( 0, 0, 0, date( 'm', $check_out_time ), date( 'd', $check_out_time ), date( 'Y', $check_out_time ) );

    $results = array();

    /**
     * Count available rooms
     */
    $query_count_available = $wpdb->prepare("
        (
            SELECT ra.meta_value
            FROM {$wpdb->postmeta} ra
            INNER JOIN {$wpdb->posts} r ON ra.post_id = r.ID AND ra.meta_key = %s
                WHERE r.ID=rooms.ID
            GROUP BY ra.post_id
        )
    ", '_hb_num_of_rooms');

    $booking_status = $wpdb->prepare("
            (
                SELECT booked.post_status
                FROM {$wpdb->posts} booked
                WHERE
                    booked.post_type = %s
                    AND bk.meta_value = booked.ID
            )
        ", 'hb_booking');

    /**
     * Count booked rooms
     */
    $query_count_not_available = $wpdb->prepare("
        (
            SELECT count(book_item.ID)
            FROM {$wpdb->posts} book_item
            INNER JOIN {$wpdb->postmeta} bm ON bm.post_id = book_item.ID AND bm.meta_key = %s
            INNER JOIN {$wpdb->postmeta} bi ON bi.post_id = book_item.ID AND bi.meta_key = %s
            INNER JOIN {$wpdb->postmeta} bo ON bo.post_id = book_item.ID AND bo.meta_key = %s
            INNER JOIN {$wpdb->postmeta} bk ON bk.post_id = book_item.ID AND bk.meta_key = %s
            WHERE
                book_item.post_type = %s
                AND bm.meta_value = rooms.ID
                AND (
	                ( bi.meta_value <= %d AND bo.meta_value >= %d )
	                OR ( bi.meta_value >= %d AND bi.meta_value < %d )
	                OR ( bo.meta_value > %d AND bo.meta_value <= %d )
                )
                AND {$booking_status} IN ( %s, %s, %s )
        )
    ", '_hb_id', '_hb_check_in_date', '_hb_check_out_date', '_hb_booking_id', 'hb_booking_item',
        $check_in_date_to_time, $check_out_date_to_time,
        $check_in_date_to_time, $check_out_date_to_time,
        $check_in_date_to_time, $check_out_date_to_time,
        'hb-pending', 'hb-processing', 'hb-completed'
    );

    /**
     * merge query select room
     */
    $query = $wpdb->prepare("
        SELECT rooms.*, {$query_count_available} - {$query_count_not_available} as available_rooms
        FROM {$wpdb->posts} rooms
        LEFT JOIN {$wpdb->postmeta} pm ON pm.post_id = rooms.ID AND pm.meta_key = %s
        LEFT JOIN {$wpdb->postmeta} pm2 ON pm2.post_id = rooms.ID AND pm2.meta_key = %s
        LEFT JOIN {$wpdb->postmeta} pm3 ON pm3.post_id = rooms.ID AND pm3.meta_key = %s
        LEFT JOIN {$wpdb->termmeta} term_cap ON term_cap.term_id = pm3.meta_value AND term_cap.meta_key = %s
        WHERE
            rooms.post_type = %s
            AND rooms.post_status = %s
            AND pm.meta_value >= %d
            AND ( term_cap.meta_value >= %d OR pm2.meta_value >= %d )
        GROUP BY rooms.post_name, rooms.ID
        HAVING available_rooms > 0
        ORDER BY term_cap.meta_value DESC
    ", '_hb_max_child_per_room', '_hb_max_adults_per_room', '_hb_room_capacity', 'hb_max_number_of_adults', 'hb_room', 'publish', $max_child, $adults, $adults );

    $query = apply_filters( 'hb_search_query', $query, array(
            'check_in'      => $check_in_date_to_time,
            'check_out'     => $check_out_date_to_time,
            'adults'        => $adults,
            'child'         => $max_child
        ));

    if( $search = $wpdb->get_results( $query ) ){
        foreach( $search as $k => $p ){
            $room = HB_Room::instance( $p, array(
                    'check_in_date'     => date( 'm/d/Y', $check_in_time ),
                    'check_out_date'    => date( 'm/d/Y', $check_out_time ),
                    'quantity'          => 1
                ) );
            $room->post->available_rooms = (int)$p->available_rooms;
            $results[ $k ] = $room;
        }
    }

    if( TP_Hotel_Booking::instance()->cart->cart_contents && $search )
    {
    	$selected_id = array();
    	foreach ( TP_Hotel_Booking::instance()->cart->cart_contents as $k => $cart ) {
    		$selected_id[ $cart->product_id ] = $cart->quantity;
    	}

    	foreach ( $results as $k => $room ) {
    		if( array_key_exists( $room->post->ID, $selected_id ) ) {
    			$in = $room->get_data( 'check_in_date' );
    			$out = $room->get_data( 'check_out_date' );
    			if(
                    ( $in < $check_in_date_to_time && $check_out_date_to_time < $out )
                    || ( $in < $check_in_date_to_time && $check_out_date_to_time < $out )
                )
    			{
	    			$total = $search[ $k ]->available_rooms;
	    			$results[ $k ]->post->available_rooms = (int)$total - (int)$selected_id[ $room->post->ID ];
    			}
    		}
    	}
    }

    global $hb_settings;
    $total = count($results);
    $posts_per_page = (int)apply_filters( 'hb_number_search_results', $hb_settings->get( 'posts_per_page', 8 ) );
    $page = isset( $_GET['hb_page'] ) ? absint( $_GET['hb_page'] ) : 1;
    $offset = ( $page * $posts_per_page ) - $posts_per_page;
    $max_num_pages = ceil($total / $posts_per_page);

    $data = array_slice( $results, $offset, $posts_per_page);

    $GLOBALS['hb_search_rooms'] = array(
            'max_num_pages'         => $max_num_pages,
            'data'                  => $max_num_pages > 1 ? array_slice( $results, $offset, $posts_per_page) : $results,
            'total'                 => $total,
            'posts_per_page'        => $posts_per_page,
            'offset'                => $offset,
            'page'                  => $page,
        );

    return apply_filters( 'hb_search_results', $GLOBALS['hb_search_rooms'], $args );
}

function hb_get_payment_gateways( $args = array() ) {
	static $payment_gateways = array();
	if ( ! $payment_gateways ) {
		$defaults         = array(
			'offline-payment' => new HB_Payment_Gateway_Offline_Payment()
		);
		$payment_gateways = apply_filters( 'hb_payment_gateways', $defaults );
	}

	$args = wp_parse_args(
		$args,
		array(
			'enable' => false
		)
	);

	if ( $args['enable'] ) {
		$gateways = array();
		foreach ( $payment_gateways as $k => $gateway ) {
			$is_enable = is_callable( array( $gateway, 'is_enable' ) ) && $gateway->is_enable();
			if ( apply_filters( 'hb_payment_gateway_enable', $is_enable, $gateway ) ) {
				$gateways[$k] = $gateway;
			}
		}
	} else {
		$gateways = $payment_gateways;
	}
	return $gateways;
}

function hb_get_user_payment_method( $slug ) {
	$methods = hb_get_payment_gateways( array( 'enable' => true ) );
	$method  = false;
	if ( $methods && !empty( $methods[$slug] ) ) {
		$method = $methods[$slug];
	}
	return $method;
}

function hb_get_page_id( $name ) {
	$settings = hb_settings();
	return $settings->get( "{$name}_page_id" );
}

function hb_get_page_permalink( $name ) {
	return get_the_permalink( hb_get_page_id( $name ) );
}

function hb_get_endpoint_url( $endpoint, $value = '', $permalink = '' ) {
	if ( !$permalink )
		$permalink = get_permalink();

	if ( get_option( 'permalink_structure' ) ) {
		if ( strstr( $permalink, '?' ) ) {
			$query_string = '?' . parse_url( $permalink, PHP_URL_QUERY );
			$permalink    = current( explode( '?', $permalink ) );
		} else {
			$query_string = '';
		}
		$url = trailingslashit( $permalink ) . $endpoint . '/' . $value . $query_string;
	} else {
		$url = add_query_arg( $endpoint, $value, $permalink );
	}

	return apply_filters( 'hb_get_endpoint_url', $url, $endpoint, $value, $permalink );
}

function hb_get_advance_payment() {
	$advance_payment = HB_Settings::instance()->get( 'advance_payment' );
	return apply_filters( 'hb_advance_payment', $advance_payment );
}

function hb_do_transaction( $method, $transaction = false ) {
	do_action( 'hb_do_transaction_' . $method, $transaction );
}

/**
 * Process purchase request
 */
function hb_handle_purchase_request() {
	$method_var = 'hb-transaction-method';
	if( ! empty( $_REQUEST[$method_var] ) ) {
		hb_get_payment_gateways();
		$requested_transaction_method = sanitize_text_field( $_REQUEST[$method_var] );
		hb_do_transaction( $requested_transaction_method );
	} else if( hb_get_page_id( 'checkout' ) && is_page( hb_get_page_id( 'checkout' ) ) && empty( TP_Hotel_Booking::instance()->cart->cart_contents ) ){
		wp_redirect( hb_get_cart_url() ); exit();
	}
}

function hb_get_bookings( $args = array() ) {
	$defaults = array(
		'post_type' => 'hb_booking',
	);
	$args     = wp_parse_args( $args, $defaults );
	$bookings = get_posts( $args );
	return apply_filters( 'hb_get_bookings', $bookings, $args );
}

/**
 *
 */
function hb_maybe_modify_page_content( $content ) {
	global $post;
	if ( is_page() && ( $post->ID == hb_get_page_id( 'search' ) || has_shortcode( $content, 'hotel_booking' ) ) ) {

		// params search result
		$page       = hb_get_request( 'hotel-booking' );
		$start_date = hb_get_request( 'check_in_date' );
		$end_date   = hb_get_request( 'check_out_date' );
		$adults     = hb_get_request( 'adults' );
		$max_child  = hb_get_request( 'max_child' );

		$content = '[hotel_booking page="' . $page . '" check_in_date="' . $start_date . '" check_in_date="' . $end_date . '" adults="' . $adults . '" max_child="' . $max_child . '"]';
	}
	return $content;
}

add_filter( 'the_content', 'hb_maybe_modify_page_content' );

/**
 * Init some task when wp init
 */
function hb_init() {
	hb_get_payment_gateways();
}

add_action( 'init', 'hb_init' );

function hb_format_order_number( $order_number ) {
	return '#' . sprintf( "%'.010d", $order_number );
}

function hb_get_support_lightboxs() {
	$lightboxs = array(
		'lightbox2' => 'Lightbox 2'
		// ,
		// 'fancyBox'  => 'fancyBox'
	);
	return apply_filters( 'hb_lightboxs', $lightboxs );
}

function hb_get_countries() {
	$countries = array(
		'AF' => __( 'Afghanistan', 'wp-hotel-booking' ),
		'AX' => __( '&#197;land Islands', 'wp-hotel-booking' ),
		'AL' => __( 'Albania', 'wp-hotel-booking' ),
		'DZ' => __( 'Algeria', 'wp-hotel-booking' ),
		'AD' => __( 'Andorra', 'wp-hotel-booking' ),
		'AO' => __( 'Angola', 'wp-hotel-booking' ),
		'AI' => __( 'Anguilla', 'wp-hotel-booking' ),
		'AQ' => __( 'Antarctica', 'wp-hotel-booking' ),
		'AG' => __( 'Antigua and Barbuda', 'wp-hotel-booking' ),
		'AR' => __( 'Argentina', 'wp-hotel-booking' ),
		'AM' => __( 'Armenia', 'wp-hotel-booking' ),
		'AW' => __( 'Aruba', 'wp-hotel-booking' ),
		'AU' => __( 'Australia', 'wp-hotel-booking' ),
		'AT' => __( 'Austria', 'wp-hotel-booking' ),
		'AZ' => __( 'Azerbaijan', 'wp-hotel-booking' ),
		'BS' => __( 'Bahamas', 'wp-hotel-booking' ),
		'BH' => __( 'Bahrain', 'wp-hotel-booking' ),
		'BD' => __( 'Bangladesh', 'wp-hotel-booking' ),
		'BB' => __( 'Barbados', 'wp-hotel-booking' ),
		'BY' => __( 'Belarus', 'wp-hotel-booking' ),
		'BE' => __( 'Belgium', 'wp-hotel-booking' ),
		'PW' => __( 'Belau', 'wp-hotel-booking' ),
		'BZ' => __( 'Belize', 'wp-hotel-booking' ),
		'BJ' => __( 'Benin', 'wp-hotel-booking' ),
		'BM' => __( 'Bermuda', 'wp-hotel-booking' ),
		'BT' => __( 'Bhutan', 'wp-hotel-booking' ),
		'BO' => __( 'Bolivia', 'wp-hotel-booking' ),
		'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'wp-hotel-booking' ),
		'BA' => __( 'Bosnia and Herzegovina', 'wp-hotel-booking' ),
		'BW' => __( 'Botswana', 'wp-hotel-booking' ),
		'BV' => __( 'Bouvet Island', 'wp-hotel-booking' ),
		'BR' => __( 'Brazil', 'wp-hotel-booking' ),
		'IO' => __( 'British Indian Ocean Territory', 'wp-hotel-booking' ),
		'VG' => __( 'British Virgin Islands', 'wp-hotel-booking' ),
		'BN' => __( 'Brunei', 'wp-hotel-booking' ),
		'BG' => __( 'Bulgaria', 'wp-hotel-booking' ),
		'BF' => __( 'Burkina Faso', 'wp-hotel-booking' ),
		'BI' => __( 'Burundi', 'wp-hotel-booking' ),
		'KH' => __( 'Cambodia', 'wp-hotel-booking' ),
		'CM' => __( 'Cameroon', 'wp-hotel-booking' ),
		'CA' => __( 'Canada', 'wp-hotel-booking' ),
		'CV' => __( 'Cape Verde', 'wp-hotel-booking' ),
		'KY' => __( 'Cayman Islands', 'wp-hotel-booking' ),
		'CF' => __( 'Central African Republic', 'wp-hotel-booking' ),
		'TD' => __( 'Chad', 'wp-hotel-booking' ),
		'CL' => __( 'Chile', 'wp-hotel-booking' ),
		'CN' => __( 'China', 'wp-hotel-booking' ),
		'CX' => __( 'Christmas Island', 'wp-hotel-booking' ),
		'CC' => __( 'Cocos (Keeling) Islands', 'wp-hotel-booking' ),
		'CO' => __( 'Colombia', 'wp-hotel-booking' ),
		'KM' => __( 'Comoros', 'wp-hotel-booking' ),
		'CG' => __( 'Congo (Brazzaville)', 'wp-hotel-booking' ),
		'CD' => __( 'Congo (Kinshasa)', 'wp-hotel-booking' ),
		'CK' => __( 'Cook Islands', 'wp-hotel-booking' ),
		'CR' => __( 'Costa Rica', 'wp-hotel-booking' ),
		'HR' => __( 'Croatia', 'wp-hotel-booking' ),
		'CU' => __( 'Cuba', 'wp-hotel-booking' ),
		'CW' => __( 'Cura&Ccedil;ao', 'wp-hotel-booking' ),
		'CY' => __( 'Cyprus', 'wp-hotel-booking' ),
		'CZ' => __( 'Czech Republic', 'wp-hotel-booking' ),
		'DK' => __( 'Denmark', 'wp-hotel-booking' ),
		'DJ' => __( 'Djibouti', 'wp-hotel-booking' ),
		'DM' => __( 'Dominica', 'wp-hotel-booking' ),
		'DO' => __( 'Dominican Republic', 'wp-hotel-booking' ),
		'EC' => __( 'Ecuador', 'wp-hotel-booking' ),
		'EG' => __( 'Egypt', 'wp-hotel-booking' ),
		'SV' => __( 'El Salvador', 'wp-hotel-booking' ),
		'GQ' => __( 'Equatorial Guinea', 'wp-hotel-booking' ),
		'ER' => __( 'Eritrea', 'wp-hotel-booking' ),
		'EE' => __( 'Estonia', 'wp-hotel-booking' ),
		'ET' => __( 'Ethiopia', 'wp-hotel-booking' ),
		'FK' => __( 'Falkland Islands', 'wp-hotel-booking' ),
		'FO' => __( 'Faroe Islands', 'wp-hotel-booking' ),
		'FJ' => __( 'Fiji', 'wp-hotel-booking' ),
		'FI' => __( 'Finland', 'wp-hotel-booking' ),
		'FR' => __( 'France', 'wp-hotel-booking' ),
		'GF' => __( 'French Guiana', 'wp-hotel-booking' ),
		'PF' => __( 'French Polynesia', 'wp-hotel-booking' ),
		'TF' => __( 'French Southern Territories', 'wp-hotel-booking' ),
		'GA' => __( 'Gabon', 'wp-hotel-booking' ),
		'GM' => __( 'Gambia', 'wp-hotel-booking' ),
		'GE' => __( 'Georgia', 'wp-hotel-booking' ),
		'DE' => __( 'Germany', 'wp-hotel-booking' ),
		'GH' => __( 'Ghana', 'wp-hotel-booking' ),
		'GI' => __( 'Gibraltar', 'wp-hotel-booking' ),
		'GR' => __( 'Greece', 'wp-hotel-booking' ),
		'GL' => __( 'Greenland', 'wp-hotel-booking' ),
		'GD' => __( 'Grenada', 'wp-hotel-booking' ),
		'GP' => __( 'Guadeloupe', 'wp-hotel-booking' ),
		'GT' => __( 'Guatemala', 'wp-hotel-booking' ),
		'GG' => __( 'Guernsey', 'wp-hotel-booking' ),
		'GN' => __( 'Guinea', 'wp-hotel-booking' ),
		'GW' => __( 'Guinea-Bissau', 'wp-hotel-booking' ),
		'GY' => __( 'Guyana', 'wp-hotel-booking' ),
		'HT' => __( 'Haiti', 'wp-hotel-booking' ),
		'HM' => __( 'Heard Island and McDonald Islands', 'wp-hotel-booking' ),
		'HN' => __( 'Honduras', 'wp-hotel-booking' ),
		'HK' => __( 'Hong Kong', 'wp-hotel-booking' ),
		'HU' => __( 'Hungary', 'wp-hotel-booking' ),
		'IS' => __( 'Iceland', 'wp-hotel-booking' ),
		'IN' => __( 'India', 'wp-hotel-booking' ),
		'ID' => __( 'Indonesia', 'wp-hotel-booking' ),
		'IR' => __( 'Iran', 'wp-hotel-booking' ),
		'IQ' => __( 'Iraq', 'wp-hotel-booking' ),
		'IE' => __( 'Republic of Ireland', 'wp-hotel-booking' ),
		'IM' => __( 'Isle of Man', 'wp-hotel-booking' ),
		'IL' => __( 'Israel', 'wp-hotel-booking' ),
		'IT' => __( 'Italy', 'wp-hotel-booking' ),
		'CI' => __( 'Ivory Coast', 'wp-hotel-booking' ),
		'JM' => __( 'Jamaica', 'wp-hotel-booking' ),
		'JP' => __( 'Japan', 'wp-hotel-booking' ),
		'JE' => __( 'Jersey', 'wp-hotel-booking' ),
		'JO' => __( 'Jordan', 'wp-hotel-booking' ),
		'KZ' => __( 'Kazakhstan', 'wp-hotel-booking' ),
		'KE' => __( 'Kenya', 'wp-hotel-booking' ),
		'KI' => __( 'Kiribati', 'wp-hotel-booking' ),
		'KW' => __( 'Kuwait', 'wp-hotel-booking' ),
		'KG' => __( 'Kyrgyzstan', 'wp-hotel-booking' ),
		'LA' => __( 'Laos', 'wp-hotel-booking' ),
		'LV' => __( 'Latvia', 'wp-hotel-booking' ),
		'LB' => __( 'Lebanon', 'wp-hotel-booking' ),
		'LS' => __( 'Lesotho', 'wp-hotel-booking' ),
		'LR' => __( 'Liberia', 'wp-hotel-booking' ),
		'LY' => __( 'Libya', 'wp-hotel-booking' ),
		'LI' => __( 'Liechtenstein', 'wp-hotel-booking' ),
		'LT' => __( 'Lithuania', 'wp-hotel-booking' ),
		'LU' => __( 'Luxembourg', 'wp-hotel-booking' ),
		'MO' => __( 'Macao S.A.R., China', 'wp-hotel-booking' ),
		'MK' => __( 'Macedonia', 'wp-hotel-booking' ),
		'MG' => __( 'Madagascar', 'wp-hotel-booking' ),
		'MW' => __( 'Malawi', 'wp-hotel-booking' ),
		'MY' => __( 'Malaysia', 'wp-hotel-booking' ),
		'MV' => __( 'Maldives', 'wp-hotel-booking' ),
		'ML' => __( 'Mali', 'wp-hotel-booking' ),
		'MT' => __( 'Malta', 'wp-hotel-booking' ),
		'MH' => __( 'Marshall Islands', 'wp-hotel-booking' ),
		'MQ' => __( 'Martinique', 'wp-hotel-booking' ),
		'MR' => __( 'Mauritania', 'wp-hotel-booking' ),
		'MU' => __( 'Mauritius', 'wp-hotel-booking' ),
		'YT' => __( 'Mayotte', 'wp-hotel-booking' ),
		'MX' => __( 'Mexico', 'wp-hotel-booking' ),
		'FM' => __( 'Micronesia', 'wp-hotel-booking' ),
		'MD' => __( 'Moldova', 'wp-hotel-booking' ),
		'MC' => __( 'Monaco', 'wp-hotel-booking' ),
		'MN' => __( 'Mongolia', 'wp-hotel-booking' ),
		'ME' => __( 'Montenegro', 'wp-hotel-booking' ),
		'MS' => __( 'Montserrat', 'wp-hotel-booking' ),
		'MA' => __( 'Morocco', 'wp-hotel-booking' ),
		'MZ' => __( 'Mozambique', 'wp-hotel-booking' ),
		'MM' => __( 'Myanmar', 'wp-hotel-booking' ),
		'NA' => __( 'Namibia', 'wp-hotel-booking' ),
		'NR' => __( 'Nauru', 'wp-hotel-booking' ),
		'NP' => __( 'Nepal', 'wp-hotel-booking' ),
		'NL' => __( 'Netherlands', 'wp-hotel-booking' ),
		'AN' => __( 'Netherlands Antilles', 'wp-hotel-booking' ),
		'NC' => __( 'New Caledonia', 'wp-hotel-booking' ),
		'NZ' => __( 'New Zealand', 'wp-hotel-booking' ),
		'NI' => __( 'Nicaragua', 'wp-hotel-booking' ),
		'NE' => __( 'Niger', 'wp-hotel-booking' ),
		'NG' => __( 'Nigeria', 'wp-hotel-booking' ),
		'NU' => __( 'Niue', 'wp-hotel-booking' ),
		'NF' => __( 'Norfolk Island', 'wp-hotel-booking' ),
		'KP' => __( 'North Korea', 'wp-hotel-booking' ),
		'NO' => __( 'Norway', 'wp-hotel-booking' ),
		'OM' => __( 'Oman', 'wp-hotel-booking' ),
		'PK' => __( 'Pakistan', 'wp-hotel-booking' ),
		'PS' => __( 'Palestinian Territory', 'wp-hotel-booking' ),
		'PA' => __( 'Panama', 'wp-hotel-booking' ),
		'PG' => __( 'Papua New Guinea', 'wp-hotel-booking' ),
		'PY' => __( 'Paraguay', 'wp-hotel-booking' ),
		'PE' => __( 'Peru', 'wp-hotel-booking' ),
		'PH' => __( 'Philippines', 'wp-hotel-booking' ),
		'PN' => __( 'Pitcairn', 'wp-hotel-booking' ),
		'PL' => __( 'Poland', 'wp-hotel-booking' ),
		'PT' => __( 'Portugal', 'wp-hotel-booking' ),
		'QA' => __( 'Qatar', 'wp-hotel-booking' ),
		'RE' => __( 'Reunion', 'wp-hotel-booking' ),
		'RO' => __( 'Romania', 'wp-hotel-booking' ),
		'RU' => __( 'Russia', 'wp-hotel-booking' ),
		'RW' => __( 'Rwanda', 'wp-hotel-booking' ),
		'BL' => __( 'Saint Barth&eacute;lemy', 'wp-hotel-booking' ),
		'SH' => __( 'Saint Helena', 'wp-hotel-booking' ),
		'KN' => __( 'Saint Kitts and Nevis', 'wp-hotel-booking' ),
		'LC' => __( 'Saint Lucia', 'wp-hotel-booking' ),
		'MF' => __( 'Saint Martin (French part)', 'wp-hotel-booking' ),
		'SX' => __( 'Saint Martin (Dutch part)', 'wp-hotel-booking' ),
		'PM' => __( 'Saint Pierre and Miquelon', 'wp-hotel-booking' ),
		'VC' => __( 'Saint Vincent and the Grenadines', 'wp-hotel-booking' ),
		'SM' => __( 'San Marino', 'wp-hotel-booking' ),
		'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'wp-hotel-booking' ),
		'SA' => __( 'Saudi Arabia', 'wp-hotel-booking' ),
		'SN' => __( 'Senegal', 'wp-hotel-booking' ),
		'RS' => __( 'Serbia', 'wp-hotel-booking' ),
		'SC' => __( 'Seychelles', 'wp-hotel-booking' ),
		'SL' => __( 'Sierra Leone', 'wp-hotel-booking' ),
		'SG' => __( 'Singapore', 'wp-hotel-booking' ),
		'SK' => __( 'Slovakia', 'wp-hotel-booking' ),
		'SI' => __( 'Slovenia', 'wp-hotel-booking' ),
		'SB' => __( 'Solomon Islands', 'wp-hotel-booking' ),
		'SO' => __( 'Somalia', 'wp-hotel-booking' ),
		'ZA' => __( 'South Africa', 'wp-hotel-booking' ),
		'GS' => __( 'South Georgia/Sandwich Islands', 'wp-hotel-booking' ),
		'KR' => __( 'South Korea', 'wp-hotel-booking' ),
		'SS' => __( 'South Sudan', 'wp-hotel-booking' ),
		'ES' => __( 'Spain', 'wp-hotel-booking' ),
		'LK' => __( 'Sri Lanka', 'wp-hotel-booking' ),
		'SD' => __( 'Sudan', 'wp-hotel-booking' ),
		'SR' => __( 'Suriname', 'wp-hotel-booking' ),
		'SJ' => __( 'Svalbard and Jan Mayen', 'wp-hotel-booking' ),
		'SZ' => __( 'Swaziland', 'wp-hotel-booking' ),
		'SE' => __( 'Sweden', 'wp-hotel-booking' ),
		'CH' => __( 'Switzerland', 'wp-hotel-booking' ),
		'SY' => __( 'Syria', 'wp-hotel-booking' ),
		'TW' => __( 'Taiwan', 'wp-hotel-booking' ),
		'TJ' => __( 'Tajikistan', 'wp-hotel-booking' ),
		'TZ' => __( 'Tanzania', 'wp-hotel-booking' ),
		'TH' => __( 'Thailand', 'wp-hotel-booking' ),
		'TL' => __( 'Timor-Leste', 'wp-hotel-booking' ),
		'TG' => __( 'Togo', 'wp-hotel-booking' ),
		'TK' => __( 'Tokelau', 'wp-hotel-booking' ),
		'TO' => __( 'Tonga', 'wp-hotel-booking' ),
		'TT' => __( 'Trinidad and Tobago', 'wp-hotel-booking' ),
		'TN' => __( 'Tunisia', 'wp-hotel-booking' ),
		'TR' => __( 'Turkey', 'wp-hotel-booking' ),
		'TM' => __( 'Turkmenistan', 'wp-hotel-booking' ),
		'TC' => __( 'Turks and Caicos Islands', 'wp-hotel-booking' ),
		'TV' => __( 'Tuvalu', 'wp-hotel-booking' ),
		'UG' => __( 'Uganda', 'wp-hotel-booking' ),
		'UA' => __( 'Ukraine', 'wp-hotel-booking' ),
		'AE' => __( 'United Arab Emirates', 'wp-hotel-booking' ),
		'GB' => __( 'United Kingdom (UK)', 'wp-hotel-booking' ),
		'US' => __( 'United States (US)', 'wp-hotel-booking' ),
		'UY' => __( 'Uruguay', 'wp-hotel-booking' ),
		'UZ' => __( 'Uzbekistan', 'wp-hotel-booking' ),
		'VU' => __( 'Vanuatu', 'wp-hotel-booking' ),
		'VA' => __( 'Vatican', 'wp-hotel-booking' ),
		'VE' => __( 'Venezuela', 'wp-hotel-booking' ),
		'VN' => __( 'Vietnam', 'wp-hotel-booking' ),
		'WF' => __( 'Wallis and Futuna', 'wp-hotel-booking' ),
		'EH' => __( 'Western Sahara', 'wp-hotel-booking' ),
		'WS' => __( 'Western Samoa', 'wp-hotel-booking' ),
		'YE' => __( 'Yemen', 'wp-hotel-booking' ),
		'ZM' => __( 'Zambia', 'wp-hotel-booking' ),
		'ZW' => __( 'Zimbabwe', 'wp-hotel-booking' )
	);
	return $countries;
}

function hb_dropdown_countries( $args = array() ) {
	$countries = hb_get_countries();
	$args      = wp_parse_args( $args, array(
			'name'              => 'countries',
			'selected'          => '',
			'show_option_none'  => false,
			'option_none_value' => ''
		)
	);
	echo '<select name="' . $args['name'] . '">';
	if ( $args['show_option_none'] ) {
		echo '<option value="' . $args['option_none_value'] . '">' . $args['show_option_none'] . '</option>';
	}
	foreach ( $countries as $code => $name ) {
		echo '<option value="' . $name . '" ' . selected( $name == $args['selected'] ) . '>' . $name . '</option>';
	}
	echo '</select>';
}

/**
 * Add a message to queue
 *
 * @param        $message
 * @param string $type
 */
function hb_add_message( $message, $type = 'message' ) {
	$messages = get_transient( 'hb_message_' . session_id() );
	if ( empty( $messages ) ) {
		$messages = array();
	}

	$messages[] = array(
		'type'    => $type,
		'message' => $message
	);

	// hold in transient for 3 minutes
	set_transient( 'hb_message_' . session_id(), $messages, MINUTE_IN_SECONDS * 3 );
}

function hb_get_customer_fullname( $customer_id, $with_title = false ) {
	if ( $customer_id ) {
		$customer = HB_Customer::instance( $customer_id );
		if ( $with_title ) {
			$title = hb_get_title_by_slug( $customer->title );
		} else {
			$title = '';
		}

		return sprintf( '%s%s %s', $title ? $title . ' ' : '', $customer->first_name, $customer->last_name );
	}
}

if ( ! function_exists( 'is_room_category' ) ) {

	/**
	 * is_room_category - Returns true when viewing a room category.
	 *
	 * @param  string $term (default: '') The term slug your checking for. Leave blank to return true on any.
	 *
	 * @return bool
	 */
	function is_room_category( $term = '' ) {
		return is_tax( 'hb_room', $term );
	}
}

if ( ! function_exists( 'is_room_taxonomy' ) ) {

	/**
	 * Returns true when viewing a room taxonomy archive.
	 * @return bool
	 */
	function is_room_taxonomy() {
		return is_tax( get_object_taxonomies( 'hb_room' ) );
	}
}

if ( ! function_exists( 'hb_render_label_shortcode' ) ) {
	/**
	 * Returns html label shortcode search.
	 * @return html
	 */
	function hb_render_label_shortcode( $atts = array(), $name = '', $text = '', $check = '' ) {
		$show = false;
		if ( !isset( $atts[$name] ) || strtolower( $atts[$name] ) === $check )
			$show = true;
		if ( $show === false )
			return;

		echo '<label>' . sprintf( __( '%1$s', 'wp-hotel-booking' ), $text ) . '</label>';
	}
}

if ( ! function_exists( 'hb_get_price_plan_room' ) ) {
	/**
	 * Returns array price of room.
	 * @return array
	 */
	function hb_get_price_plan_room( $post_id = null ) {
		if ( $post_id === null )
			return null;
		$pricing_plans = get_posts(
			array(
				'post_type'      => 'hb_pricing_plan',
				'posts_per_page' => 9999,
				'meta_query'     => array(
					array(
						'key'   => '_hb_pricing_plan_room',
						'value' => $post_id
					)
				)
			)
		);
		if ( !$pricing_plans )
			return null;
		$pricing_plans = array_pop( $pricing_plans );
		$prices        = get_post_meta( $pricing_plans->ID, '_hb_pricing_plan_prices', true );
		$price_plans   = array();
		if ( $pricing_plans && $prices ) {
			foreach ( $prices as $key => $price ) {
				$price_plans = array_merge( $price_plans, $price );
			}
		}
		return array_map( 'hb_before_generate_price', $price_plans );
	}
}

if ( ! function_exists( 'hb_before_generate_price' ) ) {
	function hb_before_generate_price( $price_plans ) {
		$tax      = 0;
		$settings = HB_Settings::instance();
		if ( $settings->get( 'price_including_tax' ) ) {
			$tax = $settings->get( 'tax' );
			$tax = (float) $tax / 100;
		}

		if ( hb_price_including_tax() ) {
			$price_plans = $price_plans + $price_plans * $tax;
		}
		return $price_plans;
	}
}

/**
 * Checks to see if a user is booked room
 *
 * @param string $customer_email
 * @param int    $room_id
 *
 * @return bool
 */
function hb_customer_booked_room( $customer_email, $room_id ) {
	return true;
}

/**
 * filter email from
 */
function hb_wp_mail_from( $email ) {
	global $hb_settings;
	if ( $email = $hb_settings->get( 'email_general_from_email', get_option( 'admin_email' ) ) ) {
		if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return $email;
		}
	}
	return $email;
}
function hb_wp_mail_from_name( $name ) {
	global $hb_settings;
	if ( $name = $hb_settings->get( 'email_general_from_name' ) ) {
		return $name;
	}
	return $name;
}

/**
 * Send email to user after they booked room
 *
 * @param int $booking_id
 */
function hb_new_booking_email( $booking_id ) {
	$settings = HB_Settings::instance();
	$booking  = HB_Booking::instance( $booking_id );

	$to            = $settings->get( 'email_new_booking_recipients' );
	$subject       = $settings->get( 'email_new_booking_subject' );
	$email_heading = $settings->get( 'email_new_booking_heading' );
	$format        = $settings->get( 'email_new_booking_format' );
	if ( ! $subject ) {
		$subject = '[{site_title}] New customer booking ({order_number}) - {order_date}';
	}

	$find = array(
		'order-date'   => '{order_date}',
		'order-number' => '{order_number}',
		'site-title'   => '{site_title}'
	);

	$replace = array(
		'order-date'   => date_i18n( 'd.m.Y', strtotime( date( 'd.m.Y' ) ) ),
		'order-number' => $booking->get_booking_number(),
		'site-title'   => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES )
	);

	$subject = str_replace( $find, $replace, $subject );

	if ( ! $email_heading ) {
		$email_heading = __( 'New customer booking', 'wp-hotel-booking' );
	}

	// new version 1.1
	if( get_post_meta( $booking_id, '_hb_booking_cart_params' , true ) ) {
		$body = hb_get_template_content( 'emails/email-booking.php', array(
			'email_heading' => $email_heading,
			'booking'       => HB_Booking::instance( $booking_id )
		) );
	} else if ( get_post_meta( $booking_id, '_hb_booking_params' , true ) ) {
		$body = hb_get_template_content( 'emails/admin-new-booking.php', array(
			'email_heading' => $email_heading,
			'booking'       => HB_Booking::instance( $booking_id )
		) );
	}

	// get CSS styles
	ob_start();
	hb_get_template( 'emails/email-styles.php' );
	$css = apply_filters( 'hb_email_styles', ob_get_clean() );
	$css = preg_replace( '!</?style>!', '', $css );
	print_r( $css );
	try {
		if ( ! class_exists( 'Emogrifier') ) {
			TP_Hotel_Booking::instance()->_include( 'includes/libraries/class-emogrifier.php' );
		}
		// apply CSS styles inline for picky email clients
		$emogrifier = new Emogrifier( $body, $css );
		$body       = $emogrifier->emogrify();

	} catch ( Exception $e ) {

	}

	$headers = "Content-Type: " . ( $format == 'html' ? 'text/html' : 'text/plain' ) . "\r\n";
	$send    = wp_mail( $to, $subject, $body, $headers );
	return $send;
}

add_action( 'hb_booking_status_pending_to_processing', 'hb_new_booking_email' );
add_action( 'hb_booking_status_publish_to_processing', 'hb_new_booking_email' );
add_action( 'hb_booking_status_pending_to_completed', 'hb_new_booking_email' );
add_action( 'hb_booking_status_publish_to_completed', 'hb_new_booking_email' );

/**
 * Filter content type to text/html for email
 *
 * @return string
 */
function hb_set_html_content_type(){
    return 'text/html';
}

/**
 * Booking details for email content
 *
 * @param $booking_id
 * @return string
 */
function hb_new_customer_booking_details( $booking_id ) {
    $booking = HB_Booking::instance( $booking_id );
    $customer = HB_Customer::instance( $booking->customer_id );
    // cart params
    $cart_params = apply_filters( 'hotel_booking_admin_cart_params', $booking->get_cart_params() );

    $title = hb_get_title_by_slug( $customer->title );
    $first_name = $customer->first_name;
    $last_name = $customer->last_name;
    $customer_name = sprintf( '%s %s %s', $title ? $title : 'Cus.', $first_name, $last_name );

    $currency = hb_get_currency_symbol( $booking->currency );

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

    ob_start();
?>
    <table style="color: #444444;background-color: #DDD;font-family: verdana, arial, sans-serif; font-size: 14px; min-width: 800px;" cellpadding="5" cellspacing="1">
        <tbody>
            <tr style="background-color: #F5F5F5;">
                <td colspan="7">
                    <h3 style="margin: 5px 0;"><?php printf( __( 'Booking Details %s(%s)', 'wp-hotel-booking' ), hb_format_order_number( $booking_id ), hb_get_booking_status_label( $booking_id ) ); ?></h3>
                </td>
            </tr>
            <tr style="background-color: #FFFFFF;">
                <td style="font-weight: bold;">
                    <?php _e( 'Customer Name', 'wp-hotel-booking' ); ?>
                </td>
                <td colspan="6" ><?php echo esc_html( $customer_name ); ?></td>
            </tr>
            <tr style="background-color: #F5F5F5;">
                <td colspan="7">
                    <h3 style="margin: 5px 0;"><?php _e( 'Booking Rooms', 'wp-hotel-booking' ) ; ?></h3>
                </td>
            </tr>
            <tr style="background-color: #FFFFFF;">
                <td style="font-weight: bold;"><?php _e( 'Room', 'wp-hotel-booking' ); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php _e( 'Capacity', 'wp-hotel-booking' ); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php _e( 'Quantity', 'wp-hotel-booking' ); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php _e( 'Check in', 'wp-hotel-booking' ); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php _e( 'Check out', 'wp-hotel-booking' ); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php _e( 'Night', 'wp-hotel-booking' ); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php _e( 'Total', 'wp-hotel-booking' ); ?></td>
            </tr>
            <?php if( $cart_params ): ?>
                <?php foreach ( $rooms as $cart_id => $room ): ?>

                        <tr style="background-color: #FFFFFF;">
                            <td style="text-align: center;" rowspan="<?php echo array_key_exists( $cart_id, $child ) ? count( $child[ $cart_id ] ) + 2 : 1 ?>">
                                <a href="<?php echo esc_attr( get_the_permalink( $room->ID ) ); ?>"><?php echo esc_html( $room->name ); ?><?php printf( '%s', $room->capacity_title ? ' ('.$room->capacity_title.')' : '' ); ?></a>
                            </td>
                            <td style="text-align: right;"><?php echo sprintf( _n( '%d adult', '%d adults', $room->capacity, 'wp-hotel-booking' ), $room->capacity ); ?> </td>
                            <td style="text-align: right;"><?php echo esc_html( $room->quantity ); ?></td>
                            <td style="text-align: right;"><?php echo esc_html( date_i18n( hb_get_date_format(), strtotime( $room->get_data( 'check_in_date' ) ) ) ); ?></td>
                            <td style="text-align: right;"><?php echo esc_html( date_i18n( hb_get_date_format(), strtotime( $room->get_data( 'check_out_date' ) ) ) ); ?></td>
                            <td style="text-align: right;"><?php echo hb_count_nights_two_dates( $room->get_data( 'check_out_date' ), $room->get_data( 'check_in_date' ) ) ?></td>
                            <td style="text-align: right;">
                                <?php echo sprintf( '%s', hb_format_price( $rooms[ $cart_id ]->amount_singular_exclude_tax, hb_get_currency_symbol( $booking->currency ) ) ); ?>
                            </td>
                        </tr>

                        <?php do_action( 'hotel_booking_email_new_booking', $cart_params, $cart_id, $booking ); ?>

                <?php endforeach; ?>
            <?php endif; ?>
            <tr style="background-color: #FFFFFF;">
                <td colspan="6" style="font-weight: bold;"><?php _e( 'Sub Total', 'wp-hotel-booking' ); ?></td>
                <td style=" text-align: right;"><?php echo hb_format_price( $booking->sub_total, $currency ); ?></td>
            </tr>
            <?php if ( $booking->tax ) : ?>
                <tr style="background-color: #FFFFFF;">
                    <td colspan="6" style="font-weight: bold;"><?php _e( 'Tax', 'wp-hotel-booking' ); ?></td>
                    <td style="text-align: right;"><?php echo abs( $booking->tax * 100 ) . '%' ?></td>
                </tr>
            <?php endif; ?>
            <tr style="background-color: #FFFFFF;">
                <td colspan="6" style="font-weight: bold;"><?php _e( 'Grand Total', 'wp-hotel-booking' ); ?></td>
                <td style="text-align: right;"><?php echo sprintf( '%s', hb_format_price( $booking->total, $currency ) ); ?></td>
            </tr>
        </tbody>
    </table>
<?php
    return ob_get_clean();
}

add_action( 'hb_new_booking', 'hb_new_customer_booking_email' );
add_action( 'hb_booking_status_changed', 'hb_new_customer_booking_email' );
// send mail
function hb_new_customer_booking_email( $booking_id = null ) {
    $return = null;
    if ( $booking_id ) {
        $customer_id = HB_Booking::instance( $booking_id )->customer_id;
        $customer = null;
        if( $customer_id ) {
            $customer = HB_Customer::instance( $customer_id );
        } else {
            throw new Exception( __( 'Customer is not exists!', 'wp-hotel-booking' ) ); die();
        }
        $settings = HB_Settings::instance()->get('offline-payment');
        $email_subject = ! empty( $settings['email_subject'] ) ? $settings['email_subject'] : false;
        $email_content = ! empty( $settings['email_content'] ) ? $settings['email_content'] : false;

        if( function_exists( 'wpautop' ) ) {
            $email_content = wpautop( $email_content );
        }
        if ( preg_match( '!{{customer_name}}!', $email_content ) ) {
            $email_content = preg_replace( '!\{\{customer_name\}\}!', hb_get_customer_fullname( $customer_id, true ), $email_content );
        }
        if ( preg_match( '!{{site_name}}!', $email_content ) ) {
            $email_content = preg_replace( '!\{\{site_name\}\}!', get_bloginfo( 'name' ), $email_content );
        }
        if ( preg_match( '!{{booking_details}}!', $email_content ) ) {
            // email template
            $booking_details = hb_new_customer_booking_details( $booking_id );
            $email_content = preg_replace( '!\{\{booking_details\}\}!', $booking_details, $email_content );
        }

        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        // set mail from email
        add_filter( 'wp_mail_from', 'hb_wp_mail_from' );
        // set mail from name
        add_filter( 'wp_mail_from_name', 'hb_wp_mail_from_name' );
        add_filter('wp_mail_content_type', 'hb_set_html_content_type' );
        $to = $customer->email;
        $return = wp_mail( $to, $email_subject, stripslashes( $email_content ), $headers );

        remove_filter('wp_mail_content_type', 'hb_set_html_content_type');
    }

    return $return;
}

function hb_get_booking_id_by_key( $booking_key ) {
	global $wpdb;

	$booking_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = '_hb_booking_key' AND meta_value = %s", $booking_key ) );

	return $booking_id;
}

function hb_get_booking_status_label( $booking_id ) {
	$statuses = hb_get_booking_statuses();
	if ( is_numeric( $booking_id ) ) {
		$status = get_post_status( $booking_id );
	} else {
		$status = $booking_id;
	}
	return ! empty( $statuses[$status] ) ? $statuses[$status] : __( 'Pending', 'wp-hotel-booking' );
}

/**
 * Get date format
 *
 * @return string
 */
function hb_date_format() {
	return apply_filters( 'hb_date_format', 'd M Y' );
}

if ( ! function_exists( 'is_room' ) ) {

	/**
	 * @return bool
	 */
	function is_room() {
		return is_singular( array( 'hb_room' ) );
	}
}

if ( ! function_exists( 'hb_get_url' ) ) {
	function hb_get_url( $params = array() ) {
		global $hb_settings;
		$query_str = '';
		if ( ! empty( $params ) ) {
			$query_str = '?hotel-booking-params=' . base64_encode( serialize( $params ) );
		}
		return get_the_permalink( $hb_settings->get( 'search_page_id' ) ) . $query_str ;
	}
}

if ( ! function_exists( 'hb_get_cart_url' ) ) {
	function hb_get_cart_url() {
		global $hb_settings;
		$id = hb_get_page_id( 'my-rooms' );
		if ( $id && $hb_settings->get( 'my-rooms' ) ) {
			$url = get_the_permalink( $id );
		} else {
			$url = hb_get_url( array( 'hotel-booking' => 'cart' ) );
		}
		// var_dump($url);
		return apply_filters( 'hb_cart_url', $url );
	}
}

if ( ! function_exists( 'hb_get_checkout_url' ) ) {
	function hb_get_checkout_url() {
		global $hb_settings;
		$id = hb_get_page_id( 'checkout' );

		if ( $id && $hb_settings->get( 'checkout' ) ) {
			$url = get_the_permalink( $id );
		} else {
			$url = hb_get_url( array( 'hotel-booking' => 'checkout' ) );
		}
		return apply_filters( 'hb_checkout_url', $url );
	}
}

if ( ! function_exists( 'hb_random_color_part' ) ) {
	function hb_random_color_part() {
		return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT );
	}

	function hb_random_color() {
		return '#' . hb_random_color_part() . hb_random_color_part() . hb_random_color_part();
	}
}

if ( ! function_exists( 'hb_get_post_id_meta' ) ) {

	function hb_get_post_id_meta( $key, $value ) {
		global $wpdb;
		$meta = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE meta_key='".esc_sql( $key )."' AND meta_value='".esc_sql( $value )."'");
		if ( is_array( $meta ) && ! empty( $meta ) && isset( $meta[0] ) ) {
			$meta = $meta[0];
		}
		if ( is_object($meta) ) {
			return $meta->post_id;
		}
		else {
			return false;
		}
	}
}

if ( ! function_exists( 'hb_get_date_format' ) ) {
	function hb_get_date_format(){
	    $dateFormat = get_option( 'date_format' );

	    $dateCustomFormat = get_option( 'date_format_custom' );
	    if ( ! $dateFormat && $dateCustomFormat ) {
	    	$dateFormat = $dateCustomFormat;
	    }

	    return $dateFormat;
	}
}