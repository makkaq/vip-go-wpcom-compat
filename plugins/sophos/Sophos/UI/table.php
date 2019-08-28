<?php
/**
 * Changes to WP_Media_List_Table UI
 *
 * @package Sophos
 * @subpackage UI
 */

namespace Sophos\UI\Table;


/**
 * Add region filter dropdown to wp-admin posts/page listing
 */
function add_region_filter() {

	// Check for post & page content
	global $typenow;

	if ( ! in_array( $typenow, \Sophos\Region\Taxonomy::POST_TYPES, true ) ) {
		return;
	}

	if ( ! \Sophos\Utils\is_regionalised() ) {
		return;
	}

	$regions = \Sophos\Region::regions();

	// Get the region, guess it if the parameter is unset, show all if it's false
	$query_var = get_query_var( \Sophos\Region\Taxonomy::NAME, \Sophos\Region::guess() );
	$show_all  = ( false === $query_var ) ? 'selected' : '';

	?>
	<select name="<?php echo esc_attr( \Sophos\Region\Taxonomy::NAME ); ?>">
		<option value="<?php echo esc_attr( \Sophos\Region\Taxonomy::ALL_REGIONS ); ?>" <?php echo esc_attr( $show_all ); ?>><?php esc_html_e( 'All Regions', 'sophos-news' ); ?></option>
		<?php foreach ( $regions as $region ) :
			$selected = ( $query_var === $region->slug ) ? 'selected' : '';
			?><option value="<?php echo esc_attr( $region->slug ); ?>" <?php echo esc_html( $selected ); ?>>
				<?php echo esc_html( $region->name ); ?>
		   </option>
		<?php endforeach; ?>
	</select>
	<?php
}
