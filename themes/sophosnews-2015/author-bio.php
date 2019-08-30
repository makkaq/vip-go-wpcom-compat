<?php

/**
 * Multiple author blocks
 * Co-Authors Plus plugin [enabled]
 */

if ( is_author() ) {
	$class = 'author-bio-block';
	$size  = 362;
} else {
	$class = 'article-author-block article-co-authors-block';
	$size  = 200;
}

$avatar	= false;
$link	= false;
$bio 	= false;
$name   = false;

if ( function_exists( 'get_coauthors' ) ) :

	global $coauthors_plus;

	$coauthors = get_coauthors();
	$coauthor  = array_shift( $coauthors );
	$guest	   = $coauthors_plus->guest_authors->get_guest_author_by( 'id', $coauthor->ID, true );

	if ( false !== $guest ) {
		$avatar = coauthors_get_avatar( $coauthor, $size );
		$link   = coauthors_posts_links_single( $coauthor );
		$bio	= $guest->description;
		$name	= $guest->display_name;
	}

endif;

$avatar = $avatar ?: get_avatar( get_the_author_meta( 'user_email' ), $size );
$link 	= $link	  ?: get_the_author_posts_link();
$bio	= $bio	  ?: get_the_author_meta( 'description' );
$name	= $name	  ?: get_the_author_meta( 'display_name' );

?><div class="<?php echo esc_attr( $class ); ?>">
	<div class="author-profile">
		<?php echo wp_kses( $avatar, [
			'img' => [
				'width'  => [],
				'height' => [],
				'src'	 => [],
				'class'	 => [],
				'alt'	 => [],
			],
		]); ?>
	</div> <!-- .author-profile -->
	<div class="author-description">
		<?php if ( is_author() ) : ?>
			<div class="author-title"><?php esc_html_e( 'Naked security author', 'naked-security' ); ?></div>
			<h3 class="author-name"><?php echo esc_html( $name ); ?></h3>
		<?php else : ?>
			<h3 class="author-name"><?php echo wp_kses( $link, [
				'a' => [
					'href' 	=> [],
					'title' => [],
					'class' => [],
					'rel' 	=> [],
				],
			]); ?></h3>
		<?php endif; ?>
		<p class="author-bio"><?php echo esc_html( $bio ); ?></p>
	</div> <!-- .author-description -->
</div>
