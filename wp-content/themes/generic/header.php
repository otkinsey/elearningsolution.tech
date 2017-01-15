	<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "container" div.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?> >
	<head>

		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	<?php do_action( 'foundationpress_after_body' ); ?>

	<?php if ( get_theme_mod( 'wpt_mobile_menu_layout' ) == 'offcanvas' ) : ?>
	<div class="off-canvas-wrapper">
		<div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>
		<?php get_template_part( 'template-parts/mobile-off-canvas' ); ?>
	<?php endif; ?>

	<?php do_action( 'foundationpress_layout_start' ); ?>

	<script>
	new WOW().init();
	</script>
	<header id="masthead" class="site-header" role="banner">
		<div class="title-bar">
			<button class="menu-icon" type="button" aria-controls="mobile-menu" data-toggle="mobile-menu"></button>
			<div class="title-bar-title">
				<?php
					$args = array('post_type' => 'company-logo', 'name' => 'elite-training-videos');
					$query = new WP_Query($args);
				?>
				<?php while($query->have_posts()) : $query->the_post(); ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php echo get_the_post_thumbnail($query->ID); ?>
					<div class="logo_text">elearning <br><span class="">solutions</span></div>
				</a>
			</div>
		</div>

		<nav id="site-navigation" class="main-navigation top-bar" role="navigation" >
			<div class="top-bar-left">
				<ul class="menu">
					<li class="home" onclick="backToTop(event);">
						<!--a href="<?php ?>"  rel="home"-->
							<div class="logo columns large-3" >
								<?php echo get_the_post_thumbnail($query->ID); ?>
							</div>
							<div class="logo_text columns large-9">elearning<br>solutions</div>
						<!--/a-->
					</li>
				</ul>
			</div>
			<div class="top-bar-right" >
				<?php foundationpress_top_bar_r(); ?>
				<?php if ( ! get_theme_mod( 'wpt_mobile_menu_layout' ) || get_theme_mod( 'wpt_mobile_menu_layout' ) == 'topbar' ) : ?>
					<?php get_template_part( 'template-parts/mobile-top-bar' ); ?>
				<?php endif; ?>
			</div>
		</nav>
		<?php endwhile; ?>
	</header>

	<section class="container" >
		<?php do_action( 'foundationpress_after_header' );
