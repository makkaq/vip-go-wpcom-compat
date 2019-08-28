<?php

namespace Sophos\CLI;

use WP_CLI;

/**
 * Sophos user commands.
 */
class User extends \WPCOM_VIP_CLI_Command {

	const USER_META_LANGUAGE = 'sophos-language';

	/**
	 * Removes the 'sophos-language' meta_key from 'wp_usermeta' for every user.
	 *
	 * Meant to be used after creating CoAuthors Plus guest accounts (via wp-cli)
	 * which copies 'sophos-language' over to 'cap-sophos-language' in 'wp_postmeta'.
	 *
	 * $ wp co-authors-plus create-guest-authors
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Perform a trial run without changing any data.
	 *
	 * ## EXAMPLES
	 *
	 *     wp sophos-user remove-region
	 *     wp sophos-user remove-region --dry-run
	 *
	 * @when init
	 * @subcommand remove-region
	 */
	function remove_region( $args, $assoc_args ) {
		$live        = empty( $assoc_args['dry-run'] );
		$users       = get_users(
			[
				'meta_key' => self::USER_META_LANGUAGE,
			]
		);
		$users_count = count( $users );

		if ( $users_count < 1 ) {
			WP_CLI::error(
				sprintf(
					'No users found with the meta_key \'%1$s\'.',
					self::USER_META_LANGUAGE
				)
			);
		}

		$this->confirm_delete( $assoc_args, $users_count );
		$this->delete_sophos_language_meta( $users, $live );
	}

	private function delete_sophos_language_meta( $users, $live = true ) {
		foreach ( $users as $user ) {
			$sophos_language = \Sophos\User\get_attribute_or_meta(
				$user->ID,
				self::USER_META_LANGUAGE,
				true
			);
			$log_message     = 'Deleted \'%1$s\' from user %3$s (%2$s).';

			if ( $live ) {
				// Delete the meta key for real.
				if ( true === \Sophos\User\delete_attribute_or_meta( $user->ID, self::USER_META_LANGUAGE ) ) {
					WP_CLI::success(
						sprintf(
							$log_message,
							$sophos_language,
							$user->user_login,
							$user->ID
						)
					);
				} else {
					WP_CLI::error( sprintf( 'Could not delete %s from %s (%d)', self::USER_META_LANGUAGE, $user->user_login, $user->ID ) );
				}
			} else {
				// Just pretend.
				WP_CLI::log(
					sprintf(
						'[dry-run]: ' . $log_message,
						$sophos_language,
						$user->user_login,
						$user->ID
					)
				);
			}
		}
	}

	private function confirm_delete( $assoc_args, $users_count ) {
		$warning_message =
			'-----------------------------------------------------------------------------------' . "\n\n" .

			'  WARNING: You are about to permanently delete \'%1$s\' from %2$s user(s).             ' . "\n\n" .

			'  - Ensure guest authors are created and \'cap-sophos-language\'                   ' . "\n" .
			'    meta_key(s) exist in \'wp_postmeta\' before proceeding.                        ' . "\n" .
			'    $ wp co-authors-plus create-guest-authors                                      ' . "\n\n" .

			'  - You can also preview the list of users affected using --dry-run                ' . "\n" .
			'    $ wp sophos-user remove-region --dry-run                                       ' . "\n\n" .

			'-----------------------------------------------------------------------------------' . "\n" .
			'Continue?';
		WP_CLI::confirm(
			sprintf(
				$warning_message,
				self::USER_META_LANGUAGE,
				$users_count
			), $assoc_args
		);
	}
}

WP_CLI::add_command( 'sophos-user', '\Sophos\CLI\User' );
