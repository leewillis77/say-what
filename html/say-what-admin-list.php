<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2><?php _e( 'Text changes', 'say_what' ); ?><a href="tools.php?page=say_what_admin&amp;say_what_action=addedit" class="add-new-h2"><?php _e( 'Add New', 'say_what' ); ?></a></h2>
	<?php
		$list_table_instance = new SayWhatListTable( $this->settings );
		$list_table_instance->prepare_items();
		$list_table_instance->display();
	?>
</div>