<?php

global $wpdb;
global $table_prefix;
$nonce = wp_create_nonce('test_nonce');


// create column (easy_static_active) in options
$easy_static_active = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 'options' . " WHERE option_name = 'easy_static_active'");
$isStatic = $easy_static_active[0]->option_value === "0" ? false : true;

// create column (static_active,static_generate) in posts
$table_posts = $wpdb->prefix . 'posts';
$table_posts_rows = $wpdb->get_row("SELECT * FROM " . $table_posts);
if (!isset($table_posts_rows->static_active)) {
    $wpdb->query("ALTER TABLE " . $table_prefix . "posts ADD static_active  BOOLEAN DEFAULT 1");
}
if (!isset($table_posts_rows->static_generate)) {
    $wpdb->query("ALTER TABLE " . $table_prefix . "posts ADD static_generate timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL");
}

// create easy-staic folder with gitignore
if (!is_dir(WP_CONTENT_DIR . "/easy-static/")) {
    mkdir(WP_CONTENT_DIR . "/easy-static/", 0755, true);
    $myfile = fopen(WP_CONTENT_DIR . "/easy-static/.gitignore", "w") or die("Unable to open file!");
    $txt = "*";
    fwrite($myfile, $txt);
    fclose($myfile);
}

// test if condition to switch to static exist in index.php
$index = htmlentities(file_get_contents(get_home_path()."/index.php"));
if(strpos($index, '/* easy-static */') !== false){
} else{
    $code = '<?php 
    /* easy-static */
    if(empty($_GET["generate"])){
        if (file_exists(__DIR__ . "/wp-content/easy-static/static/" . $_SERVER["REQUEST_URI"] . "/index.html")) {
            echo file_get_contents(__DIR__ . "/wp-content/easy-static/static/" . $_SERVER["REQUEST_URI"] . "/index.html");
            exit;
        }
    }
    /* end-easy-static */
?>
'; 
    $myfile = fopen(get_home_path()."/index.php", "w") or die("Unable to open file!");
    $txt = html_entity_decode($code.$index);
    fwrite($myfile, $txt);
    fclose($myfile);
}

/*
WIP
Ajoute host dans easy_Static bdd */

/*
function upToDate($posts)
{
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));


        $sql = "UPDATE wp_posts SET static_host = CURRENT_TIMESTAMP WHERE ID = " . $post->ID;
        mysqli_query($link, $sql);

    mysqli_close($link);
}*/

?>
<link rel='stylesheet' id='wp-block-library-css' href="<?= wp_guess_url() ?>/wp-content/plugins/easy-static/styles.css" media='all' />
<div class="wrap" id="es-main" data-static="<?= $isStatic ? true : false; ?>" data-nonce="<?= $nonce ?>" data-ajaxurl="<?= AJAX_URL ?>">
    <h1>Static website</h1>


    <div>
        TODO<br>
        add htaccess cache files<br>
        auto save uptodate when changing post<br>

    </div>

    <br>
    <div>
        <input class="switch" type="checkbox" id="plug-static-toggle-status" <?php if ($isStatic) echo 'checked' ?>>
        <label for="plug-static-toggle-status"><span></span></label>
    </div>


    <br>
    <br>


    <nav class="nav-tab-wrapper">
        <a href="#pages" class="nav-tab nav-tab-active">Pages</a>
        <a href="#parameters" class="nav-tab">Paramètres</a>
        <a href="#export" class="nav-tab">Export</a>
    </nav>

    <br>

    <?php include 'es-parameters.php'; ?>

    <?php include 'es-pages.php'; ?>

    <?php include 'es-export.php'; ?>
</div>

<script src="<?= wp_guess_url() ?>/wp-content/plugins/easy-static/app.js" ></script>