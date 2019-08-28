<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Sophos
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				$n     = get_comments_number();
				$title = sprintf( _nx( '<span>%s</span> Comment', '<span>%s</span> Comments', $n, 'Comments section title', 'sophos-news' ), number_format_i18n( $n ) );
				echo wp_kses( $title, [
					'span' => [],
				]);
			?>
		</h2>

		<?php
			wp_list_comments( array(
				'walker' => new Sophos_Comment_Walker,
				'short_ping' => true,
				'avatar_size' => 60,
				'reply_text'  => _x( 'Reply', 'Link that appears beneath a comment. Clicking on it opens the comment form so you can reply to a comment', 'sophos-news' ),
			));
		?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'sophos-news' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( 'Older Comments', 'sophos-news' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'sophos-news' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && '0' !== get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
	<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'sophos-news' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- #comments -->
