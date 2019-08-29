<?php
/**
 * AMP template for displaying co-author bylines
 *
 * The AMP template for author bylines is supplied by the AMP plugin and it's
 * overridden by another template in co-authors plus, which is actually a little
 * broken (https://github.com/Automattic/Co-Authors-Plus/issues/360). This can
 * be used in place of the co-authors template, if it's removed first. If the
 * co-authors plus plugin isn't enabled, this tempalte isn't needed.
 *
 * @package Sophos
 */

foreach ( get_coauthors() as $post_author ) : ?>
    <div class="amp-wp-meta amp-wp-byline">
        <?php if ( function_exists( 'get_avatar_url' ) ) : ?>
            <amp-img src="<?php echo esc_url( get_avatar_url( $post_author->user_email, array( 'size' => 24 ) ) ); ?>" alt="<?php echo esc_attr( $post_author->display_name ); ?>" width="24" height="24" layout="fixed"></amp-img>
        <?php endif; ?>
        <span class="amp-wp-author author vcard"><?php echo esc_html( $post_author->display_name ); ?></span>
    </div>
<?php endforeach; ?>
