<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.5.2 for parent theme tm for publication on ThemeForest
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require trailingslashit(get_template_directory()) . '/templatemela/tm-plugins-activation.php' ;
add_action( 'tgmpa_register', 'tm_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function tm_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// This is an example of how to include a plugin bundled with a theme.		
		array(
			'name'               => esc_html__( 'Templatemela Metabox', 'templatemela' ), // The plugin name.			
			'slug'               => 'TemplatemelaMetaBox', // The plugin slug (typically the folder name).
			'source'             => get_template_directory().'/templatemela/plugins/TemplatemelaMetaBox.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.		
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),
		array(
			'name'               => esc_html__( 'Templatemela Shortcodes', 'templatemela' ), // The plugin name.
			'slug'               => 'TemplatemelaShortcodes', // The plugin slug (typically the folder name).
			'source'             => get_template_directory().'/templatemela/plugins/TemplatemelaShortcodes.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.		
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),
		array(
			'name'               => esc_html__( 'Templatemela Custom Post', 'templatemela' ), // The plugin name.
			'slug'               => 'TemplatemelaCustomPost', // The plugin slug (typically the folder name).
			'source'             => get_template_directory().'/templatemela/plugins/TemplatemelaCustomPost.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.			
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),	
		array(
			'name'     		 	 => esc_html__( ' Revolution Slider', 'templatemela' ), // The plugin name.
			'slug'      		 => 'TemplatemelaSlideShow', // The plugin slug (typically the folder name).
			'source'             => get_template_directory().'/templatemela/plugins/TemplatemelaSlideShow.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.			
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),
		
		array(
			'name'      => esc_html__( 'Black Studio Tinymce Widget','templatemela'),
			'slug'      => 'black-studio-tinymce-widget',			
			'required'  => true
		),
		array(
			'name'      => esc_html__( 'Regenerate Thumbnails', 'templatemela' ),
			'slug'      => 'regenerate-thumbnails',
			'required'  => false,
		),
		array(
			'name'      => esc_html__('Contact Form 7','templatemela'),
			'slug'      => 'contact-form-7',
			'required'  => false,
		),	
		array(
			'name'      =>esc_html__( 'Newsletter','templatemela'),
			'slug'      => 'newsletter',
			'required'  => false,
		),
		array(
			'name'      => esc_html__('Woocommerce','templatemela'),
			'slug'      => 'woocommerce',
			'required'  => false,
		),
		array(
			'name'      => esc_html__('Woocommerce Accepted Payment Methods','templatemela'),
			'slug'      => 'woocommerce-accepted-payment-methods',
			'required'  => false,
		),
		array(
			'name'      => esc_html__('Woocommerce Grid List Toggle','templatemela'),
			'slug'      => 'woocommerce-grid-list-toggle',
			'required'  => false,
		),
		array(
			'name'      => esc_html__('Woosidebars','templatemela'),
			'slug'      => 'woosidebars',
			'required'  => false,
		),
		array(
			'name'      => esc_html__('WordPress Seo','templatemela'),
			'slug'      => 'wordpress-seo',
			'required'  => false,
		),
		array(
			'name'      => esc_html__('Yith Woocommerce Wishlist','templatemela'),
			'slug'      => 'yith-woocommerce-wishlist',
			'required'  => false,
		),
		array(
			'name'      => esc_html__('Yith Woocommerce Quick View','templatemela'),
			'slug'      => 'yith-woocommerce-quick-view',
			'required'  => false,
		),		
		array(
			'name'      => esc_html__('WordPress Importer','templatemela'),
			'slug'      => 'wordpress-importer',
			'required'  => true,
		),
		array(
			'name'      => esc_html__('Widget Importer Exporter','templatemela'),
			'slug'      => 'widget-importer-exporter',
			'required'  => true,
		),	
		array(
			'name'      => esc_html__('Yith Woocommerce Zoom Magnifier','templatemela'),
			'slug'      => 'yith-woocommerce-zoom-magnifier',
			'required'  => false,
		),	
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'templatemela',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tm-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => true,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
?>