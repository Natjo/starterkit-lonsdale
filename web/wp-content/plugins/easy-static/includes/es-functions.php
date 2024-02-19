<?php
// Hook the 'admin_menu' action hook, run the function named 'mfp_Add_My_Admin_Link()'
// Add a new top level menu link to the ACP
add_action('admin_menu', 'mfp_Add_My_Admin_Link');
function mfp_Add_My_Admin_Link()
{
    global $haschange;
    global $isStatic;

    add_menu_page(
        'Easy static', // Title of the page
        $haschange && $isStatic ?  'Easy static <span class="awaiting-mod es-notification">!</span>' : 'Easy static', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'easy-static/includes/es-index.php', // The 'slug' - file to display when clicking the link
        '',
        'dashicons-text-page',
    );
}


// helpers
class TinyHtmlMinifier
{
    private $options;
    private $output;
    private $build;
    private $skip;
    private $skipName;
    private $head;
    private $elements;

    public function __construct(array $options)
    {
        $this->options = $options;
        $this->output = '';
        $this->build = [];
        $this->skip = 0;
        $this->skipName = '';
        $this->head = false;
        $this->elements = [
            'skip' => [
                'code',
                'pre',
                'script',
                'textarea',
            ],
            'inline' => [
                'a',
                'abbr',
                'acronym',
                'b',
                'bdo',
                'big',
                'br',
                'cite',
                'code',
                'dfn',
                'em',
                'i',
                'img',
                'kbd',
                'map',
                'object',
                'samp',
                'small',
                'span',
                'strong',
                'sub',
                'sup',
                'tt',
                'var',
                'q',
            ],
            'hard' => [
                '!doctype',
                'body',
                'html',
            ]
        ];
    }

    // Run minifier
    public function minify(string $html): string
    {
        if (
            !isset($this->options['disable_comments']) ||
            !$this->options['disable_comments']
        ) {
            $html = $this->removeComments($html);
        }

        $rest = $html;

        while (!empty($rest)) {
            $parts = explode('<', $rest, 2);
            $this->walk($parts[0]);
            $rest = (isset($parts[1])) ? $parts[1] : '';
        }

        return $this->output;
    }

    // Walk trough html
    private function walk(&$part)
    {
        $tag_parts = explode('>', $part);
        $tag_content = $tag_parts[0];

        if (!empty($tag_content)) {
            $name = $this->findName($tag_content);
            $element = $this->toElement($tag_content, $part, $name);
            $type = $this->toType($element);

            if ($name == 'head') {
                $this->head = $type === 'open';
            }

            $this->build[] = [
                'name' => $name,
                'content' => $element,
                'type' => $type
            ];

            $this->setSkip($name, $type);

            if (!empty($tag_content)) {
                $content = (isset($tag_parts[1])) ? $tag_parts[1] : '';
                if ($content !== '') {
                    $this->build[] = [
                        'content' => $this->compact($content, $name, $element),
                        'type' => 'content'
                    ];
                }
            }

            $this->buildHtml();
        }
    }

    // Remove comments
    private function removeComments($content = '')
    {
        return preg_replace('/(?=<!--)([\s\S]*?)-->/', '', $content);
    }

    // Check if string contains string
    private function contains($needle, $haystack)
    {
        return strpos($haystack, $needle) !== false;
    }

    // Return type of element
    private function toType($element)
    {
        return (substr($element, 1, 1) == '/') ? 'close' : 'open';
    }

    // Create element
    private function toElement($element, $noll, $name)
    {
        $element = $this->stripWhitespace($element);
        $element = $this->addChevrons($element, $noll);
        $element = $this->removeSelfSlash($element);
        $element = $this->removeMeta($element, $name);
        return $element;
    }

    // Remove unneeded element meta
    private function removeMeta($element, $name)
    {
        if ($name == 'style') {
            $element = str_replace(
                [
                    ' type="text/css"',
                    "' type='text/css'"
                ],
                ['', ''],
                $element
            );
        } elseif ($name == 'script') {
            $element = str_replace(
                [
                    ' type="text/javascript"',
                    " type='text/javascript'"
                ],
                ['', ''],
                $element
            );
        }
        return $element;
    }

    // Strip whitespace from element
    private function stripWhitespace($element)
    {
        if ($this->skip == 0) {
            $element = preg_replace('/\s+/', ' ', $element);
        }
        return trim($element);
    }

    // Add chevrons around element
    private function addChevrons($element, $noll)
    {
        if (empty($element)) {
            return $element;
        }
        $char = ($this->contains('>', $noll)) ? '>' : '';
        $element = '<' . $element . $char;
        return $element;
    }

    // Remove unneeded self slash
    private function removeSelfSlash($element)
    {
        // commented because remove / in svg element
        /* if (substr($element, -3) == ' />') {
            $element = substr($element, 0, -3) . '>';
        }*/
        return $element;
    }

    // Compact content
    private function compact($content, $name, $element)
    {
        if ($this->skip != 0) {
            $name = $this->skipName;
        } else {
            $content = preg_replace('/\s+/', ' ', $content);
        }

        if (in_array($name, $this->elements['skip'])) {
            return $content;
        } elseif (
            in_array($name, $this->elements['hard']) ||
            $this->head
        ) {
            return $this->minifyHard($content);
        } else {
            return $this->minifyKeepSpaces($content);
        }
    }

    // Build html
    private function buildHtml()
    {
        foreach ($this->build as $build) {

            if (!empty($this->options['collapse_whitespace'])) {

                if (strlen(trim($build['content'])) == 0)
                    continue;

                elseif ($build['type'] != 'content' && !in_array($build['name'], $this->elements['inline']))
                    trim($build['content']);
            }

            $this->output .= $build['content'];
        }

        $this->build = [];
    }

    // Find name by part
    private function findName($part)
    {
        $name_cut = explode(" ", $part, 2)[0];
        $name_cut = explode(">", $name_cut, 2)[0];
        $name_cut = explode("\n", $name_cut, 2)[0];
        $name_cut = preg_replace('/\s+/', '', $name_cut);
        $name_cut = strtolower(str_replace('/', '', $name_cut));
        return $name_cut;
    }

    // Set skip if elements are blocked from minification
    private function setSkip($name, $type)
    {
        foreach ($this->elements['skip'] as $element) {
            if ($element == $name && $this->skip == 0) {
                $this->skipName = $name;
            }
        }
        if (in_array($name, $this->elements['skip'])) {
            if ($type == 'open') {
                $this->skip++;
            }
            if ($type == 'close') {
                $this->skip--;
            }
        }
    }

    // Minify all, even spaces between elements
    private function minifyHard($element)
    {
        $element = preg_replace('!\s+!', ' ', $element);
        $element = trim($element);
        return trim($element);
    }

    // Strip but keep one space
    private function minifyKeepSpaces($element)
    {
        return preg_replace('!\s+!', ' ', $element);
    }
}
class TinyMinify
{
    public static function html(string $html, array $options = []): string
    {
        $minifier = new TinyHtmlMinifier($options);
        return $minifier->minify($html);
    }
}
function rm_rf($path)
{
    if (@is_dir($path) && is_writable($path)) {
        $dp = opendir($path);
        while ($ent = readdir($dp)) {
            if ($ent == '.' || $ent == '..') {
                continue;
            }
            $file = $path . DIRECTORY_SEPARATOR . $ent;
            if (@is_dir($file)) {
                rm_rf($file);
            } elseif (is_writable($file)) {
                unlink($file);
            } else {
                echo $file . "is not writable and cannot be removed. Please fix the permission or select a new path.\n";
            }
        }
        closedir($dp);
        return rmdir($path);
    } else {
        return @unlink($path);
    }
}
function copyfolder($from, $to)
{
    // (A1) SOURCE FOLDER CHECK
    if (!is_dir($from)) {
        exit("$from does not exist");
    }

    // (A2) CREATE DESTINATION FOLDER
    if (!is_dir($to)) {
        if (!mkdir($to)) {
            exit("Failed to create $to");
        };
        echo "$to created\r\n";
    }

    // (A3) COPY FILES + RECURSIVE INTERNAL FOLDERS
    $dir = opendir($from);
    while (($ff = readdir($dir)) !== false) {
        if ($ff != "." && $ff != "..") {
            if (is_dir("$from$ff")) {
                copyfolder("$from$ff/", "$to$ff/");
            } else {
                if (!copy("$from$ff", "$to$ff")) {
                    exit("Error copying $from$ff to $to$ff");
                }
                echo "$from$ff copied to $to$ff\r\n";
            }
        }
    }
    closedir($dir);
}

//
/*
function zipFolder($rootPath, $filefinal)
{

    $zip = new ZipArchive();
    $zip->open($filefinal, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files as $name => $file) {
        // Skip directories (they would be added automatically)
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
}
*/

//
function loadPage($file)
{
    global $host;
    global $authentification;

    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
        /*
        'http' => array (
        	'header' => 'Authorization: Basic ' . base64_encode("groupama-ra-2022:aer3aech7Aequ6ae")
    	)
        'header' => "Accept-language: en"
        */
    );

    if (ENV_PREPROD_LONSDALE) {
        $user_pass = $authentification["user"] . ':' . $authentification["password"];
        $arrContextOptions['http'] =  array(
            'header' => array(
                'Authorization: Basic ' . base64_encode($user_pass),
                //"Accept-language: fr"
            )
        );
    }

    $html = file_get_contents($file, false, stream_context_create($arrContextOptions));

    //if ($host !== $_SERVER['SERVER_NAME']) {
    //  $html = str_replace($host, $_SERVER['SERVER_NAME'], $html);
    // }

    if (ENV_LOCAL) {
        $html = str_replace($host, $_SERVER['SERVER_NAME'], $html);
    }

    return $html;

    //return file_get_contents($url, false, stream_context_create($arrContextOptions));
}

function queryPosts()
{
    $args = array(
        'post_type' => "any",
        'posts_per_page' => -1,
        'order' => 'DESC',
        'orderby' => 'modified',
        'post_status' => 'publish'
    );
    $posts = new WP_Query($args);
    wp_reset_postdata();
    return $posts->posts;
}

function locale()
{
    $locale = "";
    if (defined("ICL_LANGUAGE_CODE")) {
        $locale = ICL_LANGUAGE_CODE . "/";
    }
    return $locale;
}


function listPages()
{
    function sousPages($posts, $id, $parent)
    {
        $html = "";
        foreach ($posts as $post) {
            if ($id === $post->post_parent) {
                $html .= '
                <div class="list-pages-row">
                <div class="list-pages-link"><a target="_blank" href="/' . $parent . "/" . $post->post_name . '">' . $post->post_title . '</a></div>
                <div class="list-pages-url">' . $parent . "/" . $post->post_name . '</div>
                <div class="list-pages-active "><input data-slug="' .  locale() . $post->post_name . '" type="checkbox" ' . ($post->static_active ? "checked" : "") . ' name="page-' . $post->ID . '" value="' . $post->static_active  . '" class="checkbox-static_active" id="' . $post->ID . '" ></div>
                <div class="list-pages-type info-update"></div>
                </div>';
            }
        }
        return $html;
    }

    $args = array(
        'post_type' => "page",
        'posts_per_page' => -1,
        'order' => 'DESC',
        'orderby' => 'modified',
        'post_status' => 'publish'
    );
    $posts = new WP_Query($args);
    wp_reset_postdata();

    foreach ($posts->posts as $post) {
        //   print_r($post);
        $child = sousPages($posts->posts, $post->ID, locale() . $post->post_name);

        if ($child !== "") {
            echo '
         <details class="list-pages-row list-pages-item">
         <summary class="list-pages-row">
         <div class="list-pages-link"><a target="_blank" href="/' . locale() . $post->post_name . '">' . $post->post_title . '</a></div>
         <div class="list-pages-url">' . locale() . $post->post_name . '</div> 
         <div class="list-pages-active"><input data-slug="' .  locale() . $post->post_name . '" type="checkbox" ' . ($post->static_active ? "checked" : "") . ' name="page-' . $post->ID . '" value="' . $post->static_active  . '" class="checkbox-static_active" id="' . $post->ID . '" ></div>
         <div class="list-pages-type info-update"></div>
         </summary><div class="list-pages-link-childs">' . $child . '</div></details>';
        } elseif (!$post->post_parent) {
            echo '
        <div  class="list-pages-row list-pages-item">
        <div class="list-pages-link"><a target="_blank" href="/' . locale() . $post->post_name . '">' . $post->post_title .  '</a></div>
        <div class="list-pages-url">' . locale() . $post->post_name . '</div>
        <div class="list-pages-active "><input data-slug="' .  locale() . $post->post_name . '" type="checkbox" ' . ($post->static_active ? "checked" : "") . ' name="page-' . $post->ID . '" value="' . $post->static_active  . '" class="checkbox-static_active" id="' . $post->ID . '" ></div>        
        <div class="list-pages-type info-update"></div>
        </div>';
        }
    }
}
function tr($posts)
{
    global  $home_folder;
    $markup = "";

    foreach ($posts as $post) {
        $slug = $post->post_name;

        if ($post->post_type == "page" && !$post->post_parent) {
            if ($post->post_parent) {
                $parent_slug = get_post_field('post_name', $post->post_parent);
                $slug = $parent_slug . "/" . $post->post_name;
            }
            //print_r($post);

            $markup .= '<tr>';
            if ($slug === $home_folder) {
                $markup .= '<td><a href="/' . locale() . '" target="_blank">' . $post->post_title . '</a></td>';
                $markup .= "<td>" . locale() . "</td>";
            } else {
                $markup .= '<td><a href="/' . locale() . $slug . '/" target="_blank">' . $post->post_title . '</a></td>';
                $markup .= "<td>" . locale() . $slug  . "</td>";
            }



            $markup .= "<td>" . $post->post_type . ' ' . $post->post_parent . "</td>";
            $markup .= '<td><input data-slug="' . $slug . '" type="checkbox" ' . ($post->static_active ? "checked" : "") . ' name="page-' . $post->ID . '" value="' . $post->static_active  . '" class="checkbox-static_active" id="' . $post->ID . '" ></td>';
            $markup .= "</tr>";

            foreach ($posts as $post1) {
                if ($post1->post_parent == $post->ID) {
                    $markup .= '<tr>';
                    $markup .= '<td><a href="/' . locale() . $slug . '/" target="_blank">' . $post1->post_title . '</a></td>';
                    $markup .= "<td>" . locale() . $slug  . "</td>";
                    $markup .= "<td>" . $post1->post_type . ' ' . $post1->post_parent . "</td>";
                    $markup .= '<td><input data-slug="' . $slug . '" type="checkbox" ' . ($post1->static_active ? "checked" : "") . ' name="page-' . $post->ID . '" value="' . $post->static_active  . '" class="checkbox-static_active" id="' . $post->ID . '" ></td>';
                    $markup .= "</tr>";
                }
            }
        }
    }
    return $markup;
}

function trCpts($posts, $post_types)
{
    $markup = "";
    foreach ($posts as $post) {
        $slug = $post->post_name;

        if (in_array($post->post_type, $post_types)) {
            $post_type_object = get_post_type_object($post->post_type);
            $slug = $post_type_object->rewrite['slug'] . "/" . $post->post_name;
            $markup .= '<tr>';
            $markup .= '<td><a href="/' . locale() . $slug . '/" target="_blank">' . $post->post_title . '</a></td>';
            $markup .= "<td>" . locale() . $slug  . "</td>";
            $markup .= "<td></td>";
            $markup .= '<td class="info-update"></td>';
            $markup .= "</tr>";
        }
    }
    return $markup;
}


function postTypes()
{

    $args = array(
        'public'   => true,
        '_builtin' => false,
    );
    $output = 'names'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'
    return get_post_types($args, $output, $operator);
}

function display()
{
    // $post_types = postTypes();
    $posts = queryPosts();
    return tr($posts);
}

function displayCpts()
{
    $post_types = postTypes();
    $posts = queryPosts();
    //print_r($post_types);
    return trCpts($posts, $post_types);
}

function upToDate($posts)
{
    global $table_prefix;
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));

    foreach ($posts as $post) {
        $sql = "UPDATE " . $table_prefix . "posts SET static_generate = CURRENT_TIMESTAMP WHERE ID = " . $post->ID;
        mysqli_query($link, $sql);
    }
    mysqli_close($link);
}

function setupToDate($id)
{
    global $table_prefix;
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table_prefix . "posts SET static_generate = CURRENT_TIMESTAMP WHERE ID = " . $id;
    mysqli_query($link, $sql);
    mysqli_close($link);
}

// cpts pages pagination generate
function ctpPages($post_type)
{
    global $host;

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'order' => 'ID',
        'orderby' => 'title',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
    );
    $queryArticles = new WP_Query($args);
    $posts_per_page = get_option('posts_per_page');
    $totalPages = ceil($queryArticles->post_count / $posts_per_page);

    $post_type_object = get_post_type_object($post_type);
    $slug = $post_type_object->rewrite['slug'];
    $has_pagination = $post_type_object->has_pagination;
    $pagination_folder = $post_type_object->pagination_folder;

    if ($has_pagination) {
        for ($i = 1; $i <= $totalPages; $i++) {
            $pp = locale() . $slug . "/" . $pagination_folder . "/" . $i . "/";
            $html = loadPage("https://" . $host . "/" . $pp . "?generate=true");
            mkdir(WP_CONTENT_DIR . '/easy-static/static/' .  $pp, 0755, true);
            file_put_contents(WP_CONTENT_DIR . '/easy-static/static/' . $pp . 'index.html', TinyMinify::html($html));
        }
    }
}

/**
 * Save post or page 
 */
function save($post)
{
    global $host;
    global $home_folder;
    global $isminify;

    //TODO regenere parent et/ou page avec pager
    if ($post->static_active) {

        $folder = $post->post_name . "/";
        /*if (in_array($post->post_type, $post_types)) {
            $post_type_object = get_post_type_object($post->post_type);
            $folder =  $post_type_object->rewrite['slug'] . "/" . $post->post_name . "/";
        }*/

        /*if ($post->post_parent) {
            $parent_slug = get_post_field('post_name', $post->post_parent);
            $folder =  $parent_slug . "/" . $post->post_name . "/";
        }*/

        if ($folder === $home_folder . "/") {
            $html = loadPage("https://" . $host . "/" . locale() . "?generate=true");
            if ($isminify === true) {
                file_put_contents(WP_CONTENT_DIR . '/easy-static/static/' . locale() . 'index.html', TinyMinify::html($html));
            } else {
                file_put_contents(WP_CONTENT_DIR . '/easy-static/static/' . locale() . 'index.html', $html);
            }
        } else {
            $html = loadPage("https://" . $host . "/" . locale() . $folder . "?generate=true");
            if (!is_dir(WP_CONTENT_DIR . "/easy-static/static/" . locale() . $folder)) {
                mkdir(WP_CONTENT_DIR . "/easy-static/static/" . locale() . $folder, 0755, true);
            }
            if ($isminify === true) {
                file_put_contents(WP_CONTENT_DIR . "/easy-static/static/" .  locale() . $folder . 'index.html', TinyMinify::html($html));
            } else {
                file_put_contents(WP_CONTENT_DIR . "/easy-static/static/" .  locale() . $folder . 'index.html', $html);
            }
        }
    }
}

/**
 * Creation pages
 */
function create($posts, $post_types)
{
    global $host;
    global $home_folder;
    global $isminify;
    global $table;

    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET value = false WHERE option ='haschange' ";
    mysqli_query($link, $sql);
    mysqli_close($link);

    rm_rf(WP_CONTENT_DIR . '/easy-static/static/' . locale());
    mkdir(WP_CONTENT_DIR . '/easy-static/static/' . locale(), 0755, true);

    // create pages pagination
    foreach ($post_types as $post_type) {
        $post_type_object = get_post_type_object($post_type);
        if ($post_type_object->has_pagination) {
            ctpPages($post_type);
        }
    }

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

            if ($folder === $home_folder . "/") {
                // echo "https://" . $host . "/" . locale() . "?generate=true";
                $html = loadPage("https://" . $host . "/" . locale() . "?generate=true");
                if ($isminify === true) {
                    file_put_contents(WP_CONTENT_DIR . '/easy-static/static/' . locale() . 'index.html', TinyMinify::html($html));
                } else {
                    file_put_contents(WP_CONTENT_DIR . '/easy-static/static/' . locale() . 'index.html', $html);
                }
            } else {
                $html = loadPage("https://" . $host . "/" . locale() . $folder . "?generate=true");
                mkdir(WP_CONTENT_DIR . "/easy-static/static/" . locale() . $folder, 0755, true);
                if ($isminify === true) {
                    file_put_contents(WP_CONTENT_DIR . "/easy-static/static/" .  locale() . $folder . 'index.html', TinyMinify::html($html));
                } else {
                    file_put_contents(WP_CONTENT_DIR . "/easy-static/static/" .  locale() . $folder . 'index.html', $html);
                }
            }
        }
    }
}
