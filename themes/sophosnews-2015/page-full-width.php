<?php

/**
 * Template Name: Full Width
 *
 * Displays the page without a sidebar.
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						ob_start();
						comments_template( '', true );
            
						// Replace references to the wordpress subdomain with the primary domain so that it matches the domain used for cookies
						echo str_replace(
							'https://sophosnews.wordpress.com/wp-comments-post.php', 
							'https://nakedsecurity.sophos.com/wp-comments-post.php', 
							ob_get_clean()
						);
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer();
