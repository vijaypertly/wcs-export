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

    public static function notifyMess($arrNotificMess = array()){
        if(!empty($arrNotificMess)){
            foreach($arrNotificMess as $mess){
                if(!empty($mess['type']) && !empty($mess['mess'])){
                    echo '<div class="'.$mess['type'].'"> <p>'.$mess['mess'].'</p> </div>';
                }
            }
        }
    }

    public static function getRecords($arrParams = array()){
		global $wpdb;
        $resp = array(
            'status'=>'error',
            'mess'=>'Please try again later sometime. ',
        );

        if(!empty($arrParams)){
            /*$customer_orders = get_posts( array(
                'numberposts' => -1,
                'meta_key'    => '_customer_user',
                'meta_value'  => get_current_user_id(),
                'post_type'   => wc_get_order_types(),
                'post_status' => array_keys( wc_get_order_statuses() ),
            ) );*/

            $wcOrderStatus = wc_get_order_statuses();

            $orderStatus = $arrParams['order_status'];
            if(in_array('all', $arrParams['order_status'])){
                $orderStatus = array_keys( $wcOrderStatus );
            }

            $limit = -1;
            $offset = 0;

            if($arrParams['limit_records']>0){
                $limit = $arrParams['limit_records'];
            }

            if($arrParams['offset_records']>0){
                $offset = $arrParams['offset_records'];
            }
			
			$datequery = '';
			$dateArray = array();
			if( $arrParams['start_date'] != '' && $arrParams['end_date'] != '' ){				
				$dateArray = array(
					array(						
						'after'     => date('F jS, Y',strtotime($arrParams['start_date'])),						
						'before'    => date('F jS, Y',strtotime($arrParams['end_date'])),						
						'inclusive' => true,
					),
				);
				$datequery = "AND post_date >= '".date('Y-m-d',strtotime($arrParams['start_date']))."' AND post_date <= '".date('Y-m-d',strtotime($arrParams['end_date']))."' ";
			}else if( $arrParams['start_date'] != '' && $arrParams['end_date'] == '' ){				
				$dateArray = array(
					array(						
						'after'     => date('F jS, Y',strtotime($arrParams['start_date'])),																
						'inclusive' => true,
					),
				);
				$datequery = "AND post_date >= '".date('Y-m-d',strtotime($arrParams['start_date']))."' ";
			}else if( $arrParams['start_date'] == '' && $arrParams['end_date'] != '' ){				
				$dateArray = array(
					array(																
						'before'    => date('F jS, Y',strtotime($arrParams['end_date'])),						
						'inclusive' => true,
					),
				);
				$datequery = "AND post_date <= '".date('Y-m-d',strtotime($arrParams['end_date']))."' ";
			}
            /*$orders = get_posts( array(
                'posts_per_page' => $limit,
                'offset' => $offset,
                'post_type'   => 'shop_order',
                'post_status' => $orderStatus,
				'date_query' => $dateArray,
            ) );	*/
			$query = "Select ID, post_status from ".$wpdb->prefix."posts where post_type='shop_order' ";
			if( $orderStatus != "" )
				$query .= "and find_in_set(post_status,'".implode(",",$orderStatus)."')  ";
			if( $arrParams['start_date'] != '' || $arrParams['end_date'] != '' ){
				$query .= $datequery;
			}
			if( $arrParams['orders_option'] !='' && $arrParams['orders_id'] !='' && is_numeric($arrParams['orders_id']) ){
				$query .= "and ID ".$arrParams['orders_option']." ".$arrParams['orders_id']." ";	
			}
			if( $offset > 0 || $limit > 0 ){
				if( $offset > 0 && $limit < 0 ){
					$query .= "limit $offset, 5000";
				}else{
					$query .= "limit $offset, $limit";
				}
			}
			
			$orders = $wpdb->get_results( $wpdb->prepare( $query, "" ) );						
			
            $arrRows = array();
            $arrRows[] = array(
                'order_id',
                'date',
                'status',

                'order_shipping',
                'order_shipping_tax',
                'order_fees',
                'order_fee_tax',
                'order_tax',
                'cart_discount',
                'order_discount',
                'order_total',

                'payment_method',
                'shipping_method',

                'customer_user',

                'billing_first_name',
                'billing_last_name',
                'billing_email',
                'billing_phone',
                'billing_address_1',
                'billing_address_2',
                'billing_postcode',
                'billing_city',
                'billing_state',
                'billing_country',
                'billing_company',

                'shipping_first_name',
                'shipping_last_name',
                'shipping_address_1',
                'shipping_address_2',
                'shipping_postcode',
                'shipping_city',
                'shipping_state',
                'shipping_country',
                'shipping_company',

                'customer_note',

                'order_items',

                'order_notes',
                'shipping_method_1',
                'shipping_cost_1',
                'shipment_tracking',

            );
            foreach($orders as $order){
                if(empty($order->ID)){
                    continue;
                }

                $orderDetails = new WC_Order($order->ID);

                $custEmail = '';
                if(!empty($orderDetails->customer_user)){
                    $user_info = get_userdata($orderDetails->customer_user);
                    if(!empty($user_info->user_email)){
                        $custEmail = $user_info->user_email;
                    }
                }

                $calculationDetails = array(
                    $orderDetails->order_shipping,
                    $orderDetails->order_shipping_tax,
                    $orderDetails->order_fees,
                    $orderDetails->order_fee_tax,
                    $orderDetails->order_tax,
                    $orderDetails->cart_discount,
                    $orderDetails->order_discount,
                    $orderDetails->order_total,
                );

                $paymentDetails = array(
                    $orderDetails->payment_method,
                    $orderDetails->get_shipping_method(),
                    /*$orderDetails->get_items(),*/
                );

                $customerDetails = array(
                    $custEmail,
                );

                $billingDetails = array(
                    $orderDetails->billing_first_name,
                    $orderDetails->billing_last_name,
                    $orderDetails->billing_email,
                    $orderDetails->billing_phone,
                    $orderDetails->billing_address_1,
                    $orderDetails->billing_address_2,
                    $orderDetails->billing_postcode,
                    $orderDetails->billing_city,
                    $orderDetails->billing_state,
                    $orderDetails->billing_country,
                    $orderDetails->billing_company,
                );

                //$billingDetails = $orderDetails->get_address('billing');

                $shippingDetails = array(
                    $orderDetails->shipping_first_name,
                    $orderDetails->shipping_last_name,
                    $orderDetails->shipping_address_1,
                    $orderDetails->shipping_address_2,
                    $orderDetails->shipping_city,
                    $orderDetails->shipping_state,
                    $orderDetails->shipping_postcode,
                    $orderDetails->shipping_country,
                    $orderDetails->shipping_company,
                );
                //$shippingDetails = $orderDetails->get_address('shipping');

                $customerNote = array(
                    $orderDetails->customer_message,
                );

                $orderItemHtml = "";

                $orderItemsArr = $orderDetails->get_items();
                if(!empty($orderItemsArr)){
                    $itemHtml = array();
                    foreach($orderItemsArr as $itemDetails){
                        $pid = !empty($itemDetails['variation_id'])?$itemDetails['variation_id']:$itemDetails['product_id'];
                        $product = new WC_Product($pid);
                        $itemHtmlArr = array(
                            "Pid: ".$pid,
                            "SKU: ".$product->get_sku(),
                            "Qty: ".$itemDetails['qty'],
                            "Total: ".$itemDetails['line_total'],
                        );
                        $itemHtml[] = implode('|', $itemHtmlArr);
                    }

                    $orderItemHtml = implode("\n", $itemHtml);
                }

                $orderItems = array(
                    $orderItemHtml
                );

                $sm = $orderDetails->get_items( 'shipping' );
                $smK = '';
                $smC = '';
                if(!empty($sm[key($sm)])){
                    $smAct = $sm[key($sm)];
                    if(!empty($smAct)){
                        $smK = $smAct['method_id'];
                        $smC = $smAct['cost'];
                    }
                }
				$orderAppendDetailsVal = '';											
				if( is_array( $orderDetails->get_customer_order_notes() ) ){
					$orderAppendDetailsArray = $orderDetails->get_customer_order_notes();
					$orderAppendDetailsVal = $orderAppendDetailsArray[0]->comment_content;					
				}
                $orderAppendDetails = array(
                    $orderAppendDetailsVal,
                    $smK,
                    $smC,
                    "",
                ); 				

                $rw = array(
                    $order->ID,
                    $orderDetails->order_date,
                    $wcOrderStatus[$order->post_status],
                );

                $rw = array_merge_recursive($rw,$calculationDetails,$paymentDetails,$customerDetails, $billingDetails, $shippingDetails, $customerNote, $orderItems, $orderAppendDetails);

                //echo "<pre>"; var_dump(count($arrRows['0'])); var_dump(count($rw));var_dump($rw); exit;
                $arrRows[] = $rw;
            }

            if(count($arrRows)>1){
                $resp['status'] = 'success';
                $resp['mess'] = '';
                $resp['rows'] = $arrRows;
            }
            else{
                $resp['status'] = 'error';
                $resp['mess'] = 'No matched rows found.';
            }
        }		
        return $resp;
    }

    public static function createCsvFile($rows = array()){
        $fln = dirname(dirname(__FILE__)).'/tmp/'.time().rand(1,2).'.csv';

        if(!empty($rows)){
            $fl = fopen($fln, 'w');
            foreach($rows as $row){
                fputcsv($fl, $row);
            }
            fclose($fl);
        }

        return $fln;
    }
}


?>