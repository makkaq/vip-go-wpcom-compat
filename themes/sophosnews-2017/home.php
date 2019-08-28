<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sophos
 */

get_header(); ?>

	<div id="primary" class="content-area active">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php if ( ! is_paged() ) : ?>
				<div id="first-article-wrapper" class="article-wrapper">
					<?php // First Article Style
					$count = 0;
					while ( have_posts() ) { the_post();
						$count++;
						get_template_part( 'content', 'first' );
						if ( $count >= 3 ) { break;
						}
					} ?>
				</div>
			<?php endif; ?>

			<div class="ajax-content-wrapper">
				<div id="second-article-wrapper" class="article-wrapper">
					<?php // Second Article Style
					while ( have_posts() ) { the_post();
						get_template_part( 'content', 'second' );
					} ?>
				</div>
			</div>

			<?php sophos_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php if ( have_posts() ) :
		get_sidebar();
	endif;

get_footer();
