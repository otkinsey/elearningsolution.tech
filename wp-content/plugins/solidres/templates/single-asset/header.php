<?php
/*------------------------------------------------------------------------
  Solidres - Hotel booking plugin for WordPress
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 - 2016 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

?>

<div class="row-fluid">
	<h3>
		<?php echo apply_filters( 'solidres_asset_name', esc_attr( $asset->name ) . ' ' ); ?>
		<?php for ( $i = 1; $i <= $asset->rating; $i++ ) : ?>
			<i class="rating uk-icon-star fa-star"></i>
		<?php endfor ?>
	</h3>
	<span class="address_1 reservation_asset_subinfo">

		<?php
		echo apply_filters( 'solidres_asset_address1', esc_attr( $asset->address_1 )).', '.
				( ! empty( $asset->city ) ? $asset->city.', ' : '' ).
				( ! empty( $asset->postcode ) ? $asset->postcode.', ' : '' ) .
					   $country->name
		?>
		<a class="show_map" href="#inline_map"><?php _e( 'Show map', 'solidres' ) ?></a>
	</span>

	<span class="address_2 reservation_asset_subinfo">
		<?php echo apply_filters( 'solidres_asset_address2', esc_attr( $asset->address_2 ) ) ?>
	</span>

	<span class="phone reservation_asset_subinfo">
		<?php _e( 'Phone: ', 'solidres' ) ?> <?php echo esc_attr( $asset->phone ) ?>
	</span>

	<span class="fax reservation_asset_subinfo">
		<?php _e( 'Fax: ', 'solidres' ) ?> <?php echo esc_attr( $asset->fax ) ?>
	</span>
	<?php
	if ( isset( $custom_fields['socialnetworks'] ) ) : ?>
	<span class="social_network reservation_asset_subinfo clearfix">
<?php
	foreach ( $custom_fields['socialnetworks'] as $network ) :
		if ( ! empty( $network[1] ) ) :
			$network_parts = explode( '.', $network[0] );
			$sr_icon = array();
			$sr_icon = explode('_', $network_parts[2]);
			?>
			<a href="<?php echo esc_url( $network[1] );?>" target="_blank">
				<?php if($sr_icon[0] == 'gplus') :?>
					<i class="fa fa-google-plus-square"></i>
				<?php else: if($sr_icon[0] == 'foursquare') : ?>
					<i class="fa fa-foursquare"></i>
				<?php else: if($sr_icon[1] != 'link') :?>
					<i class="fa fa-<?php echo $sr_icon[0] ?>-square"></i>
				<?php endif; endif; endif; ?>
			</a>
			<?php
		endif;
	endforeach;
?>
	</span>
	<?php
	endif; ?>
</div>

<div class="row-fluid">
	<div class="span12">
		<?php require( 'simple-gallery.php' ); ?>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<?php
		$tabTitle = array();
		$tabPane = array();

		if (!empty($asset->description)) :
			$tabTitle[] = '<li class="active"><a href="#asset-desc" data-toggle="tab">' . __( 'Description', 'solidres' ) . '</a></li>';
			$tabPane[] = '<div class="tab-pane active" id="asset-desc"><p>' . apply_filters( 'solidres_asset_desc', $asset->description ) . '</p></div>';
		endif;

		if (isset($asset->feedbacks->render) && !empty($asset->feedbacks->render)) :
			$activeClass = empty($tabTitle) ? 'active' : '';
			$tabTitle[] = '<li class="'.$activeClass.'"><a href="#asset-feedbacks" data-toggle="tab">'. __( 'Feedbacks', 'solidres' ).'</a></li>';
			$tabPane[] = '<div class="tab-pane '.$activeClass.'" id="asset-feedbacks">'.$asset->feedbacks->render.'</div>';
			$tabTitle[] = '<li><a href="#asset-feedback-scores" data-toggle="tab">'. __( 'Scores', 'solidres' ).'</a></li>';
			$tabPane[] = '<div class="tab-pane" id="asset-feedback-scores">'.$asset->feedbacks->scores.'</div>';
		endif;

		?>

		<?php if (!empty($tabTitle)) : ?>
			<ul class="nav nav-tabs">
				<?php echo join("\n", $tabTitle); ?>
			</ul>
		<?php endif ?>

		<?php if (!empty($tabPane)) : ?>
			<div class="tab-content">
				<?php echo join("\n", $tabPane); ?>
			</div>
		<?php endif ?>
	</div>
</div>