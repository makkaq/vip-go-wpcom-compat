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

		<article class="error-404 not-found">

			<header class="entry-header">
				<h1 class="page-title"><?php esc_html_e( '404', 'forward' ); ?></h1>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<p class="help-message"><?php esc_html_e( 'Oh no! We couldn&rsquo;t find that page. Maybe try a search?', 'forward' ); ?></p>
				<?php get_search_form(); ?>
			</div><!-- .entry-content -->

		</article>

	</main><!-- #main -->
<?php sophos_panel_close(); ?>

<?php get_footer();
