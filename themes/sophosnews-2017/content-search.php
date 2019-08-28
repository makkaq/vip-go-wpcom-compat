<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sophos
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-article' ); ?> >
	<div class="search-article__image" style="background-image: url('<?php echo esc_url( sophos_get_the_post_thumbnail() ); ?>');">
		<div class="featured-entry-meta">
			<div class="featured-entry-date">
				<span class="day"><?php echo esc_html( date_i18n( 'D', get_the_time( 'U' ) ) ); ?></span><span class="day-num"><?php echo esc_html( date_i18n( 'd', get_the_time( 'U' ) ) ); ?></span>
			</div>
		</div>
	</div>
	<div class="search-article__content">
		<header class="featured-entry-header">
			<?php the_title( sprintf( '<h1 class="featured-entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		</header> <!-- featured-entry-header -->
		<div class="entry-footer">
			<?php sophos_posted_by(); ?>
			<div class="entry-categories">
				<?php echo wp_kses( get_the_category_list( ' &bull; ' ), [
					'a' => [
						'href' => 1,
						'rel' => 1,
					],
				] ); ?>
				</div>
			</div><!-- .entry-meta -->
		</div>
	</article> <!-- post -->
