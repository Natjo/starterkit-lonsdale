<section id="pages" class="tab-content">
    <header>

        <button class="es-btn plug-static-btn-generate"><span>Generate pages</span></button>
    </header>

    <br>
    <section>
        <header>
            <h2>Pages</h2>
        </header>
        <table id="pages-list" class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Type</th>
                    <th>Static</th>
                    <!-- <th class="table-col-sm">Up to date</th> -->
                </tr>
            </thead>
            <tbody id="plug-static-pages">
                <?= display(); ?>
            </tbody>
        </table>
    </section>

    <br>

    <section>
        <header>
            <h2>Cpts</h2>
        </header>

        <div class="es-cpt-infos">
            <h2>Actualites</h2>
            <ul>
                <li><b>Type:</b> news</li>
                <li><b>Slug:</b> actualites</li>
                <li><b>Has pagination:</b> oui</li>
                <li><b>Post per page:</b> 2</li>
                <li><b>Pagination folder:</b> page</li>
            </ul>
        </div>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>

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

                <?php endforeach; ?>

            </tbody>

        </table>
    </section>
    
    <br>

    <section>
        <header>
            <h2>Articles</h2>
        </header>
    </section>
    
    <?php

    function listPages()
    {
        $cpts_slug = array();
        $args = array(
            'public'   => true,
            '_builtin' => false,
        );
        $output = 'objects'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        $post_types = get_post_types($args, $output, $operator);

        foreach ($post_types as $key => $post) {
            //  print_r($post_types);
            // echo $post->rewrite["slug"];

            array_push($cpts_slug, $post->rewrite["slug"]);
            // array_push($cpts_slug, $post->name);
            // echo $post;
            //  array_push($cpts_slug, $post);
        }

        // Display cpts pages
        function cptsPages($cpts_slug, $name)
        {
            $html = "";
            foreach ($cpts_slug  as $cpt) {
                if ($name === $cpt) {
                    $args = array(
                        'post_type' => "news",
                        'posts_per_page' => -1,
                        'order' => 'DESC',
                        'orderby' => 'modified',
                        'post_status' => 'publish'
                    );
                    $posts = new WP_Query($args);
                    wp_reset_postdata();

                    foreach ($posts->posts as $post) {
                        $html .= '
                        <div class="list-pages-row">
                        <div class="list-pages-link">-<a target="_blank" href="/' . locale() . $cpt . "/" . $post->post_name . '">' . $post->post_title . '</a></div>
                        <div class="list-pages-url">' . locale() . $cpt . "/" . $post->post_name . '</div>
                        <div class="list-pages-type">' . $post->post_type . '</div>
                        <div class="list-pages-active "><input type="checkbox"></div>
                        </div>';
                    }
                }
            }

            return $html;
        }

        $args = array(
            'post_type' => "page",
            'posts_per_page' => -1,
            'order' => 'DESC',
            'orderby' => 'modified',
            'post_status' => 'publish'
        );
        $posts = new WP_Query($args);
        wp_reset_postdata();

        foreach ($posts->posts as $post) {
            //   print_r($post);
            $child = cptsPages($cpts_slug, $post->post_name);

            if ($child !== "") {
                echo '
             <details open class="list-pages-row list-pages-item">
             <summary class="list-pages-row">
             <div class="list-pages-link"><a target="_blank" href="/' . locale() . $post->post_name . '">' . $post->post_title . '</a></div>
             <div class="list-pages-url">' . locale() . $post->post_name . '</div>
             <div class="list-pages-type">' . $post->post_type . '</div>
             <div class="list-pages-active "><input type="checkbox"></div>
             </summary>' . $child . '</details>';
            } else {
                echo '
            <div  class="list-pages-row list-pages-item">
            <div class="list-pages-link"><a target="_blank" href="/' . locale() . $post->post_name . '">' . $post->post_title . '</a></div>
            <div class="list-pages-url">' . locale() . $post->post_name . '</div>
            <div class="list-pages-type">' . $post->post_type . '</div>
            <div class="list-pages-active "><input type="checkbox"></div>
            </div>';
            }
        }
    }

    echo "<br><br>";

    ?>

    <div class="list-pages">
        <?php listPages(); ?>
    </div>

</section>