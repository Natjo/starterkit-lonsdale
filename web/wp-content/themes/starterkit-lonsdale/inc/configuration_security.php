<?php

//SECURITY : disable feed
add_action('do_feed', 'itsme_disable_feed', 1);
add_action('do_feed_rdf', 'itsme_disable_feed', 1);
add_action('do_feed_rss', 'itsme_disable_feed', 1);
add_action('do_feed_rss2', 'itsme_disable_feed', 1);
add_action('do_feed_atom', 'itsme_disable_feed', 1);
add_action('do_feed_rss2_comments', 'itsme_disable_feed', 1);
add_action('do_feed_atom_comments', 'itsme_disable_feed', 1);

//SECURITY : remove wp version
remove_action('wp_head', 'wp_generator');

function itsme_disable_feed() {
    wp_die( __( 'Non disponible, <a href="'. esc_url( home_url( '/' ) ) .'">accueil</a>' ) );
}

//SECURITY :  remove version from rss
add_filter('the_generator', '__return_empty_string');

//SECURITY :  remove version from scripts and styles
function remove_version_scripts_styles($src) {
    if (strpos($src, 'ver=') && !strpos($src, '/app.css') && !strpos($src, '/app.js')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'remove_version_scripts_styles', 9999);
add_filter('script_loader_src', 'remove_version_scripts_styles', 9999);

// YOAST remove version number
add_filter( 'wpseo_hide_version', '__return_true' );

add_action('wp_head',function() { ob_start(function($o) {
    return preg_replace('/^\n?<!--.*?[Y]oast.*?-->\n?$/mi','',$o);
}); },~PHP_INT_MAX);

//SECURITY : change errors hints
function no_wordpress_errors(){
    return 'Erreur d\'authentification !';
}
add_filter( 'login_errors', 'no_wordpress_errors' );

//SECURITY : disable xmlrpc
add_filter('xmlrpc_methods', function () {
    return [];
}, PHP_INT_MAX);

//SECURITY : Disable X-Pingback to header
add_filter( 'wp_headers', 'disable_x_pingback' );
function disable_x_pingback( $headers ) {
    unset( $headers['X-Pingback'] );

    return $headers;
}

//SECURITY : disable REST API
add_filter( 'rest_api_init', 'rest_only_for_authorized_users', 99 );
function rest_only_for_authorized_users($wp_rest_server){
    if ( !is_user_logged_in() ) {
        wp_die('La parole est d\'argent, le silence est d\'or...','Non autorisé',403);
    }
}

//SECURITY : remove link rsd
remove_action( 'wp_head', 'rsd_link' );
