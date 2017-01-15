<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "off-canvas-wrap" div and all content after.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>

		</section>
		<div id="footer-container">
			<footer id="footer">
				<?php $args = array('post_type' => 'company-logo', 'name' => 'footer-logo'); ?>
				<?php $query = new WP_Query($args); ?>
				<?php while( $query->have_posts() ) : $query->the_post() ; ?>
					<section>
						<div class="large-4 columns">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<div class="logo columns large-1" >
									<?php echo get_the_post_thumbnail($query->ID); ?>
								</div>
								<div class="logo_text columns large-11">elearning<br>solutions</div>
							</a>
							<span class="subscript">&copy; 2016 copyright Elearning Solutions</span>
						</div>
						<div class="large-4 columns"></div>
						<div class="large-4 columns">
							<i class="sm fa fa-facebook"></i>
							<i class="sm fa fa-twitter"></i>
							<i class="sm fa fa-linkedin"></i>
						</div>
					</section>

			</footer>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
		</div>

		<?php do_action( 'foundationpress_layout_end' ); ?>

<?php if ( get_theme_mod( 'wpt_mobile_menu_layout' ) == 'offcanvas' ) : ?>
		</div><!-- Close off-canvas wrapper inner -->
	</div><!-- Close off-canvas wrapper -->
</div><!-- Close off-canvas content wrapper -->
<?php endif; ?>


<?php wp_footer(); ?>
<?php do_action( 'foundationpress_before_closing_body' ); ?>
</body>
</html>
