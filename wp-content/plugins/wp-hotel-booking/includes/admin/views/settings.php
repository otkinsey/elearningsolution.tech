<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$tabs = hb_admin_settings_tabs();
$selected_tab = ! empty( $_REQUEST['tab'] ) ? sanitize_text_field( $_REQUEST['tab'] ) : '';

if( ! array_key_exists( $selected_tab, $tabs ) ){
    $tab_keys = array_keys( $tabs );
    $selected_tab = reset( $tab_keys );
}
?>
<div class="wrap">
    <h2><?php _e( 'WP Hotel Booking', 'wp-hotel-booking' ); ?></h2>
    <h2 class="nav-tab-wrapper">
    <?php if( $tabs ): foreach( $tabs as $slug => $title){?>
        <a class="nav-tab<?php echo sprintf( '%s', $selected_tab == $slug ? ' nav-tab-active' : '' ); ?>" href="?page=tp_hotel_booking_settings&tab=<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $title ); ?></a>
    <?php } endif; ?>
    </h2>
    <form method="post" action="" enctype="multipart/form-data" name="hb-admin-settings-form">
        <?php do_action( "hb_admin_settings_tab_before", $selected_tab ); ?>
        <?php do_action( "hb_admin_settings_tab_{$selected_tab}" ); ?>
        <?php wp_nonce_field( "hb_admin_settings_tab_{$selected_tab}", "hb_admin_settings_tab_{$selected_tab}_field" ); ?>
        <?php do_action( "hb_admin_settings_tab_after", $selected_tab ); ?>
        <div class="clearfix"></div>
        <p class="clearfix">
            <button class="button button-primary"><?php _e( 'Update', 'wp-hotel-booking' ); ?></button>
        </p>
    </form>
</div>