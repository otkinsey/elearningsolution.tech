<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$settings = hb_settings();
?>
<h3><?php _e( 'Catalog settings', 'wp-hotel-booking' ); ?></h3>
<p class="description">
    <?php _e( 'Catalog settings display column number and image size used in room list ( archive page, related room )', 'wp-hotel-booking' ); ?>
</p>
<table class="form-table">
    <tr>
        <th><?php _e( 'Number of column display catalog page', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="number" name="<?php echo esc_attr( $settings->get_field_name('catalog_number_column') ); ?>" value="<?php echo esc_attr( $settings->get('catalog_number_column', 4) ); ?>" />
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Number of post display in page', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="number" name="<?php echo esc_attr( $settings->get_field_name('posts_per_page') ); ?>" value="<?php echo esc_attr( $settings->get('posts_per_page', 8) ); ?>" size="8" min="0"/>
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Catalog images size', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="number" name="<?php echo esc_attr( $settings->get_field_name('catalog_image_width') ); ?>" value="<?php echo esc_attr( $settings->get('catalog_image_width', 270) ); ?>" size="4" min="0"/>
            x
            <input type="number" name="<?php echo esc_attr( $settings->get_field_name('catalog_image_height') ); ?>" value="<?php echo esc_attr( $settings->get('catalog_image_height', 270) ); ?>" size="4" min="0"/>
            px
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Display rating', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="hidden" name="<?php echo esc_attr( $settings->get_field_name('catalog_display_rating') ); ?>" value="0" />
            <input type="checkbox" name="<?php echo esc_attr( $settings->get_field_name('catalog_display_rating') ); ?>" <?php checked( $settings->get('catalog_display_rating') ? 1 : 0, 1 ); ?> value="1"/>
        </td>
    </tr>
</table>

<h3><?php _e( 'Room settings', 'wp-hotel-booking' ); ?></h3>
<p class="description">
    <?php _e( 'Room settings display column number and image size used in gallery single page', 'wp-hotel-booking' ); ?>
</p>
<table class="form-table">
    <tr>
        <th><?php _e( 'Room images size gallery', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="number" name="<?php echo esc_attr( $settings->get_field_name('room_image_gallery_width') ); ?>" value="<?php echo esc_attr( $settings->get('room_image_gallery_width', 270) ); ?>" size="4" min="0"/>
            x
            <input type="number" name="<?php echo esc_attr( $settings->get_field_name('room_image_gallery_height') ); ?>" value="<?php echo esc_attr( $settings->get('room_image_gallery_height', 270) ); ?>" size="4" min="0"/>
            px
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Room images thumbnail', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="number" name="<?php echo esc_attr( $settings->get_field_name('room_thumbnail_width') ); ?>" value="<?php echo esc_attr( $settings->get('room_thumbnail_width', 150) ); ?>" size="4" min="0"/>
            x
            <input type="number" name="<?php echo esc_attr( $settings->get_field_name('room_thumbnail_height') ); ?>" value="<?php echo esc_attr( $settings->get('room_thumbnail_height', 150) ); ?>" size="4" min="0"/>
            px
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Display pricing plans', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="hidden" name="<?php echo esc_attr( $settings->get_field_name('display_pricing_plans') ); ?>" value="0" />
            <input type="checkbox" name="<?php echo esc_attr( $settings->get_field_name('display_pricing_plans') ); ?>" <?php checked( $settings->get('display_pricing_plans') ? 1 : 0, 1 ); ?> value="1" />
        </td>
    </tr>
</table>

<h3 class="description"><?php _e( 'Room Ratings', 'wp-hotel-booking' ); ?></h3>
<table class="form-table">
    <tr>
        <th><?php _e( 'Enable ratings on reviews', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="hidden" name="<?php echo esc_attr( $settings->get_field_name('enable_review_rating') ); ?>" value="0" />
            <input type="checkbox" name="<?php echo esc_attr( $settings->get_field_name('enable_review_rating') ); ?>" <?php checked( $settings->get('enable_review_rating') ? 1 : 0, 1 ); ?> value="1" onchange="jQuery('.enable_ratings_on_reviews').toggleClass('hide-if-js', ! this.checked );" />
        </td>
    </tr>
    <tr class="enable_ratings_on_reviews<?php echo sprintf( '%s', $settings->get('enable_ratings_on_reviews') ? '' : ' hide-if-js' ); ?>">
        <th><?php _e( 'Ratings are required to leave a review', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="hidden" name="<?php echo esc_attr( $settings->get_field_name('review_rating_required') ); ?>" value="0" />
            <input type="checkbox" name="<?php echo esc_attr( $settings->get_field_name('review_rating_required') ); ?>" <?php checked( $settings->get('review_rating_required') ? 1 : 0, 1 ); ?> value="1" />
        </td>
    </tr>
</table>

<h3 class="description"><?php _e( 'Gallery images', 'wp-hotel-booking' ); ?></h3>
<table class="form-table">
    <tr>
        <th><?php _e( 'Enable gallery lightbox', 'wp-hotel-booking' ); ?></th>
        <td>
            <input type="hidden" name="<?php echo esc_attr( $settings->get_field_name('enable_gallery_lightbox') ); ?>" value="0" />
            <input type="checkbox" name="<?php echo esc_attr( $settings->get_field_name('enable_gallery_lightbox') ); ?>" <?php checked( $settings->get('enable_gallery_lightbox') ? 1 : 0, 1 ); ?> value="1"/>
        </td>
    </tr>
</table>