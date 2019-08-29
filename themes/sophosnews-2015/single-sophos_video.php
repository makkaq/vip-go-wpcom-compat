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

		<?php get_template_part( 'content', 'single-sophos_video' ); ?>

		<?php sophos_post_navigation(); ?>

	<?php endwhile; // end of the loop. ?>

	</main><!-- #main -->
<?php sophos_panel_close(); ?>

<div id="newsletter-signup"></div>

<?php if ( comments_open() || get_comments_number() ) : ?>
	<?php sophos_panel_open( 'comments-panel' ); ?>
		<?php comments_template(); ?>
		<div class="comments-widget-area">
			<?php sophos_random_advert( 'content-advert', 'card' ); ?>
		</div>
	<?php sophos_panel_close(); ?>
<?php endif; ?>

<?php get_template_part( 'panel', 'recommended' ); ?>

<?php get_footer();
