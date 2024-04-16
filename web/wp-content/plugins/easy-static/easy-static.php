<?php
/*
Plugin Name: Easy static
Description: Generate static site
Version: 1.4.0
Author: Martin Jonathan
*/

global $wpdb;
global $authentification;
global $table;
global $haschange;
global $isStatic;
global $isminify;

//$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];

// Create table easystatic if not exist
$charset_collate = $wpdb->get_charset_collate();
$table = $table_prefix . "easystatic";
if (!$wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
    $sql = "CREATE TABLE $table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        option tinytext NOT NULL,
        value tinytext NOT NULL,
        PRIMARY KEY  (id)
      ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Create options
$easy_static_active = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'active'");
if (empty($easy_static_active)) {
    $data = array('option' => "active", 'value' => false);
    $format = array('%s', '%d');
    $wpdb->insert($table, $data, $format);
    $isStatic = false;
} else {
    $isStatic = $easy_static_active[0]->value === "0" ? false : true;
}

$easy_static_user = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'user'");
if (empty($easy_static_user)) {
    $data = array('option' => "user", 'value' => "");
    $format = array('%s', '%s');
    $wpdb->insert($table, $data, $format);
}

$easy_static_password = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'password'");
if (empty($easy_static_password)) {
    $data = array('option' => "password", 'value' => "");
    $format = array('%s', '%s');
    $wpdb->insert($table, $data, $format);
}

$easy_static_slug = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'slug'");
if (empty($easy_static_slug)) {
    $data = array('option' => "slug", 'value' => "");
    $format = array('%s', '%s');
    $wpdb->insert($table, $data, $format);
}

$easy_static_minify = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'minify'");
if (empty($easy_static_minify)) {
    $data = array('option' => "minify", 'value' => false);
    $format = array('%s', '%d');
    $wpdb->insert($table, $data, $format);
}

$easy_static_generate = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'generate'");
if (empty($easy_static_generate)) {
    $data = array('option' => "generate", 'value' => "");
    $format = array('%s', '%s');
    $wpdb->insert($table, $data, $format);
    $last_generate = "";
} else {
    $last_generate = $easy_static_generate[0]->value;
}

$easy_static_haschange = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'haschange'");
if (empty($easy_static_haschange)) {
    $data = array('option' => "haschange", 'value' => false);
    $format = array('%s', '%d');
    $wpdb->insert($table, $data, $format);
    $haschange = false;
} else {
    $haschange = $easy_static_haschange[0]->value === "0" ? false : true;
}


$minify = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE option = 'minify'");
$isminify =  $minify[0]->value === "true" ? true : false;

// authentification
$user = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE option = 'user'");
$password = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE option = 'password'");
$authentification["user"] =  $user[0]->value;
$authentification["password"] = $password[0]->value;

// Include mfp-functions.php, use require_once to stop the script if mfp-functions.php is not found
require_once plugin_dir_path(__FILE__) . 'includes/es-functions.php';

require_once plugin_dir_path(__FILE__) . 'includes/es-admin-ajax.php';

// set haschange to true if page/post is edited
add_action('save_post', 'wpdocs_notify_subscribers', 10, 3);
function wpdocs_notify_subscribers($post_id, $post, $update)
{
    global $easy_static_active;

    //print_r($post);
    //echo $post->post_name;

    if ($easy_static_active[0]->value) {
        if ($post->post_type == "page" || $post->post_type == "post") {
            if ($post->static_active) {
                hasChanged();
            }
        }
    }
}

add_action('check_admin_referer', 'check_nav_menu_updates', 11, 1);
function check_nav_menu_updates($action)
{
    if (('update-nav_menu' != $action) or !isset($_POST['menu-locations'])) {
        return;
    }
    hasChanged();
}

// set haschange to true if change in parameters
function clear_advert_main_transient($post_id)
{
    global $easy_static_active;
    $screen = get_current_screen();
    if ($easy_static_active[0]->value) {
        if ($screen->base === "toplevel_page_acf-options-parametres") {
            hasChanged();
        }
    }
}
add_action('acf/save_post', 'clear_advert_main_transient', 20);
