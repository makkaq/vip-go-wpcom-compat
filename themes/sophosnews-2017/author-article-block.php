<?php

/**
 * Multiple author blocks
 * Co-Authors Plus plugin [enabled]
 */

if ( is_author() ) {
	$class = 'author-bio-block';
	$size  = 400;
} else {
	$class = 'article-author-block article-co-authors-block';
	$size  = 145;
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
	<?php if ( function_exists( 'get_coauthors' ) ) :
		global $coauthors_plus;
		global $post;

		if ( is_author() ) :
			$author_slug = sanitize_user( get_query_var( 'author_name' ) );
			$coauthors 	 = [ $coauthors_plus->get_coauthor_by( 'user_nicename', $author_slug ) ];
		else:
			$coauthors = get_coauthors();
		endif;
	?>

		<?php if ( ! is_author() ) : ?>
			<h2 class="author-title">
				<?php echo esc_html( _n( 'About the Author', 'About the Authors', count( $coauthors ), 'sophos-news' ) ); ?>
			</h2>
		<?php endif; ?>

		<?php foreach ( $coauthors as $co ) : ?>
			<div class="author-block-container">
				<div class="author-profile">
					<?php echo wp_kses( coauthors_get_avatar( $co, $size ), [
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
					<h3 class="author-name"><?php echo wp_kses( coauthors_posts_links_single( $co ), [
						'a' => [
							'href' 	=> [],
							'title' => [],
							'class' => [],
							'rel' 	=> [],
						],
					]); ?></h3>

					<?php

					$guest = $coauthors_plus->guest_authors->get_guest_author_by( 'id', $co->ID, true );

					?>

					<div class="author-bio">
						<?php echo wp_kses( wpautop( $guest->description ), [
							'p' => []
						]); ?>
					</div> <!-- .author-bio -->
				</div> <!-- .author-description -->
			</div> <!-- .author-block-container -->
		<?php endforeach; ?>
	<?php else: ?>
		<div class="author-block-container">

			<?php if ( ! is_author() ) : ?>
				<h2 class="author-title">
					<?php esc_html_e( 'About the Author', 'sophos-news' ); ?>
				</h2>
			<?php endif; ?>

			<div class="author-profile">
				<?php echo wp_kses_post( get_avatar( get_the_author_meta( 'user_email' ), $size ) ); ?>
			</div> <!-- .author-profile -->

			<div class="author-description">
				<h3 class="author-name"><?php echo wp_kses( get_the_author_posts_link(), [
					'a' => [
						'href' 	=> [],
						'title' => [],
						'class' => [],
						'rel' 	=> [],
					],
				]); ?></h3>

				<div class="author-bio">
					<?php echo wp_kses( wpautop( get_the_author_meta( 'description' ) ), [
						'p' => []
					]); ?>
				</div> <!-- .author-bio -->
			</div> <!-- .author-description -->
		</div> <!-- .author-block-container -->
	<?php endif; ?>
</div>
