<?php

function theme_setup()
{
    load_theme_textdomain('theme', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ),
        'post-thumbnails'
    );

    register_nav_menus(array(
        'menu-footer' => 'Menu Footer',
        'menu-header' => 'Menu Header',
        'sitemap' => 'Sitemap'
    ));

    show_admin_bar(false);

   
}


//REMOVE FILE TYPE
add_filter('style_loader_tag', 'remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'remove_type_attr', 10, 2);

function remove_type_attr($tag, $handle)
{
    return preg_replace("/type=['\"]text\/(javascript|css)['\"]/", '', $tag);
}

function remove_menus()
{
    remove_menu_page('edit.php'); // remove articles from menu
    remove_menu_page('edit-comments.php'); //Comments
}
add_action('admin_menu', 'remove_menus');

function acf_add_main_options()
{
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page('Paramètres');
    }
}

add_filter('wp_default_scripts', 'removeJqueryMigrate');
function removeJqueryMigrate(&$scripts)
{
    if (!is_admin()) {
        $scripts->remove('jquery');
        $scripts->add('jquery', false, array('jquery-core'), '1.4.1');
    }
}

add_action('after_setup_theme', 'theme_setup');
// add_action( 'admin_bar_menu', 'remove_default_post_type_menu_bar', 999 );
// add_action( 'wp_dashboard_setup', 'remove_draft_widget', 999 );
add_action('wp_loaded', 'acf_add_main_options');

//REMOVE : emoji 🗑
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// Remove wp-embed.min.js
function my_deregister_scripts()
{
    wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'my_deregister_scripts');

// Remove default css
function smartwp_remove_wp_block_library_css()
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style'); // Remove WooCommerce block CSS
}
add_action('wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100);


// Remove unused format
function disable_unused_format($sizes)
{
    unset($sizes['300x300']);
    unset($sizes['600x800']);
    /* unset($sizes['768x768']);*/
    unset($sizes['large']);
    unset($sizes['2048x2048']);
    unset($sizes['scaled']);
    unset($sizes['1536x1536']);
    unset($sizes['thumbnail_example']);
    unset($sizes['medium_large']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'disable_unused_format');


// no image compression
add_filter('jpeg_quality', function ($arg) {
    return -1;
});
add_filter('wp_editor_set_quality', function ($arg) {
    return -1;
});


/*  DISABLE GUTENBERG STYLE IN HEADER| WordPress 5.9 */
function wps_deregister_styles()
{
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'wps_deregister_styles', 100);


// --------------------------------------------------------------------------------------
// Désactivation de l'éditeur wordpress sur les page (pour n'avoir que les champs ACF)
// --------------------------------------------------------------------------------------
add_action('init', 'init_remove_support', 100);

function init_remove_support()
{
    $template_file = "";

    // If not in the admin, return.
    if (!is_admin()) {
        return;
    }
    // Get the post ID on edit post with filter_input super global inspection.
    $current_post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
    // Get the post ID on update post with filter_input super global inspection.
    $update_post_id = filter_input(INPUT_POST, 'post_ID', FILTER_SANITIZE_NUMBER_INT);
    // Check to see if the post ID is set, else return.
    if (isset($current_post_id)) {
        $post_id = absint($current_post_id);
    } else {
        if (isset($update_post_id)) {
            $post_id = absint($update_post_id);
        } else {
            return;
        }
    }
    // Don't do anything unless there is a post_id.
    if (isset($post_id)) {
        $template_file = get_post_meta($post_id, '_wp_page_template', true);
    }
    if ('default' !== $template_file) {
        remove_post_type_support("page", 'editor');
    }
}
