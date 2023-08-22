<?php

global $host;
$easy_static_slug = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 'options' . " WHERE option_name = 'easy_static_slug'");
$nonce = wp_create_nonce('test_nonce');

?>

<section id="export" class="tab-content">

    <h2>1: Relative Url</h2>
    <p>Url from website root, <small>(pas mettre de / au début et à la fin)</small></p>
    <span style="opacity: .6;">https://www.mywebsite.com/</span>
    <p class="fake-input" contenteditable="true" id="es-relative" translate="no"><?= $easy_static_slug[0]->option_value ?></p><span style="opacity: .6;">/</span>

    <br>

    <h3>2: Générer les pages</h3>
    <p>If faut regénérer les pages si changement d'url relative.<br>Il faut généerer les autres langues indépendamment</p>
    <button class="es-btn" id="es-download-pages"><span>Générer les pages <?= "(" . apply_filters('wpml_current_language', NULL) . ")"; ?></span></button>


    <br>

    <h3>3: Export</h3>
    <p>S'assurer que toutes les pages de toutes les langues soient générées</p>
    <ul>
        <li><button class="es-btn" id="es-zip-uploads"><span>Export all</span></button>
            <a class="es-link-upload" id="es-download-uploads" href="" download>Download export</a>
        </li>
        <li> <button class="es-btn" id="es-zip-uploads-light"><span>Export without upload</span></button></li>
    </ul>

    <br>

    <h3>4: remove zip file from serveur</h3>
    <button class="es-btn" id="es-zip-remove"><span>Remove zip</span></button>


    <!--  <br><br>
    <hr>
    <br><br>
    <h3>Dossier static</h3>
    <p>Il doit être organisé de la façon suivante :</p>
    <ul>

        <li>assets/</li>
        <li>uploads/</li>
        <?php

        $languages = apply_filters('wpml_active_languages', NULL);
        if (!empty($languages)) {
            foreach ($languages as $key => $lang) {
                echo '<li>' . $key . '/</li>';
            }
        } else {
            echo '<i style="opacity:.6;">Pages content</i>';
        }
        ?>
    </ul> -->


</section>
