<?php

// toggle all static/not static
add_action('wp_ajax_static_change_status', 'static_change_status_callback');
add_action('wp_ajax_nopriv_static_change_status', 'static_change_status_callback');
function static_change_status_callback()
{
    checkNonce('test_nonce');
    $response = array();

    if ($_POST['status'] == "true") {
        $post_types = postTypes();

        $posts = queryPosts();

        create($posts, $post_types, $_POST['status']);

        upToDate($posts);

        $posts = queryPosts();

        $response['markup'] = tr($posts, $post_types);
    } else {
        rm_rf(WP_CONTENT_DIR . '/static');
    }


    /* $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE wp_static_options SET static_active = " . $_POST['status'] . " WHERE options_id = 1";
    mysqli_query($link, $sql);
    mysqli_close($link);*/

    wp_send_json($response);
}

add_action('wp_ajax_static_posts_his_active', 'static_posts_his_active_callback');
add_action('wp_ajax_nopriv_static_posts_his_active', 'static_posts_his_active_callback');

function static_posts_his_active_callback()
{
    global $host;
    checkNonce('test_nonce');
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE wp_posts SET static_active = " . $_POST['status'] . " WHERE ID = " . $_POST['id'];
    mysqli_query($link, $sql);
    mysqli_close($link);

    $folder = $_POST['slug'] . "/";

    // create or remove index.html
    if ($_POST['status'] == "true") {
        $html = loadPage("https://" . $host . "/" . $folder . "?generate=true");
        if ($folder === "home/" || $folder === "homepage/") {
            file_put_contents(WP_CONTENT_DIR . '/static/index.html', TinyMinify::html($html));
        } else {
            mkdir(WP_CONTENT_DIR . "/static/" . $folder, 0755, true);
            file_put_contents(WP_CONTENT_DIR . "/static/" . $folder . 'index.html', TinyMinify::html($html));
        }
    } else {
        if ($folder === "home/" || $folder === "homepage/") {
            unlink(WP_CONTENT_DIR . '/static/index.html');
        } else {
            unlink(WP_CONTENT_DIR . '/static/' . $folder . 'index.html');
        }
    }

    setupToDate($_POST['id']);
}


add_action('wp_ajax_test', 'test_callback');
add_action('wp_ajax_nopriv_test', 'test_callback');
function test_callback()
{

    checkNonce('test_nonce');

    $post_types = postTypes();

    $posts = queryPosts();

    create($posts, $post_types, $_POST['status']);

    upToDate($posts);

    $posts = queryPosts();

    $response['markup'] = tr($posts, $post_types);

    wp_send_json($response);

    wp_die();
}


add_action('wp_ajax_static_change_host', 'static_change_host_callback');
add_action('wp_ajax_nopriv_static_change_host', 'static_change_host_callback');

function static_change_host_callback()
{
    checkNonce('test_nonce');
    $host = $_POST['host'];
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE wp_options SET option_value = '$host' WHERE option_name ='easy_static_host' ";
    mysqli_query($link, $sql);
    mysqli_close($link);
}
