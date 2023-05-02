<?php

global $wpdb;
global $table_prefix;
$nonce = wp_create_nonce('test_nonce');
//$isStatic  = (file_exists(WP_CONTENT_DIR . '/static/' . locale()))  ? 1 : 0;


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
        Update gitigore;<br>
        web/wp-content/easy-static/<br>
 
    </div>

    <div>
        TODO<br>
        add condition in index.php if not exist (maj wp)<br>
        add htaccess cache files<br>
        auto save uptodate when changing post<br>

    </div>

    <br>
    <div>
        <input class="switch" type="checkbox" id="plug-static-toggle-status" <?php if ($isStatic) echo 'checked' ?>>
        <label for="plug-static-toggle-status"><span></span></label>
    </div>

    <style>
        .switch {
            display: none !important;
        }

        .switch+label {
            display: inline-flex;
            align-items: center;
        }

        .switch:not(:checked)+label {}

        .switch+label span {
            background-color: #fff;
            display: flex;
            align-items: center;
            width: 60px;
            height: 30px;
            border-radius: 15px;
            border: 1px solid #ccc;
            margin-right: 10px;
            padding: 2px;
            box-sizing: border-box;
        }

        .switch+label span:before {
            content: '';
            display: block;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background-color: #ccc;

        }

        .switch:checked+label span:before {
            margin-left: auto;
            background-color: #2271b1;

        }

        .switch+label:after {
            content: 'Mode static inactive';
        }

        .switch:checked+label:after {
            content: 'Mode static active';
        }
    </style>

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



<script>
    const pages_result = document.getElementById('plug-static-pages');
    const btn_generate = document.querySelector('.plug-static-btn-generate');

    const toogle_status = document.getElementById("plug-static-toggle-status");
    toogle_status.checked = Boolean(<?= $isStatic ? true : false; ?>);

    btn_generate.onclick = () => {
        document.getElementById('pages').classList.add('disabled');
        
        btn_generate.classList.add('loading');
        const data = new FormData();
        data.append('action', "test");
        data.append('nonce', '<?= $nonce ?>');
        data.append('status', toogle_status.checked);
        const xhr = new XMLHttpRequest();
        xhr.open("post", '<?= AJAX_URL ?>', true);
        xhr.send(data);
        xhr.onload = () => {
            btn_generate.classList.remove('loading');
            document.getElementById('pages').classList.remove('disabled');
            const response = JSON.parse(xhr.responseText);
            pages_result.innerHTML = response.markup;
        }
    }

    // posts is active
    const checkbox_static_active = pages_result.querySelectorAll(".checkbox-static_active");
    checkbox_static_active.forEach((el) => {
        el.onchange = () => {

            if (!el.checked) {
                el.value = 0;
            } else {
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

    // Switch mode 
    if (toogle_status.checked) {
        document.getElementById('pages').classList.remove('disabled');
    } else {
        document.getElementById('pages').classList.add('disabled');
    }

    toogle_status.onchange = () => {
        const data = new FormData();

        if (toogle_status.checked) {
            document.getElementById('pages').classList.remove('disabled');
        } else {
            document.getElementById('pages').classList.add('disabled');
        }

        data.append('action', "static_change_status");
        data.append('nonce', '<?= $nonce ?>');
        data.append('status', toogle_status.checked);
        const xhr = new XMLHttpRequest();
        xhr.open("post", '<?= AJAX_URL ?>', true);
        xhr.send(data);
        xhr.onload = () => {
            btn_generate.disabled = false;
            toogle_status.disabled = false;

        }


        /* btn_generate.disabled = true;
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
         }*/
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