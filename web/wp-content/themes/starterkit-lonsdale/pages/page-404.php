<?php
get_header();
get_template_part('template-parts/general/block', 'header_nav');

$link = get_field('404-link', 'option');

?>

<main id="main" role="main" tabindex="-1" class="page-404">
    <div class="container">
        <h1 class="title-1">O<strong>O</strong><strong>O</strong>UPS</h1>
        <div class="intro"><?= get_field('404-text', 'option');?></div>
        <a class="btn-1" href="<?= $link['url']; ?>">
            <div class="picto-btn-1 picto-save"><?= icon("arrow-down", 19, 19); ?></div>
            <?= $link['title']; ?>
        </a>
    </div>
    <img class="img-animation" src="/wp-content/themes/valorplast/assets/img/404.png" />
</main>

<?php
get_footer();
