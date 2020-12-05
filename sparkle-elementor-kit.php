<?php
/**
 * Plugin Name: Sparkle Elementor Kit
 * Plugin URI: https://github.com/sparklewpthemes/sparkle-elementor-kit
 * Description: Sparkle Elementor Blocks
 * Version: 1.0.0
 * Author: sparklewpthemes
 * Author URI:  https://sparklewpthemes.com
 * Text Domain: sparkle-elementor-kit
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */
if (!defined('ABSPATH')) exit;


define('SPARKLE_EK_VERSION', '1.0.0');

define('SPARKLE_EK_FILE', __FILE__);
define('SPARKLE_EK_PLUGIN_BASENAME', plugin_basename(SPARKLE_EK_FILE));
define('SPARKLE_EK_PATH', plugin_dir_path(SPARKLE_EK_FILE));
define('SPARKLE_EK_URL', plugins_url('/', SPARKLE_EK_FILE));

if (!defined('ABSPATH'))
    exit;

if (!class_exists('Sparkle_Elementor_Kit')) {
    class Sparkle_Elementor_Kit{
        public $this_uri;
        public $this_dir;
        /*
         * Constructor
         */

        public function __construct() {

            // This uri & dir
            $this->this_uri = SPARKLE_EK_URL;
            $this->this_dir = SPARKLE_EK_PATH;
            
            if (!did_action('elementor/loaded')) {
                add_action( 'admin_notices', array($this, 'admin_notice__error') );
            }else{
                //elementor hooks 
                add_action( 'elementor/frontend/after_enqueue_scripts', array($this, 'sparkle_elementor_kit_elementor_scripts') );
                add_action( 'elementor/init', array($this, 'sparklestore_elementor_category') );
                add_action( 'elementor/widgets/widgets_registered', array($this, 'saprkle_elementor_widgets_registered') );
            }

            
        }

        function _is_plugin_installed($plugin_path ) {
            $installed_plugins = get_plugins();
            return isset( $installed_plugins[ $plugin_path ] );
        }

        function admin_notice__error() {

            if (!current_user_can('activate_plugins')) {
                return;
            }
    
            $elementor = 'elementor/elementor.php';
    
            if ($this->_is_plugin_installed($elementor)) {
                $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor);
                
                $message = sprintf( __('%1$sSparkle Elementor Kit%2$s requires %1$sElementor%2$s plugin to be active. Please activate Elementor to continue.', 'sparkle-elementor-kit'), "<strong>", "</strong>");
    
                $button_text = __('Activate Elementor', 'sparkle-elementor-kit');
            } else {
                $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
    
                $message = sprintf(__('%1$sSparkle Elementor Kit%2$s requires %1$sElementor%2$s plugin to be installed and activated. Please install Elementor to continue.', 'sparkle-elementor-kit'), '<strong>', '</strong>');
                $button_text = __('Install Elementor', 'sparkle-elementor-kit');
            }
    
            $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
    
            printf('<div class="error"><p>%1$s</p>%2$s</div>', __($message), $button);

        }

        /**
         * Load and register the required Elementor widgets file
         *
         * @param $widgets_manager
         *
         * @since Sparkle Elmeentor Kit
         */
        function saprkle_elementor_widgets_registered( $widgets_manager ) {

            //  Load Elementor Featured Service
            require_once $this->this_dir . 'widgets/buy-now.php';
            

            //  Register Featured Service Widget
            $widgets_manager->register_widget_type( new \Elementor\SparkleEKBuyNow() );

        }

    
        
        /**
         * Loads scripts on elementor editor
         *
         * @since Sparkle Elementor Kit 1.0.0
         */
        function sparkle_elementor_kit_elementor_scripts() {
            wp_enqueue_script('sparkle-elementor-kit-script', $this->this_uri . 'assets/script.js', array( 'jquery' ) );
            wp_enqueue_style('sparkle-elementor-kit-style', $this->this_uri . 'assets/style.css' );
        }

        /**
         * 
         * @since Sparkle Elmentor Kit 1.0.0
         */
        function sparklestore_elementor_category() {

            // Register widget block category for Elementor section
            \Elementor\Plugin::instance()->elements_manager->add_category( 'sparkle-elementor-kit', array(
                'title' => esc_html__( 'Sparkle Elementor Kit', 'sparkle-elementor-kit' ),
            ), 1 );
        }
    }
}

add_action('after_setup_theme', function(){
    new Sparkle_Elementor_Kit;
});
