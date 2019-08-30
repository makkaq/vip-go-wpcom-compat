<?php
/*
 Plugin Name: Naked Security Translate
 Plugin URI: https://nakedsecurity.sophos.com
 Description: Machine Translation
 Version: 0.6.5
 Author: Mark Stockley (Compound Eye Ltd)
 Author URI: 
 License: GPL3

 Copyright 2011  Mark Stockley  (email : mark@compoundeye.co.uk)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * @package cTranslate
 * @author Mark Stockley
 * @version 0.6.3
 * @copyright Copyright (c) 2011, Mark Stockley
 * @license https://opensource.org/licenses/gpl-3.0.html
 */
require_once 'Utils.php';
require_once 'PostType.php';
require_once 'views/Admin.php';
require_once 'Google.php';
require_once 'WP_Widget_cTranslate.php';
require_once 'template-tags.php';


// CLI scripts
if ( defined( 'WP_CLI' ) && WP_CLI ) {
    require_once 'cli/UpdateLanguageStorage.php';
}

define( 'C_VERSION',     				  '0.6.2' );
define( 'C_SECONDS_BETWEEN_TRANSLATIONS', 5 );
define( 'C_TRANSIENT_TIME_OUT',        	  604800 );
define( 'C_PLUGIN_DIR',    				  plugin_dir_url( __FILE__ ) );
define( 'C_OPT_API_KEY',     			  'cet_google_api_key' );
define( 'C_OPT_LANGUAGES',   			  'cet_languages' );
define( 'C_OPT_LANGUAGES_v2', 'translation_languages' );
define( 'C_POST_TYPE',     				  'translation' );
define( 'C_ISO_REGEX',     				  '([a-z][a-z](?:-[A-Z][A-Z])?)');
define( 'C_BASE_LANG',     				  get_bloginfo('language') );
define( 'CET_ERROR_UNKNOWN',              100);
define( 'CET_ERROR_GET_POST',             101);
define( 'CET_ERROR_ISO_VALIDATION',       102);
define( 'CET_ERROR_POST_TYPE',            103);
define( 'CET_ERROR_NO_RIGHTS',            104);
define( 'CET_ERROR_POSTMETA',             105);
define( 'CET_NOT_SCHEDULED',              106);

add_action( 'init',                  	  'cet_register_query_var' );
add_action( 'init',                  	  'cet_allow_notranslate' );
add_action( 'admin_enqueue_scripts', 	  'cet_register_css');
add_action( 'admin_init',            	  'cet_create_settings' );
add_action( 'admin_menu',            	  'cet_create_menu' );
add_action( 'admin_notices',              'cet_translation_status' );
add_action( 'add_meta_boxes',        	  'cet_create_translation_box' );
add_action( 'cet_run_translation',   	  'cet_trigger_translation', 10, 1 );
add_action( 'save_post',             	  'cet_save_language_choices' );
add_action( 'trashed_post',               'cet_cleanup_trashed_translation' );
add_action( 'before_delete_post',         'cet_cleanup_trashed_translation' );
add_action( 'publish_post',          	  'cet_process_translation_requests' );
add_action( 'parse_request',              'cet_redirect_base_lang');
add_action( 'pre_get_posts',              'cet_modify_post_request');



add_filter( 'post_type_link',                                          'cet_permalink', 10, 3);
add_filter( 'generate_rewrite_rules',                  	               'cet_modify_rewrite_rules' );
add_filter( 'pre_get_posts',                                           'cet_modify_search' );

// Register post type, create singleton
add_action( 'init', array('\Sophos\Translation\PostType', 'register'));

\Sophos\Translation\Views\Admin::init();

/* FIXME comment from Daniel Bachhuber
 * Also, as a point of reference (and this was included in my slides too),
 * flushing the rewrite rules on the activation hook doesn't actually work.
 * This is because the activation hook happens too late in the process.
 * The best thing to do is to instruct your user to visit the permalink
 * options page. Alternatively, if you have an install/upgrade path, you
 * can do it then. Just a heads up if you plan to release this as well.*/
// register_activation_hook( __FILE__, 'cet_activate' );

/**
 * Execute code that should only run on activation
 */
function cet_activate() {
	global $wp_rewrite;

	$wp_rewrite->flush_rules();

	add_option( 'cet_active',  true );
	add_option( 'cet_version', C_VERSION );
}

/**
 * Modifies URL structures to accomodate language ISO codes
 * @param WP_Rewrite $wp_rewrite
 */
function cet_modify_rewrite_rules (WP_Rewrite $wp_rewrite) {
	$keytag_token = '%language%';
	$wp_rewrite->add_rewrite_tag( $keytag_token, C_ISO_REGEX, 'language_iso_code=' );

	/* Categories, tags and other views will follow later once I have queries for them. For now it's just
	 * permalinks.
	 */
	$permalinks = $wp_rewrite->generate_rewrite_rules( "/$keytag_token/%year%/%monthnum%/%day%/%postname%/" );

	$wp_rewrite->rules = $permalinks + $wp_rewrite->rules;
}

/**
 * Add language_iso_code to the list of parameters that can be used in queries
 */
function cet_register_query_var () {
	global $wp;
	$wp->add_query_var( 'language_iso_code' );
}

/**
 * Render translation permalinks correctly
 * @param string $permalink
 * @param object $post_id
 * @param unknown_type $leavename
 */
function cet_permalink( $permalink, $post_id, $leavename ) {

	$translation = is_object( $post_id ) ? $post_id : get_post( $post_id );

	if ( get_post_type( $translation ) === C_POST_TYPE ) {
		if ( $master_id = get_post_meta( $translation->ID, 'cet_has_master_with_id', true ) ) {
			if ( $iso = get_post_meta( $translation->ID, 'cet_language_iso_code', true ) ) {

				$url      = parse_urL( get_permalink( $master_id ) );
				$scheme   = isset( $url['scheme'])   ?       $url['scheme'] . '://' : '';
				$host     = isset( $url['host'])     ?       $url['host']           : '';
				$port     = isset( $url['port'])     ? ':' . $url['port']           : '';
				$path     = isset( $url['path'])     ?       $url['path']           : '';
				$query    = isset( $url['query'])    ? '?' . $url['query']          : '';
				$fragment = isset( $url['fragment']) ? '#' . $url['fragment']       : '';

				return $scheme . $host . $port . "/$iso" . $path . $query . $fragment;

			} else {
                // It seems that on wordpress.com the post_type_link filter
                // might be called before the post meta has been written so
                // we don't die here.
            }
		} else {
            // It seems that on wordpress.com the post_type_link filter
            // might be called before the post meta has been written so
            // we don't die here.
        }
	}
}

function cet_unparse_url( $parsed_url ) {

  return "$scheme$user$pass$host$port$path$query$fragment";
}


/**
 * To support Google's code for marking-up things that should't be
 * translated the span element and class attribute are added to the
 * list of tags allowed in posts.
 * <code>
 * <span class="notranslate">Do not translate me</span>
 * </code>
 */
function cet_allow_notranslate () {
	global $allowedposttags;

	$modifications   = array( 'span' => array( 'class' => array() ) );
	$allowedposttags = array_merge( $allowedposttags, $modifications );
}

/**
 * Register and load css
 */
function cet_register_css () {
	$name = 'cet_css';
	$url  = sprintf('%s/inc/plugins/translate/css/ctranslate.css', get_stylesheet_directory_uri());

	wp_register_style( $name, $url, false, C_VERSION );
	wp_enqueue_style( $name );
}

/**
 * Returns an array of available language codes and names. Language names
 * are written in the language specified by gltr_base_lang
 *
 * @return array in the format
 * array(n) { [0]=> array(2) { ["language"]=> string(2) "af" ["name"]=> string(9) "Afrikaans" } ... }
 */
function cet_get_available_languages () {
    try {
        $api = new \Google();

        if ( $api instanceof \Google ) {
            return $api->languages(C_BASE_LANG);
        } else {
            throw new \Exception('Could not create Google Translate object');
        }
    } catch (Exception $e) {
        trigger_error( $e->getMessage(), E_USER_ERROR );
        wp_die( $e->getMessage() );
    }

    return array();
}

/**
 * Trigger a 301 redirect for URL paths that start with the base lang
 *
 * To prevent duplicate content and associated SEO penalties we redirect
 * requests for translated URLs to a corresponding URL without the language
 * iso code in it.
 *
 * @param WP $wp
 */
function cet_redirect_base_lang (WP $wp ) {
	if ( !is_admin() ) {
		$path  = $wp->request;
		$parts = preg_split( '|/|', $path, 2 );

		if ( $parts ) {
			$start = array_shift( $parts );
			$rest  = array_shift( $parts );

			if ( $start === C_BASE_LANG ) {
				wp_redirect( site_url( "/$rest" ), 301 ); exit;
			}
		}
	}
}

/**
 * Modifies post request queries to include language ISO codes
 * @param WP_Query $wp_query
 */
function cet_modify_post_request (\WP_Query $query) {
    if ( $query->is_main_query() && post_type_exists(\Sophos\Translation\PostType::POST_TYPE) && $query->get('language_iso_code') ) {
        $query->set('post_type', \Sophos\Translation\PostType::POST_TYPE);

        if ( $name = $query->get('name') ) {
            $query->set('name', sprintf('%s-%s*', $name, $query->get('language_iso_code')));
        } else {
            $query->set('tax_query', array(	
                array(
                    'taxonomy' => \Sophos\Translation\PostType::TAXONOMY,
                    'field'    => 'slug',
                    'terms'    => array(sprintf('iso-639-1-%s', $query->get('language_iso_code')))
                )
            ));
        }
    }
    
    return $query;
}

/**
 * Exclude translations from search results
 * Enter description here ...
 * @param unknown_type $wp_query
 */
function cet_modify_search (WP_Query $wp_query)
{
    if ( ! is_admin() && $wp_query->is_main_query() && $wp_query->is_search() )
    {
        // FIXME this should be smarter - we need per-language searches
		$wp_query->set( 'post_type', array( 'post', 'page' ) ); // exclude translations from search for now
    }

    return $wp_query;
}





function cet_create_settings () {

	register_setting(
		'cet_options',					// Group ID
		C_OPT_API_KEY,					// Option name
		'cet_validate_google_api_key'	// Function to validate data
	);

	register_setting(
            'cet_options',              // Group ID
            C_OPT_LANGUAGES_v2,         // Option the data will be saved to. Submitted data structure must include this as a key
            'cet_validate_languages'    // Function to validate data
	);

	add_settings_section(
		'cet_google_section',			// Section ID
		null,							// Title
		'cet_output_google_section',	// Function to output section
		'cet_page'						// Page ID
	);

	add_settings_field(
		C_OPT_API_KEY,					// ID
		__('Google API Key'),			// Title
		'cet_output_google_api_key',	// Callback
		'cet_page',						// Page ID
		'cet_google_section'			// Section ID
	);

	add_settings_field(
		C_OPT_LANGUAGES,				// ID
		__('Default languages'),		// Title
		'cet_output_languages',			// Callback
		'cet_page',						// Page ID
		'cet_google_section'			// Section ID
	);
}

/**
 * Validate a user-submitted Google API key
 * @param string $key
 */
function cet_validate_google_api_key ( $key ) {
	$key = trim( $key );

    if ( \Google::key_looks_ok( $key ) ) {
        return $key;
	} else {
        add_settings_error(
            C_OPT_API_KEY, // Setting the error applies to
		    C_OPT_API_KEY, // ID to use in HTML id
			__('You didn\'t enter a valid Google API key'),
			'error'
		);

		return null;
	}
}

/**
 * Return a data structure showing default languages
 * 
 * The structure that's output depends on what its fed
 * v1 array('ar' => true, ...)
 * v2 array('ar' => 'google' ...)
 * 
 * @param array $input
 * @return array
 */
function cet_validate_languages ( $input ) {
    $selected = array();
    
    if ( is_array($input) and count($input) ) {
        foreach ( cet_get_available_languages() as $lang ) {
            if ( isset( $lang['language'] ) ) {
                $iso = $lang['language'];
                if ( array_key_exists($iso, $input) ) {
                    $selected[$iso] = empty($input[$iso]) ? false : $input[$iso];
                }
            }
        }
    }

    return $selected;
}

function cet_output_google_section () {

}

/**
 * Render an input for capturing a Google Translate API key
 */
function cet_output_google_api_key () {
	$id      = C_OPT_API_KEY;
	$key     = Sophos\Translation\Utils::getGoogleAPIKey();
	$output  = "<input id=\"$id\" name=\"$id\" size=\"50\" maxlength=\"50\" type=\"text\" value=\"$key\" />\n";
	$output .= '<span class="description">' . __('To use this plugin you\'ll need an <a href="https://code.google.com/apis/language/translate/v2/using_rest.html#auth">API KEY</a>') . '</span>';

	echo $output;
}

/**
 * Render inputs for capturing default translation languages on settings pages
 */
function cet_output_languages ($post = null) {
    if ( $key = Sophos\Translation\Utils::getGoogleAPIKey() ) :

        $translated = array();
        $local      = array();
        $defaults   =  get_option(C_OPT_LANGUAGES_v2) // v2 data structure FIXME necessary?
                    ?: get_option(C_OPT_LANGUAGES);   // v1 data structure
        //$baseline   = array_fill_keys(array_keys($defaults), false);

        // var_dump($baseline);
        
        // Check for post-specific defaults
        if ( $post instanceof \WP_Post ) {
            /* ''           - not set
             * true         - set but no translations required 
             * array( ... ) - set and some translations are required
             */
            $local = get_post_meta($post->ID, '_cet_translate_to', true) ?: array();

            foreach (get_post_meta($post->ID, '_cet_has_translation_with_id', false ) as $id) {
                if (get_post_meta( $id, 'cet_is_translated', true )) {
                    $code              = get_post_meta($id, 'cet_language_iso_code', true);
                    $translated[$code] = sprintf('%s/post.php?post=%d&action=edit', admin_url(), $id);
                }
            }
        }

        // Upgrade old data structures on-the-fly
        $defaults = array_map(function ($value) {
            return ($value === true) ? 'google' : $value;
        }, array_merge($defaults, $local));
        
        ?><fieldset>
            <legend class="screen-reader-text"><?php echo esc_html(__('Languages')); ?></legend>
            <p><?php echo esc_html(__('Select the translation methods you want to use for each language.')); ?></p>
        </fieldset>
        <table class="translation languages">
            <tr>
                <th>&nbsp;</th><th>None</th><th>Google Translate</th><th>Manual Translation</th>
            </tr>

            <?php foreach ( cet_get_available_languages() as $lang ) : 
                $iso      = $lang['language'];
                $disabled = array_key_exists($iso, $translated) ? 'disabled="disabled"' : '';
                $name     = !empty($disabled) ? sprintf('<a href="%s">%s</a>', $translated[$iso], $lang['name']) : $lang['name'];
                $input    = sprintf('%s[%s]', C_OPT_LANGUAGES_v2, $iso);
                $checked  = ''; // default
              ?><tr>
                    <td>
                        <label><?php echo $name; ?></label>
                        <?php if ($disabled) : ?>
                            <input value="<?php echo esc_attr($defaults[$iso]); ?>" name="<?php echo esc_attr($input); ?>" type="hidden">
                        <?php endif; ?>
                    </td>
                    <?php foreach (array(false,'google','manual') as $method) : 
                        
                        $checked = ($defaults[$iso] === $method) ? 'checked="checked"' : '';
                        
                        ?><td><input name="<?php echo esc_attr($input); ?>" type="radio" value="<?php echo esc_attr($method); ?>" <?php echo $checked; ?> <?php echo $disabled; ?> /></td>
                    
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if ($post instanceof \WP_Post) wp_nonce_field( plugin_basename( __FILE__ ), 'cet_nonce' ); ?>
    <?php else: ?>
        
        <p class="error"><?php echo esc_html(__('You will need to enter a valid Google API key')); ?></p>;
    
    <?php endif;
}

/**
 * Create the Translation Settings page
 */
function cet_create_menu () {
	add_options_page(
	__('Translation Settings'),
	__('Translation'),
		'manage_options',
		'translation_settings',
		'cet_render_settings'
		);
}

/**
 * Output the HTML for the Translation Settings screen
 */
function cet_render_settings () {
	if ( !current_user_can('manage_options') )  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	ob_start();
	?>
<div class="wrap">
	<div id="icon-options-general" class="icon32">
		<br>
	</div>
	<h2>
	<?php echo __('Translation Settings') ?>
	</h2>
	<form method="post" action="options.php">
	<?php settings_fields('cet_options'); ?>
	<?php do_settings_sections('cet_page'); ?>
		<p class="submit">
			<input name="submit" id="submit" class="button-primary"
				value="<?php echo __('Save Changes') ?>" type="submit">
		</p>
	</form>
</div>
	<?php
	ob_end_flush();
}

/**
 * Add the translation meta box to the Post editing screen
 */
function cet_create_translation_box () {

	$available_languages = cet_get_available_languages();

	if ( is_array( $available_languages) and count( $available_languages ) )
	{
		add_meta_box(
		'cet_translations',		// ID
		__('Translations'),		// Title
		'cet_output_languages', // 'cet_meta_box',			// Function to render box
		'post',					// Page type to add the box to
		'normal',				// Context
		'low'					// Priority within the interface
		);
	}
}

/**
 * Save the languages selected on the edit post screen as post meta data. Note that this
 * is called whenever a post or page is created or updated
 */
function cet_save_language_choices ( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    if ( get_post_type($post_id) === 'post' || wp_is_post_revision($post_id) ) {
        if ( current_user_can('edit_post', $post_id) ) {
            if ( !isset( $_POST['cet_nonce'] ) or ( isset( $_POST['cet_nonce'] ) && wp_verify_nonce( $_POST['cet_nonce'], plugin_basename( __FILE__ )) ) ) {
                if ( isset( $_POST['translation_languages'] ) ) {

                    $keys  = array_filter(array_keys($_POST['translation_languages']), array('\Sophos\Translation\Utils', 'validateLanguageISOCode'));
                    $codes = array_fill_keys($keys , false);
                    $sane  = array_intersect_key($_POST['translation_languages'], $codes);
                    $sane  = array_map(function ($a) {
                        if ($a === '') return false;
                        return $a;
                    }, $sane);
                    
                    add_post_meta( $post_id, '_cet_translate_to', $sane, true ) 
                        or update_post_meta( $post_id, '_cet_translate_to', $sane );                    
                }
            } else {
                wp_die( __("Sorry, I've had to cancel your update because it might have been unsafe. I couldn't verify that the request came from " . get_bloginfo('name') . ". Please hit the back button and try again." ) );
            }
        } else {
            wp_die( __("Sorry, I've had to cancel your update because you don't have the correct user rights to edit that page." ) );
        }
    }
}

/**
 * For each of a post's selected languages create draft translations in
 * English and then schedule translation into the target language.
 * @param int $post_id
 */
function cet_process_translation_requests($post_id) {

    if (get_post_type($post_id) === 'post') {
        if (current_user_can('edit_posts')) {

            $langs = get_post_meta($post_id, '_cet_translate_to', true);

            if (is_array($langs) && count($langs) > 0) {

                // FIXME poor assumption - it exists therefore it is scheduled
                $ids = get_post_meta($post_id, '_cet_has_translation_with_id', false);

                // Exclude anything we've already translated
                if (is_array($ids) && count($ids) > 0) {
                    foreach ($ids as $id) {
                        $iso = get_post_meta($id, 'cet_language_iso_code', true);
                        if ($iso && \Sophos\Translation\Utils::validateLanguageISOCode($iso) && array_key_exists($iso, $langs)) {
                            unset($langs[$iso]);
                        }
                    }
                }

                foreach ( array_keys($langs) as $iso ) {
                    if ( in_array($langs[$iso], array('google','manual')) ) {
                        $draft = cet_create_translation_draft($post_id, $iso, $langs[$iso]);
                        if (is_int($draft)) {
                           // Schedule Google translations
                            if ( $langs[$iso] === 'google' ) {
                                $time = cet_schedule_translation($draft, $iso);
                                if ( is_wp_error($time) ) {
                                    wp_die($time->get_error_message());
                                }
                            }
                        } else if ( is_wp_error($draft) ) {
                            wp_die($draft->get_error_message());
                        }   
                    }
                }
            }
        }
    } else {
        wp_die("I'm sorry, an error occured and I had to cancel your translation request. The system tried to translate a post that does not exist");
    }
}

/**
 * Create a draft translation in English
 * @param int $post_id
 * @param string $iso
 * @return ID of draft translation of WP_Error object
 */
function cet_create_translation_draft($post_id, $iso, $translator) {
    if (get_post_type($post_id) === 'post') {
        if (\Sophos\Translation\Utils::validateLanguageISOCode($iso)) {
            if ($parent = get_post($post_id)) {

                $child = clone $parent;
                $child->ID             = null; // ensures we don't save over the top of the parent
                $child->post_type      = \Sophos\Translation\PostType::POST_TYPE;
                $child->post_name      = $parent->post_name . '-' . $iso; // FIXME fragile. Won't withstand a rename
                $child->post_status    = 'draft';
                $child->post_content   = cet_markup_short_codes($child->post_content);
                
                // David Binovec: wp_get_post_categories and wp_get_post_tags are actually 
                // uncached functions and it's better to replace them by get_the_terms
                $child->post_category  = wp_list_pluck(get_the_terms($post_id, 'category'), 'term_id');
                $child->tags_input     = wp_list_pluck(get_the_terms($post_id, 'post_tag'), 'term_id');
                $child->comment_status = 'open';
                
                $result = wp_insert_post($child, true);

                if (is_int($result)) {
                    $parent_id = $parent->ID;
                    $translation_id = $result;

                    // this key is not unique, posts can have many translations
                    add_post_meta($parent_id, '_cet_has_translation_with_id', $translation_id, false);

                    // these keys are unique, a translation can only have one of each
                    if (!add_post_meta($translation_id, 'cet_is_translated', 0, true)) {
                        return new WP_Error(CET_ERROR_UNKNOWN, "Could not add post meta <var>cet_is_translated</var> to post $translation_id with value 0");
                    }

                    // Featured image
                    if ($thumbnail = get_post_thumbnail_id($parent->ID)) {
                        set_post_thumbnail($translation_id, $thumbnail);
                    }

                    // Add language
                    if ( ($wp_error = wp_set_object_terms($translation_id, sprintf('iso-639-1-%s', $iso), Sophos\Translation\PostType::TAXONOMY)) instanceof \WP_Error) {
                        return $wp_error;
                    }

                    // Add language meta
                    if (!add_post_meta($translation_id, 'cet_language_iso_code', $iso, true)) {
                        return new WP_Error(CET_ERROR_UNKNOWN, "Could not add post meta <var>cet_language_iso_code</var> to post $translation_id with value $iso");
                    }

                    if (!add_post_meta($translation_id, 'cet_has_master_with_id', $parent_id, true)) {
                        return new WP_Error(CET_ERROR_UNKNOWN, "Could not add post meta <var>cet_has_master_with_id</var> to post $translation_id with value $parent_id");
                    }

                    if (!add_post_meta($translation_id, 'cet_translator', $translator, true)) {
                        return new WP_Error(CET_ERROR_UNKNOWN, "Could not add post meta <var>cet_translator</var> to post $translation_id with value $translator");
                    }

                    return $translation_id;
                } elseif (is_wp_error($result)) {
                    return $result;
                } else {
                    return new WP_Error(CET_ERROR_UNKNOWN, 'wp_insert_post() returned a result that is not an integer or WP_Error object');
                }
            } else {
                return new WP_Error(CET_ERROR_GET_POST, "get_post failed on ID $post_id");
            }
        } else {
            return new WP_Error(CET_ERROR_ISO_VALIDATION, "$iso is not a valid 2-letter ISO language code");
        }
    } else {
        return new WP_Error(CET_ERROR_POST_TYPE, "get_post_type() did not return Post");
    }
}

/**
 * Wrap short codes in a span tag that will prevent them from being translated
 * @param string $content
 * @return string
 */
function cet_markup_short_codes ( $content ) {

	$shortcode = get_shortcode_regex();

	return preg_replace_callback(
		"/$shortcode/s",
		'_cet_markup_short_codes_callback',
		$content
	);
}

function _cet_markup_short_codes_callback ( $matches ) {

	if ( is_array( $matches ) ) {
		if ( $shortcode = array_shift( $matches ) ) {
			return "<span class=\"notranslate\">$shortcode</span>";
		}
	}
}


/**
 * Schedule an English draft for translation
 * @param int $post_id
 * @param string $iso
 * @return time of next translation or WP_Error object
 */
function cet_schedule_translation ( $post_id, $iso ) {

	if ( get_post_type( $post_id ) === C_POST_TYPE ) {
		if ( \Sophos\Translation\Utils::validateLanguageISOCode( $iso ) ) {
			if ( current_user_can( 'edit_posts' ) ) {
				if ( !get_post_meta( $post_id, 'cet_is_translated', true ) ) {

                                        // FIXME this is supposed to schedule translations
                                        // consecutively but it's not. As an interim fix I'm
                                        // throwing in some randomness to prevent translations
                                        // landing on top of each other.
					$hook = 'cet_run_translation';
					$next = wp_next_scheduled( $hook );
                                        $rand = rand( 0, C_SECONDS_BETWEEN_TRANSLATIONS * 2);
					$time = ( ( $next ) ? $next : time() ) + $rand + C_SECONDS_BETWEEN_TRANSLATIONS;
					$args = array( $post_id );

					// wp_clear_scheduled_hook(         $hook, array( $post_id ) );
                                        if ( false === wp_schedule_single_event($time, $hook, $args) ) {
                                            return new WP_Error( CET_NOT_SCHEDULED, __("Sorry, The translation was cancelled by another plugin." ) );
                                        }

					// check if it actually got scheduled
					if ( $scheduled = wp_next_scheduled( $hook, $args ) ) {
                                            add_post_meta( $post_id, '_cet_schedule', $scheduled, true ) or update_post_meta( $post_id, '_cet_schedule', $scheduled );
					}
					else
					{
					    return new WP_Error( CET_ERROR_NO_RIGHTS, __("Sorry, I've had to cancel your update because you don't have the correct user rights to schedule a translation." ) );
					}

					return $time;
				}
			} else {
				return new WP_Error( CET_ERROR_NO_RIGHTS, __("Sorry, I've had to cancel your update because you don't have the correct user rights to schedule a translation." ) );
			}
		} else {
			return new WP_Error( CET_ERROR_ISO_VALIDATION, "$iso is not a valid 2-letter ISO language code" );
		}
	} else {
		return new WP_Error( CET_ERROR_POST_TYPE, "get_post_type() did not return " . C_POST_TYPE );
	}
}

/**
 * Clean up any artefacts a translation has left on the system prior to being trashed
 * @param int $post_id
 */
function cet_cleanup_trashed_translation ( $post_id )
{
    /* If this is scheduled for translation then unschedule it. If we don't do this
     * then Wordpress will try to run this each time cron is executed, it will fail
     * and all other cron jobs scheduled after it will fail too
     */
    wp_clear_scheduled_hook( 'cet_run_translation', array( $post_id ) );

    // FIXME should we reinstate the schedule if the post is untrashed?
    // FIXME Ukraine shows GB (UK) flag
}

/**
 * Translate the title, excerpt and content of a post
 * @param int $id
 */
function cet_trigger_translation ( $id ) { // note number of arguments is controlled in add_action
                                        
	try {
		if ( get_post_type( $id ) === C_POST_TYPE ) {
			$translation = get_post( $id );
			$iso         = get_post_meta( $id, 'cet_language_iso_code', true );

			if ( !is_null( $translation ) and ( $translation instanceof WP_Post ) ) {
				if ( \Sophos\Translation\Utils::validateLanguageISOCode( $iso ) ) {
					$api = new \Google();

					if ( !empty( $translation->post_title ) ) {
						$translation->post_title = $api->translate( array(
							'source' => C_BASE_LANG,
							'target' => $iso,
							'text'   => $translation->post_title,
							'format' => 'text'
							));
					}
					if ( !empty( $translation->post_excerpt ) ) {
						$translation->post_excerpt = $api->translate( array(
							'source' => C_BASE_LANG,
							'target' => $iso,
							'text'   => wpautop( $translation->post_excerpt ), // FIXME some people have conversion of brs turned off (2nd arg)
							'format' => 'text'
							));
					}
					if ( !empty( $translation->post_content ) ) {
						$translation->post_content = $api->translate( array(
							'source' => C_BASE_LANG,
							'target' => $iso,
							'text'   => wpautop( $translation->post_content ),
							'format' => 'html'
							));
					}

					$translation->post_status = 'publish';

					if ( wp_update_post( $translation ) ) {
						update_post_meta( $id, 'cet_is_translated', 1 );
						add_post_meta(    $id, 'cet_translator', 'Google', true );

					} else {
						throw new Exception( "Call to wp_update_post failed" );
					}
				} else {
					throw new Exception( "Didn't recognise $iso as a valid ISO language code" );
				}
			} else {
				throw new Exception( "get_post() failed for post $id" );
			}
		} else {
			throw new Exception( "Post $id is not a " . C_POST_TYPE );
		}
	} catch (Exception $e) {
		/* At this point we're probably running under cron so there's nothing
		 * for the user to see. We need to add something to the logs and then
		 * leave a message for the user.
		 */
        
		add_post_meta( $id, '_cet_error', $e->getMessage(), false );
		trigger_error( $e->getMessage(), E_USER_ERROR );
	}
}

/* Content is marked-up so that Google and other search engines can identify
 * the text as machine translated.
 * https://code.google.com/apis/language/translate/v2/attribution.html#html_specification
 */
// TODO Unfortunately these mess with the layout too much. Alternatives:
// - Add a meta tag (but the whole page might not be a machine translation)
// - Give these a display: inline-block style (but they could still mess with layout)
// - Tell people to modify themes (which they won't or can't...)
// - other...
//add_filter( 'the_title',   'cet_add_mtfrom_to_inline', 100, 2 );
//add_filter( 'the_excerpt', 'cet_add_mtfrom_to_block',  100, 2 );
//add_filter( 'the_content', 'cet_add_mtfrom_to_block',  100, 2 );

/**
 * Create the appropriate value for an HTML lang attribute that indicates content is machine translated
 * @param int $id
 * @return int $lang or WP_Error
 */
function cet_get_mtfrom ( $id ) {

	$lang = C_BASE_LANG; // if all else fails

	if ( is_int( $id )  ) {
		if ( get_post_type( $id ) === C_POST_TYPE ) { // TODO add from language as post meta
			if ( $to = get_post_meta( $id, 'cet_language_iso_code', true ) ) {
				$lang = sprintf( '%s-x-mtfrom-%s', $to, C_BASE_LANG );
			} else {
				return new WP_Error( CET_ERROR_POSTMETA, __("Sorry, I couldn't retrieve the cet_language_iso_code for Post $id") );
			}
		}
	}

	return $lang;
}

/**
 * Wrap an inline HTML element with a div that identifies itself as machine translated
 * @param string $content
 * @return string
 */
function cet_add_mtfrom_to_inline ( $content, $post_id ) {

	$mtfrom = cet_get_mtfrom( $post_id );
	if ( !is_wp_error( $mtfrom ) )
	{
		return is_admin() ? $content : '<span lang="' . $mtfrom . "\">$content</span>";
	}
	else
	{
		return $content;
	}
}

/**
 * Wrap a block-level HTML element with a div that identifies itself as machine translated
 * @param string $content
 * @return string
 */
function cet_add_mtfrom_to_block ( $content ) {

	return is_admin() ? $content : '<div lang="' . cet_get_mtfrom( get_the_ID() ) . "\">$content</span>";
}

/**
 * Display notifications
 */
function cet_translation_status () {
	if ( is_admin() ) {
		if ( isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['post']) && get_post_type( $_GET['post'] ) === C_POST_TYPE ) {
			$post_id  = $_GET['post'];
			$errors   = get_post_meta( $post_id, '_cet_error', false );

			if ( is_array( $errors ) && count( $errors ) > 0 ) {
				foreach ( $errors as $error ) {
					$message = sprintf( __('We\'re sorry. An error occured and we were unable to translate this post. The error was: <br/> <strong>%s</strong>'), $error);

					echo "<div class=\"error\">
       					<p>$message</p>
    				</div>";
				}
            } else {
				$translation_schedule = get_post_meta( $post_id, '_cet_schedule', true );

				if ( $translation_schedule >= time() ) {
					$wait    = $translation_schedule - time();
					$date    = date( DateTime::RFC822, $time );
					$message = sprintf( __('This post will be sent for translation in %d seconds'), $wait);
					echo "<div class=\"updated\">
       					<p>$message</p>
    				</div>";
                                } elseif ( !get_post_meta( $post_id, 'cet_is_translated', true ) ) {
                    $message = __('This post is being translated');
					echo "<div class=\"updated\">
       					<p>$message</p>
    				</div>";
                }
			}
		}
	}
}


class PostMetaException extends Exception { };

?>
