<?php
/**
 * Co-Authors Plus Plugin setup and utilities
 *
 * @package Sophos
 * @subpackage UI
 */

namespace Sophos\UI\CoAuthors;


/**
 * Similar to Core's get_user_meta but we pull data from the CoAuthors Plus plugin.
 *
 * @param $user_id User ID
 * @param string $key The meta key to retrieve.
 *
 * @return string|bool
 */
function get_coauthor_user_meta( $user_id, $key = '' ) {

	if ( ! is_coauthor_managed_field( $key ) ) {
		return false;
	}

	global $coauthors_plus;

	if ( ! $coauthors_plus instanceof \CoAuthors_Plus ) {
		return false;
	}

	$author = $coauthors_plus->get_coauthor_by( 'id', $user_id );

	return is_object( $author ) && property_exists( $author, $key ) ? $author->$key : false;
}

/**
 * Similar to Core's update_user_meta but we update data for the CoAuthors Plus plugin.
 *
 * @param $user_id
 * @param $meta_key
 * @param $meta_value
 *
 * @return int|bool
 */
function update_coauthor_user_meta( $user_id, $meta_key, $meta_value ) {

	if ( ! is_coauthor_managed_field( $meta_key ) ) {
		return false;
	}

	global $coauthors_plus;

	if ( ! $coauthors_plus instanceof \CoAuthors_Plus ) {
		return false;
	}

	if ( ! property_exists( $coauthors_plus, 'guest_authors' ) ) {
		return false;
	}

	$coauthor = $coauthors_plus->get_coauthor_by( 'id', $user_id );
	$guests   = $coauthors_plus->guest_authors;
	$pm_key   = $guests->get_post_meta_key( $meta_key );

	return update_post_meta( $coauthor->ID, $pm_key, $meta_value );
}

/**
 * Check if a user_meta key is being managed by CoAuthors Plus.
 *
 * @param $key
 *
 * @return bool
 */
function is_coauthor_managed_field( $key ) {

	global $coauthors_plus;

	if ( ! $coauthors_plus instanceof \CoAuthors_Plus ) {
		return false;
	}

	if ( ! property_exists( $coauthors_plus, 'guest_authors' ) ) {
		return false;
	}

	$fields 	= $coauthors_plus->guest_authors->get_guest_author_fields();
	$is_managed = in_array( $key, array_column( $fields, 'key' ), true );

	return $is_managed;
}

/**
 * Add a "Region" field to Co-Authors Plus Guest Author
 * https://vip.wordpress.com/documentation/add-guest-bylines-to-your-content-with-co-authors-plus/#incorporating-new-profile-fields
 *
 * @param $fields_to_return
 * @param $groups
 *
 * @return array
 */
function guest_author_sophos_fields( $fields_to_return, $groups ) {

	if ( in_array( 'all', $groups, true ) || in_array( \Sophos\Language::USER_META_KEY, $groups, true ) ) {
		$fields_to_return[] = array(
			'key'   => \Sophos\Language::USER_META_KEY,
			'label' => __( 'Author\'s Region', 'sophos-news' ),
			'group' => 'sophos-settings',
		);

		$fields_to_return[] = array(
			'key'   => 'sophos-staff',
			'label' => __( 'Show as staff', 'sophos-news' ),
			'group' => 'sophos-settings',
		);
	}

	return $fields_to_return;
}

/**
 * Add a meta-box to the CoAuthors Plugin custom post type so we can manage our custom settings.
 * https://developer.wordpress.org/reference/hooks/add_meta_boxes/
 */
function action_add_meta_boxes() {
	if ( 'guest-author' === get_post_type() ) {
		add_meta_box( 'coauthors-manage-guest-author-sophos', __( 'Sophos Settings', 'sophos-news' ),
		'\Sophos\UI\CoAuthors\manage_guest_author_sophos_settings', 'guest-author', 'normal', 'default' );
	}
}

/**
 * Output our fields for our meta-box in the CoAuthor's Plus custom post type.
 * https://developer.wordpress.org/reference/functions/add_meta_box/
 */
function manage_guest_author_sophos_settings() {

	if ( ! \Sophos\Utils\is_regionalised() ) {
		return;
	}

	global $post, $coauthors_plus;

	if ( ! $coauthors_plus instanceof \CoAuthors_Plus ) {
		return;
	}

	$regions  = \Sophos\Region::regions();
	$disabled = ( current_user_can( \Sophos\User\ADMIN_CAPABILITY ) ) ? '' : 'disabled';

	if ( ! property_exists( $coauthors_plus, 'guest_authors' ) ) {
		return;
	}

	$guests   = $coauthors_plus->guest_authors;
	$fields   = $guests->get_guest_author_fields( \Sophos\Language::USER_META_KEY );

	?><table class="form-table">
		<tbody>
		<?php foreach ( $fields as $field ) :
			$pm_key = $guests->get_post_meta_key( $field['key'] );
			$value  = get_post_meta( $post->ID, $pm_key, true ); ?>
			<tr>
				<th>
					<label for="<?php echo esc_attr( $pm_key ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
				</th>
				<td>

					<?php if ( \Sophos\Language::USER_META_KEY === $field['key'] ) : ?>
						<select name="<?php echo esc_attr( $pm_key ); ?>" <?php echo esc_attr( $disabled ); ?>>
							<option value=""><?php esc_html_e( 'Select a region', 'sophos-news' ); ?></option>

							<?php foreach ( $regions as $region ) :
								$selected = ( $value === $region->slug ) ? 'selected' : ''; ?>
								<option value="<?php echo esc_attr( $region->slug ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $region->name ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php endif; ?>

					<?php if ( 'sophos-staff' === $field['key'] ) : ?>
						<select name="<?php echo esc_attr( $pm_key ); ?>" <?php echo esc_attr( $disabled ); ?>>
							<?php foreach ( [
							'0' => 'No',
							'1' => 'Yes',
] as $toggle => $name ) :
								$selected = ( $value === $toggle ) ? 'selected' : ''; ?>
								<option value="<?php echo esc_attr( $toggle ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $name ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php
}
