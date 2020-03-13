<?php
/**
 * The template for displaying archive pages.
 *
 * @package nakedsecurity
 */

get_header(); ?>

</div> <!-- .container -->
</div> <!-- #content -->

	<?php sophos_panel_open( 'bio-panel' ); ?>
		<?php get_template_part( 'author-bio' ); ?>
	<?php sophos_panel_close(); ?>

	<?php sophos_panel_open( 'results-panel' ); ?>
		<h3 class="block-title"><span class="total-count"><?php esc_html_e( $wp_query->found_posts ); ?></span> <span class="uppercase">articles</span> by <?php the_author(); ?></h3>
	<?php sophos_panel_close(); ?>

	<?php sophos_panel_open( 'cards-panel' ); ?>
	<?php if ( have_posts() ) : ?>

		<div class="content-wrapper">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php /* Start a new collection (row) on the 1st, 4th and 7th post */
			if ( in_array( $wp_query->current_post, [ 0, 3, 6 ] ) ) : ?>
				<div class="card-collection">
			<?php endif; ?>

			<?php get_template_part( 'content', 'card' ); ?>

			<?php /* End the collection (row) on the 3rd, 6th and 9th post */
			if ( in_array( $wp_query->current_post, [ 2, 5, 8 ] ) ) : ?>
				</div> <!-- card-collection-->
			<?php endif; ?>

		<?php endwhile; ?>
		</div> <!-- .content-wrapper -->

	<?php else : ?>
		<?php get_template_part( 'content', 'none' ); ?>
	<?php endif; ?>
	<?php sophos_panel_close(); ?>

	<?php sophos_posts_navigation(); ?>

	<?php get_template_part( 'panel', 'recommended' ); ?>

<?php get_footer();
