<?php
/**
 * The template for displaying search results pages.
 *
 * @package Sophos
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php if ( have_posts() ) : ?>
				<h3 class="archive-title">
				<?php
					// translators: %1$s will contain the number of results, %2$s will be what the user searched for
					$h3 = sprintf( _x( '%1$s Results for <span>%2$s</span>', 'Heading that appears above search results', 'sophos-news' ), (int) $wp_query->found_posts, get_search_query() );
					echo wp_kses( $h3, [
						'span' => [],
					]);
				?>
				</h3>
				<div class="ajax-content-wrapper">
					<div id="second-article-wrapper" class="article-wrapper">

						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>

							<?php
								/* Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
								 */
								get_template_part( 'content', 'search' );
							?>

						<?php endwhile; ?>

					</div>
				</div>

				<?php sophos_posts_navigation(); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>

<?php
	get_footer();
