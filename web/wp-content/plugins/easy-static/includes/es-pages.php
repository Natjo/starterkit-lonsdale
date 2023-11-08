<section id="pages" class="tab-content">
    <header>

        <button class="es-btn plug-static-btn-generate"><span>Generate pages</span></button>
    </header>

    <br>
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

    <br>

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
            echo $post->rewrite["slug"];
            array_push($cpts_slug, $post->rewrite["slug"]);
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
                        <div class="list-pages-link">-<a target="_blank" href="/' . locale() . $cpt . "/". $post->post_name . '">' . $post->post_title . '</a></div>
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