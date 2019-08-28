<?php
/**
 * Changes to Post edit UI
 *
 * @package Sophos
 * @subpackage UI
 */

namespace Sophos\Comment;


/**
 * Render a redirect_to field with the URL of the current post
 */
function add_field_redirect_to() {
	?><input type="hidden" name="redirect_to" value="<?php echo esc_url( get_permalink() ); ?>" id="redirect_to"><?php
}
