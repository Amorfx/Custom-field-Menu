<?php
/*
Plugin Name: Custom Field Menu
Plugin URL: http://agence-sba.com
Description: Plugin ajout url pour un big menu
Version: 1.0
Author: Clément Décou
Author URI: http://clement-decou.olympe.in
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

class custom_field_menu {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// load the plugin translation files
		add_action( 'init', array( $this, 'textdomain' ) );
		
		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'cfm_add_custom_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'cfm_update_custom_nav_fields'), 10, 3 );
		
		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'cfm_edit_walker'), 10, 2 );

		add_action('admin_enqueue_scripts', array($this, 'cm_load_media'));


	} // end constructor


	/**
	 * Load files for media library
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function cm_load_media(){
		global $pagenow;
		if($pagenow == "nav-menus.php"){
			wp_enqueue_media();
			wp_register_script('cm-admin-js', WP_PLUGIN_URL.'/custom-field-menu/js/cm_script.js', array('jquery'));
        	wp_enqueue_script('cm-admin-js');
		}
	}
	
	/**
	 * Load the plugin's text domain
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'cfm_scm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	
	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function cfm_add_custom_nav_fields( $menu_item ) {
	
	    $menu_item->urlimg = get_post_meta( $menu_item->ID, '_menu_item_urlimg', true );
	    return $menu_item;
	    
	}
	
	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function cfm_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
	
	    // Check if element is properly sent
	    if ( is_array( $_REQUEST['menu-item-urlimg']) ) {
	        $url_value = $_REQUEST['menu-item-urlimg'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_urlimg', $url_value );
	    }
	    
	}
	
	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function cfm_edit_walker($walker,$menu_id) {
	    return 'Walker_Nav_Menu_Edit_Custom';
	}

}

// instantiate plugin's class
$GLOBALS['custom_field_menu'] = new custom_field_menu();


include_once( 'edit_custom_walker.php' );
include_once( 'custom_walker.php' );