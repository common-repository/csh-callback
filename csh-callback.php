<?php

/**
 * @link              https://codecanyon.net/user/cmssuperheroes
 * @since             1.0.0
 * @package           Csh_Callback
 *
 * @wordpress-plugin
 * Plugin Name:       Csh Callback
 * Plugin URI:        http://demo.cmssuperheroes.com/csh-plugins/csh-callback
 * Description:       Add a callback request form to wordpress site
 * Version:           1.0.0
 * Author:            Tony
 * Author URI:        https://codecanyon.net/user/cmssuperheroes
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cshcallback
 * Domain Path:       /languages
 */

// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CSHCB_PLUGIN_VERSION', '1.0.0' );

define( 'CSHCB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CSHCB_PLUGIN_URL', plugins_url("", __FILE__) );

define( 'CSHCB_PLUGIN_ADMIN_DIR', CSHCB_PLUGIN_DIR . "/admin/" );
define( 'CSHCB_PLUGIN_ADMIN_URL', CSHCB_PLUGIN_URL . "/admin/" );

define( 'CSHCB_PLUGIN_ASSETS_DIR', CSHCB_PLUGIN_DIR . "/assets/" );
define( 'CSHCB_PLUGIN_ASSETS_URL', CSHCB_PLUGIN_URL . "/assets/" );

define( 'CSHCB_PLUGIN_INCLUDES_DIR', CSHCB_PLUGIN_DIR . "/includes/" );
define( 'CSHCB_PLUGIN_INCLUDES_URL', CSHCB_PLUGIN_URL . "/includes/" );

define( 'CSHCB_PLUGIN_LAYOUTS_DIR', CSHCB_PLUGIN_DIR . "/layouts/" );
define( 'CSHCB_PLUGIN_LAYOUTS_URL', CSHCB_PLUGIN_URL . "/layouts/" );

define( 'CSHCB_PLUGIN_PUBLIC_DIR', CSHCB_PLUGIN_DIR . "/public/" );
define( 'CSHCB_PLUGIN_PUBLIC_URL', CSHCB_PLUGIN_URL . "/public/" );

/* Return csh-callback options data */
$cshcb_options = get_option( 'csh-callback' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require CSHCB_PLUGIN_INCLUDES_DIR . 'class-csh-callback-table-db.php';
require CSHCB_PLUGIN_INCLUDES_DIR . 'class-csh-callback.php';
require CSHCB_PLUGIN_INCLUDES_DIR . 'csh-callback-utils.php';
require CSHCB_PLUGIN_INCLUDES_DIR . 'widget-callback.php';


add_action( 'plugins_loaded', 'cshcb_load_textdomain' );
function cshcb_load_textdomain() {
    $language_folder = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
    load_plugin_textdomain( 'cshcallback', false, $language_folder);
}

register_activation_hook( __FILE__, 'cshcb_install' );
register_deactivation_hook( __FILE__, 'cshcb_uninstall' );

function cshcb_install(){
	$tablepayment = new Csh_Callback_Table_Database();
	$tablepayment->create_table_payment();
}

function cshcb_uninstall(){
	
}












