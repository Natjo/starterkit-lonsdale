<?php
/*
Template Name: Page Homepage
*/

get_header();
get_template_part('template-parts/general/block', 'header_nav');

$pageID =  get_the_ID();

?>

<main id="main" role="main" tabindex="-1" class="page-homepage">

    <?php

    $cta = get_field('hero-homepage-cta',  $pageID);
    $title = get_field('hero-homepage-title',  $pageID);
    $args = [
        'title' => strip_tags($title, '<strong>'),
        'intro' => get_field('hero-homepage-intro', $pageID),
        'cta' => [
            'label' => $cta['title'],
            'link' => $cta['url'],
        ],
        'images' =>  [
            'desktop' => lsd_get_thumb(get_field('hero-homepage-img-desktop',  $pageID), 'full'),
            'tablet' => lsd_get_thumb(get_field('hero-homepage-img-tablet',  $pageID), 'full'),
            'mobile' => lsd_get_thumb(get_field('hero-homepage-img-mobile',  $pageID), 'full'),
            'width' => '1000',
            'height' => '700'
        ]
    ];

    get_template_part('template-parts/heros/hero', 'homepage', $args);
    ?>

    <?php get_template_part('template-parts/general/block', 'strates');
    ?>
</main>

<?php
get_footer();
