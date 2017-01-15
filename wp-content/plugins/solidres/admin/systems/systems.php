<?php
/*------------------------------------------------------------------------
Solidres - Hotel booking plugin for WordPress
------------------------------------------------------------------------
@Author    Solidres Team
@Website   http://www.solidres.com
@Copyright Copyright (C) 2013 - 2016 Solidres. All Rights Reserved.
@License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sr_systems() {
	$solidres_plugins = array(
		//'camera_slideshow',
		'complextariff',
		//'hub',
		'invoice',
		'limitbooking',
		'simple_gallery',
        'statistics',
		'discount',
		'advancedextra',
		'paypal',
        //'cielo',
        //'authorizenet',
        //'atlantic',
        //'unionpay',
        'offline',
		'user',
		'sms',
		'googleanalytics'
	);

	$solidres_widgets = array(
		'advancedsearch',
		'camera',
		'checkavailability',
		'currency',
		'filter',
		'roomtypes',
		'locationmap',
		'coupons',
		'extras',
		'assets',
		'map',
	);

	wp_enqueue_script( 'jquery-ui-accordion' );

	?>

	<div id="wpbody">
		<h2><?php _e( 'Solidres System', 'solidres' ); ?></h2>

		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content" class="edit-form-section table_sr_system">
				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'Version info', 'solidres' ); ?></label></h3>

					<div class="inside">
						<div class="sr_logo">
							<img src="<?php echo plugins_url( 'solidres/assets/images/logo_black.png' ); ?>"
							     alt="Solidres Logo">
						</div>
						<?php
						$message_version = __( 'Version ' . solidres_check_version( 'solidres/solidres.php' ) . '.Stable', 'solidres' );
						SR_Helper::show_message( $message_version );
						?>
					</div>
				</div>

				<?php
				if ( isset( $_POST['install_simple_data'] ) ) :
					solidres_install_simpledata();
					$message_update = __( 'Sample data installed success.', 'solidres' );
					SR_Helper::show_message( $message_update );
				endif;
				?>

				<div class="alert_block">
					<form name="srform_install_simple_data" action="" method="post" id="srform">
						<?php
						global $wpdb;
						$asset_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_reservation_assets" );
						if ( $asset_count > 0 ) {
							$message_error = __( 'Sample data installed before.', 'solidres' );
							SR_Helper::show_message( $message_error, 'error' );
						} else { ?>
							<h4><?php _e( 'Warning', 'solidres' ); ?></h4>
							<?php _e( "You are about to install Solidres's sample data into your website. Sample data is the easiest way for you to get started and learn how to use Solidres. Before proceed please read the following notices:", 'solidres' ); ?>
							<ul>
								<li><?php _e( 'Always make a backup of your website first.', 'solidres' ); ?></li>
								<li><?php _e( 'Please make sure that you only install sample data right after the initial installation Solidres (when Solidres has no data).', 'solidres' ); ?></li>
								<li><?php _e( 'Do not install sample data twice because it will create duplicated entries in your databases.', 'solidres' ); ?></li>
							</ul>
							<input type="submit" name="install_simple_data" value="I understand and want to install the sample data"
							       class="srform_button install_simple_data">
						<?php }
						?>
					</form>
				</div>

				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'Plugins status', 'solidres' ); ?></label></h3>

					<div class="inside">
						<table class="form-table widefat striped">
							<thead>
							<tr>
								<th><?php _e( 'Plugin Name', 'solidres' ); ?></th>
								<th><?php _e( 'Plugin Status', 'solidres' ); ?></th>
							</tr>
							</thead>
							<tbody>
							<?php
							foreach ( $solidres_plugins as $key => $name ) {
								$check_plugin_result = solidres_check_plugin( $name );
								echo '<tr>';
								echo '<th scope="row">solidres_' . $name . '</th>';
								echo '<td>' . $check_plugin_result['message'] . '</td>';
								echo '</tr>';
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'Widget status', 'solidres' ); ?></label></h3>

					<div class="inside">
						<table class="form-table widefat striped">
							<thead>
							<tr>
								<th><?php _e( 'Widget Name', 'solidres' ); ?></th>
								<th><?php _e( 'Widget Status', 'solidres' ); ?></th>
							</tr>
							</thead>
							<tbody>
							<?php
							foreach ( $solidres_widgets as $key => $name ) {
								$check_plugin_result = solidres_check_plugin( $name );
								echo '<tr>';
								echo '<th scope="row">solidres-' . $name . '</th>';
								echo '<td>' . $check_plugin_result['message'] . '</td>';
								echo '</tr>';
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'System check list', 'solidres' ); ?></label></h3>

					<div class="inside">
						<table class="form-table widefat striped">
							<thead>
							<tr>
								<th><?php _e( 'Setting name', 'solidres' ); ?></th>
								<th><?php _e( 'Status', 'solidres' ); ?></th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<th scope="row"><?php _e( 'GD is enabled in your server', 'solidres' ); ?></th>
								<td><?php extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ? _e( '<span class="sr_enable">YES</span>', 'solidres' ) : _e( '<span class="sr_warning">NO</span>', 'solidres' ); ?>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php _e( '/wp-content/upload is writable?', 'solidres' ); ?></th>
								<td><?php
									$upload_dir = wp_upload_dir();
									is_writable( $upload_dir['basedir'] ) ? _e( '<span class="sr_enable">Yes</span>', 'solidres' ) : _e( '<span class="sr_warning">NO</span>', 'solidres' ); ?></td>
							</tr>
							<?php if ( defined('SR_PLUGIN_INVOICE_ENABLED') ) : ?>
							<tr>
								<th scope="row"><?php _e( '/wp-content/plugins/solidres-invoice/libraries/invoice is writable?', 'solidres' ); ?></th>
								<td><?php

									is_writable( ABSPATH . '/wp-content/plugins/solidres-invoice/libraries/invoice' ) ? _e( '<span class="sr_enable">Yes</span>', 'solidres' ) : _e( '<span class="sr_warning">NO</span>', 'solidres' ); ?></td>
							</tr>
							<?php endif ?>
							<?php if (function_exists('curl_version')) : ?>
							<tr>
								<th scope="row">
									(Optional) Does my server support <a href="https://www.paypal-knowledge.com/infocenter/index?page=content&id=FAQ1914&expand=true&locale=en_US" target="_blank">the new Paypal's protocols</a> (TLS 1.2 and HTTP1.1)? If you don't use Paypal, just skip it.
								</th>
								<td>
									<?php
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, "https://tlstest.paypal.com/");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									$result = curl_exec($ch);
									echo $result == 'PayPal_Connection_OK'
										? '<span class="sr_enable">YES</span>'
										: '<span class="sr_warning">NO</span>';
									curl_close($ch);
									?>
								</td>
							</tr>
							<?php endif ?>
							</tbody>
						</table>
					</div>
				</div>


				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'Database check list', 'solidres' ); ?></label></h3>

					<div class="inside">
						<table class="form-table widefat striped">
							<thead>
							<tr>
								<th>
									Setting name
								</th>
								<th>
									Status
								</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td>
									Current Solidres database schema version
								</td>
								<td>
									<?php
									$schemaVersion = get_option( 'solidres_db_version' );
									if ( !empty( $schemaVersion ) && $schemaVersion == solidres()->version ) :
										echo '<span class="label label-success">' . $schemaVersion . '</span> Your database is in good state.';
									else :
										echo '<span class="label label-warning">No version found</span> If you are using Solidres pre-installed in some template\'s quickstart package, your quickstart package database could have missing entries which leads to this issue. You should contact them so that they can fix it for you. More info can be found in our <a href="http://www.solidres.com/support/frequently-asked-questions">FAQ - #30</a>';
									endif;
									?>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'Template override check list', 'solidres' ); ?></label></h3>

					<div class="inside">

					<?php
					$theme_folder_iter = new DirectoryIterator(get_theme_root());
					foreach ($theme_folder_iter as $file_info) {
						if ($file_info->isDir() && !$file_info->isDot()) {
							$theme_list[] = $file_info->getBasename();
						}
					}
					$override_paths = array();
					foreach ( $theme_list as $theme_name ) {
						$theme_override_folder = get_theme_root() . '/' . $theme_name . '/solidres';
						if ( is_dir( $theme_override_folder ) ) {
							$theme_override_iter = new DirectoryIterator( $theme_override_folder );

							foreach ( $theme_override_iter as $override_folder ) {
								if ( $override_folder->isDir() && !$override_folder->isDot() ) {
									$override_paths[$theme_name][] = $override_folder->getRealPath();
								}
							}
						}
					}

					if ( !empty( $override_paths ) ) { ?>
					<div id="theme-override">
						<?php foreach ( $override_paths as $theme_name => $theme_override_paths ) { ?>
						<h3><?php echo $theme_name ?></h3>
						<div>
							<?php foreach ($theme_override_paths as $theme_override_path ) { ?>
								<p><?php echo $theme_override_path ?></p>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
					<script>
						jQuery(function($) {
							$( "#theme-override" ).accordion({
								heightStyle: "content",
								collapsible: true
							});
						});
					</script>
					<?php
					}
					?>

					</div>
				</div>

			</div>
		</div>
	</div>

<?php }