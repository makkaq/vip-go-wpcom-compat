<?php
$selected_breaking_news_post_id = get_theme_mod( 'sophos_breaking_news', 'disable' );
if ( 'disable' !== $selected_breaking_news_post_id ) : ?>

	<?php // Display the breaking news posts
	$breaking_news_post = new WP_Query( [ 'posts_per_page' => 1, 'p' => $selected_breaking_news_post_id, ] );
	while ( $breaking_news_post->have_posts() ) : $breaking_news_post->the_post(); ?>

		<section id="post-<?php the_ID(); ?>" class="breaking-news-panel">
			<div class="breaking-news-image" style="background-image:linear-gradient(rgba(0,0,0,0.25),rgba(0,0,0,0.75)), url('<?php echo esc_url( sophos_get_featured_image_url() ); ?>');">
				<div class="panel-strip">
					<div class="container">
						<div class="strip-title"><?php esc_html_e('Breaking news', 'forward'); ?></div>
						<div class="strip-hide"><a id="hide-breaking-news" href="#"><?php esc_html_e('Hide', 'forward'); ?></a></div>
					</div>
				</div>
				<div class="container">
					<div class="panel-content">
						<article class="card-article">
							<div class="card-content">
								<div class="meta-box">
									<div class="date-box">
										<span class="month"><?php the_time( 'M' ); ?></span><span class="day"><?php the_time( 'd' ); ?></span>
									</div>
									<div class="categories-box"><?php the_category( ', ' ); ?></div>
								</div>
								<?php the_title( sprintf( '<h3 class="card-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
							</div>
							<div class="card-pointer"></div>
						</article>
					</div> <!-- .panel-container -->
				</div> <!-- .container -->
			</div>
		</section> <!-- .panel -->

	<?php endwhile; wp_reset_postdata(); ?>
<?php endif;
