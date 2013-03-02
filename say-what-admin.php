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
		add_action( 'admin_menu', array ( &$this, 'admin_menu' ) );

	}



	/**
	 * Register the menu item for the admin pages
	 */
	public function admin_menu() {

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



	/**
	 * Add javascript to admin pages
	 */
	public function enqueue_scripts() {
		// Placeholder - suspect I might want some admin JS later
	}



	/**
	 * The main admin page controller
	 */
	public function admin() {

		if ( isset ( $_POST['say_what_save'] ) ) {
			$this->save();
		}

		require_once('html/say_what_admin.php');
		// Admin page goes here

	}



	/**
	 * Something on the admin pages needs saved. handle it all here
	 * Output error/warning messages as required
	 */
	private function save() {

	}

}
