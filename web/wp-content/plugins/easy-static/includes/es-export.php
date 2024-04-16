<?php


global $table;

$easy_static_slug = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE option = 'slug'");
$nonce = wp_create_nonce('test_nonce');

?>

<section id="export" class="tab-content">

    <section>
        <header>
            <h2>1: Url finale</h2>
        </header>

        <div style="display: flex">
            <span style="">https://www.mywebsite.com/</span>
            <p class="fake-input" contenteditable="true" id="es-relative" translate="no"><?= $easy_static_slug[0]->value ?></p><span style="opacity: .6;"><span class="es-notroot">/</span></span>
        </div>
    </section>

    <hr>

    <section>
        <header>
            <h2>2: Exporter le site static</h2>
        </header>

        <button class="es-btn" id="es-download-pages"><span>Exporter</span></button>

        &nbsp; &nbsp;<a class="es-link-upload" id="es-download-uploads" href="" download>Download export</a>
    </section>


    <hr>
    <section>
        <header>
            <h2>3: Effacer le zip pour libérer l'espace mémoire</h2>
        </header>

        <button class="es-btn" id="es-zip-remove"><span>Remove</span></button>
    </section>






</section>