<?php

$class = 'card-article';

/**
 * Changes the class name of the article.
 * Set in the template using `set_query_var( 'is_slide', true );`
 */
if ( isset( $article_class ) ) {
	$class = $article_class;
}

?>
<article id="post-<?php the_ID(); ?>" class="<?php esc_attr_e( $class ); ?>">
	<div class="card-image">
		<div class="image-pointer"></div>
		<a href="<?php echo esc_url( get_permalink() ); ?>"><?php sophos_get_featured_image_as_background( 'card-thumbnail' ); ?></a>
	</div>
	<div class="card-content">
		<div class="meta-box">
			<div class="date-box">
				<span class="month"><?php the_time( 'M' ); ?></span><span class="day"><?php the_time( 'd' ); ?></span>
			</div>
 			<div class="author-box"><?php sophos_content_card_entry_authors(); ?></div>
			<div class="comment-box">
				<span class="count"><?php echo esc_html( get_comments_number() ); ?></span>
			</div>
		</div>
		<?php the_title( sprintf( '<h3 class="card-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
	</div>
</article> <!-- .card-article -->
