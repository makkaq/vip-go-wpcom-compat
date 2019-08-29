<?php $takeover = sophos_get_add_takeover(); ?>
<?php sophos_panel_open( 'takeover-panel' ); ?>
	<a class="takeover-image-link" href="<?php echo esc_url( $takeover['url'] ); ?>"><img class="takeover-image" src="<?php echo esc_url( sophos_image_asset( 'promos/' ) . $takeover['img'] ); ?>" alt="<?php esc_attr_e( $takeover['name'] ); ?>"></a>
<?php sophos_panel_close();
