<?php
/**
 * Enqueue all styles and scripts
 *
 * Learn more about enqueue_script: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_script}
 * Learn more about enqueue_style: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_style }
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

if ( ! function_exists( 'foundationpress_scripts' ) ) :
	function foundationpress_scripts() {

	// Enqueue the main Stylesheet.
	wp_enqueue_style( 'main-stylesheet', get_template_directory_uri() . '/assets/stylesheets/foundation.css', array(), '2.6.1', 'all' );
	wp_enqueue_style( 'custom-stylesheet', get_template_directory_uri() . '/assets/stylesheets/custom.css', array(), '2.6.1', 'all' );
	wp_enqueue_style( 'wow-stylesheet', get_template_directory_uri() . '/assets/javascript/custom/WOW/css/libs/animate.css', array(), '2.6.1', 'all' );
	// Deregister the jquery version bundled with WordPress.
	wp_deregister_script( 'jquery' );

	// CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
	wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js', array(), '2.1.0', false );
	// wp_enqueue_script( 'jquery', get_template_directory_uri() . '/assets/javascript/jquery-1.11.3.js', array(), '2.1.0', false );
	// It's a good idea to do this, performance-wise. No need to load everything if you're just going to use the grid anyway, you know :)
	wp_enqueue_script( 'foundation', get_template_directory_uri() . '/assets/javascript/foundation.js', array('jquery'), '2.6.1', true );
	wp_enqueue_script( 'custom', get_template_directory_uri() . '/assets/javascript/custom/custom.js', array('jquery'), '2.6.1', true );
	// wp_enqueue_script('offscreen', get_template_directory_uri() . '/assets/javascript/custom/offCanvas.js' , array('jquery'), '2.6.1', true);
	 wp_enqueue_script('wow', get_template_directory_uri() . '/assets/javascript/custom/WOW/dist/wow.js', array('jquery'), '2.6.1', false);
	// Add the comment-reply library on pages where it is necessary
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	}

	add_action( 'wp_enqueue_scripts', 'foundationpress_scripts' );
endif;
