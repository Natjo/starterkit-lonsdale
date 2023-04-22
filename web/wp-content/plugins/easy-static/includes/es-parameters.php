<section id="parameters" class="tab-content">

    <header>

        <h2>local host</h2>
        <p>
            En local, mettre le vhost de la machine virtuelle.<br>
            En ligne, mettre l'url (default)
        </p>
        <div style="
background: #fff;
border: 1px solid #c3c4c7;
border-left-color: rgb(195, 196, 199);
border-left-width: 1px;
border-left-width: 4px;
box-shadow: 0 1px 1px rgba(0,0,0,.04);
margin: 5px 0 2px;
padding: 1px 12px;
">
            <u>Pour récuperer le vhost dans docker :</u><br>
            docker exec -it starterkit-lonsdale-nginx bash<br>
            cat /etc/hosts
        </div>
        <br>
        <input type="text" id="es-host" value="<?= $host ?>" style="width: 300px"><br>

        <br>

        <!--
A METTRE DANS web/index.php
// load static if exist or if no generate var available
if(empty($_GET['generate'])){
if (file_exists(__DIR__ . '/wp-content/static/' . $_SERVER['REQUEST_URI'] . '/index.html')) {
echo file_get_contents(__DIR__ . '/wp-content/static/' . $_SERVER['REQUEST_URI'] . '/index.html');
exit;
}
} -->

</header>


    <h2>Cpts</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>Slug</th>
                <th>has pagination</th>
                <th>Static</th>
                <th>post per page</th>

            </tr>
        </thead>
        <tbody>
            <?php

            // static_active
            $post_types = postTypes();
            foreach ($post_types as $post_type) :
                $args = array(
                    'post_type' => $post_type,
                    'posts_per_page' => -1,
                    'order' => 'ID',
                    'orderby' => 'title',
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => 1,
                );
                $queryArticles = new WP_Query($args);
                $post_type_object = get_post_type_object($post_type);
            ?>
                <tr>
                    <td>news</td>
                    <td><?= $post_type_object->rewrite['slug'] ?></td>
                    <td><?= $post_type_object->has_pagination ? "oui" : "non" ?></td>
                    <td>oui</td>
                    <td><?= $post_type_object->posts_per_page ?></td>
                </tr>
            <?php endforeach; ?>

        </tbody>

    </table>
</section>