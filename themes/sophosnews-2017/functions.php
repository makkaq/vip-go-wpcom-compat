<?php
/**
 * Sophos functions and definitions
 *
 * @package Sophos
 */

wpcom_vip_load_plugin( 'sophos' );
wpcom_vip_load_plugin( 'ad' );
wpcom_vip_load_plugin( 'comment/moderation.php' );
wpcom_vip_load_plugin( 'campaign' );

require get_template_directory() . '/inc/init.php';
require get_template_directory() . '/inc/roles.php';
require get_template_directory() . '/inc/conditionals.php';
require get_template_directory() . '/inc/widgets.php';
require get_template_directory() . '/inc/theme.php';
require get_template_directory() . '/inc/fonts.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/extras.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/class-sophos-comment-walker.php';
require get_template_directory() . '/inc/custom-avatar-defaults.php';
require get_template_directory() . '/inc/field-manager.php';
require get_template_directory() . '/inc/shortcodes.php';

wpcom_vip_load_plugin( 'fieldmanager', NULL, '1.1' );
wpcom_vip_load_plugin( 'msm-sitemap' );
