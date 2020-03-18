<?php sophos_panel_open( 'about-panel' ); ?>
	<div class="description"><span><?php
        echo wp_kses(
            __( 'Have you listened to our podcast? <a href="/podcast/">Listen now</a>', 'nakedsecurity' ),
            [
                'a' => [
                    'href' => []
                ]
            ]
        );
        ?></span></div>
<?php sophos_panel_close();
