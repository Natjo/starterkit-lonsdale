<section id="parameters" class="tab-content">

    <section>
        <header>
            <h2>Host</h2>

            <p class="desc">
                En local, vhost de la machine virtuelle: <b><?= $_SERVER['SERVER_ADDR']; ?></b><br>
                Preprod/prod: <b><?= $_SERVER['SERVER_NAME']; ?></b><br>
            </p>

        </header>

        <input type="text" id="es-host" value="<?= $host ?>" style="width: 300px"><br>
    </section>

    <hr>

    <section>
        <header>
            <h2>Htaccess preprod</h2>

        </header>

        <?php
        $user = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE option = 'user'");
        $password = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE option = 'password'");
        ?>

        <div class="es-auth">
            <div>
                <label for="">User</label>
                <input type="text" id="es-auth-user" value="<?= $user[0]->value ?>">
            </div>

            <div>
                <label for="">Password</label>
                <input type="password" id="es-auth-password" value="<?= $password[0]->value ?>">
            </div>
        </div>
    </section>

    <hr>

    <section>
        <header>
            <h2>Options</h2>
        </header>

        <?php

        ?>
        <ul>
            <li><input id="es-option-minify" type="checkbox" <?= $isminify === true ? "checked" : "" ?>><label>Compresser les pages générées</label></li>
            <li><input id="es-option-localisfolder" type="checkbox" <?= $localisfolder[0]->value === "true" ? "checked" : "" ?>><label>Multilangue local (default) est un dossier</label></li>
        </ul>
    </section>

    <hr>

    <section>
        <header>
            <h2>Cpts</h2>
        </header>

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
</section>