<?php

if ( ! function_exists( 'sophos_social_links' ) ) :
	/**
 * Show social links on post using the permalink.
 */
	function sophos_social_links() {
	?>

	<ul class="social-sharing" id="social-sharing">
		<li class="comments">
			<a href="#comments" title="<?php esc_html_e( 'Leave a Reply', 'sophos-news' ); ?>">
				<?php echo esc_html( number_format_i18n( get_comments_number() ) ); ?>
			</a>
		</li>
		<li class="twitter"><a class="js-share-modal" href="http://twitter.com/intent/tweet?text=<?php echo rawurlencode( get_the_title() . ' ' . wp_get_shortlink() ); ?>" data-title="<?php esc_attr( get_the_title() ); ?>" title="<?php esc_html_e( 'Share on Twitter', 'sophos-news' ); ?>"><?php esc_html_e( 'Share on Twitter', 'sophos-news' ); ?></a></li>
		<li class="facebook"><a class="js-share-modal" href="http://www.facebook.com/share.php?u=<?php echo esc_url( sprintf( '%s&amp;title=%s', wp_get_shortlink(), get_the_title() ) ); ?>" data-title="<?php echo esc_attr( get_the_title() ); ?>" title="<?php esc_html_e( 'Share on Facebook', 'sophos-news' ); ?>"><?php esc_html_e( 'Share on Facebook', 'sophos-news' ); ?></a></li>
		<li class="linkedin"><a class="js-share-modal" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url( sprintf( '%s&amp;title=%s', wp_get_shortlink(), get_the_title() ) ); ?>" data-title="<?php echo esc_attr( get_the_title() ); ?>" title="<?php esc_html_e( 'Share on LinkedIn', 'sophos-news' ); ?>"><?php esc_html_e( 'Share on LinkedIn', 'sophos-news' ); ?></a></li>
	</ul>

	<?php
	}
endif;
