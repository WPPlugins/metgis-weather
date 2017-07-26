<?php
/**
 *
 * @package   MetGIS Weather
 * @author    Bergwerk Web & Multimedia OG <info@bergwerk.co>
 * @license   GPL-2.0+
 * @link      http://www.metgis.com
 *
 * @wordpress-plugin
 * Plugin Name:       MetGIS Weather
 * Plugin URI:        http://www.metgis.com
 * Description:       Plugin to show MetGIS-powered weather | Up to 3.000 API calls per month included. For more calls please get in touch with us at office@metgis.com
 * Version:           1.0.0
 * Author:            Bergwerk Web & Multimedia OG
 * Author URI:        www.bergwerk.co
 * Text Domain:       metgis-weather
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.t≤xt
 */
 
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

require_once("metgis.class.php");

class MetGIS_Weather extends WP_Widget {

  protected $widget_slug = 'metgis-weather';
		
	public $defaultOptions;

	public function __construct() {
		$this->defaultOptions = array(
			"location"=>"47.211290,11.452218",
			"alt"=>"2200",
			"days"=>"4",
			"cachefile"=>plugin_dir_path(__FILE__).'cache/',
			"title" => "MetGIS",
					);

		add_action( 'init', array( $this, 'widget_textdomain' ) );

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		parent::__construct(
			$this->get_widget_slug(),
			__( 'MetGIS Weather', $this->get_widget_slug() ),
			array(
				'classname'  => $this->get_widget_slug().'-class',
				'description' => __( 'Plugin to show MetGIS-powered weather', $this->get_widget_slug() )
			)
		);


		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		
	} 
  
	public function get_widget_slug() {
        return $this->widget_slug;
    }

	
	public function widget( $args, $instance ) {

		$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset ( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset ( $cache[ $args['widget_id'] ] ) )
			return print $cache[ $args['widget_id'] ];
			
		$imagepath = plugins_url( 'images/icons', __FILE__ );
		$args["lang"] = get_locale();
		$args = array_merge($this->defaultOptions,array_merge($args,$instance));
		$metgis = new metgisWeather($args);
	
		$data = $metgis->data;
		$info = $data->info;
		$data = $data->data;
		
		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'views/widget.php' );
		$widget_string .= ob_get_clean();
		$widget_string .= $after_widget;
		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

		print $widget_string;

	}
	
	
	public function flush_widget_cache() {
    	wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $new_instance;
		return $instance;
	} 
	
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance, $this->defaultOptions
		);
		include( plugin_dir_path(__FILE__) . 'views/admin.php' );

	} 
	
	public function widget_textdomain() {
		load_plugin_textdomain( $this->get_widget_slug(), false, dirname(plugin_basename( __FILE__ )) . '/lang/' );
	} 
	
	public function activate( $network_wide ) { } 
	public function deactivate( $network_wide ) { } 
	
	public function register_admin_styles() {
		wp_enqueue_style( $this->get_widget_slug().'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );
		wp_enqueue_style( 'wp-color-picker' );        
    wp_enqueue_style('thickbox');
	} 
	
	public function register_admin_scripts() {
		wp_enqueue_script( $this->get_widget_slug().'-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( 'wp-color-picker' ); 
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
	} 
	
	public function register_widget_styles() {
		wp_enqueue_style( $this->get_widget_slug().'-widget-styles', plugins_url( 'css/widget.css', __FILE__ ) );
	} 
	
	public function register_widget_scripts() {
		wp_enqueue_script( $this->get_widget_slug().'-script', plugins_url( 'js/widget.js', __FILE__ ), array('jquery') );
	} 
	
	public function print_input($instance,$name, $title, $type="text") {
		echo '
			<p>
				<label for="'.esc_attr( $this->get_field_id($name) ).'">'.$this->lang(strtoupper($title)).': </label>
				<input class="widefat" id="'.esc_attr( $this->get_field_id($name) ).'" name="'.esc_attr( $this->get_field_name($name) ).'" type="'.$type.'" value="'.$instance[$name].'" />
			</p>
			';
	}

	public function print_checkbox($instance,$name, $title) {
		echo '
				<input id="'.esc_attr( $this->get_field_id($name) ).'" name="'.esc_attr( $this->get_field_name($name) ).'" type="checkbox" value="1" '.(($instance[$name]==1) ? "checked" : "").' />
				<label for="'.esc_attr( $this->get_field_id($name) ).'">'.$this->lang(strtoupper($title)).'</label>
			';
		}
	
	public function print_radio($instance,$name,$title,$options) {
		echo '<p>
			<label for="'.esc_attr( $this->get_field_id($name) ).'">'.$this->lang(strtoupper($title)).'</label>';
		foreach($options as $key=>$o) {
			echo '<br /><input id="'.esc_attr( $this->get_field_id($name) ).$key.'" name="'.esc_attr( $this->get_field_name($name) ).'" type="radio" value="'.$key.'" '.(($instance[$name]==$key) ? "checked" : "").' /> '.$this->lang(strtoupper($o));
		}
		echo '</p>';
	}

	function lang($shortcode) {		
		return __($shortcode, 'metgis-weather');
	}
	
}

add_action( 'widgets_init', create_function( '', 'register_widget("MetGis_Weather");' ) );

add_action('the_content', 'addIframe');

function addIframe($content) {
	if(isset($_GET["data"])) {
		$url = base64_decode($_GET["data"]);
		if(strstr($url,"data.metgis.com")) {
			$content .= "<iframe frameborder=0 width='100%' height='1400' class='metgisframe' src='".$url."'></iframe>";
		}
	}
	return $content;
}