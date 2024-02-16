<?php
/*
Plugin Name: Easy static
Description: Generate static site
Version: 1.2.3
Author: Martin Jonathan
*/

global $wpdb;
global $host;
global $authentification;
global $table;
global $haschange;
global $isStatic;

$homepageID = get_option('page_on_front');
$homepagePost = get_post($homepageID);
$home_folder = $homepagePost->post_name; // "homepage";
$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];

// Create table easystatic if not exist
$charset_collate = $wpdb->get_charset_collate();
$table =  $table_prefix . "easystatic";
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
$result_host = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'host'");
if (empty($result_host)) {
    $data = array('option' => "host", 'value' => $url);
    $format = array('%s', '%s');
    $wpdb->insert($table, $data, $format);
}

$easy_static_active = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'active'");
if (empty($easy_static_active)) {
    $data = array('option' => "active", 'value' => false);
    $format = array('%s', '%d');
    $wpdb->insert($table, $data, $format);
}
$isStatic = $easy_static_active[0]->value === "0" ? false : true;

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
    $data = array('option' => "slug", 'value' => "/");
    $format = array('%s', '%s');
    $wpdb->insert($table, $data, $format);
}

$easy_static_minify = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'minify'");
if (empty($easy_static_minify)) {
    $data = array('option' => "minify", 'value' => true);
    $format = array('%s', '%d');
    $wpdb->insert($table, $data, $format);
}

$easy_static_localisfolder = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'localisfolder'");
if (empty($easy_static_localisfolder)) {
    $data = array('option' => "localisfolder", 'value' => false);
    $format = array('%s', '%d');
    $wpdb->insert($table, $data, $format);
}

$easy_static_haschange = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option = 'haschange'");
if (empty($easy_static_haschange)) {
    $data = array('option' => "haschange", 'value' => false);
    $format = array('%s', '%d');
    $wpdb->insert($table, $data, $format);
}
$haschange = $easy_static_haschange[0]->value === "0" ? false : true;

$host = $result_host[0]->value;

global $isminify;
$minify = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE option = 'minify'");
$localisfolder = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE option = 'localisfolder'");
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
    global $table;

    if ($easy_static_active[0]->value) {
        if ($post->post_type == "page" || $post->post_type == "post") {
            if ($post->static_active) {
                $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
                $sql = "UPDATE " . $table . " SET value = true WHERE option ='haschange' ";
                mysqli_query($link, $sql);
                mysqli_close($link);
            }
        }
    }

}

// set haschange to true if change in parameters
function clear_advert_main_transient($post_id)
{   global $table;
    global $easy_static_active;
    $screen = get_current_screen();
    if ($easy_static_active[0]->value) {
        if ($screen->base === "toplevel_page_acf-options-parametres") {
            // if (strpos($screen->id, "acf-options-adverts") == true) {
            $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
            $sql = "UPDATE " . $table . " SET value = true WHERE option ='haschange' ";
            mysqli_query($link, $sql);
            mysqli_close($link);
        }
    }
}
add_action('acf/save_post', 'clear_advert_main_transient', 20);