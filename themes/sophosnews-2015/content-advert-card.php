<?php $meta_data = get_post_meta( get_the_ID(), 'sophos_advert_fields' ); ?>
<article id="post-<?php the_ID(); ?>" class="card-article card-advert">
	<a class="advert-link" href="<?php echo esc_url( $meta_data[0]['destination_url'] ); ?>" title="<?php the_title_attribute(); ?>">
		<?php echo wp_kses_post( wp_get_attachment_image( $meta_data[0]['card_image'], 'banner-card' ) ); ?>
	</a>
</article> <!-- .card-advert -->
