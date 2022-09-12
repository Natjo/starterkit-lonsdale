<?php
/*
Plugin Name: Easy static
Description: Generate static site
Version: 1
Author: Martin Jonathan
*/

global $host;
global $hostfinal;

$host = "172.22.0.3";
$hostfinal = $_SERVER['SERVER_NAME'];
//$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];


// Include mfp-functions.php, use require_once to stop the script if mfp-functions.php is not found
require_once plugin_dir_path(__FILE__) . 'includes/es-functions.php';

require_once plugin_dir_path(__FILE__) . 'includes/es-admin-ajax.php';

