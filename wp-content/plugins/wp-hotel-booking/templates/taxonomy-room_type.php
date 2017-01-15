<?php
/**
 * The Template for displaying all archive products.
 *
 * Override this template by copying it to yourtheme/wp-hotel-booking/templates/taxonomy-room_type.php
 *
 * @author 		ThimPress
 * @package 	wp-hotel-booking/templates
 * @version     1.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

hb_get_template_part( 'archive', 'room' );