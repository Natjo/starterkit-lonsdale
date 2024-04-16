<?php

// toggle all static/not static
add_action('wp_ajax_static_change_status', 'static_change_status_callback');
add_action('wp_ajax_nopriv_static_change_status', 'static_change_status_callback');
function static_change_status_callback()
{
    checkNonce('test_nonce');

    $response = array();

    global $table;

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

    wp_send_json($response);
}


/**
 * Génération des pages
 */

add_action('wp_ajax_test', 'test_callback');
add_action('wp_ajax_nopriv_test', 'test_callback');
function test_callback()
{
    checkNonce('test_nonce');

    global $isminify;
    global $authentification;
    global $table;

    class SitemapGenerator1
    {
        private $config;
        private $scanned;
        private $isminify;
        private $site_url_base;
        private $authentification;

        // Constructor sets the given file for internal use
        public function __construct($isminify, $authentification)
        {
            $conf =  array(
                "SITE_URL" => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . '/',

                // Boolean for crawling external links.
                // <Example> *Domain = https://www.student-laptop.nl* , *Link = https://www.google.com* <When false google will not be crawled>
                "ALLOW_EXTERNAL_LINKS" => false,

                // Boolean for crawling element id links.
                // <Example> <a href="#section"></a> will not be crawled when this option is set to false
                "ALLOW_ELEMENT_LINKS" => false,

                // If set the crawler will only index the anchor tags with the given id.
                // If you wish to crawl all links set the value to ""
                // <Example> <a id="internal-link" href="/info"></a> When CRAWL_ANCHORS_WITH_ID is set to "internal-link" this link will be crawled
                // but <a id="external-link" href="https://www.google.com"></a> will not be crawled.
                "CRAWL_ANCHORS_WITH_ID" => "",

                // Array with absolute links or keywords for the pages to skip when crawling the given SITE_URL.
                // <Example> https://student-laptop.nl/info/laptops or you can just input student-laptop.nl/info/ and it will not crawl anything in that directory
                // Try to be as specific as you can so you dont skip 300 pages
                "KEYWORDS_TO_SKIP" => array('mailto', 'upload'),
            );
            // Setup class variables using the config
            $this->config = $conf;
            $this->scanned = [];
            $this->site_url_base = parse_url($this->config['SITE_URL'])['scheme'] . "://" . parse_url($this->config['SITE_URL'])['host'];
            $this->isminify = $isminify;
            $this->authentification = $authentification;
        }

        public function GenerateSitemap()
        {
            rm_rf(WP_CONTENT_DIR . '/easy-static/static/');
            mkdir(WP_CONTENT_DIR . '/easy-static/static/', 0755, true);

            $this->crawlPage($this->site_url_base . "/");
        }

        private function getHtml($url, $page_url)
        {

            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );
            if (ENV_PREPROD_LONSDALE) {
                $user_pass =  $this->authentification["user"] . ':' .  $this->authentification["password"];
                $arrContextOptions['http'] =  array(
                    'header' => array(
                        'Authorization: Basic ' . base64_encode($user_pass),
                    )
                );
            }

            // folder
            $folder = str_replace($this->site_url_base . "/", "", $page_url);

            rm_rf(WP_CONTENT_DIR . '/easy-static/static/' . $folder);
            mkdir(WP_CONTENT_DIR . '/easy-static/static/' . $folder, 0755, true);

            if (ENV_LOCAL) {
                $docker_url = str_replace($this->site_url_base . "/", 'https://' . $_SERVER['SERVER_ADDR'] . '/', $url);
                $html = file_get_contents($docker_url . "?generate=true", false, stream_context_create($arrContextOptions));
                $html1 = str_replace('https://' . $_SERVER['SERVER_ADDR'],  "", $html);
                $html1 = str_replace(str_replace("/", "\/", 'https://' . $_SERVER['SERVER_ADDR']),  "", $html1);
                $html1 = str_replace($this->site_url_base,  "", $html1);
            } else {
                $html = file_get_contents($url . "?generate=true", false, stream_context_create($arrContextOptions));
                //TODO replace $this->site_url_base to "" and params theme url
                $html1 = $html;
            }

            if ($this->isminify  === true) {
                file_put_contents(WP_CONTENT_DIR . "/easy-static/static/" .  $folder  . 'index.html', TinyMinify::html($html1));
            } else {
                file_put_contents(WP_CONTENT_DIR . "/easy-static/static/" .  $folder  . 'index.html', $html1);
            }

            //Load the html and store it into a DOM object
            $dom = new DOMDocument();
            @$dom->loadHTML($html);

            return $dom;
        }

        // Recursive function that crawls a page's anchor tags and store them in the scanned array.
        private function crawlPage($page_url)
        {
            if (ENV_LOCAL) {
                $page_url = str_replace('https://' . $_SERVER['SERVER_ADDR'], $this->site_url_base, $page_url);
            }

            $url = filter_var($page_url, FILTER_SANITIZE_URL);

            // Check if the url is invalid or if the page is already scanned;
            if (in_array($url, $this->scanned, FALSE) || !filter_var($page_url, FILTER_VALIDATE_URL)) {
                return;
            }

            // Add the page url to the scanned array
            array_push($this->scanned, $page_url);

            // Get the html content from the 
            $html = $this->getHtml($url, $page_url);

            $anchors = $html->getElementsByTagName('a');

            // Loop through all anchor tags on the page
            foreach ($anchors as $a) {

                $next_url = $a->getAttribute('href');

                if (ENV_LOCAL) {
                    $next_url = str_replace('https://' . $_SERVER['SERVER_ADDR'], $this->site_url_base, $next_url);
                }

                // Check if there is a anchor ID set in the config.
                if ($this->config['CRAWL_ANCHORS_WITH_ID'] != "") {
                    // Check if the id is set and matches the config setting, else it will move on to the next anchor
                    if ($a->getAttribute('id') != "" || $a->getAttribute('id') == $this->config['CRAWL_ANCHORS_WITH_ID']) {
                        continue;
                    }
                }

                // Split page url into base and extra parameters
                $base_page_url = explode("?", $page_url)[0];

                if (!$this->config['ALLOW_ELEMENT_LINKS']) {
                    // Skip the url if it starts with a # or is equal to root.
                    if (substr($next_url, 0, 1) == "#" || $next_url == "/") {
                        continue;
                    }
                }

                // Check if the given url is external, if yes it will skip the iteration
                // This code will only run if you set ALLOW_EXTERNAL_LINKS to false in the config.
                if (!$this->config['ALLOW_EXTERNAL_LINKS']) {
                    $parsed_url = parse_url($next_url);
                    if (isset($parsed_url['host'])) {
                        if ($parsed_url['host'] != parse_url($this->config['SITE_URL'])['host']) {
                            continue;
                        }
                    }
                }

                // Check if the link is absolute or relative.
                if (substr($next_url, 0, 7) != "http://" && substr($next_url, 0, 8) != "https://") {
                    $next_url = $this->convertRelativeToAbsolute($base_page_url, $next_url);
                }

                // Check if the next link contains any of the pages to skip. If true, the loop will move on to the next iteration.
                $found = false;
                foreach ($this->config['KEYWORDS_TO_SKIP'] as $skip) {
                    if (strpos($next_url, $skip) || $next_url === $skip) {
                        $found = true;
                    }
                }

                // Call the function again with the new URL
                if (!$found) {
                    $this->crawlPage($next_url);
                }
            }
        }

        // Convert a relative link to a absolute link
        // Example: Relative /articles
        // Absolute https://student-laptop.nl/articles
        private function convertRelativeToAbsolute($page_base_url, $link)
        {
            $first_character = substr($link, 0, 1);
            if ($first_character == "?" || $first_character == "#") {
                return $page_base_url . $link;
            } else if ($first_character != "/") {
                return $this->site_url_base . "/" . $link;
            } else {
                return $this->site_url_base . $link;
            }
        }
    }

    $smg = new SitemapGenerator1($isminify, $authentification);
    $smg->GenerateSitemap();

    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET value = CURRENT_TIMESTAMP WHERE option ='generate' ";
    mysqli_query($link, $sql);
    mysqli_close($link);

    $response['markup'] = "done";

    wp_send_json($response);

    wp_die();
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

    global $isminify;

    class SitemapGeneratorExport
    {
        private $config;
        private $scanned;
        private $theme_slug;
        private $dist_folder;
        private $isminify;
        private $site_url_base;
        private $authentification;

        // Constructor sets the given file for internal use
        public function __construct($dist_folder, $isminify, $authentification)
        {
            $conf =  array(
                // Site to crawl and create a sitemap for.
                // <Syntax> https://www.your-domain-name.com/ or http://www.your-domain-name.com/
                "SITE_URL" => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . '/',

                // Boolean for crawling external links.
                // <Example> *Domain = https://www.student-laptop.nl* , *Link = https://www.google.com* <When false google will not be crawled>
                "ALLOW_EXTERNAL_LINKS" => false,

                // Boolean for crawling element id links.
                // <Example> <a href="#section"></a> will not be crawled when this option is set to false
                "ALLOW_ELEMENT_LINKS" => false,

                // If set the crawler will only index the anchor tags with the given id.
                // If you wish to crawl all links set the value to ""
                // <Example> <a id="internal-link" href="/info"></a> When CRAWL_ANCHORS_WITH_ID is set to "internal-link" this link will be crawled
                // but <a id="external-link" href="https://www.google.com"></a> will not be crawled.
                "CRAWL_ANCHORS_WITH_ID" => "",

                // Array with absolute links or keywords for the pages to skip when crawling the given SITE_URL.
                // <Example> https://student-laptop.nl/info/laptops or you can just input student-laptop.nl/info/ and it will not crawl anything in that directory
                // Try to be as specific as you can so you dont skip 300 pages
                "KEYWORDS_TO_SKIP" => array('mailto', 'upload'),
            );
            // Setup class variables using the config
            $this->config = $conf;
            $this->scanned = [];
            $this->site_url_base = parse_url($this->config['SITE_URL'])['scheme'] . "://" . parse_url($this->config['SITE_URL'])['host'];
            $this->theme_slug = get_option('stylesheet');
            $this->dist_folder = $dist_folder;
            $this->isminify = $isminify;
            $this->authentification = $authentification;
        }

        public function GenerateSitemap()
        {

            rm_rf(WP_CONTENT_DIR . '/easy-static/export/');
            mkdir(WP_CONTENT_DIR . '/easy-static/export/', 0755, true);

            $this->crawlPage($this->site_url_base . "/");

            //assets
            copyfolder(THEME_DIR . "/assets/", WP_CONTENT_DIR . "/easy-static/export/assets/");
            $appjs_file = file_get_contents(WP_CONTENT_DIR . "/easy-static/export/assets/js/app.js");
            $appjs_file = str_replace("/wp-content/themes/" . $this->theme_slug . "/assets/", "/" . $this->dist_folder . "assets/", $appjs_file);
            file_put_contents(WP_CONTENT_DIR . "/easy-static/export/assets/js/app.js", $appjs_file);
            $appcss_file = file_get_contents(WP_CONTENT_DIR . "/easy-static/export/assets/css/app.css");
            $appcss_file = str_replace("/wp-content/themes/" . $this->theme_slug . "/assets/", "/" . $this->dist_folder . "assets/", $appcss_file);
            file_put_contents(WP_CONTENT_DIR . "/easy-static/export/assets/css/app.css", $appcss_file);

            // uploads
            copyfolder(WP_CONTENT_DIR . '/uploads/', WP_CONTENT_DIR . "/easy-static/export/uploads/");
        }

        private function getHtml($url, $page_url)
        {
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );
            if (ENV_PREPROD_LONSDALE) {
                $user_pass =  $this->authentification["user"] . ':' .  $this->authentification["password"];

                // $user_pass = 'groupama-ra-2023:see1uoPh6Ahf9EeR';
                $arrContextOptions['http'] =  array(
                    'header' => array(
                        'Authorization: Basic ' . base64_encode($user_pass),
                    )
                );
            }

            // folder
            $folder = str_replace($this->site_url_base . "/", "", $page_url);
            rm_rf(WP_CONTENT_DIR . '/easy-static/export/' . $folder);
            mkdir(WP_CONTENT_DIR . '/easy-static/export/' . $folder, 0755, true);

            if (ENV_LOCAL) {
                $docker_url = str_replace($this->site_url_base . "/", 'https://' . $_SERVER['SERVER_ADDR'] . '/', $url);
                $html = file_get_contents($docker_url . "?generate=true", false, stream_context_create($arrContextOptions));
                $html1 = str_replace('https://' . $_SERVER['SERVER_ADDR'] . "/", "/" . $this->dist_folder, $html);
                $site_url_baseSlashed = str_replace("/", "\/", 'https://' . $_SERVER['SERVER_ADDR'] . "/");
            } else {
                $html = file_get_contents($url . "?generate=true", false, stream_context_create($arrContextOptions));
                $html1 = str_replace($this->site_url_base . '/', "/" . $this->dist_folder . "", $html);
                $site_url_baseSlashed = str_replace("/", "\/", $this->site_url_base . '/');
                //TODO test if working
            }

            $html1 = str_replace("/wp-content/uploads/", "/uploads/", $html1);
            $html1 = str_replace("/wp-content/themes/" . $this->theme_slug . "/assets/", "/assets/", $html1);
            $dist_folderSlashed = str_replace("/", "\/", $this->dist_folder);
            $html1 = str_replace($site_url_baseSlashed . "wp-content\/themes\/" . $this->theme_slug . "\/",   "\/" . $dist_folderSlashed, $html1);

            if ($this->isminify  === true) {
                file_put_contents(WP_CONTENT_DIR . "/easy-static/export/" .  $folder  . 'index.html', TinyMinify::html($html1));
            } else {
                file_put_contents(WP_CONTENT_DIR . "/easy-static/export/" .  $folder  . 'index.html', $html1);
            }

            //Load the html and store it into a DOM object
            $dom = new DOMDocument();
            @$dom->loadHTML($html);

            return $dom;
        }

        // Recursive function that crawls a page's anchor tags and store them in the scanned array.
        private function crawlPage($page_url)
        {

            if (ENV_LOCAL) {
                $page_url = str_replace('https://' . $_SERVER['SERVER_ADDR'], $this->site_url_base, $page_url);
            }

            $url = filter_var($page_url, FILTER_SANITIZE_URL);

            // Check if the url is invalid or if the page is already scanned;
            if (in_array($url, $this->scanned, FALSE) || !filter_var($page_url, FILTER_VALIDATE_URL)) {
                return;
            }

            // Add the page url to the scanned array
            array_push($this->scanned, $page_url);

            // Get the html content from the 
            $html = $this->getHtml($url, $page_url);

            $anchors = $html->getElementsByTagName('a');

            // Loop through all anchor tags on the page
            foreach ($anchors as $a) {

                $next_url = $a->getAttribute('href');

                if (ENV_LOCAL) {
                    $next_url = str_replace('https://' . $_SERVER['SERVER_ADDR'], $this->site_url_base, $next_url);
                }

                // Check if there is a anchor ID set in the config.
                if ($this->config['CRAWL_ANCHORS_WITH_ID'] != "") {
                    // Check if the id is set and matches the config setting, else it will move on to the next anchor
                    if ($a->getAttribute('id') != "" || $a->getAttribute('id') == $this->config['CRAWL_ANCHORS_WITH_ID']) {
                        continue;
                    }
                }

                // Split page url into base and extra parameters
                $base_page_url = explode("?", $page_url)[0];

                if (!$this->config['ALLOW_ELEMENT_LINKS']) {
                    // Skip the url if it starts with a # or is equal to root.
                    if (substr($next_url, 0, 1) == "#" || $next_url == "/") {
                        continue;
                    }
                }

                // Check if the given url is external, if yes it will skip the iteration
                // This code will only run if you set ALLOW_EXTERNAL_LINKS to false in the config.
                if (!$this->config['ALLOW_EXTERNAL_LINKS']) {
                    $parsed_url = parse_url($next_url);
                    if (isset($parsed_url['host'])) {
                        if ($parsed_url['host'] != parse_url($this->config['SITE_URL'])['host']) {
                            continue;
                        }
                    }
                }

                // Check if the link is absolute or relative.
                if (substr($next_url, 0, 7) != "http://" && substr($next_url, 0, 8) != "https://") {
                    $next_url = $this->convertRelativeToAbsolute($base_page_url, $next_url);
                }

                // Check if the next link contains any of the pages to skip. If true, the loop will move on to the next iteration.
                $found = false;
                foreach ($this->config['KEYWORDS_TO_SKIP'] as $skip) {
                    if (strpos($next_url, $skip) || $next_url === $skip) {
                        $found = true;
                    }
                }

                // Call the function again with the new URL
                if (!$found) {
                    $this->crawlPage($next_url);
                }
            }
        }

        // Convert a relative link to a absolute link
        // Example: Relative /articles
        // Absolute https://student-laptop.nl/articles
        private function convertRelativeToAbsolute($page_base_url, $link)
        {
            $first_character = substr($link, 0, 1);
            if ($first_character == "?" || $first_character == "#") {
                return $page_base_url . $link;
            } else if ($first_character != "/") {
                return $this->site_url_base . "/" . $link;
            } else {
                return $this->site_url_base . $link;
            }
        }
    }

    if (empty($_POST['slug'])) {
        $dist_folder = $_POST['slug'];
    } else {
        $dist_folder = $_POST['slug'] . "/";
    }

    global $authentification;

    $smg = new SitemapGeneratorExport($dist_folder, $isminify, $authentification);
    $smg->GenerateSitemap();

    $response['markup'] = "done";

    wp_send_json($response);
}



/**
 * Update export dist slug
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

// Zip
function zipFolder2($rootPath, $filefinal)
{

    $zip = new ZipArchive();
    $zip->open($filefinal, ZipArchive::CREATE | ZipArchive::OVERWRITE);



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
 * Download with uploads
 */

add_action('wp_ajax_static_export_download_no_uploads', 'static_export_download_no_uploads_callback');
add_action('wp_ajax_nopriv_static_export_no_download_uploads', 'static_export_download_no_uploads_callback');
function static_export_download_no_uploads_callback()
{
    zipFolder2(WP_CONTENT_DIR . '/uploads', WP_CONTENT_DIR . '/easy-static/export.zip');
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
