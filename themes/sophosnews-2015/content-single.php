<?php
/**
 * @package Forward
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta">
			<div>
				<?php sophos_entry_published_date(); ?>
				<a href="#comments" class="comment-box">
					<span class="count"><?php echo esc_html( get_comments_number() ); ?></span>
				</a>
			<?php sophos_entry_categories(); ?>
		</div>
	</header> <!-- .entry-header -->

    <div id="newsletter-signup" class="container top"></div>

	<?php sophos_featured_image(); ?>

	<?php sophos_post_navigation(); ?>

	<div class="entry-content">
		<div class="entry-prefix">
			<div class="entry-author">
				<?php sophos_entry_authors(); ?>
			</div>
			<div class="entry-sharing">
				<?php sophos_social_links( false, 'article-social-links' ); ?>
			</div>
		</div>
		<?php the_content(); ?>
		<?php
		wp_link_pages(
			[
				'before'   => '<div class="page-links">' . esc_html__( 'Pages:', 'forward' ),
				'after'    => '</div>',
				'pagelink' => '<span>%</span>',
			]
		);
		?>
	</div> <!-- .entry-content -->
	<ul class="block social follow">
		<li>
			<svg style="height: 24px" viewBox="0 0 24 24" class="icon twitter"><use xlink:href="#twitter"></use></svg>
			<p><?php echo wp_kses( __( 'Follow <strong><a href="https://twitter.com/nakedsecurity">@NakedSecurity on Twitter</a></strong> for the latest computer security news.', 'sophos-nakedsecurity' ), [
				'strong' => [],
				'a'      => [
					'href' => []
				],
			]); ?></p>
		</li>
		<li>
			<svg style="height: 24px" viewBox="0 0 24 24" class="icon instagram"><use xlink:href="#instagram"></use></svg>
			<p><?php echo wp_kses( __( 'Follow <strong><a href="https://www.instagram.com/nakedsecurity/">@NakedSecurity on Instagram</a></strong> for exclusive pics, gifs, vids and LOLs!', 'sophos-nakedsecurity' ), [
				'strong' => [],
				'a'      => [
					'href' => []
				],
			]); ?></p>
		</li>
	</ul>
</article><!-- #post-## -->
