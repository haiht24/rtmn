<?php
/**
 * Defult Header
 * Centered Logo
 *
 * @package SimpleMag
 * @since 	SimpleMag 3.0
**/
global $ti_option;
?>

<div class="header header-default">
    <a class="logo" href="<?php echo home_url( '/' ); ?>">
        <img src="<?php echo $ti_option['site_logo']['url']; ?>" alt="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>" width="<?php echo $ti_option['site_logo']['width']; ?>" height="<?php echo $ti_option['site_logo']['height']; ?>" />
    </a><!-- Logo -->
    
    <?php 
    // Show or Hide site tagline under the logo based on Theme Options
    if( $ti_option['site_tagline'] == true ) {
    ?>
    <span class="tagline" itemprop="description"><?php bloginfo( 'description' ); ?></span>
    <?php } ?>
</div><!-- .header-default -->