<?php
function create_cpt()
{
    // Actualités - news
    $labelsNews = array(
        'name' => 'Actualités',
        'singular_name' => 'Actualités',
        'menu_name' => 'Actualités',
        'name_admin_bar' => 'Actualités',
        'all_items' => 'Toutes les actualités',
        'add_new_item' => 'Ajouter une nouvelle actualité',
        'add_new' => 'Ajouter',
        'new_item' => 'Nouvelle actualité',
        'edit_item' => 'Editer l\'actualité',
        'update_item' => 'Mettre à jour l\'actualité',
        'view_item' => 'Voir l\'actualité',
        'view_items' => 'Voir les actualités',
        'search_items' => 'Rechercher une actualité',
        'not_found' => 'Non trouvé',
        'not_found_in_trash' => 'Non trouvé dans la corbeille',
        'featured_image' => 'Image',
        'set_featured_image' => 'Mettre une image',
        'remove_featured_image' => 'Retirer l\'image',
        'use_featured_image' => 'Utiliser cette image',
    );

    $argsNews = array(
        'labels' => $labelsNews,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => array('title', 'thumbnail'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 2,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => false,
        'has_pagination' => true,
        'pagination_folder' => "page",//see function url rewrite
        'posts_per_page' => get_option('posts_per_page'),
        'hierarchical' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'taxonomies' => array(''),
        'rewrite' => array(
            'slug' => 'actualites',
            'with_front' => true,
        ),
    );
    register_post_type('news', $argsNews);

}
add_action( 'init', 'create_cpt', 0 );


function create_taxo() {

    $labelsTest = array(
        'name'                       => 'Catégories',
        'singular_name'              => 'Catégories',
        'search_items'               => 'Rechercher des catégories',
        'popular_items'              => 'Catégories populaires',
        'all_items'                  => 'Tous les catégories',
        'edit_item'                  => 'Modifier la catégorie',
        'update_item'                => 'Mettre à jour la catégorie',
        'add_new_item'               => 'Ajouter une categorie',
        'not_found'                  => 'aucune categorie trouvée',
        'menu_name'                  => 'Catégories',
    );
    $argsTest = array(
        'hierarchical'          => true,
        'labels'                => $labelsTest,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'query_var'             => false,
        'show_in_rest' => true,
    );
    register_taxonomy( 'Catégories', 'news', $argsTest );
}
add_action( 'init', 'create_taxo', 0 );
