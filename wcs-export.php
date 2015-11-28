<?php
/*
Plugin Name: WCS Export
Plugin URI: http://www.wpsupport.io/
Description: Woocommerce orders export plugin.
Author: Vijay M
Text Domain: wcs-export
Domain Path: /languages/
Version: 1.0
*/
defined( 'ABSPATH' ) or die('');
$timezone = get_option('timezone_string');
$timezone = empty($timezone)?'Europe/London':$timezone;

date_default_timezone_set($timezone);

define( 'WCS_EXPORT_ACCESS', true );
define( 'WCS_EXPORT', '1.0' );
define( 'WCS_EXPORT_PLUGIN', __FILE__ );
define( 'WCS_EXPORT_PLUGIN_BASENAME', plugin_basename( WCS_EXPORT_PLUGIN ) );
define( 'WCS_EXPORT_PLUGIN_NAME', trim( dirname( WCS_EXPORT_PLUGIN_BASENAME ), '/' ) );
define( 'WCS_EXPORT_PLUGIN_DIR', untrailingslashit( dirname( WCS_EXPORT_PLUGIN ) ) );
define( 'WCS_EXPORT_PLUGIN_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );

require_once WCS_EXPORT_PLUGIN_DIR.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'wcs-export.php';

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //Don't proceed further if there is no active woocommerce.
    return;
}

add_action( 'admin_enqueue_scripts', array('WCSExport', 'wcsWcAfrScripts') );

add_filter( "plugin_action_links_".WCS_EXPORT_PLUGIN_BASENAME, array('WCSExport', 'pluginSettingsLink') );
add_action( 'admin_menu', array('WCSExport', 'pluginAdminLinks') );

?>