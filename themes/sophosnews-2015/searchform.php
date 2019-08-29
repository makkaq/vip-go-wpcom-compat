<?php

/**
 * Note that if this is included on AMP pages it requires the amp-form library to be loaded too
 */

?>
<div class="site-search-block">
	<form target="_top" role="search" class="search-form" method="get" action="<?php echo esc_url( home_url( '/', 'https' ) ); ?>">
        <fieldset>
            <div class="field">
				<input type="search" value="<?php if ( is_search() ) { esc_attr_e( get_search_query() ); } ?>" name="s" class="search-field" placeholder="<?php esc_attr_e( 'Search naked security...', 'forward' ); ?>">
            </div>
            <div class="submit">
				<button type="submit" class="search-submit button"><?php esc_html_e( 'Go', 'forward' ); ?></button>
            </div>
        </fieldset>
    </form>
</div>
