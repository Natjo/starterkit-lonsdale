<?php
/*
Template Name: Plan du site
*/

get_header();
get_template_part('template-parts/general/block', 'header_nav');
?>

<main id="main" role="main" tabindex="-1" class="page-sitemap">
    <?php get_template_part('template-parts/general/block', 'breadcrumb'); ?>

    <?php
    $args['title'] = "Plan du site";
    get_template_part('template-parts/heros/hero', 'page', $args);
    ?>

    <section class="strate-sitemap">
        <div class="container">
            <ul>
                <?php wp_list_pages(array(
                    'title_li' => '',
                ));
                ?>
            </ul>
        </div>
    </section>
</main>

<?php
get_footer();
