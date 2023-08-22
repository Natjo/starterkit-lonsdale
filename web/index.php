<?php 
    /* easy-static */
    if(empty($_GET["generate"])){
        if (file_exists(__DIR__ . "/wp-content/easy-static/static/" . $_SERVER["REQUEST_URI"] . "/index.html")) {
            echo file_get_contents(__DIR__ . "/wp-content/easy-static/static/" . $_SERVER["REQUEST_URI"] . "/index.html");
            exit;
        }
    }
    /* end-easy-static */
?>
<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';
