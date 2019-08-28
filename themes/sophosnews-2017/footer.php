<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Sophos
 */
?>

		</div> <!-- .container -->
	</div> <!-- #content -->

	<footer class="site-footer" role="contentinfo">
		<div class="container">
			<div class="social-footer-links">
				<?php if ( \Sophos\Region\Menu\exists( 'footer-social' ) ) : ?>
				    <?php wp_nav_menu([
						'menu'        => \Sophos\Region\Menu\slug( 'footer-social' ),
						'menu_class'  => 'social-sharing',
						'fallback_cb' => '',
						'container'   => 'ul',
					]); ?>
				<?php endif; ?>
			</div>
			<div class="site-footer-links">
				<?php if ( \Sophos\Region\Menu\exists( 'footer-popular' ) ) : ?>
					<div class="site-footer-title">
						<?php esc_html_e( 'Popular', 'sophos-news' ) ?>
					</div>
					<?php wp_nav_menu([
						'menu'        => \Sophos\Region\Menu\slug( 'footer-popular' ),
						'fallback_cb' => '',
						'container'   => 'ul',
					]); ?>
				<?php endif; ?>
			</div>
			<div class="site-footer-links">
				<?php if ( \Sophos\Region\Menu\exists( 'footer-community' ) ) : ?>
					<div class="site-footer-title">
						<?php esc_html_e( 'Community', 'sophos-news' ); ?>
					</div>
					<?php wp_nav_menu([
						'menu'        => \Sophos\Region\Menu\slug( 'footer-community' ),
						'fallback_cb' => '',
						'container'   => 'ul',
					]); ?>
				<?php endif; ?>
			</div>
			<div class="site-footer-links">
				<?php if ( \Sophos\Region\Menu\exists( 'footer-work' ) ) : ?>
					<div class="site-footer-title">
						<?php esc_html_e( 'Work With Us', 'sophos-news' ); ?>
					</div>
					<?php wp_nav_menu([
						'menu'        => \Sophos\Region\Menu\slug( 'footer-work' ),
						'fallback_cb' => '',
						'container'   => 'ul',
					]); ?>
				<?php endif; ?>
			</div>
			<div class="site-footer-links">
				<?php if ( \Sophos\Region\Menu\exists( 'footer-about' ) ) : ?>
					<div class="site-footer-title">
						<?php esc_html_e( 'About Sophos', 'sophos-news' ); ?>
					</div>
					<?php wp_nav_menu([
						'menu'        => \Sophos\Region\Menu\slug( 'footer-about' ),
						'fallback_cb' => '',
						'container'   => 'ul',
					]); ?>
				<?php endif; ?>
			</div>
			<div class="site-footer-links">
				<?php if ( \Sophos\Region\Menu\exists( 'footer-support' ) ) : ?>
					<div class="site-footer-title">
						<?php esc_html_e( 'Support', 'sophos-news' ); ?>
					</div>
					<?php wp_nav_menu([
						'menu'        => \Sophos\Region\Menu\slug( 'footer-support' ),
						'fallback_cb' => '',
						'container'   => 'ul',
					]); ?>
				<?php endif; ?>
			</div>
		</div>
	</footer> <!-- site-footer -->

	<div class="secondary-footer">
		<div class="container">
			<div class="site-logo">
				<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
			</div>
			<div class="site-copyright">
			<?php
				/* Translators: Copyright statement such as "© 1997 - 2017 Sophos Ltd. All rights reserved". &copy; is turned into the copyright symbol, %1$s and %2$s are turned into start and end dates automatically. */
				echo esc_html( sprintf( _x( '&copy; %1$s - %2$s Sophos Ltd. All rights reserved', 'Copyright statement in the website footer', 'sophos-news' ), date_i18n( 'Y', 852076800 ), date_i18n( 'Y' ) ) );
			?>
			</div>
			<nav class="site-legal-navigation" role="navigation">
				<ul>
					<?php wp_nav_menu([
						'menu'        => \Sophos\Region\Menu\slug( 'footer-legal' ),
						'fallback_cb' => '',
						'container'   => 'false',
					]); ?>
					<?php if ( function_exists( 'vip_powered_wpcom' ) ) :
						?><li><?php echo vip_powered_wpcom(); ?></li><?php
					endif; ?>
				</ul>
			</nav>
		</div>
	</div> <!-- secondary-footer -->

</div> <!-- #page -->

<?php if ( is_front_page() || is_tax( 'region' ) ) : ?>
	<div class="mobile-footer">
		<ul>
			<li class="active"><a href="#latest">Latest</a></li>
			<li><a href="#featured">Featured</a></li>
		</ul>
	</div>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>
