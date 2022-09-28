<?php
/*
Plugin Name: Easy static
Description: Generate static site
Version: 1
Author: Martin Jonathan
*/

global $host;
global $hostfinal;

$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];

$result_host = $wpdb->get_results( "SELECT * FROM wp_options WHERE option_name = 'easy_static_host'" );
if(empty($result_host)){
    $table_options = $wpdb->prefix.'options';
    //$data = array('option_name' => "easy_static_host", 'option_value' => "192.168.48.3");
    $data = array('option_name' => "easy_static_host", 'option_value' => $url);
    $format = array('%s','%s');
    $wpdb->insert($table_options,$data,$format);
}

$host = $result_host[0]->option_value;
$hostfinal = $_SERVER['SERVER_NAME'];


// Include mfp-functions.php, use require_once to stop the script if mfp-functions.php is not found
require_once plugin_dir_path(__FILE__) . 'includes/es-functions.php';

require_once plugin_dir_path(__FILE__) . 'includes/es-admin-ajax.php';

