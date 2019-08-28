<?php
/**
 * @package Sophos
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-categories">
			<?php echo wp_kses_post( sophos_term_listing( '&bull;' ) ); ?>
		</div>

		<?php if ( has_excerpt() ) : ?>
			<div class="entry-excerpt">
				<?php the_excerpt(); ?>
			</div>
		<?php endif; ?>

		<div class="entry-meta">
			<?php sophos_posted_on(); ?>
		</div><!-- .entry-meta -->

		<div class="entry-social">
			<?php sophos_social_links(); ?>
		</div><!-- .entry-social -->
	</header><!-- .entry-header -->

	<?php sophos_featured_image(); ?>

	<div class="entry-meta">
		<?php sophos_posted_by(); ?>
	</div><!-- .entry-meta -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'sophos-news' ),
				'after'  => '</div>',
				'pagelink' => '<span>%</span>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
