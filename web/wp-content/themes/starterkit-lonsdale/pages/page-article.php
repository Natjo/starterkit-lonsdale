<?php

get_header();
get_template_part('template-parts/general/block', 'header_nav');
?>

<main id="main" role="main" tabindex="-1" class="page-article">
    <?php get_template_part('template-parts/general/block', 'breadcrumb'); ?>

    <article>
        <?php
        $args['title'] = get_the_title();
        $args['date'] = get_the_date('d.m.Y');
        $args['categories'] = lsd_get_the_terms_name($post->ID, 'Catégories');
        $args['images'] = array(
            'desktop' => lsd_get_featured($post->ID, 'medium'),
            'width' => '590',
            'height' => '491'
        );
        get_template_part('template-parts/heros/hero', 'article', $args);
        ?>

        <?php get_template_part('template-parts/general/block', 'strates'); ?>
    </article>
</main>

<?php
get_footer();
