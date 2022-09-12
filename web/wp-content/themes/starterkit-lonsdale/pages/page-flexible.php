<?php
/*
Template Name: Page flexible
*/

get_header();
get_template_part('template-parts/general/block', 'header_nav');
?>

<main id="main" role="main" tabindex="-1" class="page-flexible">
    <?php get_template_part('template-parts/general/block', 'breadcrumb'); ?>

    <?php
        $args = [
            'title' => get_field('hero-page_flexible-title', get_the_ID()),
            'color-scroll' => get_field('hero-page_flexible-color-scroll', get_the_ID()),
            'theme' => get_field('hero-page_flexible-theme', get_the_ID()),
            'image' => lsd_get_thumb(get_field('hero-page_flexible-img', get_the_ID()), 'medium'),
        ];
    ?>
    
    <?php get_template_part('template-parts/heros/hero', 'flexible', $args); ?>

    <?php get_template_part('template-parts/general/block', 'strates'); ?>
</main>

<?php
get_footer();
