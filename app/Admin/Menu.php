<?php
/**
 * The Menu class defines the admin menu for the Billplz CF7 plugin.
 *
 * @package BillplzCF7\Admin
 */

namespace BillplzCF7\Admin;

/**
 * Class Menu
 */
class Menu {
	
	/**
	 * Registers the admin menu.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	/**
	 * Adds the submenu page.
	 *
	 * @return void
	 */
	public function add_menu() {
		add_submenu_page(
			'wpcf7',
			__( 'Billplz for Contact Form 7', BCF7_TEXT_DOMAIN ),
			__( 'Billplz', BCF7_TEXT_DOMAIN ),
			'manage_options',
			'billplz-cf7',
			array( $this, 'callback' )
		);
	}

	/**
	 * Renders the page callback.
	 *
	 * @return void
	 */
	public function callback() {
		require_once BCF7_PLUGIN_PATH .
			'app/views/page-callback.php';
	}
}