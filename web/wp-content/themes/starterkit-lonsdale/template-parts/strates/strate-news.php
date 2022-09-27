<?php
$items = getCptNews();
?>
<section class="strate-news" data-module="strates/news">
    <header class="container">
        <h2><?= $args["title"] ?></h2>
    </header>

    <div class="slider full">
        <ul class="slider-content" aria-label="Les dernières actualités">
            <?php if (!empty($items)) : ?>
                <?php foreach ($items as $item) : ?>
                    <?php get_template_part('template-parts/cards/card', "news", $item); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <button class="slider-btn prev" aria-hidden="true" tabindex="-1">prev</button>
        <button class="slider-btn next" aria-hidden="true" tabindex="-1">next</button>
    </div>
</section>