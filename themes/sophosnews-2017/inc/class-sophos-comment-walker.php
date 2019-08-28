<?php
class Sophos_Comment_Walker extends Walker_Comment {

	// constructor – wrapper for the comments list
	function __construct() {
	    ?><section class="comments-list"><?php
	}

	// start_lvl – wrapper for child comments list
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 2;
		?><section class="child-comments comments-list"><?php
	}

	// end_lvl – closing wrapper for child comments list
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 2;
		?></section><?php
	}

	// start_el – HTML for comment template
	function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;
		$parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' );

		if ( 'article' === $args['style'] ) {
			$tag = 'article';
			$add_below = 'comment';
		} else {
			$tag = 'article';
			$add_below = 'comment';
		}

		// Customize Date/Time Format. Absolute Datetime if comment is older than 24 hours, relative if younger.
		$datetime_string = get_comment_date( 'd F Y' ) . ' at ' . get_comment_time( 'g:i a' );
		if ( strtotime( "{$comment->comment_date_gmt} GMT" ) > strtotime( '-24 hours' ) ) {
			$datetime_string = human_time_diff( strtotime( "{$comment->comment_date_gmt} GMT" ) );
		}

        $bystaff = false;
        if ( class_exists( 'CoAuthors_Plus' ) ) {
            global $coauthors_plus;
            if ( $author = $coauthors_plus->get_coauthor_by( 'id', $comment->user_id ) ) {
                $key        = 'sophos-staff';
                $meta_value = property_exists( $author, $key ) ? $author->$key : false;
                $bystaff    = ! empty( $meta_value );
            }
        }

        if ( false === $bystaff && user_can( $comment->user_id, 'publish_posts' ) ) {
            $bystaff = true;
        }

        $comment_classes = [
            empty( $args['has_children'] ) ? '' : 'parent',
            $bystaff ? 'bystaff' : '',
        ];

		$avatar = get_avatar( $comment, $args['avatar_size'] );
		?>

		<article <?php comment_class( $comment_classes ) ?> id="comment-<?php comment_ID() ?>" itemprop="comment" itemscope itemtype="http://schema.org/Comment">
			<div class="comment-wrapper">
				<figure class="gravatar"><?php echo wp_kses( $avatar, [
					'img' => [
						'alt' => 1,
						'src' => 1,
						'srcset' => 1,
						'class' => 1,
						'height' => 1,
						'width' => 1,
					],
				] ); ?></figure>
				<div class="comment-meta post-meta" role="complementary">
					<h2 class="comment-author">
						<?php if ( get_comment_author_url() ) : ?>
							<a class="comment-author-link" href="<?php echo esc_url( get_comment_author_url() ); ?>" itemprop="author"><?php echo esc_html( get_comment_author() ); ?></a>
						<?php else : ?>
							<?php echo esc_html( get_comment_author() ); ?>
						<?php endif; ?>
					</h2>
					<time class="comment-meta-item" datetime="<?php echo esc_attr( get_comment_date( 'Y-m-d' ) ); ?>T<?php echo esc_attr( comment_time( 'H:iP' ) ); ?>" itemprop="datePublished"><a href="#comment-<?php echo esc_attr( get_comment_ID() ); ?>" itemprop="url"><?php echo esc_html( $datetime_string ); ?></a></time>
					<?php edit_comment_link( sprintf( '<p class="comment-meta-item">%s</p>', __( 'Edit this comment' ) ),'','' ); ?>
					<?php if ( 0 === $comment->comment_approved ) : ?>
					<p class="comment-meta-item"><?php esc_html_e( 'Your comment is awaiting moderation.', 'sophos-news' ); ?></p>
					<?php endif; ?>
				</div>
				<div class="comment-content post-content" itemprop="text">
					<?php comment_text() ?>
					<?php comment_reply_link( array_merge( $args, array(
						'add_below' => $add_below,
						'depth' => $depth,
						'max_depth' => $args['max_depth'],
					) ) ) ?>
				</div>
			</div>

	<?php }

	// end_el – closing HTML for comment template
	function end_el( &$output, $comment, $depth = 0, $args = array() ) {
	    ?></article><?php
	}

	// destructor – closing wrapper for the comments list
	function __destruct() {
	    ?></section><?php
	}

}
