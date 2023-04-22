<?php

global $wpdb;
$nonce = wp_create_nonce('test_nonce');
$isStatic  = (file_exists(WP_CONTENT_DIR . '/static/'))  ? 1 : 0;

// create column (static_active,static_generate) in wp_posts
$table_posts = $wpdb->prefix . 'posts';
$table_posts_rows = $wpdb->get_row("SELECT * FROM " . $table_posts);
if (!isset($table_posts_rows->static_active)) {
    $wpdb->query("ALTER TABLE wp_posts ADD static_active  BOOLEAN DEFAULT 1");
}
if (!isset($table_posts_rows->static_generate)) {
    $wpdb->query("ALTER TABLE wp_posts ADD static_generate timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL");
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

<div class="wrap">
    <h1>Static website</h1>

    <div>
        TODO<br>
        display last generate<br>
        add ondition in index.php if not exist (maj wp)<br>
        langues<br>
        export static<br>
    </div>

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



<script>
    const pages_result = document.getElementById('plug-static-pages');
    const btn_generate = document.querySelector('.plug-static-btn-generate');
    const btns_regenerate = document.querySelectorAll('.btn-regenerate');
    const toogle_status = document.getElementById("plug-static-toggle-status");
    toogle_status.checked = Boolean(<?= $isStatic ? true : false; ?>);

    btn_generate.onclick = () => {
        btn_generate.disabled = true;
        const data = new FormData();
        data.append('action', "test");
        data.append('nonce', '<?= $nonce ?>');
        data.append('status', toogle_status.checked);
        const xhr = new XMLHttpRequest();
        xhr.open("post", '<?= AJAX_URL ?>', true);
        xhr.send(data);
        xhr.onload = () => {
            btn_generate.disabled = false;
            const response = JSON.parse(xhr.responseText);
            pages_result.innerHTML = response.markup;
        }
    }

    // posts is active
    const checkbox_static_active = pages_result.querySelectorAll(".checkbox-static_active");
    checkbox_static_active.forEach((el) => {
        el.onchange = () => {

            if (!el.checked) {
                el.parentNode.parentNode.querySelector('.btn-regenerate').setAttribute('disabled', true);
                el.value = 0;
            } else {
                el.parentNode.parentNode.querySelector('.btn-regenerate').removeAttribute('disabled');
                el.parentNode.parentNode.querySelector('.info-update').classList.remove('error');
                el.value = 1;
            }

            const data = new FormData();
            data.append('action', "static_posts_his_active");
            data.append('nonce', '<?= $nonce ?>');
            data.append('id', el.id);
            data.append('slug', el.dataset.slug);
            data.append('status', el.checked);
            const xhr = new XMLHttpRequest();
            xhr.open("post", '<?= AJAX_URL ?>', true);
            xhr.send(data);
            xhr.onload = () => {}
        }
    })
// regenerate
btns_regenerate.forEach(btn => {
    btn.onclick = () => {
        alert(btn.dataset.slug);
    }
});

    // status
    toogle_status.onchange = () => {
        btn_generate.disabled = true;
        toogle_status.disabled = true;
        const data = new FormData();
        data.append('action', "static_change_status");
        data.append('nonce', '<?= $nonce ?>');
        data.append('status', toogle_status.checked);
        const xhr = new XMLHttpRequest();
        xhr.open("post", '<?= AJAX_URL ?>', true);
        xhr.send(data);
        xhr.onload = () => {
            btn_generate.disabled = false;
            toogle_status.disabled = false;
            const response = JSON.parse(xhr.responseText);
            pages_result.innerHTML = response.markup;
        }
    }


    //
    const input_host = document.getElementById("es-host");
    input_host.onchange = () => {
        const data = new FormData();
        data.append('action', "static_change_host");
        data.append('nonce', '<?= $nonce ?>');
        data.append('host', input_host.value);
        const xhr = new XMLHttpRequest();
        xhr.open("post", '<?= AJAX_URL ?>', true);
        xhr.send(data);
        xhr.onload = () => {}
    }

    // tabs
    const tab_links = document.querySelectorAll('.nav-tab-wrapper .nav-tab');
    const tab_content = document.querySelectorAll('.tab-content');

    tab_content.forEach((tab, i) => {
        tab.style.display = tab.id === 'pages' ? 'block' : 'none'
    })

    tab_links.forEach(link => {
        link.onclick = e => {
            e.preventDefault();

            tab_links.forEach(aa => {
                if (aa === link)
                    aa.classList.add('nav-tab-active');
                else aa.classList.remove('nav-tab-active');
            })

            const id = link.getAttribute('href');
            tab_content.forEach(tab => {
                tab.style.display = '#' + tab.id === id ? 'block' : 'none'
            })
        }
    })
</script>