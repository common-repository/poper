<?php 

function poper_review_notice() {
    ?>
    <div class="notice notice-success is-dismissible poper-admin-notice">
    <h2><?php esc_html_e( 'Enjoying Poper? Help us grow by leaving a review!', 'poper' ); ?></h2>
    <p><?php esc_html_e( 'We\'re dedicated to providing you with the best possible experience. Your review helps us improve and helps other users make informed decisions.', 'poper' ); ?></p>
    <p><a class="poper-action-btn" href="https://wordpress.org/support/plugin/poper/reviews/"><?php esc_html_e( 'Review Us Now', 'poper' ); ?></a></p>
    <p><a class="poper-dismiss-perma" href="#"><?php esc_html_e( 'Already done? Click here', 'poper' ); ?></a></p>
    </div>
    <?php
}

if( 
    empty( get_option( 'poper-dismiss-notice' ) ) && 
    !get_transient( 'poper_dismiss_notice_temporary' )
) {
    add_action( 'admin_notices', 'poper_review_notice' );
}

add_action( 'wp_ajax_poper_dismiss_notice', 'poper_dismiss_notice' );

function poper_dismiss_notice() {
	update_option( 'poper-dismiss-notice', 1 );
}

add_action( 'wp_ajax_poper_dismiss_notice_temporary', 'poper_dismiss_notice_temporary' );

function poper_dismiss_notice_temporary() {
    // Set a transient to dismiss the notice for 14 days
    set_transient( 'poper_dismiss_notice_temporary', 1, 14 * 24 * 60 * 60 );
}
