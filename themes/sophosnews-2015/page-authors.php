<?php

/**
 * Template Name: Authors Page
 *
 * The main authors page.
 */

class Sophos_User extends \WP_User {

	/**
	 * @var Number of posts this author has published
	 */
	private $_count_posts = false;

	/**
	 * Has this author been published?
	 * 
	 * @return boolean 
	 */
	public function is_published() {
		return $this->count_posts() > 0 ? true : false;
	}

	/**
	 * Number of posts this author has published
	 * 
	 * @return int
	 */ 
	public function count_posts() {
		if ( false === $this->_count_posts ) {
			if ( function_exists('wpcom_vip_count_user_posts') ) {
			    $this->_count_posts = wpcom_vip_count_user_posts($this->ID);		
			} else {
			    $this->_count_posts = count_user_posts($this->ID);
			} 
		}
	
		return $this->_count_posts;
	}
}

// Get a list of published authors and store it for 12 hours
if ( false === ($authors = wp_cache_get('sophos_authors')) ) {
	$ids = new WP_User_Query([
		'fields'        => 'ID',
		'number'        => 999,
		'count_total'   => false,
		/* WARNING - 'post_count' is not available on VIP for performance reasons
		 * and using it will result in the query returning no results. I'm leaving
		 * the code in but commenting it out to avoid any repeat.
		 * 'orderby'    => 'post_count',
		 * 'order'      => 'DESC'
		 */
	]);

	// Convert user IDs in to Sophos_Users so we can filter and sort them
	$users = array_map( function ($id) { 
		return new \Sophos_User($id);		
	}, $ids->results);

	// Filter out users who aren't published authors
	$authors = array_filter( $users, function ($user) {
		return $user->is_published();
	});

	// Sort published authors by the number of articles they've written
	usort( $authors, function ($a, $b) {
		if ( $b->count_posts() === $a->count_posts() ) {
			return 0;
		}

		return ($a->count_posts() > $b->count_posts()) ? -1 : 1;	
	});
	wp_cache_set( 'sophos_authors', $authors, '', 43200 );
}

$per_page	   = 9; # FIXME Get this from global config
$paged		   = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$offset		   = ( $paged - 1 ) * $per_page;
$total_pages   = intval( count($authors) / $per_page ) + 1;
$this_page     = array_slice($authors, $offset, $per_page);

// We need to override the 'maxPages' var for wp_ajax_page_loader to work properly
add_filter( 'sophos_ajax_loader_max_pages', function () use ( $total_pages ) {
	return $total_pages;
} );

get_header(); ?>

</div> <!-- .container -->
</div> <!-- #content -->

<?php if ( !empty( $this_page ) ) : ?>

	<?php sophos_panel_open( 'author-results-panel' ); ?>
	<h3 class="block-title">
		<?php esc_html_e( 'NAKED SECURITY authors', 'nakedsecurity' ); ?>
	</h3>
	<?php sophos_panel_close(); ?>

	<?php sophos_panel_open( 'author-cards-panel' ); ?>
		<div class="author-card-collection">
			<div class="content-wrapper">
				<?php foreach ( $this_page as $author ) : ?>
					<?php // Including the template this way so we can use the $author variable in it.
					include( locate_template( 'content-author-card.php' ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	<?php sophos_panel_close(); ?>

	<?php if ( next_posts( $total_pages, false ) ) : ?>
		<div class="load-more">
			<div class="container">
				<a href="<?php next_posts( $total_pages, true ); ?>" class="button"><?php esc_html_e( 'Show more authors', 'nakedsecurity' ); ?></a>
			</div>
		</div>
	<?php endif; ?>

<?php endif; ?>

<?php get_footer();
