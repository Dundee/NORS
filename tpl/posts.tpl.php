<h1 class="hlavni">Novinky<span>&nbsp;</span></h1>
<?php
if (iterable($posts)) {
	foreach ($posts as $post) {
		echo '<h3>'.$post['title'].'</h3>';
		echo $post['url'];
	}
}