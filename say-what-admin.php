<?php



if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly


/**
 * FIXME
 */
class say_what_admin {



	private $settings;

	/**
	 * Constructor
	 */
	function __construct($settings) {

		$this->settings = $settings;
		add_action( 'admin_init', array ( &$this, 'admin_init' ) );

	}



	function admin_init() {

        if ( current_user_can ( 'manage_options' ) ) {

            $page = add_management_page ( __('Text changes', 'say_what'),
                                          __('Text changes', 'say_what'),
                                          'manage_options',
                                          'say_what_admin',
                                          array ( $this, 'admin' ) );

            $this->admin_url = 'tools.php?page=say_what_admin'; // FIXME - not needed?

            add_action('admin_print_styles-' . $page, array ( &$this, 'enqueue_scripts' ) );

        }

	}



	function enqueue_scripts() {
		// Placeholder - suspect I might want some admin JS later
	}

}
