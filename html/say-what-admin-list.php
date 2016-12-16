<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2><?php _e( 'Text changes', 'say_what' ); ?><a href="tools.php?page=say_what_admin&amp;say_what_action=addedit" class="add-new-h2"><?php _e( 'Add New', 'say_what' ); ?></a></h2>
	<?php
		$list_table_instance = new SayWhatListTable( $this->settings );
		$list_table_instance->prepare_items();
		$list_table_instance->display();
	?>
	<div class="saywhat-gopro">
		<h2>Go pro</h2>
		<p><a href="http://plugins.leewillis.co.uk/downloads/say-what-pro/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade">Say What? Pro</a> makes it even easier for you to change strings:
			<ul class="ul-disc">
				<li><a href="http://plugins.leewillis.co.uk/doc_post/string-discovery/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade" target="_blank">String Discovery</a></em> and autocomplete - find the strings you need without diving through code.</li>
				<li>Import &amp; export your strings between sites.</li>
				<li>Multi-lingual support - set replacements for multiple languages [beta]</li>
			</ul>
		</p>
		<p><strong>Upgrade to the Pro version today, and <em>save 15%</em> with the code <code>WPSAYWHAT</code> at checkout.</strong></p>
		<p><center><a href="http://plugins.leewillis.co.uk/downloads/say-what-pro/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade" class="button button-primary">Go Pro now &raquo;</a></center></p>
	</div>
</div>
