<?php
/**
 * @package Forward
 */

$sophos_video_meta = get_post_meta( get_the_ID(), 'sophos_video_fields' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta">
			<?php sophos_entry_published_date(); ?><?php sophos_entry_categories(); ?>
		</div>
	</header> <!-- .entry-header -->

	<div class="entry-featured-image video-image">
		<a href="<?php echo esc_url( $sophos_video_meta[0]['youtube_video_url'] ); ?>">
			<?php the_post_thumbnail( 'post-thumbnail', [ 'class' => 'attachment-post-thumbnail' ] ); ?>
		</a>
	</div> <!-- .entry-featured-image -->

	<div class="entry-row">
		<div class="entry-sharing">
			<?php sophos_social_links( false, 'article-social-links' ); ?>
		</div>
		<div class="entry-content">
			<div class="entry-prefix">
				<div class="entry-author">
					<span class="by"><?php esc_html_e( 'video by', 'forward' ) ?></span> <?php the_author_posts_link(); ?>
				</div>
			</div>
			<?php the_content(); ?>

			<p><a href="<?php echo esc_url( get_post_type_archive_link( 'sophos_video' ) ); ?>"><?php esc_html_e( 'go to Video Archives', 'forward' ); ?></a></p>

			<?php
			wp_link_pages(
				[
					'before'   => '<div class="page-links">' . esc_html__( 'Pages:', 'forward' ),
					'after'    => '</div>',
					'pagelink' => '<span>%</span>',
				]
			);
			?>
		</div> <!-- .entry-content -->
	</div> <!-- .entry-row -->

</article><!-- #post-## -->
