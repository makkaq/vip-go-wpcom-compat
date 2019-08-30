<?php
/**
 * The template for displaying archive pages.
 *
 * @package nakedsecurity
 */

get_header(); ?>

</div> <!-- .container -->
</div> <!-- #content -->

<?php if ( have_posts() ) : ?>
	<?php /* Only show the hero panel on the first page */
	if ( !is_paged() ) : the_post() ?>
		<section class="hero-panel with-widget">
			<div class="container">
				<div class="panel-content <?php esc_attr_e( $content_class ); ?>">
					<h3 class="block-title"><span class="total-count"><?php esc_html_e( $wp_query->found_posts ); ?></span> <?php the_archive_title(); ?></h3>
					<div class="slide-collection-disabled">
						<?php get_template_part( 'content', 'hero-slide' ); ?>
					</div>
				</div> <!-- .panel-container -->
			</div> <!-- .container -->
		</section> <!-- .panel -->
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<section class="cards-panel">
			<div class="container">
				<div class="panel-content">
					<div class="content-wrapper">

						<?php while ( have_posts() ) : the_post();

							$items = 3;
							$shim  = is_paged() ? 1 : 0;

							if ( 1 === ( ( $wp_query->current_post + $shim ) % $items ) ) :
								?><div class="card-collection"><?php
							endif;

							get_template_part( 'content', 'card' );

							if ( 0 === ( ( $wp_query->current_post + $shim ) % $items ) ) :
								?></div><?php
							endif;

						endwhile; ?>
					</div> <!-- .content-wrapper -->
				</div> <!-- .panel-container -->
			</div> <!-- .container -->
		</section> <!-- .panel -->
	<?php endif; ?>
	<?php sophos_posts_navigation(); ?>
<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>

<?php get_footer();
