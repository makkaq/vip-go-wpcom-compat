<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Forward
 */
?>
	<?php if (!sophos_is_custom_layout()) : ?>
			</div><!-- .container -->
		</div><!-- #content -->
	<?php endif; ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="footer-container">
			<a href="#" class="js-collapse-footer"><svg style="height: 20px" viewBox="0 0 132 24" class="block icon sophos"><use xlink:href="#sophos"></use></svg></a>
			<div id="collapseFooter" class="blocks">
				<div class="sophos block">
					<?php wp_nav_menu(
						[
							'theme_location' => 'footer-primary',
							'container'      => false,
							'menu_class'     => 'primary menu',
							'depth'          => - 1,
						]
					); ?>
				</div>
				<?php
					foreach ([
						'news' 	   => 'footer-network-protection',
						'products' => 'footer-enduser-protection',
						'threats'  => 'footer-server-protection'
					] as $class => $location ) : ?>
						<div class="<?php echo esc_attr( $class ); ?> block">
							<?php wp_nav_menu(
								[
									'theme_location' => $location,
									'container'      => false,
									'menu_class'     => 'small menu',
									'depth'          => - 1,
								]
							); ?>
						</div>
					<?php endforeach;
				?>
			</div>
			<ul class="block social footer">
				<li class="twitter"><a rel="nofollow" href="https://twitter.com/NakedSecurity" title="<?php esc_attr_e( 'Follow our team of security experts on Twitter', 'forward' ); ?>"><svg style="height: 14px" viewBox="0 0 100 100" class="icon twitter"><use xlink:href="#twitter"></use></svg></a></li>
				<li class="facebook"><a rel="nofollow" href="https://www.facebook.com/SophosSecurity" title="<?php esc_attr_e( 'Join over 250,000 people in our Facebook community', 'forward' ); ?>"><svg style="height: 14px" viewBox="0 0 100 100" class="icon facebook"><use xlink:href="#facebook"></use></svg></a></li>
				<li class="instagram"><a rel="nofollow" href="https://www.instagram.com/nakedsecurity/" title="Follow Naked Security on Instagram"><svg style="height: 14px" viewBox="0 0 100 100" class="icon instagram"><use xlink:href="#instagram"></use></svg></a></li>
				<li class="linkedin"><a rel="nofollow" href="https://www.linkedin.com/company/5053/" title="<?php esc_attr_e( 'Join the naked security discussion group on LinkedIn', 'forward' ); ?>"><svg style="height: 14px" viewBox="0 0 100 100" class="icon linkedin"><use xlink:href="#linkedin"></use></svg></a></li>
				<li class="rss"><a rel="nofollow" href="/feed" title="<?php esc_attr_e( 'Subscribe to our RSS feed', 'forward' ); ?>"><svg style="height: 14px" viewBox="0 0 100 100" class="icon rss"><use xlink:href="#rss"></use></svg></a></li>
			</ul>

			<?php if ( function_exists( 'vip_powered_wpcom' ) ) : ?>
				<div class="vip-powered-wpcom">
					<span class="copyright">© 1997 - <?php echo esc_html( date( 'Y' ) ); ?> Sophos Ltd.</span>
					All rights reserved.
					<?php echo wp_kses_post( vip_powered_wpcom() ); ?>
				</div>
			<?php endif; ?>
		</div>
		<script type="text/javascript">
			window.cookieconsent_options = {
				"message":"This site uses cookies. By continuing to browse the site you are agreeing to our use of cookies.",
				"dismiss":"Got it!",
				"learnMore":"Learn More",
				"link":"/cookies-and-scripts",
				"theme":"<?php echo esc_url( get_stylesheet_directory_uri() ) ?>/css/sophos.css"};
		</script>
	</footer><!-- #colophon -->
</div><!-- #page -->
<div id="survey-wrapper" class="mfp-hide"><div class="pd-embed" id="pd_1499706777" data-settings=""></div></div>
<?php wp_footer(); ?>
</body>
</html>
