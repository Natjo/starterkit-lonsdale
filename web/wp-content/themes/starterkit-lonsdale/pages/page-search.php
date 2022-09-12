<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 */

get_header();
get_template_part('template-parts/general/block', 'header_nav');

$card_tpl = "news";

$currentSearch = get_query_var('s');
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$postsPerPage = get_option('posts_per_page');
$argsPosts = getSearchCptNews(["s" => $currentSearch], $paged, $postsPerPage);

$totalPosts = $argsPosts['pager']['total_posts'];

?>

<main id="main" role="main" tabindex="-1" class="page-search">
    <header>
        <div class="container">

            <br><br><br><br><br>
            <form id="search" method="post" action="/">
                <input type="text" name="s">
            </form>
            <br><br><br>

            <?php if ($totalPosts > -1) : ?>
                <?php
                $search = "« " . htmlspecialchars($currentSearch) . " »";
                $plurial = $totalPosts > 1 ? "s" : "";
                if ($totalPosts) $title =  $totalPosts . " résultat" . $plurial . " correspondent à votre recherche " . $search;
                else $title = "Aucun article correspond à votre recherche " . $search;
                ?>
                <h1><?= $title; ?></h1>
            <?php else : ?>
                <div> Merci de saisire une recherche</div>
            <?php endif; ?>
        </div>
    </header>

    <section>
        <div class="container">
            <ul>
                <?php if (!empty($argsPosts['items'])) : ?>
                    <?php foreach ($argsPosts['items'] as $item) : ?>
                        <?php get_template_part('template-parts/cards/card', $card_tpl, $item); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <?php pager($argsPosts['pager'], "/" . "?s=" . htmlspecialchars($currentSearch) . "&paged="); ?>
        </div>
    </section>
</main>
<?php
get_footer();
