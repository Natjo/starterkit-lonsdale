<?php
/*
Template Name: Actualites
*/

get_header();
get_template_part('template-parts/general/block', 'header_nav');

$currentSearch = -1;
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$postsPerPage = get_option('posts_per_page');
$argsPosts = getSearchCptNews(["s" => $currentSearch], $paged, $postsPerPage);
?>

<main id="main" role="main" tabindex="-1" class="page-actualities">
    <?php get_template_part('template-parts/general/block', 'breadcrumb'); ?>

    <?php
    $args['title'] =  get_the_title();
    get_template_part('template-parts/heros/hero', 'page', $args);
    ?>

    <section>
        <div class="container">
            <ul class="list">
                <?php if (!empty($argsPosts['items'])) : ?>
                    <?php foreach ($argsPosts['items'] as $item) : ?>
                        <?php get_template_part('template-parts/cards/card', "news", $item); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <?php pager($argsPosts['pager'], "/" . get_post_field('post_name') . "/page/"); ?>
        </div>
    </section>
</main>

<?php
get_footer();
