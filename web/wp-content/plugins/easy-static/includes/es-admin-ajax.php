<?php

// toggle all static/not static
add_action('wp_ajax_static_change_status', 'static_change_status_callback');
add_action('wp_ajax_nopriv_static_change_status', 'static_change_status_callback');
function static_change_status_callback()
{
    checkNonce('test_nonce');
    $response = array();
    global $table;

    /** TODO */
    if ($_POST['status'] == "true") {

        // create static folder if not exist and create all pages with different languages
        // else remove -disabled- to static folder
        if (is_dir(WP_CONTENT_DIR . '/easy-static/static/')) {
        } else {
            rename(WP_CONTENT_DIR . '/easy-static/static-disabled-/', WP_CONTENT_DIR . '/easy-static/static/');
        }

        $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
        $sql = "UPDATE " . $table . " SET value = '1' WHERE option = 'active'";
        mysqli_query($link, $sql);
        mysqli_close($link);
    } else {
        rename(WP_CONTENT_DIR . '/easy-static/static/', WP_CONTENT_DIR . '/easy-static/static-disabled-/');
        $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
        $sql = "UPDATE " . $table . " SET value = '0' WHERE option = 'active'";
        mysqli_query($link, $sql);
        mysqli_close($link);
    }



    /*
    if ($_POST['status'] == "true") {
        $post_types = postTypes();

        $posts = queryPosts();

        create($posts, $post_types, $_POST['status']);

        upToDate($posts);

        $posts = queryPosts();

        $response['markup'] = tr($posts, $post_types);
    } else {
        rm_rf(WP_CONTENT_DIR . '/static/'.locale());
    }*/


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
    global $table_prefix;
    global $isminify;
    checkNonce('test_nonce');
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table_prefix . "posts SET static_active = " . $_POST['status'] . " WHERE ID = " . $_POST['id'];
    mysqli_query($link, $sql);
    mysqli_close($link);

    $folder =  $_POST['slug'] . "/";

    // create or remove index.html
    if ($_POST['status'] == "true") {

        if ($folder === locale() . "accueil/" || $folder === locale() . "home/" || $folder === locale() . "homepage/") {
            $html = loadPage("https://" . $host . "/?generate=true");
            if ($isminify === true) {
                file_put_contents(WP_CONTENT_DIR . '/easy-static/static/' .  locale() . 'index.html', TinyMinify::html($html));
            } else {
                file_put_contents(WP_CONTENT_DIR . '/easy-static/static/' . locale() . 'index.html', $html);
            }
        } else {
            $html = loadPage("https://" . $host . "/" . $folder . "?generate=true");
            mkdir(WP_CONTENT_DIR . "/easy-static/static/" . $folder, 0755, true);
            if ($isminify === true) {
                file_put_contents(WP_CONTENT_DIR . "/easy-static/static/" . $folder . 'index.html', TinyMinify::html($html));
            } else {
                file_put_contents(WP_CONTENT_DIR . "/easy-static/static/" . $folder . 'index.html', $html);
            }
        }
    } else {
        if ($folder ===  locale() . "accueil/" || $folder === locale() . "home/" || $folder === locale() . "homepage/") {
            unlink(WP_CONTENT_DIR . '/easy-static/static/' . locale() . 'index.html');
        } else {
            unlink(WP_CONTENT_DIR . '/easy-static/static/' . $folder . 'index.html');
        }
    }

    setupToDate($_POST['id']);
}

/**
 * Génération des pages
 */

add_action('wp_ajax_test', 'test_callback');
add_action('wp_ajax_nopriv_test', 'test_callback');
function test_callback()
{

    checkNonce('test_nonce');

    $post_types = postTypes();

    $posts = queryPosts();
    //print_r($posts);

    create($posts, $post_types, $_POST['status']);

    upToDate($posts);

    global $table;

    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET value = CURRENT_TIMESTAMP WHERE option ='generate' ";
    mysqli_query($link, $sql);
    mysqli_close($link);



    //$posts = queryPosts();

    $response['markup'] = tr($posts, $post_types);

    wp_send_json($response);

    wp_die();
}


add_action('wp_ajax_static_change_host', 'static_change_host_callback');
add_action('wp_ajax_nopriv_static_change_host', 'static_change_host_callback');
/*
function static_change_host_callback()
{
    global $table_prefix;
    checkNonce('test_nonce');
    $host = $_POST['host'];
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table_prefix . "options SET option_value = '$host' WHERE option_name ='easy_static_host' ";
    mysqli_query($link, $sql);
    mysqli_close($link);
}*/
function static_change_host_callback()
{
    global $table;
    checkNonce('test_nonce');
    $host = $_POST['host'];
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET value = '$host' WHERE option ='host' ";
    mysqli_query($link, $sql);
    mysqli_close($link);
}


/**
 * Export pages and rewrite urls
 * cta gégérer les pages
 */

add_action('wp_ajax_static_export_pages', 'static_export_pages_callback');
add_action('wp_ajax_nopriv_static_export_pages', 'static_export_pages_callback');

function static_export_pages_callback()
{

    checkNonce('test_nonce');

    $post_types = postTypes();

    $posts = queryPosts();

    global $host;
    global $home_folder;
    global  $isminify;

    // create pages pagination
    foreach ($post_types as $post_type) {
        $post_type_object = get_post_type_object($post_type);
        if ($post_type_object->has_pagination) {
            ctpPages($post_type);
        }
    }

    //$newUrlSlug = "rapport-annuel-2022";
    $newUrlSlug = $_POST['slug'];

    $currentUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . '/';
    $currentUrlSlashed = str_replace("/", "\/", $currentUrl);
    $theme_slug = get_option('stylesheet');

    // create folders and files
    foreach ($posts as $post) {
        if ($post->static_active) {

            $folder = $post->post_name . "/";
            if (in_array($post->post_type, $post_types)) {
                $post_type_object = get_post_type_object($post->post_type);
                $folder =  $post_type_object->rewrite['slug'] . "/" . $post->post_name . "/";
            }

            if ($post->post_parent) {
                $parent_slug = get_post_field('post_name', $post->post_parent);
                $folder =  $parent_slug . "/" . $post->post_name . "/";
            }

            if ($folder === "accueil/" || $folder === "home/" || $folder === "homepage/") {
                $html = loadPage("https://" . $host . "/?generate=true");
            } else {
                $html = loadPage("https://" . $host . "/" . locale() . $folder . "?generate=true");
            }

            // 1: uploads
            $test = str_replace($currentUrl . "wp-content/uploads/", "/" . $newUrlSlug . "/uploads/", $html);

            // 2: themes
            $test = str_replace($currentUrl . "wp-content/themes/" . $theme_slug . "/assets/", "/" . $newUrlSlug . "/assets/", $test);

            // 3: paramsdatas
            $test = str_replace($currentUrlSlashed . "wp-content\/themes\/" . $theme_slug . "\/",  "\/" . $newUrlSlug . "\/", $test);

            //Remove wpml 
            $test = str_replace("<link rel='stylesheet' id='wpml-blocks-css' href='" . $currentUrl . "wp-content/plugins/sitepress-multilingual-cms/dist/css/blocks/styles.css'  media='all' />", '', $test);
            $test = str_replace("background: url(" . $currentUrl . "wp-content/plugins/sitepress-multilingual-cms/vendor/otgs/installer//res/img/icon-wpml-info-white.svg) no-repeat;'  media='all' />", '', $test);
            $test = str_replace('<div class="otgs-development-site-front-end"><span class="icon"></span>This site is registered on <a href="https://wpml.org">wpml.org</a> as a development site.</div >', '', $test);

            // 4: urls
            $test = str_replace($currentUrl, "/" . $newUrlSlug . "/", $test);

            // 5: urls homepage
            $test = str_replace('"/"', '"/' . $newUrlSlug . '/"', $test);


            if ($folder === $home_folder . "/") {
                if ($isminify === true) {
                    file_put_contents(WP_CONTENT_DIR . '/easy-static/export/' . locale() . 'index.html', TinyMinify::html($test));
                } else {
                    file_put_contents(WP_CONTENT_DIR . '/easy-static/export/' . locale() . 'index.html', $test);
                }
            } else {
                mkdir(WP_CONTENT_DIR . "/easy-static/export/" . locale() . $folder, 0755, true);
                if ($isminify === true) {
                    file_put_contents(WP_CONTENT_DIR . "/easy-static/export/" .  locale() . $folder . 'index.html', TinyMinify::html($test));
                } else {
                    file_put_contents(WP_CONTENT_DIR . "/easy-static/export/" .  locale() . $folder . 'index.html', $test);
                }
            }
        }
    }


    upToDate($posts);

    $posts = queryPosts();

    $response['markup'] = tr($posts, $post_types);



    // Assets
    copyfolder(THEME_DIR . "/assets/", WP_CONTENT_DIR . "/easy-static/export/assets/");

    // app.js
    $appjs_file = file_get_contents(WP_CONTENT_DIR . "/easy-static/export/assets/js/app.js");
    $appjs_file = str_replace("/wp-content/themes/" . $theme_slug . "/assets/", "/" . $newUrlSlug . "/assets/", $appjs_file);
    file_put_contents(WP_CONTENT_DIR . "/easy-static/export/assets/js/app.js", $appjs_file);

    //app.css
    $appcss_file = file_get_contents(WP_CONTENT_DIR . "/easy-static/export/assets/css/app.css");

    //font:
    // $appcss_file = str_replace("/wp-content/themes/".$theme_slug."/assets/fonts/", "/" . $newUrlSlug . "/assets/fonts/", $appjs_file);
    $appcss_file = str_replace("/wp-content/themes/" . $theme_slug . "/assets/", "/" . $newUrlSlug . "/assets/", $appcss_file);
    file_put_contents(WP_CONTENT_DIR . "/easy-static/export/assets/css/app.css", $appcss_file);

    wp_send_json($response);

    wp_die();
}


/**
 * Update dist slug
 */
add_action('wp_ajax_static_export_slug', 'static_export_slug_callback');
add_action('wp_ajax_nopriv_static_export_slug', 'static_export_slug_callback');

function static_export_slug_callback()
{
    global $table;
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET value = '" . $_POST['slug'] . "' WHERE option = 'slug'";
    mysqli_query($link, $sql);
    mysqli_close($link);
}


function zipFolder1($rootPath, $filefinal)
{

    $zip = new ZipArchive();
    $zip->open($filefinal, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files as $name => $file) {
        // Skip directories (they would be added automatically)
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            $zip->addFile($filePath, 'uploads/' . $relativePath);
        }
    }

    $rootPath1 = WP_CONTENT_DIR . '/easy-static/export';
    $files1 = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath1),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files1 as $name => $file) {
        // Skip directories (they would be added automatically)
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath1) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }



    $zip->close();
}



/**
 * Download uploads
 */

add_action('wp_ajax_static_export_download_uploads', 'static_export_download_uploads_callback');
add_action('wp_ajax_nopriv_static_export_download_uploads', 'static_export_download_uploads_callback');

function static_export_download_uploads_callback()
{
    // zipFolder(WP_CONTENT_DIR . '/uploads', WP_CONTENT_DIR . '/uploads.zip');
    zipFolder1(WP_CONTENT_DIR . '/uploads', WP_CONTENT_DIR . '/easy-static/export.zip');
    $response['ready'] =  true;
    wp_send_json($response);
}


/**
 * remove zip
 */

add_action('wp_ajax_static_export_download_remove', 'static_export_download_remove_callback');
add_action('wp_ajax_nopriv_static_export_download_remove', 'static_export_download_remove_callback');

function static_export_download_remove_callback()
{
    rm_rf(WP_CONTENT_DIR . '/easy-static/export.zip');
    $response['ready'] =  true;
    wp_send_json($response);
}



/**
 * Authentification
 */

add_action('wp_ajax_static_authentification', 'static_authentification_callback');
add_action('wp_ajax_nopriv_static_authentification', 'static_authentification_callback');

function static_authentification_callback()
{
    global $table;
    checkNonce('test_nonce');
    $user = $_POST['user'];
    $password = $_POST['password'];

    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET value = '$user' WHERE option ='user' ";
    mysqli_query($link, $sql);
    mysqli_close($link);

    $link_password = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET value = '$password' WHERE option ='password' ";
    mysqli_query($link_password, $sql);
    mysqli_close($link_password);
}


/**
 * Options
 */
add_action('wp_ajax_static_minify', 'static_minify_callback');
add_action('wp_ajax_nopriv_static_minify', 'static_minify_callback');
function static_minify_callback()
{
    global $table;
    checkNonce('test_nonce');
    $minify = $_POST['minify'];

    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET value = '$minify' WHERE option ='minify' ";
    mysqli_query($link, $sql);
    mysqli_close($link);
}
add_action('wp_ajax_static_localisfolder', 'static_localisfolder_callback');
add_action('wp_ajax_nopriv_static_localisfolder', 'static_localisfolder_callback');
function static_localisfolder_callback()
{
    global $table;
    checkNonce('test_nonce');
    $localisfolder = $_POST['localisfolder'];

    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET value = '$localisfolder' WHERE option ='localisfolder' ";
    mysqli_query($link, $sql);
    mysqli_close($link);
}
