<?php
defined( 'ABSPATH' ) or die('');
defined('WCS_EXPORT_ACCESS') or die();
if(class_exists('WCSExport')){ return; }

class WCSExport{
    public static function pluginSettingsLink($links){
        $settings_link = '<a href="'.get_site_url().'/wp-admin/admin.php?page=wcs-export">' . __( 'Settings' ) . '</a>';
        array_push( $links, $settings_link );
        return $links;
    }

    public static function pluginAdminLinks(){
        add_submenu_page('woocommerce', 'Export Orders', 'Export Orders', 'manage_woocommerce', 'wcs-export', array('WCSExport', 'wcsExportAdminDashboardPage'));
    }

    public static function wcsExportAdminDashboardPage(){
        $dashboardPage = self::getHtml('admin_dashboard');
        echo $dashboardPage;
    }

    public static function getHtml($file = '', $data = array()){
        $htmlData = '';

        if(!empty($file)){
            if(file_exists(WCS_EXPORT_PLUGIN_DIR.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.$file.'.php')){
                ob_start();
                $data = $data;
                include WCS_EXPORT_PLUGIN_DIR.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.$file.'.php';
                //$htmlData = ob_get_contents();
                $htmlData = ob_get_clean();
                //ob_end_clean();
            }
        }

        return $htmlData;
    }

    public static function wcsWcAfrScripts(){

        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

        wp_enqueue_style( 'wcs-export-css', plugins_url() . '/wcs-export/assets/wcs-export.css' );
        wp_enqueue_script( 'wcs-export-js', plugins_url() . '/wcs-export/assets/wcs-export.js', array(), '1.0.0', true);
    }
}


?>