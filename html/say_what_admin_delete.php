<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2><?php _e ( 'Text changes', 'say_what' ); ?></h2>

	<p>Are you sure you want to delete the replacement string for &quot;<?php esc_html_e($replacement->orig_string); ?>&quot;?</p>
	<p>
		<a href="tools.php?page=say_what_admin&amp;say_what_action=delete-confirmed&amp;id=<?php echo urlencode($_GET['id']); ?>&amp;nonce=<?php echo urlencode($_GET['nonce']); ?>" class="button">Yes</a> <a href="tools.php?page=say_what_admin" class="button button-primary">No</a>

</div>