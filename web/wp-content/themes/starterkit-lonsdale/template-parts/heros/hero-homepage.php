<?php
$images = $args['images'];
?>

<header class="hero-homepage">
    <div class="container">
        <?php if (!empty($args['title'])) : ?>
            <h1 class="title-1"><?= $args['title']; ?></h1>
        <?php endif; ?>
        <?php if (!empty($args['intro'])) : ?>
            <div class="intro"><?= $args['intro']; ?></div>
        <?php endif; ?>
        <?php if (!empty($args['cta']['label']) && !empty($args['cta']['link'])) : ?>
            <a class="btn-1" href="<?= $args['cta']['link']; ?>">
                <?= $args['cta']['label']; ?>
            </a>
        <?php endif; ?>
    </div>
    <?php if (!empty($images['desktop'])) : ?>
        <picture>
            <?php if (!empty($images['mobile'])) : ?>
                <source srcset="<?= $images['mobile'] ?>.webp" media="(max-width: 767px)" type="image/webp">
                <source srcset="<?= $images['mobile'] ?>" media="(max-width: 767px)" type="image/jpeg">
            <?php endif; ?>
            <source srcset="<?= $images['desktop'] ?>.webp" media="(min-width: 1200px)" type="image/webp">
            <source srcset="<?= $images['desktop'] ?>" media="(min-width: 1200px)" type="image/jpeg">
            <?php if (!empty($images['tablet'])) : ?>
                <source srcset="<?= $images['tablet'] ?>.webp" media="(min-width: 768px)" type="image/webp">
                <source srcset="<?= $images['tablet'] ?>" media="(min-width: 768px)" type="image/jpeg">
            <?php endif; ?>
            <img src="<?= $images['desktop'] ?>" alt="" width="<?= $images['width'] ?>" height="<?= $images['height'] ?>">
        </picture>
    <?php endif; ?>
</header>