<?php
/**
 * The template for displaying all single posts.
 *
 * @package Forward
 */

get_header(); ?>

</div> <!-- .container -->
</div> <!-- #content -->

<?php sophos_panel_open( 'content-panel', 'framed-content-area' ); ?>
	<main id="main" class="site-main" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'single' ); ?>

		<div class="free-tools-block">
			<?php get_template_part( 'panel', 'tools' ); ?>
		</div>

		<?php sophos_post_navigation(); ?>

	<?php endwhile; // end of the loop. ?>

	</main><!-- #main -->
<?php sophos_panel_close(); ?>

<?php if ( comments_open() || get_comments_number() ) : ?>
	<?php sophos_panel_open( 'comments-panel', 'framed-content-area' ); ?>
		<?php
	    	ob_start();
            comments_template( '', true );

			// Replace references to the wordpress subdomain with the primary domain so that it matches the domain used for cookies
            echo str_replace(
            	'https://sophosnews.wordpress.com/wp-comments-post.php',
            	'https://nakedsecurity.sophos.com/wp-comments-post.php',
         		ob_get_clean()
           	); ?>
		<div class="comments-widget-area">
			<?php sophos_random_advert( 'content-advert', 'card' ); ?>
		</div>
	<?php sophos_panel_close(); ?>
<?php endif; ?>

<?php get_template_part( 'panel', 'recommended' ); ?>

<?php get_footer();
