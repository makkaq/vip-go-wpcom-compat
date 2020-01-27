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
	<footer>
		<section class="cta">
			<p><?php esc_html_e( 'Start a Sophos demo in less than a minute. See exactly how our solutions work in a full environment without a commitment.', 'sophos-news' ); ?></p>
			<a href="https://secure2.sophos.com/en-us/products/demos.aspx?cmp=70130000001xKqzAAE"><?php esc_html_e( 'Learn More', 'sophos-news' ); ?></a>
		</section>
		<section class="connected">
			<div class="social">
				<h3><?php esc_html_e( 'Stay Connected', 'sophos-news' ); ?></h3>
				<ul>
					<li><a href="https://www.facebook.com/securitybysophos" target="_blank"><img src="https://www.sophos.com/en-us/medialibrary/SophosNext/Images/Navigation/Footer/facebook-icon.svg?la=en&amp;hash=1CA0A5A0E9C9999E7E079A5D32C86E9BF9C7AD1E" alt="Facebook"></a>
					</li>
					<li><a href="https://www.instagram.com/sophossecurity/" target="_blank"><img src="https://www.sophos.com/en-us/medialibrary/SophosNext/Images/Navigation/Footer/instagram-icon.svg?la=en&amp;hash=2B50C2B67C3023D9E7D4E1C3A16401EAD8F10F76" alt="Instagram"></a>
					</li>
					<li><a href="https://www.linkedin.com/company/sophos" target="_blank"><img src="https://www.sophos.com/en-us/medialibrary/SophosNext/Images/Navigation/Footer/linkedin-icon.svg?la=en&amp;hash=B6D5378C1E1A16F239A4C862EAE3499B8EC1F3F9" alt="LinkedIn"></a></li>
					<li><a href="https://www.sophos.com/en-us/company/rss-feeds.aspx"><img src="https://www.sophos.com/en-us/medialibrary/SophosNext/Images/Navigation/Footer/rss-icon.svg?la=en&amp;hash=76C4DAFD331DC675814191A66794A778D8C6DCF7" alt="RSS"></a></li>
					<li><a href="https://twitter.com/Sophos" target="_blank"><img src="https://www.sophos.com/en-us/medialibrary/SophosNext/Images/Navigation/Footer/twitter-icon.svg?la=en&amp;hash=9A90D9F99ABE6454741BBAAF9C3FB6966624C92D" alt="Twitter"></a></li>
					<li><a href="https://www.youtube.com/user/SophosProducts" target="_blank"><img src="https://www.sophos.com/en-us/medialibrary/SophosNext/Images/Navigation/Footer/youtube-icon.svg?la=en&amp;hash=CCCF647AFA78C8DB74D654DB9FEFBF0AC54F42D3" alt="YouTube"></a></li>
				</ul>
			</div>
			<ul class="links">
				<li><a href="https://www.sophos.com/en-us/company/careers.aspx"><?php esc_html_e( 'Careers', 'sophos-news' ); ?> </a></li>
				<li><a href="https://www.sophos.com/en-us/partners/partner-locator.aspx"><?php esc_html_e( 'Find a Partner', 'sophos-news' ); ?></a></li>
				<li><a href="https://secure2.sophos.com/en-us/support.aspx"><?php esc_html_e( 'Support', 'sophos-news' ); ?></a></li>
				<li><a href="https://www.sophos.com/en-us/threat-center/technical-papers.aspx"><?php esc_html_e( 'Technical Papers', 'sophos-news' ); ?></a></li>
				<li><a href="https://www.sophos.com/en-us/security-news-trends/whitepapers.aspx"><?php esc_html_e( 'Whitepapers', 'sophos-news' ); ?></a></li>
			</ul>
		</section>
		<section class="legal">
			<div class="copyright"><?php echo esc_html( sprintf( _x( '&copy; %1$s - %2$s Sophos Ltd. All rights reserved', 'Copyright statement in the website footer', 'sophos-news' ), date_i18n( 'Y', 852076800 ), date_i18n( 'Y' ) ) ); ?></div>
			<ul>
				<li><a href="https://www.sophos.com/en-us/legal.aspx"><?php esc_html_e( 'Legal', 'sophos-news' ); ?></a></li>
				<li><a href="https://www.sophos.com/en-us/legal/sophos-group-privacy-policy.aspx"><?php esc_html_e( 'Privacy', 'sophos-news' ); ?></a></li>
				<li><a href="https://www.sophos.com/en-us/legal/cookie-information.aspx"><?php esc_html_e( 'Cookie Information', 'sophos-news' ); ?></a></li>
				<li><a href="https://www.sophos.com/en-us/legal/modern-slavery-act-transparency-statement.aspx"><?php esc_html_e( 'Modern Slavery Statement', 'sophos-news' ); ?></a></li>
			</ul>
		</section>
		<div class="vip" style="background: #002939">
			<?php echo vip_powered_wpcom(); ?>
		</div>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>
