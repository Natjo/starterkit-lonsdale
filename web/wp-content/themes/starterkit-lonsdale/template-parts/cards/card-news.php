<?php
$images = $args['images'];
?>

<li class="item card-article" data-view="card-article">
	<?php if (!empty($args['tag'])) : ?>
		<span><?= $args['tag'] ?></span>
	<?php endif ?>
	<h3><?= $args['title'] ?></h3>
	<time datetime="<?= $args['datetime'] ?>"><?= $args['date'] ?></time>
	<a href="<?= $args['link'] ?>">More</a>
	<p>
		<?= $args['text'] ?>
	</p>

	<picture>
		<?php if (!empty($images['mobile'])) : ?>
			<source srcset="<?= $images['mobile'] ?>.webp" media="(max-width: 575px)" type="image/webp">
			<source srcset="<?= $images['mobile'] ?>" media="(max-width: 575px)" type="image/jpeg">
		<?php endif; ?>
		<source srcset="<?= $images['desktop'] ?>.webp" media="(min-width: 1200px)" type="image/webp">
		<source srcset="<?= $images['desktop'] ?>" media="(min-width: 1200px)" type="image/jpeg">
		<?php if (!empty($images['tablet'])) : ?>
			<source srcset="<?= $images['tablet'] ?>.webp" media="(min-width: 576px)" type="image/webp">
			<source srcset="<?= $images['tablet'] ?>" media="(min-width: 576px)" type="image/jpeg">
		<?php endif; ?>
		<img src="<?= $images['desktop'] ?>" alt="" loading="lazy" width="<?= $images['width'] ?>" height="<?= $images['height'] ?>">
	</picture>
</li>