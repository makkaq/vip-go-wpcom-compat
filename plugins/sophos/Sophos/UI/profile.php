<?php
/**
 * Changes to user profile page
 *
 * @package Sophos
 * @subpackage UI
 */

namespace Sophos\UI\Profile;


/**
 * Add region field to user profile
 *
 * Allows the user to select their region preference from a list of (region)
 * taxonomy terms.
 *
 * @FIXME remove this when we've successfully migrated to Guest Authors?
 *
 * @return string
 */
function add_region_field( $user ) {

	if ( ! \Sophos\Utils\is_regionalised() ) {
		return;
	}

	$regions   = \Sophos\Region::regions();
	$disabled  = ( current_user_can( \Sophos\User\ADMIN_CAPABILITY ) ) ? '' : 'disabled';
	$selection = '';

	if ( $user instanceof \WP_User ) {
		$selection = \Sophos\User\get_meta( $user->ID, \Sophos\Language::USER_META_KEY );
	}

	?>
	<table class="form-table" id="sophos-region">
		<tr>
			<th>
				<label for="<?php echo esc_attr( \Sophos\Language::USER_META_KEY ); ?>">
					<?php esc_html_e( 'Region', 'sophos-news' ); ?>
				</label>
			</th>
			<td>
				<select class="" name="<?php echo esc_attr( \Sophos\Language::USER_META_KEY ); ?>" <?php echo esc_attr( $disabled ); ?>>
					<option><?php echo esc_html( __( 'Select a region', 'sophos-news' ) ); ?></option>
					<?php foreach ( $regions as $region ) :
						$selected = ( $selection === $region->slug ) ? 'selected' : '';
					?><option value="<?php echo esc_attr( $region->slug ); ?>" <?php echo esc_attr( $selected ); ?>>
						<?php echo esc_html( $region->name ); ?>
					</option>
					<?php endforeach; ?>
				</select>
				<?php if ( ! current_user_can( \Sophos\User\ADMIN_CAPABILITY ) ) : ?>
					<p class="description"><?php esc_html_e( 'Please contact an administrator to change your region.', 'sophos-news' ); ?></p>
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<?php
}


/**
 * Save user profile region field
 *
 * Saves the user's preferred region from the user profile page.
 */
function save_region_field( $user_id ) {

	// FIXME should check current_user_can( \Sophos\User\ADMIN_CAPABILITY ) but
	// on the production environment admins can't actually get access to users'
	// Personal Settings page to change it, users can only change it themselves.
	// Restriction removed pending: https://wordpressvip.zendesk.com/hc/en-us/requests/65464
	if ( ! is_admin() ) {
		return;
	}

	$iso = filter_input( INPUT_POST, \Sophos\Language::USER_META_KEY, FILTER_VALIDATE_REGEXP, [
		'options' => [
			'regexp' => \Sophos\Language::LOCALE_PATTERN,
		],
	]);

	if ( ! \Sophos\Utils\is_region( $iso ) ) {
		return;
	}

	\Sophos\User\update_meta( $user_id, \Sophos\Language::USER_META_KEY, $iso );
}

/**
 * Add a new column to the Users listing table.
 * https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_users_columns
 *
 * @param $columns array    an array of column name => label
 *
 * @return array
 */
function region_column( $columns ) {
	$columns['region'] = __( 'Region', 'sophos-news' );

	return $columns;
}

/**
 * Output the user's selected region in our custom column.
 *
 * @param $output
 * @param $column_name
 * @param $user_id
 *
 * @return string
 */
function region_column_value( $output, $column_name, $user_id ) {

	if ( 'region' !== $column_name ) {
		return $output;
	}

	$user_region = \Sophos\User\get_meta( $user_id, \Sophos\Language::USER_META_KEY );

	if ( false !== $user_region ) {
		try {
			$region = \Sophos\Region::from_slug( $user_region );

			return $region->name;
		} catch ( \Sophos\Exception\TaxonomyError $e ) {
			return '';
		}
	}

	return '';
}
