<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2><?php esc_html_e( 'Text changes', 'say-what' ); ?></h2>

	<p><?php
	// Translators: %1$s is opening <a> tag, %2%s is the closing tag.
    printf(esc_html__( 'Fill in the details of the original translatable string, the string\'s text domain, and the string you would like to use instead. For more information check out the %1$sgetting started guide%2$s.', 'say-what' ),'<a href="https://plugins.leewillis.co.uk/doc_post/adding-string-replacement/" target="_blank" rel="noopener noreferrer">','</a>');
    ?></p>
	<form action="tools.php?page=say_what_admin&amp;say_what_action=addedit" method="post">
		<input type="hidden" name="say_what_save" value="1">
		<?php wp_nonce_field( 'swaddedit', 'nonce' ); ?>
		<?php if ( ! empty ( $replacement->string_id ) ) : ?>
			<input type="hidden" name="say_what_string_id" value="<?php echo esc_attr( htmlspecialchars( $replacement->string_id ) ); ?>">
		<?php endif; ?>
		<p class="saywhat_label_container">
			<label for="say_what_orig_string"><?php esc_html_e( 'Original string', 'say-what' ); ?></label>
        </p>
        <p>
			<textarea class="say_what_orig_string" name="say_what_orig_string" rows="1" cols="120"><?php echo esc_textarea( $replacement->orig_string ); ?></textarea>
		</p>
		<p class="saywhat_label_container">
			<label for="say_what_domain"><?php esc_html_e( 'Text domain', 'say-what' ); ?></label> <a href="https://plugins.leewillis.co.uk/doc_post/adding-string-replacement/" target="_blank" rel="noopener noreferrer"><i class="dashicons dashicons-info">&nbsp;</i></a>
        </p>
        <p>
			<input type="text" name="say_what_domain" size="30" value="<?php echo esc_attr( htmlspecialchars( $replacement->domain ) ); ?>"><br/>
		</p>
		<p class="saywhat_label_container">
			<label for="say_what_context"><?php esc_html_e( 'Text context', 'say-what' ); ?></label> <a href="https://plugins.leewillis.co.uk/doc_post/replacing-wordpress-strings-context/" target="_blank" rel="noopener noreferrer"><i class="dashicons dashicons-info">&nbsp;</i></a>
        </p>
        <p>
			<input type="text" name="say_what_context" size="30" value="<?php echo esc_attr( htmlspecialchars( $replacement->context ) ); ?>"><br/>
		</p>
		<p class="saywhat_label_container">
			<label for="say_what_replacement_string"><?php esc_html_e( 'Replacement string', 'say-what' ); ?></label>
        </p>
        <p>
			<textarea class="say_what_replacement_string" name="say_what_replacement_string" cols="120" rows="1"><?php echo esc_textarea( $replacement->replacement_string ); ?></textarea>
		</p>
		<p class="saywhat_label_container">
			<input type="submit" class="button-primary" value="<?php  echo esc_html( ! empty( $replacement->string_id ) ? __( 'Update', 'say-what' ) : __( 'Add', 'say-what' ) ); ?>">
		</p>
	</form>
</div>
