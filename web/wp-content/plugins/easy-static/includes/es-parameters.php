<section id="parameters" class="tab-content">

    <header>

        <h2>Host</h2>

        <p>
            En local, vhost de la machine virtuelle: <b><?= $_SERVER['SERVER_ADDR']; ?></b><br>
            Preprod/prod: <b><?= $_SERVER['SERVER_NAME']; ?></b><br>
        </p>
        <input type="text" id="es-host" value="<?= $host ?>" style="width: 300px"><br>
        <br> <br>

        <?php if ($authentification['active'] === true) : ?>
            <h2>Htaccess</h2>
            <p>Si preprod ou recette</p>

            <div>
                <div>
                    <label for="">User</label>
                    <input type="text" id="es-auth-user" value="<?= $authentification['user'] ?>">
                </div>

                <div>
                    <label for="">Password</label>
                    <input type="password" id="es-auth-password" value="<?= $authentification['password'] ?>">
                </div>
            </div>
        <?php endif; ?>
    </header>

    <br>
    <hr>

    <h2>Options</h2>
    <input type="checkbox"><label>Compresser les pages générées</label>
    <br>
    <input type="checkbox"><label>Ajouter htaccess pour la mise en caches des fichier</label>
    <br>
    <input type="checkbox"><label>Ajouter htaccess redirection si multilangue dans folder</label>
    <br>
    <br>

    <hr>
    <hr>
    <br>
    <h2>Cpts</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>Slug</th>
                <th>has pagination</th>
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
                 
                    <td><?= $post_type_object->posts_per_page ?></td>
                </tr>
            <?php endforeach; ?>

        </tbody>

    </table>
</section>