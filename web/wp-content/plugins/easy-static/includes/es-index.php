<?php
global $wpdb;
global $table_prefix;
global $table;
$nonce = wp_create_nonce('test_nonce');

// force to disable static dir if static disabled
if (!$isStatic) {
    if (is_dir(WP_CONTENT_DIR . '/easy-static/static/')) {
        rename(WP_CONTENT_DIR . '/easy-static/static/', WP_CONTENT_DIR . '/easy-static/static-disabled-/');
    }
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
$index = htmlentities(file_get_contents(get_home_path() . "/index.php"));
if (strpos($index, '/* easy-static */') !== false) {
} else {
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
    $myfile = fopen(get_home_path() . "/index.php", "w") or die("Unable to open file!");
    $txt = html_entity_decode($code . $index);
    fwrite($myfile, $txt);
    fclose($myfile);
}

?>

<link rel='stylesheet' id='wp-block-library-css' href="<?= wp_guess_url() ?>/wp-content/plugins/easy-static/styles.css" media='all' />
<div class="wrap" id="es-main" data-static="<?= $isStatic ? true : false; ?>" data-nonce="<?= $nonce ?>" data-ajaxurl="<?= AJAX_URL ?>">
    <h1>Static website</h1>

    <?php if (!empty($haschange) && !empty($isStatic)) : ?>
        <div class="es-notice notice-warning">
            <ul>
                <li><b>Des modifications nécessitent de regenerer le site.</b></li>

            </ul>
        </div>
    <?php endif; ?>


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

<script src="<?= wp_guess_url() ?>/wp-content/plugins/easy-static/app.js"></script>