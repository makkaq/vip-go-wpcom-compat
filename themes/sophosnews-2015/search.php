<?php
/**
 * The template for displaying search results pages.
 *
 * @package Forward
 */

get_header(); ?>

	<section id="primary" class="content-area search-content-area">
		<main id="main" class="search-results-list" role="main">

		<?php if ( have_posts() ) :
			$results = $wp_query->found_posts;
		?>

			<header class="page-header">
				<h1 class="results-title"><span class="results-number lighten"><?php esc_html_e( $results ); ?></span> <?php printf( __( 'RESULTS for "%s"', 'forward' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<div class="content-wrapper">
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'content', 'search' );
					?>

				<?php endwhile; ?>
			</div> <!-- .content-wrapper -->

			<?php sophos_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer();