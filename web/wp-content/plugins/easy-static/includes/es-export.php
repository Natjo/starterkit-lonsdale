<?php

global $host;
global $table;

$easy_static_slug = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE option = 'slug'");
$nonce = wp_create_nonce('test_nonce');

?>

<section id="export" class="tab-content">
    <input type="checkbox"><label>Ajouter htaccess pour la mise en caches des fichiers</label>
    
    <br><br>

    <section>
        <header>
            <h2>1: Relative Url</h2>
            <small>(pas mettre de / au début et à la fin)</small></p>
        </header>
        <div style="display: flex">
            <span style="opacity: .6;">https://www.mywebsite.com/</span>
            <p class="fake-input" contenteditable="true" id="es-relative" translate="no"><?= $easy_static_slug[0]->value ?></p><span style="opacity: .6;"><span class="es-notroot">/</span></span>
        </div>
    </section>

    <hr>

    <section>
        <header>
            <h2>2: Générer les pages</h2>
            <p>Il faut généerer chaque langues indépendamment</p>
        </header>
        <?php if (defined("ICL_LANGUAGE_CODE")) : ?>
            <button class="es-btn" id="es-download-pages"><span>Générer les pages <?= "(" . apply_filters('wpml_current_language', NULL) . ")"; ?></span></button>
        <?php else : ?>
            <button class="es-btn" id="es-download-pages"><span>Générer les pages</span></button>

        <?php endif; ?>
    </section>

    <hr>

    <section>
        <header>
            <h2>3: Export</h2>
            <p>S'assurer que toutes les pages de toutes les langues soient générées</p>
        </header>
        <ul style="display: flex;gap: 10px;">
            <li><button class="es-btn" id="es-zip-uploads"><span>Export all</span></button>
               
            </li>
            <li> <button class="es-btn" id="es-zip-no_upload"><span>Export without upload</span></button>
        </li>
        </ul> 
        <a class="es-link-upload" id="es-download-uploads" href="" download>Download export</a>
    </section>

    <hr>

    <section>
        <header>
            <h2>4: remove zip file from serveur</h2>
            <p>Une fois télécharger vous pouvez effacé le fichier compressé pour libérer l'espace mémoire</p>
        </header>
        <button class="es-btn" id="es-zip-remove"><span>Remove zip</span></button>
    </section>

</section>