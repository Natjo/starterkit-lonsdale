<?php
$img = $args['image'];
?>

<section class="strate-image">
    <div class="container">
        <picture>
            <?php if (!empty($img['mobile'])) : ?>
                <source srcset="<?= $img['mobile'] ?>.webp" media="(max-width: 767px)" type="image/webp">
                <source srcset="<?= $img['mobile'] ?>" media="(max-width: 767px)" type="image/jpeg">
            <?php endif; ?>
            <source srcset="<?= $img['desktop'] ?>.webp" media="(min-width: 768px)" type="image/webp">
            <source srcset="<?= $img['desktop'] ?>" media="(min-width: 768px)" type="image/jpeg">
            <img src="<?= $img['desktop'] ?>" alt="" loading="lazy" width="<?= $img['width'] ?>" height="<?= $img['height'] ?>">
        </picture>
    </div>
</section>