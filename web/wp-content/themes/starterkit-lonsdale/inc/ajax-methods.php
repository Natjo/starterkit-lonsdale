<?php
add_action('wp_ajax_example', 'example_callback');
add_action('wp_ajax_nopriv_example', 'example_callback');
function example_callback()
{
    // Security
    checkNonce('exampleNonce');

    $var = isset($_POST['var']) ? filter_var($_POST['var'], FILTER_SANITIZE_STRING) : '';

    if (!empty($var)) {

        $response['status'] = 200;

        ob_start();
        ?>

        <!-- HTML -->

        <?php
        $response['content'] = ob_get_clean();
        $message = '';
    } else {

        $response['status'] = 300;
        $message = '';
    }

    $response['message'] = $message;

    wp_send_json($response);
}

add_action('wp_ajax_rgpd', 'rgpd_callback');
add_action('wp_ajax_nopriv_rgpd', 'rgpd_callback');
function rgpd_callback()
{
    // Security
    checkNonce('securite_nonce_rgpd');

    /**
     * Si inexistante, on créée la table SQL "commissions" après l'activation du thème
     */
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $rgpd_table_name = $wpdb->prefix . 'rgpd';

    $create_table_rgpd_sql = "CREATE TABLE IF NOT EXISTS $rgpd_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip varchar(45) DEFAULT NULL,
        time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($create_table_rgpd_sql);

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $wpdb->get_results("INSERT INTO $rgpd_table_name (ip, time) VALUES ('$ip', (SELECT TIMESTAMP(\"YYYY-MM-DD\", \"HH:MM\")));");

    $response['status'] = 200;
    $response['message'] = "OK";

    wp_send_json($response);
}
