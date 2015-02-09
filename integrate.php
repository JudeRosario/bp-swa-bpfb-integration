<?php
/**
 * Plugin Name: Integrate Activity+ and Site Wide Activity
 * Version: 1.1.0
 * Description: This plugin contains glue code to hande sitewide activity widgets and safely integrates with Activity Plus
 * Author: Jude Rosario (WPMU DEV)
 * Author URI: http://premium.wpmudev.org/
 */

if(!class_exists('Swa_Bpfb') ):

include_once(plugin_dir_path( dirname(__FILE__) ).'buddypress-activity-plus/lib/class_bpfb_binder.php');

class Swa_Bpfb{

	var $swa_bpfb ;

    function __construct(){
    		$this->swa_bpfb = new BpfbBinder() ;
        }
// The starting point to this addon/class
	public static function serve () {
		$me = new self;
		$me->add_hooks();
	}

	private function add_hooks () {
		add_action( 'wp_footer', array ( $this, 'inject_styles' ));
        add_action( 'wp_footer', array ( $this, 'inject_scripts' ));
	}

	function inject_styles() {
		wp_enqueue_style('thickbox');
		wp_enqueue_style('file_uploader_style', BPFB_PLUGIN_URL . '/css/external/fileuploader.css');
		if (!current_theme_supports('bpfb_interface_style')) {
			wp_enqueue_style('bpfb_interface_style', BPFB_PLUGIN_URL . '/css/bpfb_interface.css');
		}
		if (!current_theme_supports('bpfb_toolbar_icons')) {
			wp_enqueue_style('bpfb_toolbar_icons', BPFB_PLUGIN_URL . '/css/bpfb_toolbar.css');
		}
	return;
	}

	function inject_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('thickbox');
		if (!current_theme_supports('bpfb_file_uploader')) {
			wp_enqueue_script('file_uploader', BPFB_PLUGIN_URL . '/js/external/fileuploader.js', array('jquery'));
		}
		wp_enqueue_script('bpfb_integrate_script', plugin_dir_url( __FILE__ ) . '/integrate.js', array('jquery'));
		$this->swa_bpfb->js_plugin_url();
		wp_localize_script('bpfb_integrate_script', 'l10nBpfb', array(
			'add_photos' => __('Add photos', 'bpfb'),
			'add_remote_image' => __('Add image URL', 'bpfb'),
			'add_another_remote_image' => __('Add another image URL', 'bpfb'),
			'add_videos' => __('Add videos', 'bpfb'),
			'add_video' => __('Add video', 'bpfb'),
			'add_links' => __('Add links', 'bpfb'),
			'add_link' => __('Add link', 'bpfb'),
			'add' => __('Add', 'bpfb'),
			'cancel' => __('Cancel', 'bpfb'),
			'preview' => __('Preview', 'bpfb'),
			'drop_files' => __('Drop files here to upload', 'bpfb'),
			'upload_file' => __('Upload a file', 'bpfb'),
			'choose_thumbnail' => __('Choose thumbnail', 'bpfb'),
			'no_thumbnail' => __('No thumbnail', 'bpfb'),
			'paste_video_url' => __('Paste video URL here', 'bpfb'),
			'paste_link_url' => __('Paste link here', 'bpfb'),
			'images_limit_exceeded' => sprintf(__("You tried to add too many images, only %d will be posted.", 'bpfb'), BPFB_IMAGE_LIMIT),
			// Variables
			'_max_images' => BPFB_IMAGE_LIMIT,
		));
	return;
	}
}
endif ;

$integrate = new Swa_Bpfb() ; 

// Check if the base plugin is installed before activating the addon 
add_action('plugins_loaded', 'init_swa_bpfb') ;

	function init_swa_bpfb () {
		if (class_exists('BpfbBinder'))
			// Start the addon
			Swa_Bpfb::serve();
	}

?>