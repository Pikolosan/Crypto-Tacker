<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Fast_Crypto_Tracker
 * @subpackage Fast_Crypto_Tracker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fast_Crypto_Tracker
 * @subpackage Fast_Crypto_Tracker/admin
 * @author     Your Name <email@example.com>
 */
class Fast_Crypto_Tracker_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $Fast_Crypto_Tracker    The ID of this plugin.
	 */
	private $Fast_Crypto_Tracker;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $Fast_Crypto_Tracker       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $Fast_Crypto_Tracker, $version ) {

		$this->Fast_Crypto_Tracker = $Fast_Crypto_Tracker;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fast_Crypto_Tracker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fast_Crypto_Tracker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->Fast_Crypto_Tracker, plugin_dir_url( __FILE__ ) . 'css/fast-crypto-tracker-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fast_Crypto_Tracker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fast_Crypto_Tracker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->Fast_Crypto_Tracker, plugin_dir_url( __FILE__ ) . 'js/fast-crypto-tracker-admin.js', array( 'jquery' ), $this->version, false );

	}

}
