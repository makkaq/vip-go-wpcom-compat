<?php
/**
 * @package Forward
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		<div class="entry-meta">
			<div>
				<?php sophos_entry_published_date(); ?>
				<a href="#comments" class="comment-box">
					<span class="count"><?php echo esc_html( get_comments_number() ); ?></span>
				</a>
			<?php sophos_entry_categories(); ?>
			<?php sophos_article_counts( 'article-social-links' ); ?>
		</div>
	</header> <!-- .entry-header -->

	<?php sophos_featured_image(); ?>

	<div class="entry-content">
		<?php
		/* translators: %s: Name of current post */
		the_content(
			sprintf(
				__( 'Continue reading %s', 'forward' ), the_title( '<span class="screen-reader-text">"', '"</span>', false )
			)
		);
		?>

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

</article><!-- #post-## -->
