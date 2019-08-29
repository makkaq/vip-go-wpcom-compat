<?php

namespace Sophos\Translation\CLI;

/**
 * Implements replace-meta-with-taxonomy command.
 */
class Translations extends \WPCOM_VIP_CLI_Command {

    const LIVE_RUN       = 'run';
    const DRY_RUN        = 'dry-run';
    const POSTS_PER_PAGE = 100;
    
    private function _dryRun () {
        $page = 1;

        do {
            $posts = get_posts( array(
                'post_type'      => 'translation',
                'posts_per_page' => self::POSTS_PER_PAGE,
                'paged'          => $page,
            ));

            foreach ($posts as $translation) {
                
                $iso = get_post_meta($translation->ID, 'cet_language_iso_code', true);
                
                if ( !empty($iso) ) {
                    
                    $tax = sprintf('iso-639-1-%s', $iso);
                    
                    \WP_CLI::line(sprintf('Post ID: %d, Existing ISO: %s, Taxonomy Slug: %s', $translation->ID, $iso, $tax));

                } else {
                     \WP_CLI::error(sprintf('Unable to retrieve ISO code for translation %d', $translation->ID));
                }
            }
            $page++;
                
            // Free up memory
            $this->stop_the_insanity();
        } while ( count( $posts ) );
    }
    
    private function _liveRun () {
        $page = 1;

        do {
            $posts = get_posts( array(
                'post_type'      => 'translation',
                'posts_per_page' => self::POSTS_PER_PAGE,
                'paged'          => $page,
            ));

            foreach ($posts as $translation) {
                
                $iso = get_post_meta($translation->ID, 'cet_language_iso_code', true);
                
                if ( !empty($iso) ) {
                    
                    $result = wp_set_object_terms($translation->ID, sprintf('iso-639-1-%s', $iso), \Sophos\Translation\PostType::TAXONOMY);
                    
                    if( is_wp_error($result) ) {
                        trigger_error($return->get_error_message(), E_USER_WARNING);
                    } else {
                        \WP_CLI::success(sprintf('Added language taxonomy to translation %d', $translation->ID));
                    }
                } else {
                     \WP_CLI::error(sprintf('Unable to retrieve ISO code for translation %d', $translation->ID));
                }
            }
            $page++;
                
            // Free up memory
            $this->stop_the_insanity();
        } while ( count( $posts ) );
    }
    
    /**
     * Switch language storage from post meta to taxonomy.
     * 
     * ## EXAMPLES
     * 
     *     wp translations replace-meta-with-taxonomy
     *
     * @synopsis [<run|dry-run>]
     * @subcommand replace-meta-with-taxonomy
     */
    function replacePostMetaToTaxonomy ($args = array()) {

        switch (count($args)) {
            case 0:
                // NO arguments, do a dry run
                $this->_dryRun();
                break;
            case 1:
                
                switch ($args[0]) {
                    // Explicit live run
                    case self::LIVE_RUN:
                        $this->_liveRun();
                        break;
                    // Explicit dry run
                    case self::DRY_RUN:
                        $this->_dryRun();
                        break;
                    default:
                        \WP_CLI::error(sprintf('Unknown argument %s', $args[0]));
                        break;
                }
                
                break;
            default:
               \WP_CLI::error('Too many arguments supplied');
        }
    }
}

\WP_CLI::add_command('translations', 'Sophos\Translation\CLI\Translations');