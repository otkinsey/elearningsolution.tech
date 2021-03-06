<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

?>

<h3><?php _e( 'Customer Details', 'wp-hotel-booking' ); ?></h3>
<div class="hb-customer clearfix">
    <?php hb_get_template( 'customer/existing.php', array( 'customer' => $customer ) ); ?>
    <?php hb_get_template( 'customer/new.php', array( 'customer' => $customer ) ); ?>
</div>
<div class="hb-col-margin"></div>