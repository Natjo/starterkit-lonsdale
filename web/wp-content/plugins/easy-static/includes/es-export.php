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


<script>
    const relative = document.getElementById('es-relative');
    relative.oninput = (e) => {

        /* let value = relative.innerText;
         if (value.charAt(0) === '/') {
             value = value.substring(1)
         }
         if (value.charAt(value.length - 1) === "/") {
             value = value.slice(0, -1)
         }
         relative.innerText = value;
         if (relative.innerText.length > 1) {
             document.querySelector('.es-action').classList.remove('disabled');

         } else {
             document.querySelector('.es-action').classList.add('disabled');
         }*/
    }

    relative.addEventListener('keypress', (e) => {
        //if (e.which === 47)  e.preventDefault();
        if (e.which === 13) e.preventDefault();
    });
    relative.onblur = () => {
        if (relative.innerText.length > 1) {
            const data = new FormData();
            data.append('action', "static_export_slug");
            data.append('nonce', '<?= $nonce ?>');
            data.append('slug', relative.innerText);
            const xhr = new XMLHttpRequest();
            xhr.open("post", '<?= AJAX_URL ?>', true);
            xhr.send(data);
            xhr.onload = () => {}
            document.querySelector('.es-action').classList.remove('disabled');

        } else {
            document.querySelector('.es-action').classList.add('disabled');
        }
    }
</script>

<script>
    // generate pages
    const btn_download_pages = document.getElementById('es-download-pages');
    btn_download_pages.onclick = () => {
        btn_download_pages.classList.add('loading');
        const data = new FormData();
        data.append('action', "static_export_pages");
        data.append('nonce', '<?= $nonce ?>');
        data.append('slug', relative.innerText);
        const xhr = new XMLHttpRequest();
        xhr.open("post", '<?= AJAX_URL ?>', true);
        xhr.send(data);
        xhr.onload = () => {
            btn_download_pages.classList.remove('loading');
        }
    }

    // zip all
    const btn_zip_uploads = document.getElementById('es-zip-uploads');
    const link_download_uploads = document.getElementById('es-download-uploads');
    btn_zip_uploads.onclick = () => {
        btn_zip_uploads.classList.add('loading');
        const data = new FormData();
        data.append('action', "static_export_download_uploads");
        data.append('nonce', '<?= $nonce ?>');
        const xhr = new XMLHttpRequest();
        xhr.open("post", '<?= AJAX_URL ?>', true);
        xhr.send(data);
        xhr.onload = () => {
            const response = JSON.parse(xhr.response);
            link_download_uploads.href = window.location.origin + "/wp-content/easy-static/export.zip";
            link_download_uploads.dowload = "export";
            link_download_uploads.style.display = "inline";
            btn_zip_uploads.classList.remove('loading');
        }
    }
    link_download_uploads.addEventListener('click', () => {
        setTimeout(() => {
            link_download_uploads.style.display = "none";
        }, 300);
    });

    // remov ezip
    const btn_zip_remove = document.getElementById('es-zip-remove');
    btn_zip_remove.onclick = () => {
        btn_zip_remove.classList.add('loading');
        const data = new FormData();
        data.append('action', "static_export_download_remove");
        data.append('nonce', '<?= $nonce ?>');
        const xhr = new XMLHttpRequest();
        xhr.open("post", '<?= AJAX_URL ?>', true);
        xhr.send(data);
        xhr.onload = () => {
            btn_zip_remove.classList.remove('loading');
        }
    }
</script>


<style>
    .fake-input {
        display: inline-block;
        padding-left: 5px;
        padding-right: 5px;
        background-color: rgba(255, 255, 255, .5);
        white-space: nowrap;
        margin-left: 2px;
        margin-right: 2px;
    }

    .es-action.disabled {
        pointer-events: none;
        opacity: .6;
    }

    .es-link-upload {
        display: none;
    }

    .es-btn.loading {
        opacity: .5;
        pointer-events: none;
        position: relative;

    }

    @keyframes es_rotate {
        from {
            transform: translate(-50%, -50%) rotate(0);
        }

        to {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    .es-btn.loading:before {
        display: block;
        position: absolute;
        left: 50%;
        top: 50%;
        animation: es_rotate linear 1s infinite;
    }

    .es-btn.loading span {
        opacity: .4;
    }

    .es-btn:before {
        display: none;
        content: "\f463";
        font-family: dashicons;
        line-height: 1;
        font-weight: 400;
        font-style: normal;
        text-transform: none;
        text-rendering: auto;
        -moz-osx-font-smoothing: grayscale;
        font-size: 20px;
        text-align: center;
    }
</style>