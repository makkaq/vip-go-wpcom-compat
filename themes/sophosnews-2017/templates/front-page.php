<?php

/**
 * Template Name: Front Page
 */

get_header(); ?>

<div id="primary" class="content-area active">
	<main id="main" class="site-main" role="main">

		<?php
		/**
		 * @FIXME: Temporary query to get some posts
		 */
			$the_query = new WP_Query([
				'post_type' => 'post',
				'post__in' => sophos_get_random_posts(),
				'posts_per_page' => '3',
			]);
		?>

	    <div id="first-article-wrapper" class="article-wrapper">
	  		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	  			<?php get_template_part( 'content', 'first' ); ?>
	  		<?php endwhile;
$the_query->rewind_posts(); ?>
		</div>

		<?php
		/**
		 * @FIXME: Temporary query to get some posts
		 */
			$the_query = new WP_Query([
				'post_type' => 'post',
				'post__in' => sophos_get_random_posts(),
				'posts_per_page' => '8',
			]);
		?>

		<div class="ajax-content-wrapper">
		    <div id="second-article-wrapper" class="article-wrapper">
		  		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
		  			<?php get_template_part( 'content', 'second' ); ?>
		  		<?php endwhile;
$the_query->rewind_posts(); ?>
			</div>
		</div>

		<div class="load-more">
			<a href="#" class="btn btn-outline-blue">Load More</a>
		</div>

		<?php
		/**
		 * @FIXME: Temporary query to get some posts
		 */
			$the_query = new WP_Query([
				'post_type' => 'post',
				'post__in' => sophos_get_random_posts(),
				'posts_per_page' => '8',
			]);
		?>

		<div id="third-article-wrapper" class="article-wrapper">
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	  			<?php get_template_part( 'content', 'third' ); ?>
	  		<?php endwhile;
$the_query->rewind_posts(); ?>
		</div>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>

<?php
	get_footer();
