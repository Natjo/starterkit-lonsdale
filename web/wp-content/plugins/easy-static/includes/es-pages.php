
<section id="pages" class="tab-content">
    <header>
        <button class="es-btn plug-static-btn-generate"><span>Generate pages</span></button> 
        <?php if(!empty($last_generate)) : ?>
        <span class="es-last_generated"> Last generated : <?= date("j F Y h:m", strtotime($last_generate)) ?></span>
        <?php endif; ?>
    </header>
    <section>
        <header>
            <h2>Pages</h2>
        </header>

        <div class="list-pages">
            <ul class="list-pages-header">
                <li>Name</li>
                <li>Slug</li>
                <li>Static</li>
                <li>Up to date</li>
            </ul>
            <div class="list-pages-wrapper">
                <?php listPages(); ?>
            </div>
        </div>


        <!--   <table id="pages-list" class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Type</th>
                    <th>Static</th>
                    <th class="table-col-sm">Up to date</th> 
                </tr>
            </thead>
            <tbody id="plug-static-pages">
                <?= display(); ?>
            </tbody>
        </table> -->
    </section>

    <br>

    <section>
        <header>
            <h2>Cpts</h2>
        </header>

        <?php
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
            <div class="es-cpt-infos">
                <h2><?= $post_type_object->label ?></h2>
                <ul>
                    <li><b>Type:</b> <?= $post_type_object->name ?></li>
                    <li><b>Slug:</b> <?= $post_type_object->rewrite['slug'] ?></li>
                    <li><b>Has pagination:</b> <?= $post_type_object->has_pagination ? "oui" : "non" ?></li>
                    <li><b>Post per page:</b> <?= $post_type_object->posts_per_page ?></li>
                    <li><b>Pagination folder:</b> <?= $post_type_object->pagination_folder ?></li>
                </ul>
            </div>
            <table id="pages-list" class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th></th>
                        <th>Up to date</th>
                   
                    </tr>
                </thead>

                <tbody id="plug-static-pages">
                    <?= displayCpts(); ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </section>

    <br>

    <section>
        <header>
            <h2>Articles</h2>
            <p>No article</p>
        </header>
    </section>

</section>