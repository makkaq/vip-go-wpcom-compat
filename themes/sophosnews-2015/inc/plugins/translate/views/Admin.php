<?php

namespace Sophos\Translation\Views;

class Admin {

    /**
     * Configure the Translation admin screen
     */
    public static function init () {

        add_action('manage_posts_custom_column', array('Sophos\Translation\Views\Admin','render'), 10, 2);

       /**
        * Setup columns
        *
        * @param array $cols
        * @return array
        */
        add_filter('manage_edit-translation_columns', function ($cols) {
            return array(
		'cb'           => '<input type="checkbox" />',
		'iso_code'     => __( 'Flag' ),
		'language'     => __( 'Language' ),
                'translator'   => __( 'Translator' ),
                'title'        => __( 'Title'),
		'master-title' => __( 'Original' ),
		'date'         => __( 'Date' )
            );
        }, 10, 1);

        /**
         * Make columns sortable
         *
         * @return array
         */
        add_filter('manage_edit-translation_sortable_columns', function () {
            return array(
                'title'        => 'title',
                'language'     => 'iso_code',
                'iso_code'     => 'iso_code',
                'master-title' => 'master-title',
                'date'         => 'date'
            );
        });

        /**
         * Make the language flags column sort correctly
         *
         * @param array $vars
         * @return array
         */
        add_filter('request', function ($vars) {
            if ( (array_key_exists('post_type', $vars ) && $vars['post_type'] === 'translation') and (array_key_exists('orderby', $vars) && $vars['orderby'] === 'iso_code') ) {
                // sort on the value of "meta_value" for each post's post_meta "cet_language"
                $vars = array_merge( $vars, array(
                    'meta_key' => 'cet_language_iso_code',
                    'orderby'  => 'meta_value'
                ));
            }

            return $vars;
        });
    }

    /**
     *
     * @param string $column
     * @param int $post_id
     * @param string $translator
     * @throws PostMetaException
     * @throws Exception
     */
    public static function render($column, $post_id) {
        try {
            if ($child = get_post($post_id)) {
                if ($iso = get_post_meta($child->ID, 'cet_language_iso_code', true)) {
                    if (\Sophos\Translation\Utils::validateLanguageISOCode($iso)) {
                        $languages = \cet_get_available_languages();
                        $language = array_filter($languages, function ( $arr ) use ( $iso ) {
                            if (array_key_exists('language', $arr) && $arr['language'] == $iso) {
                                return true;
                            }
                        });

                        $language = array_shift($language);
                    }
                } else {
                    throw new \PostMetaException("Post $child->ID is missing cet_language_iso_code");
                }

                if ($master_id = get_post_meta($child->ID, 'cet_has_master_with_id', true)) {

                    switch ($column) {
                        case 'iso_code':
                            if ($iso = get_post_meta($child->ID, 'cet_language_iso_code', true)) : ?>
                                <img src="<?php echo esc_url(get_stylesheet_directory_uri() . "/inc/plugins/translate/flags/$iso.png"); ?>">
                            <?php else:
                                throw new \PostMetaException("Post $child->ID is missing cet_language_iso_code");
                            endif;
                            break;

                        case 'language':
                            echo $language['name'];
                            break;

                        case 'translator':
                            $translator = get_post_meta($child->ID, 'cet_translator', true);
                            echo $translator ?: 'google';
                            break;

                        case 'master-title':
                            if ($master = get_post($master_id)) {
                                $title = $master->post_title;

                                // FIXME we check for edit_published_posts here because we publish automatically. At the moment the post would only
                                // be sent for translation and appear in this list if it has been published. We may wish to change that in future which
                                // makes this a bit of a weak solution.
                                if (current_user_can('edit_published_posts')) {
                                    $href = sprintf('/wp-admin/post.php?post=%d&action=edit', $master->ID);
                                    echo "<a href=\"$href\">$title</a>";
                                } else {
                                    echo $title;
                                }
                            } else {
                                // The master might have been deleted. That's OK.
                            }
                            break;
                    }
                } else {
                    throw new \PostMetaException("Post $post_id is missing cet_has_master_with_id");
                }
            } else {
                throw new \Exception("get_post() failed for post $post_id");
            }
        } catch (\PostMetaException $e) {
            /* At this point we're mid-way through rendering the columns so
             * it's too late to issue admin notices or to prevent the row being
             * rendered. wp_die() only drops a message in-line and we don't want
             * to stop the whole page from displaying anyway so just a warning...
             */
        }
    }

}
