<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">

	<?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template. ?>

	<header id="header" class="site-header" role="banner">

		<div class="container">
			<div class="row">

				<div class="site-branding col-md-4">
					<?php gomedia_site_branding(); // Custom function to display site title or logo. ?>
				</div>
				
				<?php gomedia_header_ad(); // Get the header ad. ?>

			</div><!-- .row -->
		</div><!-- .container -->

	</header><!-- #header -->

	<?php get_template_part( 'menu', 'secondary' ); // Loads the menu-secondary.php template. ?>

	<div id="main">