
<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2><?php _e( 'Text changes', 'say_what' ); ?></h2>

	<form action="tools.php?page=say_what_admin&amp;say_what_action=addedit" method="post">
		<input type="hidden" name="say_what_save" value="1">
		<?php wp_nonce_field( 'swaddedit', 'nonce' ); ?>
		<?php if ( ! empty ( $replacement->string_id ) ) : ?>
			<input type="hidden" name="say_what_string_id" value="<?php echo esc_attr( $replacement->string_id ); ?>">
		<?php endif; ?>
		<p>
			<label for="say_what_orig_string"><?php _e( 'Original string', 'say_what' ); ?></label><br/>
			<input type="text" name="say_what_orig_string" size="120" value="<?php echo esc_attr( $replacement->orig_string ) ?>"><br/>
			<label for="say_what_domain"><?php _e( 'Domain', 'say_what' ); ?></label><br/>
			<input type="text" name="say_what_domain" value="<?php echo esc_attr( $replacement->domain ); ?>"><br/>
			<label for="say_what_replacement_string"><?php _e( 'Replacement string', 'say_what' ); ?></label><br/>
			<input type="text" name="say_what_replacement_string" size="120" value="<?php echo esc_attr( $replacement->replacement_string ); ?>"><br/>
		</p>
		<p>
			<input type="submit" class="button-primary" value="<?php ! empty( $replacement->string_id ) ? _e( 'Update', 'say_what' ) : _e( 'Add', 'say_what' ); ?>">
		</p>
	</form>

</div>