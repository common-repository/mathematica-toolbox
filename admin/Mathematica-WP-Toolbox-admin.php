<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Mathematica_WP_Toolbox
 * @subpackage Mathematica_WP_Toolbox/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks that
 * enqueue scripts and CSS styles. Also adds a meta box
 * with editor buttons and whitelists CDF/.nb/.m files.
 *
 * @package    Mathematica_WP_Toolbox
 * @subpackage Mathematica_WP_Toolbox/admin
 * @author     Calle Ekdahl <calle@ekdahl.email>
 */
class Mathematica_WP_Toolbox_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	}

	/**
	 * Add CDFs, notebooks and .m-files to the white list of extensions and mime types.
	 *
	 * @since    1.0.0
	 */
	 public function add_wolfram_mime_types( $mime_types = array() ) {
		 
		 $mime_types['cdf'] = 'application/vnd.wolfram.cdf';
		 $mime_types['nb'] = 'application/vnd.wolfram.mathematica';
		 $mime_types['m'] = 'application/vnd.wolfram.mathematica.package';

		 return $mime_types;
	 }

	/**
	 * A get custom field function for the XML-RPC API.
	 * It transfers base64-encoded strings so that all
	 * formatting is preserved.
	 *
	 * @since    1.0.4
	 */
	 public function wl_get_custom_field( $args ) {
	 	global $wp_xmlrpc_server;
	 	$wp_xmlrpc_server->escape( $args );
	 	
	 	$blog_id = $args[0];
	 	$username = $args[1];
	 	$password = $args[2];
	 	
	 	if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {
	 		return $wp_xmlrpc_server->error;
	 	}
		
		$post_id = $args[3];
		$field_name = $args[4];
		
		if( ! $field_value = get_post_meta( $post_id, $field_name, true ) ) {
			return new IXR_Error( 403, __( 'Custom field value could not be retrieved.' ) );
		}
		
		return base64_encode($field_value);
	 }
	 
	/**
	 * A set custom field function for the XML-RPC API.
	 * It expects base64-encoded strings so that all
	 * formatting is preserved.
	 *
	 * @since    1.0.4
	 */
	 public function wl_set_custom_field( $args ) {
	 	global $wp_xmlrpc_server;
	 	$wp_xmlrpc_server->escape( $args );
	 	
	 	$blog_id = $args[0];
	 	$username = $args[1];
	 	$password = $args[2];
	 	
	 	if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {
	 		return $wp_xmlrpc_server->error;
	 	}
		
		$post_id = $args[3];
		$field_name = $args[4];
		$field_value = base64_decode($args[5]);
		
		if( ! $field_value = update_post_meta( $post_id, $field_name, $field_value ) ) {
			return new IXR_Error( 403, __( 'Custom field value could not be set.' ) );
		}
		
		return true; 
	 }
	 
	/**
	 * Add new methods to the XML-RPC API.
	 *
	 * @since    1.0.4
	 */
	 public function wl_new_xmlrpc_methods( $methods ) {
	     $methods['wl.getCustomField'] = array($this, 'wl_get_custom_field');
	     $methods['wl.setCustomField'] = array($this, 'wl_set_custom_field');
	     return $methods;   
	 }

	 /**
	  * Add a buttons to the editor that inserts shortcode templates for the various
	  * shortcodes defined by this plugin. Create meta boxes to put them in.
	  *
	  * @since 1.0.0
	  */
	public function add_media_button() {
	
		$screens = array( 'post', 'page' );
		
		foreach ( $screens as $screen ) {
		
		add_meta_box(
			'Mathematica-WP-Toolbox-shortcodes',
			__( 'Mathematica Toolbox', 'Mathematica-WP-Toolbox-textdomain' ),
			function( $post ) { ?>
<a id="mathematica-wp-toolbox-shortcode-cdf" class="button add_media mathematica-wp-toolbox-shortcode" title="WolframCDF shortcode">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> WolframCDF
</a>
<a id="mathematica-wp-toolbox-shortcode-api" class="button add_media mathematica-wp-toolbox-shortcode" title="WolframCloudAPI shortcode">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> WolframCloudAPI
</a> 
<a id="mathematica-wp-toolbox-shortcode-wlembedded" class="button add_media mathematica-wp-toolbox-shortcode" title="Highlight embedded code shortcode">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> Highlight embedded code
</a>
<a id="mathematica-wp-toolbox-shortcode-wlfield" class="button add_media mathematica-wp-toolbox-shortcode" title="Highlight custom field code shortcode">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> Highlight custom field code
</a>
<a id="mathematica-wp-toolbox-shortcode-wlinline" class="button add_media mathematica-wp-toolbox-shortcode" title="Inline code">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> Inline code
</a>
<a id="mathematica-wp-toolbox-shortcode-wldoc" class="button add_media mathematica-wp-toolbox-shortcode" title="Documentation link shortcode">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> Documentation link
</a>
<?php
			}, $screen);

		add_meta_box(
			'Mathematica-Toolbox-StackExchange-shortcodes',
			__( 'Mathematica StackExchange', 'Mathematica-WP-Toolbox-textdomain' ),
			function( $post ) { ?>
<a id="mathematica-wp-toolbox-shortcode-mma-se-username" class="button add_media mathematica-wp-toolbox-shortcode" title="Username shortcode">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> Username
</a>
<a id="mathematica-wp-toolbox-shortcode-mma-se-user-questions" class="button add_media mathematica-wp-toolbox-shortcode" title="Users's questions shortcode">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> Questions by user ID
</a>
<a id="mathematica-wp-toolbox-shortcode-mma-se-user-answers" class="button add_media mathematica-wp-toolbox-shortcode" title="User's answers shortcode">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> Answers by user ID
</a>
<a id="mathematica-wp-toolbox-shortcode-mma-se-questions" class="button add_media mathematica-wp-toolbox-shortcode" title="Questions by IDs shortcode">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> Questions by IDs
</a>
<a id="mathematica-wp-toolbox-shortcode-mma-se-answers" class="button add_media mathematica-wp-toolbox-shortcode" title="Answers by IDs shortcode">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> Answers by IDs
</a>
<a id="mathematica-wp-toolbox-shortcode-mma-se-profile-box" class="button add_media mathematica-wp-toolbox-shortcode" title="Profile box shortcode">
	<span class="wp-media-buttons-icon mathematica-wp-toolbox-icon"></span> Profile box
</a>
<?php
			}, $screen);

		}
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/Mathematica-WP-Toolbox-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the Javascript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/Mathematica-WP-Toolbox-admin.js', array( 'jquery' ), $this->version, true );

	}

}
