<?php
$items = getCptNews();
?>
<section class="strate-news" data-module="strates/news">
    <header class="container">
        <h2><?= $args["title"] ?></h2>
    </header>
    
    <div class="slider full">
        <ul class="slider-content">
            <?php if (!empty($items)) : ?>
                <?php foreach ($items as $item) : ?>
                    <?php get_template_part('template-parts/cards/card', "news", $item); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</section>