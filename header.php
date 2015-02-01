<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 */
 
?><!DOCTYPE html>
<!--[if IE ]>    <html <?php language_attributes(); ?> class="ie"> <![endif]-->
 <!--[if !(IE)]><!-->  <html <?php language_attributes(); ?>>  <!--<![endif]-->

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

<title><?php wp_title( '|', true, 'right' ); ?></title>

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div class='container_12 wrapper'>
	<header>
	<div class='moby-img'></div>
	<div class='grid_12 title'>
		<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
		<<?php echo $heading_tag; ?> id="site-title">
		<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
		</<?php echo $heading_tag; ?>>
		<div id="site-description"><?php bloginfo( 'description' ); ?></div>
		<div id='sub-tagline'>* Fun &amp; Profit Pending</div>
	
	<div id="contact-icons">
		<a href="http://www.twitter.com/hyperphelan"><i class="icon-twitter"></i></a>
		<a href="http://www.stephenphelan.co.uk/contact"><i class="icon-envelope"></i></a>
	</div><!-- end contact icons -->
	</div><!-- end title -->
	<div class='clear'></div>
	<nav id="site-navigation" class="main-navigation" role="navigation">
		<!-- <div class="menu-top-nav-container"> -->
			<ul class='nav-menu'>
				<li class='menu-item'>
					<a href="http://www.stephenphelan.co.uk">Home</a>
				</li>
				<li class='menu-item'>
					<a href="#">Writing</a>
					<ul class='sub-menu'>
						<?php
						$args = array(
						  'taxonomy'     => 'article_category',
						  'orderby'      => 'name',
						  'show_count'   => 0,
						  'pad_counts'   => 0,
						  'hierarchical' => 1,
						  'title_li'     => ''
						); ?>

						<?php wp_list_categories( $args ); ?>

					</ul>
				</li>
				<li class='menu-item'>
					<a href="http://www.stephenphelan.co.uk/about">About</a>
				</li>
				<li class='menu-item'>
					<a href="http://www.stephenphelan.co.uk/contact">Contact</a>
				</li>
			</ul><!-- end nav menu -->
		<!-- </div> --><!-- end menu top nav container -->
	</nav><!-- #site-navigation -->
</header>
<!-- <div id="content">	 -->