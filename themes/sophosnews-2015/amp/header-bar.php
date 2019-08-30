<?php
/**
 * Header bar template part.
 *
 * @package AMP
 */

?>
<header id="masthead" class="site-header variation variation-3" role="banner">
	<div class="header-container">
		<div class="site-branding">
			<div class="site-title">
				<a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>"><amp-img src="<?php echo esc_url( get_template_directory_uri() . '/img/naked-security-logo-white@2x.png' ); ?>" width="150" height="22.5" alt="<?php echo esc_attr( wptexturize( $this->get( 'blog_name' ) ) ); ?>" class=""></amp-img></a>
			</div>
		</div>
		<nav id="site-navigation" class="main-navigation" role="navigation">
			<div class="container">
				<?php wp_nav_menu(
					[
						'theme_location' => 'main',
						'container'      => false,
						'menu_class'     => 'exit-links menu',
						'depth'          => - 1,
						'fallback_cb'	 => false
					]
				); ?>
			</div>
		</nav>
	</div>
</header>
