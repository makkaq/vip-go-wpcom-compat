<?php
/**
 * @package Sophos
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php sophos_featured_image(); ?>

	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php sophos_posted_on(); ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">

		<?php
		/* translators: %s: Name of current post */
		the_content( sprintf( __( 'Continue Reading %s', 'sophos-news' ), the_title( '<span class="screen-reader-text">"', '"</span>', false ) ) );
		?>

		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'sophos-news' ),
				'after'  => '</div>',
				'pagelink' => '<span>%</span>',
				)
		);
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php sophos_entry_footer(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
