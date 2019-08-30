<?php
/**
 * The template part for displaying results in search pages.
 *
 * @package nakedsecurity
 */

global $wp_query;

if ( 3 === $wp_query->current_post ) :
	echo do_shortcode( '[ad]' );
endif;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="result-thumbnail">
		<?php the_post_thumbnail( 'thumbnail' ); ?>
	</div>
	<div class="result-details">
		<header class="result-header">
			<?php the_title( sprintf( '<h1 class="result-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
			<?php if ( 'post' == get_post_type() ) : ?>
				<div class="result-meta">
					<?php the_time( 'M d Y g:ia' ); ?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .result-header -->
	</div>
</article><!-- #post-## -->
