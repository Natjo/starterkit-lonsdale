<?php

/************************************
|	Custom Breadcrumb liste
 ************************************/
function custom_breadcrumb()
{
    global $wp_query;

    $i = 1;

    /************************************
    |	Lien accueil toujour visible
     ************************************/
    echo (custom_breadcrumb_li('/', 'Accueil', $i));
    $i++;

    /************************************
    |	Pour les pages
     ************************************/
    if (is_page()) {
        // Page parente
        $pages_parent = array_reverse(get_post_ancestors($wp_query->queried_object->ID));
        foreach ($pages_parent as $k => $v) {
            $link     = get_the_permalink($v);
            $title    = get_the_title($v);
            echo (custom_breadcrumb_li($link, $title, $i));
            $i++;
        }

        // Page courante
        $title    = get_the_title($wp_query->queried_object->ID);
        echo (custom_breadcrumb_li('', $title, $i));
        $i++;
    }

    /************************************
    |	Single
     ************************************/
    if (is_single()) {

        /************************************
        |	Articles
         ************************************/

        if (get_post_type() == 'post') {
            // Page category
            $terms = get_the_terms(get_the_id(), 'category');
            $term = reset($terms);
            $link     = get_term_link($term);
            $title    = $term->name;
            echo (custom_breadcrumb_li($link, $title, $i));
            $i++;

            // Page courante
            $title    = get_the_title($wp_query->queried_object->ID);
            echo (custom_breadcrumb_li('', $title, $i));
            $i++;
        }



        if (get_post_type() == 'news') {
            // Page category
            /*$terms = get_the_terms(get_the_id(), 'Catégories');
            $term = reset($terms);
            $link 	= get_term_link($term);
            $title	= $term->name;
            echo( custom_breadcrumb_li($link, $title, $i) );
            $i++;*/

            // Page cpt slug
            global $wp_post_types;
            $obj = $wp_post_types['news'];
            $link = "/" . $obj->rewrite['slug'];
            $post_slug = $obj->labels->singular_name;
            echo (custom_breadcrumb_li($link, $post_slug, $i));
            $i++;

            // Page courante
            $title    = get_the_title($wp_query->queried_object->ID);
            echo (custom_breadcrumb_li('', $title, $i));
            $i++;
        }
    }
}

/************************************
| Génération breadcrum custom
 ************************************/
function custom_breadcrumb_li($link = '', $title = '', $position = 1)
{
    $html = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    if ($link) :
        $html .= '<a disabled href="' . $link . '" itemprop="item"><span itemprop="name">' . $title . '</span></a>';
    else :
        $html .= '<span itemprop="item" aria-current="page">' . $title . '</span>';
    endif;
    $html .= '<meta itemprop="position" content="' . $position . '" />';
    $html .= '</li>';
    return $html;
}
