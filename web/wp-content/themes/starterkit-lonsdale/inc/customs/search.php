<?php

function getSearchCptNews($filters, $paged = 1, $itemPerPage = -1,  $tax_query = null)
{
    if (!empty($filters['s'])) {
        $args = array(
            'post_type' => 'news',
            'post_status' => 'publish',
            'posts_per_page' => $itemPerPage,
            'paged' => $paged,
            'orderby' => 'date',
            'order' => 'DESC',
            's' => $filters['s'] === -1 ? null : $filters['s'],
            'tax_query' => array($tax_query),
        );
        $queryArticles = new WP_Query($args);

        $items = [];
        if ($queryArticles->have_posts()) {
            while ($queryArticles->have_posts()) {
                $queryArticles->the_post();
                $rowId = get_the_ID();
                $terms =  lsd_get_the_terms_name($rowId, 'Catégories');
                $items[] = [
                    'is_h2' => true,
                    'title' => get_the_title(),
                    'date' => get_the_date('d.m.Y'),
                    'tag' => !empty($terms) ? $terms[0] : "",
                    'text' =>  get_field('cpt-news-text', $rowId),
                    'link' =>  get_the_permalink($rowId),
                    'images' => array(
                        //'desktop' => lsd_get_thumb($rowId, 'medium'),
                        'desktop' => lsd_get_featured($rowId, 'medium'),
                        'width' => 346,
                        'height' => 288
                    )
                ];
            }
            wp_reset_postdata();
        }

        // On récupère le nombre total de résultats
        $args['posts_per_page'] = -1;
        $queryArticleCount = new WP_Query($args);
        $totalPages = ceil($queryArticleCount->post_count / $itemPerPage);
        $totalPosts = $queryArticleCount->post_count;

        $pager = [
            'current_page' => $paged,
            'total_pages' => $totalPages,
            'total_posts' => $totalPosts
        ];

        return [
            'pager' => $pager,
            'items' => $items
        ];
    } else {
        return [
            'pager' => [
                'total_posts' => -1,
                'total_pages' => -1,
                'current_page' => -1,
            ]
        ];
    }
}

/**
 * 
 * Pagination for search page / cpt archive or page
 * 
 * @param array $pager {
 *      @type number $current_page - page active
 *      @type number $total_pages - total pages
 * }
 * 
 * @param string $query set the slug url or query string 
 * 
 * 
 */

function pager($pager, $query = null)
{
    $page = $pager['current_page'];
    $total = $pager['total_pages'];
    $prev = $page - 1;
    $next = $page + 1;
    $arr = array();
    $offset = 3;
    $break = 4;

    if ($total > 1) {

        if ($total > $break + $offset + 1) {

            if ($page <= $break) {
                for ($i = 1; $i <= $offset + $break; $i++) {
                    if ($i <= $page + $offset && $i <= $total) {
                        array_push($arr, $i);
                    }
                }
            } else {
                array_push($arr, 1);
                array_push($arr, null);
            }

            if ($page > $break && $page <= $total - $break) {
                for ($i = $offset; $i < $total; $i++) {
                    if ($i <= $page + $offset  && $i >= $page - $offset) {
                        array_push($arr, $i);
                    }
                }
            }
            if ($page > $total - $break) {
                for ($i = $total - $break - $offset; $i <= $total; $i++) {
                    if ($i >= $page - $offset) {
                        array_push($arr, $i);
                    }
                }
            } else {
                array_push($arr, null);
                array_push($arr, $total);
            }
        } else {
            for ($i = 1; $i <= $total; $i++) {
                array_push($arr, $i);
            }
        }

        //
        $pictoPrev = '<div class="picto-btn-1 left">'.icon("arrow-down", 19, 19).'</div>';
        $pictoNext = '<div class="picto-btn-1 right">'.icon("arrow-down", 19, 19).'</div>';
        echo '<div class="pager">';
        echo $prev >= 1 ? '<a rel="prev" href="' . $query . $prev . '" class="btn-1 prev">'.$pictoPrev.'</a>' : '<button class="btn-1 prev disabled">'.$pictoPrev.'</button>';
        for ($i = 0; $i < count($arr); $i++) {
            $index = $arr[$i];
            $active = ($index === $page) ? ' class="active"' : '';
            echo ($index === null) ?  "<span>...</span>" :  '<a href="' . $query . $index . '"' . $active . '>' . $index . '</a>';
        }
        echo $next <= $total ? '<a rel="next" href="' . $query  . $next . '" class="btn-1 next">'.$pictoNext.'</a>' : '<button class="btn-1 next disabled">'.$pictoNext.'</button>';
        echo '</div>';
    }
}
