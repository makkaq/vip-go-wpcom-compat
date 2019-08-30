<?php
/**
 * The template part for displaying results in search pages.
 *
 * @package nakedsecurity
 */

$sophos_video_meta = get_post_meta( get_the_ID(), 'sophos_video_fields' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( ! empty( $sophos_video_meta ) && ! empty( $sophos_video_meta[0]['static_display_image'] ) ) : ?>
		<div class="result-thumbnail video-image">
			<a href="<?php echo esc_url( $sophos_video_meta[0]['youtube_video_url'] ); ?>"><?php echo wp_kses_post( wp_get_attachment_image( $sophos_video_meta[0]['static_display_image'], 'thumbnail' ) ); ?></a>
		</div>
	<?php endif; ?>
	<div class="result-details">
		<header class="result-header">
			<?php the_title( sprintf( '<h1 class="result-title video-title"><a href="%s" rel="bookmark">', esc_url( $sophos_video_meta[0]['youtube_video_url'] ) ), '</a></h1>' ); ?>

			<div class="result-meta">
				<?php the_time('M d Y g:ia'); ?>
			</div><!-- .entry-meta -->
		</header><!-- .entry-header -->
	</div>
</article><!-- #post-## -->
