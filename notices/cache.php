<?php 

// Poper Clear Cache Notice
function poper_dismiss_cache_notice() {
	update_option( 'poper-cache-notice', 0 );
}

add_action( 'wp_ajax_poper_dismiss_cache_notice', 'poper_dismiss_cache_notice' );

function poper_cache_notice() {
    ?>
    <div class="notice notice-success is-dismissible poper-admin-c-notice">
    <h2><?php esc_html_e( 'Clear your website\'s cache', 'poper' ); ?></h2>
    <p><?php esc_html_e( 'Note: Please clear your website\'s cache and configure your caching plugin to exclude Poper\'s files.', 'poper' ); ?></p>
    <p><a class="poper-action-btn" target="_blank" href="https://help.poper.ai/portal/en/kb/articles/clearing-cache-after-installing-poper-for-wordpress"><?php esc_html_e( 'Guide', 'poper' ); ?></a></p>
    <p><a class="poper-dismiss-perma" href="#"><?php esc_html_e( 'Already done? Click here', 'poper' ); ?></a></p>
    </div>
    <?php
}

if( 
    get_option( 'poper-cache-notice' )
) {
    add_action( 'admin_notices', 'poper_cache_notice' );
}
