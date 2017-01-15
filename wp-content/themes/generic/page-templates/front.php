<?php
/*
Template Name: Front
*/
get_header(); ?>
<?php
	$args = array('post_type' => 'banner-image', 'category_name' => 'home');
	$query = new WP_Query($args);
?>

<?php
		while($query->have_posts() ) : $query->the_post();
?>
<header id="front-hero" role="banner" style="background: url('<?php echo wp_get_attachment_url(get_post_thumbnail_id($query->ID));  ?>') no-repeat 0 0px/cover ;">
	<div class='overlay'></div>
	<div class="marketing">
		<div class="tagline wow slideInRight" data-wow-duration="1500ms">
			<span class="subheader">
				<h1>the solution is here.</h1>
				<span class='orange-text'>online training services<br><span class="dim" >for modern companies</span></span>
				<ul>
					<li><b>PRODUCE</b> <span class="dim">training and educational materials</span></li>
					<li><b>explain</b> <span class="dim">new policies and best practices</span></li>
					<li><b>create</b> <span class="dim" >oNboarding presentations </span></li>
					<!-- <li><b>assess</b> <span class="dim" >individual performance</span></li> -->
				</ul>
			</span>
		</div>
<?php endwhile; ?>
<?php wp_reset_query(); ?>
	</div>

</header>


<!---SECTION ONE--->
<?php $args = array('post_type' => 'widget-area', 'name' => 'section-two'); ?>
<?php $query = new WP_Query($args); ?>
<?php while( $query->have_posts() ) : $query->the_post(); ?>
<section class="background_container dark_background" id="section_two" >
<div class="row  section_two wow bounceInUp" data-wow-duration="1500ms">

	<div class="" >
		<div class="wa_text"><?php echo types_render_field('wa_text-one'); ?></div>
<div class="" ></div>
	</div>

<?php endwhile; ?>
<?php wp_reset_query();  ?>
</div>
</section>

<!---SECTION TWO--->
<?php $args = array('post_type' => 'widget-area', 'name' => 'widget-area-one'); ?>
<?php $query = new WP_Query($args); ?>
<?php while( $query->have_posts() ) : $query->the_post(); ?>
<section class="row wow fadeInDown" data-wow-duration="1500ms" id="section_one">
	<header>
		<h3><span class="orange-text">We serve</span><br> a variety of organizations</h3>
	</header>

	<div class="large-4 columns">
		<div class='wa_image'><img src="<?php echo types_render_field('wa_image-one', array('output' => 'raw')); ?>" alt="responsive"></div>
		<div class="wa_text"><?php echo types_render_field('wa_text-one'); ?></div>
<div class="" ></div>
	</div>
	<div class="large-4 columns">
		<div class='wa_image'><img src="<?php echo types_render_field('wa_image-two', array('output' => 'raw')); ?>" alt="responsive"></div>
		<div class="wa_text"><?php echo types_render_field('wa_text-two'); ?></div>
<div class="" ></div>
	</div>
	<div class="large-4 columns">
		<div class='wa_image'><img src="<?php echo types_render_field('wa_image-three', array('output' => 'raw')); ?>" alt="responsive"></div>
		<div class="wa_text"><?php echo types_render_field('wa_text-three'); ?></div>
<div class="" ></div>
	</div>
<?php endwhile; ?>
<?php wp_reset_query();  ?>
</section>


<!---SECTION THREE--->
<section id="section_three" class="dark_background">
<div class="row wow fadeInDown" data-wow-duration="1500ms" >
	<header>
		<h3>our service model</h3>
	</header>
<?php $args = array('post_type' => 'widget-area', 'name' => 'widget-area-two'); ?>
<?php $query = new WP_Query($args); ?>
<?php while( $query->have_posts() ) : $query->the_post(); ?>
	<div class="large-4 columns">
		<div class='wa_image image_one'><img src="<?php echo types_render_field('wa_image-one', array('output' => 'raw')); ?>" alt="responsive"></div>
		<div class="wa_text"><?php echo types_render_field('wa_text-one'); ?></div>
<div class="" ></div>
	</div>
	<div class="large-4 columns">
		<div class='wa_image image_two'><img src="<?php echo types_render_field('wa_image-two', array('output' => 'raw')); ?>" alt="responsive"></div>
		<div class="wa_text"><?php echo types_render_field('wa_text-two'); ?></div>
<div class="" ></div>
	</div>
	<div class="large-4 columns">
		<div class='wa_image image_three'><img src="<?php echo types_render_field('wa_image-three', array('output' => 'raw')); ?>" alt="responsive"></div>
		<div class="wa_text"><?php echo types_render_field('wa_text-three'); ?></div>
<div class="" ></div>
	</div>
<?php endwhile; ?>
<?php wp_reset_query();  ?>
</div>
</section>

<!---SECTION FIVE--->
<section id="section_five" class="section_5">
	<header>
		<h3><span class="orange-text">Customizable</span><br>elearning solutions</h3>
	</header>
	<div class="animateBox_1">
		<div class="large_text"><span class="orange-text">What can we do</span> for your organization?</div>
	</div>
	<div class="animateBox_2" >
		<!-- <div class=""> -->

			<div class='wa_image'>
					<!--video player-->
					<div class="overflow_container" style="background: url('')">
						<div class="video_container columns" style="background: url('wp-content/uploads/2016/07/training_video_container-1.png') no-repeat top center/100% ;">
								<div class="video_border">
									<div class="presentation_text">
											<!-- <div class="textItem" id="t1"></div> -->
											<div class="textItem" id="t2" style="margin-top:40px;">
												<span>Engage.</span><br>
												<span >Inform.</span><br>
												<span class="counted highlight">Grow.</span>
											</div>
											<div class="textItem" id="t5" style="margin-top:40px;"><span class="counted ">How?</span></div>
											<div class="textItem" id="t6">
												<span class="counted highlight">Interactive<br> presentations</span><br>
												<span>+</span><br>
												<span>trackable<br> assesments</span>
											</div>
											<div class="textItem" id="t8" style=""><span class="counted">=</span></div>
											<div class="textItem" id="t9" style="margin-top:40px;"><span class="counted highlight">informed</span> <br>decision <br>making</div>
											<div class="textItem" id="t10" style=""><span class="counted">and</span></div>
											<div class="textItem" id="t11" style="margin-top:40px;">
												<div><span class="counted highlight">enhanced</span><br>performance</div>
												<div id="scrollto" class="button" href="#section_four" >let's get started!</div>
											</div>
									</div>
								</div>
								<!--
								**  VIDEO PLAYER CONTROL BUTTONS:
								**  these may be brought back at a later time
								*************************************-->
								<div class="controls_container">
									<!--span class="control close" id="close"></span-->
									<span class="control rwd" id="rwd"></span>
									<span class="control pause" id="pause"></span>
									<span class="control play" id="play"></span>
									<span class="control fwd" id="fwd"></span>
								</div>
						</div>
					</div>
			</div>
			<div class="wa_text"><?php echo types_render_field('wa_text-one'); ?></div>
	<div class="" ></div>
		<!-- </div> -->
	</div>
</section>

<!---SECTION FOUR--->
<?php $args = array('post_type' => 'widget-area', 'name' => 'section-four'); ?>
<?php $query = new WP_Query($args); ?>
<?php while( $query->have_posts() ) : $query->the_post(); ?>
<?php $r = $_GET['r']; ?>
<?php	if(empty($r)) { ?>
<section class="section_4 background_container " id="section_four" >
<section class="dark_background">
		<!-- <div class="large-6 columns image">
			<img src="<?php echo types_render_field('wa_image-one', array('output' => 'raw')); ?>" >
		</div> -->
		<div class="large-6 columns wow fadeInDown" data-wow-duration="1500ms" id='contact_form'>
			<form action="http://localhost:8888/practice/eliteTrainingVideos/elspcf" method="POST">
				<header><h1>Schedule a meeting</h1><span class="subscript">We'd love to speak with you in person.</span></header>
				<input type="text" name="name" placeholder = "name">
				<input type="text" name="companyName" placeholder = "company name">
				<input type="text" name="phoneNumber" placeholder = "phone number">
				<input type="text" name="email" placeholder = "email">
				<input type="hidden" name="formName" value="contactUs" >
				<button class="button">send</button>
			</form>
		</div>
</section>
</section>
<?php }else{ ?>
	<section class="section_4 background_container dark_background" id="section_four" >
	<section class="" id="form_row">
			<div class="large-6 columns" id='contact_form'>
				<div class="form_response"> <h1>Thanks for your interest!</h1>  <header>We'll be contacting you shortly.</header></div>
				<a href="home" ><button class="button">refresh</button></a>
			</div>
	</section>
	</section>
	<?php } ?>
<?php endwhile; ?>
<?php wp_reset_query();  ?>

<?php get_footer();
