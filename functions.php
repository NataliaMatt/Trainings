<?php
function euf_enqueue_styles() {
    $theme = wp_get_theme();

    // Parent style
    wp_enqueue_style(
        'divi-style',
        get_template_directory_uri() . '/style.css',
        array(),
        $theme->get('Version')
    );

    // Child main style
    wp_enqueue_style(
        'child-style',
        get_stylesheet_uri(),
        array('divi-style'),
        $theme->get('Version')
    );

    // Fonts CSS
    wp_enqueue_style(
        'child-fonts',
        get_stylesheet_directory_uri() . '/css/fonts.css',
        array('child-style'),
        $theme->get('Version')
    );
}

add_action('wp_enqueue_scripts', 'euf_enqueue_styles');

// CUSTOM FUNCTIONS BELOW

// Disable Google Fonts
add_filter('et_builder_google_fonts_enabled', '__return_false');

// Hide WordPress version
function remove_version() {
    return '';
}
add_filter('the_generator', 'remove_version');

// Show WordPress REST API only to Administrators.
// Beware: it can break some plugins that rely on the REST API.
add_filter('rest_authentication_errors', function($result) {
    if (!empty($result)) {
        return $result;
    }
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
    }
    if (!current_user_can('administrator')) {
        return new WP_Error('rest_not_admin', 'You are not an administrator.', array('status' => 401));
    }
    return $result;
});

// Hide REST API links from site header
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('template_redirect', 'rest_output_link_header', 11);

// Increase upload max size
ini_set('upload_max_filesize', '64M');
ini_set('post_max_size', '64M');
ini_set('max_execution_time', '300');