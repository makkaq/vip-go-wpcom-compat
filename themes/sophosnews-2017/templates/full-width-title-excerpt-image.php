<?php

/**
 * Template Name: Full Width - Excerpt above Image
 * Template Post Type: post, page
 */
	get_header(); ?>

		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<header class="entry-header">

					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					<?php if ( is_single() ) : ?>
						<div class="entry-categories">
				<?php echo wp_kses_post( sophos_term_listing( '&bull;' ) ); ?>
						</div>
					<?php endif; ?>

					<div class="entry-excerpt">
						<?php the_excerpt(); ?>
					</div>

					<?php if ( is_single() ) : ?>
						<div class="entry-meta">
							<?php sophos_posted_by(); ?>
							<?php sophos_posted_on(); ?>
						</div><!-- .entry-meta -->
					<?php endif; ?>
				</header><!-- .entry-header -->

				<?php sophos_featured_image(); ?>

				<?php if ( is_single() ) : ?>
					<div class="entry-social">
						<?php sophos_social_links(); ?>
					</div><!-- .entry-social -->
				<?php endif; ?>

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

			<?php if ( is_single() ) : ?>
	 			<div class="related-articles">
					<?php
					$related_posts = get_post_meta( get_the_ID(), 'related_posts' );

					if ( empty( $related_posts[0] ) ) {
						// Get posts tagged sidebar
						$the_query = new WP_Query([
							'post_type' => 'post',
							'posts_per_page' => '3',
							'tag' => 'sidebar',
						]);

						// If no results, grab 4 random posts
						if ( ! $the_query->have_posts() ) {
							$the_query = new WP_Query([
								'post_type' => 'post',
								'post__in' => sophos_get_random_posts(),
								'posts_per_page' => '3',
							]);
						}
					} else {
						$the_query = new WP_Query([
							'post_type' => 'post',
							'post__in' => sophos_get_random_posts(),
							'post__in' => array_values( $related_posts[0] ),
							'posts_per_page' => '3',
						]);
					}
					?>

					<div id="second-article-wrapper" class="article-wrapper">
						<h3 class="widget-title">
							<?php esc_html_e( 'You might also enjoy...', 'sophos-news' ); ?>
						</h3>
						<?php // Fourth article style
						while ( $the_query->have_posts() ) { $the_query->the_post();
							get_template_part( 'content', 'second' );
						} wp_reset_postdata(); ?>
					</div>
	 			</div>

	 			<?php get_template_part( 'author', 'article-block' ); ?>
			<?php endif; ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->
		</div><!-- #primary -->

<?php
	get_footer();
