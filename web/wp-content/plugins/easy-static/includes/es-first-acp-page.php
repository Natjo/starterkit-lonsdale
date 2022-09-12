<?php

global $wpdb;
$nonce = wp_create_nonce('test_nonce');
$isStatic  = (file_exists(WP_CONTENT_DIR . '/static/'))  ? 1 : 0;

// create column (static_active,static_generate) in wp_posts
$table_posts = $wpdb->prefix . 'posts';
$table_posts_rows = $wpdb->get_row("SELECT * FROM " . $table_posts);
if (!isset($table_posts_rows->static_active)) {
	$wpdb->query("ALTER TABLE wp_posts ADD static_active  BOOLEAN DEFAULT 1 ");
}
if (!isset($table_posts_rows->static_generate)) {
	$wpdb->query("ALTER TABLE wp_posts ADD static_generate timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL ");
}

?>

<div class="wrap">
	<h1>Static website</h1>
	TODO<br>
	display last generate<br>
	rec host docker<br>

	<br><br><br>
	<u>dans easy-static.php mettre le host du nginx :</u><br>
	docker exec -it starterkit-lonsdale-nginx bash<br>
	cat /etc/hosts 
	<br><br><br>

	<div>
		<input type="checkbox" id="plug-static-toggle-status" <?php if ($isStatic) echo 'checked' ?>><label for="plug-static-toggle-status">Mode static active</label>
		&nbsp;&nbsp;
		<button class="plug-static-btn-generate">Generate all pages</button>
	</div>

	<br>
	<label>docker host</label>
	<input type="text" value="172.19.0.3" style="width: 300px"><br>

	<br>

	<section>
		<h2>Cpts</h2>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>Type</th>
					<th>Slug</th>
					<th>has pagination</th>
					<th>Static</th>
					<th>post per page</th>

				</tr>
			</thead>
			<tbody>
				<?php

				// static_active
				$post_types = postTypes();
				foreach ($post_types as $post_type) :
					$args = array(
						'post_type' => $post_type,
						'posts_per_page' => -1,
						'order' => 'ID',
						'orderby' => 'title',
						'post_status' => 'publish',
						'ignore_sticky_posts' => 1,
					);
					$queryArticles = new WP_Query($args);
					$post_type_object = get_post_type_object($post_type);
				?>
					<tr>
						<td>news</td>
						<td><?= $post_type_object->rewrite['slug'] ?></td>
						<td><?= $post_type_object->has_pagination ? "oui" : "non" ?></td>
						<td>oui</td>
						<td><?= $post_type_object->posts_per_page ?></td>
					</tr>
				<?php endforeach; ?>

			</tbody>

		</table>
	</section>
	<br>

	<section>
		<h2>Post</h2>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Type</th>
					<th>Static</th>
					<th>Up to date</th>
				</tr>
			</thead>
			<tbody id="plug-static-pages">
				<?= display(); ?>
			</tbody>
		</table>
	</section>
</div>



<script>
	const pages_result = document.getElementById('plug-static-pages');
	const btn_generate = document.querySelector('.plug-static-btn-generate');
	const toogle_status = document.getElementById("plug-static-toggle-status");
	toogle_status.checked = Boolean(<?= $isStatic ? true : false; ?>);

	btn_generate.onclick = () => {
		btn_generate.disabled = true;
		const data = new FormData();
		data.append('action', "test");
		data.append('nonce', '<?= $nonce ?>');
		data.append('status', toogle_status.checked);
		const xhr = new XMLHttpRequest();
		xhr.open("post", '<?= AJAX_URL ?>', true);
		xhr.send(data);
		xhr.onload = () => {
			btn_generate.disabled = false;
			const response = JSON.parse(xhr.responseText);
			pages_result.innerHTML = response.markup;
		}
	}

	// posts is active
	const checkbox_static_active = pages_result.querySelectorAll(".checkbox-static_active");
	checkbox_static_active.forEach((el) => {
		el.onchange = () => {
			const data = new FormData();
			data.append('action', "static_posts_his_active");
			data.append('nonce', '<?= $nonce ?>');
			data.append('id', el.id);
			data.append('status', el.checked);
			const xhr = new XMLHttpRequest();
			xhr.open("post", '<?= AJAX_URL ?>', true);
			xhr.send(data);
			xhr.onload = () => {}
		}
	})


	// status
	toogle_status.onchange = () => {
		btn_generate.disabled = true;
		toogle_status.disabled = true;
		const data = new FormData();
		data.append('action', "static_change_status");
		data.append('nonce', '<?= $nonce ?>');
		data.append('status', toogle_status.checked);
		const xhr = new XMLHttpRequest();
		xhr.open("post", '<?= AJAX_URL ?>', true);
		xhr.send(data);
		xhr.onload = () => {
			btn_generate.disabled = false;
			toogle_status.disabled = false;
			const response = JSON.parse(xhr.responseText);
			pages_result.innerHTML = response.markup;
		}
	}
</script>