<?php /* Pull the latest post for the author */
$most_recent_post = new WP_Query(
	[
		'author'              => $author->ID,
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => 1,
	]
); ?>

<article class="author-card">

	<div class="author-profile">
		<div class="image-pointer"></div>
		<a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php echo wp_kses_post( get_avatar( $author->user_email, 300 ) ); ?></a>
	</div> <!-- .author-profile -->

	<div class="author-description">
		<h3 class="author-name">
			<a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php echo esc_html( $author->display_name ); ?></a>
		</h3>

		<?php if ( $most_recent_post->have_posts() ) : $most_recent_post->the_post(); ?>
			<div class="card-meta">Most Recent / <?php echo wp_kses_post( get_the_date( 'M j, Y' ) ); ?></div>
			<h3 class="card-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h3>
		<?php endif; ?>
	</div> <!-- .author-description -->

</article> <!-- .card-article -->

<?php wp_reset_postdata();
