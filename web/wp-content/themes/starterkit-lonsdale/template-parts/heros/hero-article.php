<?php
$images = $args['images'];
?>
<header class="hero-article">
    <div class="container">
        <?php if (!empty($args['title'])) : ?>
            <h1 class="title-1"><?= $args['title']; ?></h1>
        <?php endif; ?>

        <?php if (!empty($args['date'])) : ?>
            <time><?= $args['date']; ?></time>
        <?php endif; ?>
        <?php if (!empty($args['categories'])) : ?>
            <div><?= $args['categories']; ?></div>
        <?php endif; ?>
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
                    <img src="<?= $images['desktop'] ?>" alt="" loading="lazy" width="<?= $images['width'] ?>" height="<?= $images['height'] ?>">
                </picture>
            <?php endif; ?>
    </div>
</header>