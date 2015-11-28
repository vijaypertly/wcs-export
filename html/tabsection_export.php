<?php defined('WCS_EXPORT_ACCESS') or die(); ?>
<?php
if(!empty($_POST)){
    /*$arrNMess = array();
    $startDate = !empty($_POST['start_date'])?$_POST['start_date']:'';
    $arrStartDate = explode('-', $startDate);

    $endDate = !empty($_POST['end_date'])?$_POST['end_date']:'';
    $arrEndDate = explode('-', $endDate);

    if(!empty($arrStartDate['0']) && !empty($arrStartDate['1']) && !empty($arrStartDate['2'])){
        if(checkdate($arrStartDate['1'], $arrStartDate['2'], $arrStartDate['0'])){
            //
        }
        else{
            $arrNMess[] = array(
                'type'=>'error',
                'mess'=>'Invalid date for start date. Date format should be YYYY-mm-dd.',
            );
        }
    }
    else if(!empty($startDate)){
        //Seems invalid date format.
        $arrNMess[] = array(
            'type'=>'error',
            'mess'=>'Invalid date format for start date. Date format should be YYYY-mm-dd',
        );
    }

    if(!empty($arrEndDate['0']) && !empty($arrEndDate['1']) && !empty($arrEndDate['2'])){
        if(checkdate($arrEndDate['1'], $arrEndDate['2'], $arrEndDate['0'])){
            //
        }
        else{
            $arrNMess[] = array(
                'type'=>'error',
                'mess'=>'Invalid date for end date. Date format should be YYYY-mm-dd.',
            );
        }
    }
    else if(!empty($endDate)){
        //Seems invalid date format.
        $arrNMess[] = array(
            'type'=>'error',
            'mess'=>'Invalid date format for end date. Date format should be YYYY-mm-dd',
        );
    }

    if(!empty($_POST['limit_records'])){
        if($_POST['limit_records']<=0){
            $arrNMess[] = array(
                'type'=>'error',
                'mess'=>'If limit records set, make sure it should be greater than zero.',
            );
        }
    }

    if(empty($_POST['order_status'])){
        $arrNMess[] = array(
            'type'=>'error',
            'mess'=>'Select order status.',
        );
    }

    if(!empty($_POST['offset_records'])){
        if($_POST['offset_records']<0){
            $arrNMess[] = array(
                'type'=>'error',
                'mess'=>'If offset records set, make sure it should be greater than or equal to zero.',
            );
        }
    }

    if(empty($arrNMess)){
        //No error messages so for.
        $arrParams = array(
            'start_date'=>$startDate,
            'end_date'=>$endDate,
            'limit_records'=>$_POST['limit_records'],
            'offset_records'=>$_POST['offset_records'],
            'order_status'=>$_POST['order_status'],
        );
        $resp = WCSExport::getRecords($arrParams);
        if($resp['status'] == 'error'){
            $arrNMess[] = array(
                'type'=>'error',
                'mess'=>$resp['mess'],
            );
        }
        else if($resp['status'] == 'success'){
            if(!empty($resp['rows'])){
                $fileNm = WCSExport::createCsvFile($resp['rows']);
                exit;
            }
        }

    }

    WCSExport::notifyMess($arrNMess); $arrNMess = array();*/
}
if(!empty($_GET['erms'])){
    $arrErs = explode(',', $_GET['erms']);
    $arrErrs = array(
        '1'=>'Invalid date for start date. Date format should be YYYY-mm-dd.',
        '2'=>'Invalid date format for start date. Date format should be YYYY-mm-dd',
        '3'=>'Invalid date for end date. Date format should be YYYY-mm-dd.',
        '4'=>'Invalid date format for end date. Date format should be YYYY-mm-dd',
        '5'=>'If limit records set, make sure it should be greater than zero.',
        '6'=>'Select order status.',
        '7'=>'If offset records set, make sure it should be greater than or equal to zero.',
        '8'=>'No records found.',
        '9'=>'Please try again later.',
    );
    if(!empty($arrErs)){
        foreach($arrErs as $erC){
            if(!empty($arrErrs[$erC])){
                $ms = $arrErrs[$erC];
                echo '<div class="error"><p>'.$ms.'</p></div>';
            }
        }
    }
}
?>

<div class="complete-wrap">
    <h1 id="js-settings">Export Orders</h1>
    <form id="js-afrsettings" action="<?php echo WCS_EXPORT_PLUGIN_URL."/dwnl.php"; ?>" method="post">
        <input type="hidden" name="wcs_export_csv_ord" value="yes">
        <table class="form-table">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="order_status">Order Status </label>
                    </th>
                    <td>
                        <select name="order_status[]" multiple style="height: 200px; width: 200px">
                            <option value="all" selected="selected">All</option>
                            <option value="wc-pending">Pending Payment</option>
                            <option value="wc-processing">Processing</option>
                            <option value="wc-on-hold">On Hold</option>
                            <option value="wc-completed">Completed</option>
                            <option value="wc-cancelled">Cancelled</option>
                            <option value="wc-refunded">Refunded</option>
                            <option value="wc-failed">Failed</option>
                        </select>
                    </td>
                </tr>

                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="start_date">Start Date </label>
                    </th>
                    <td>
                        <input type="text" value="" class="date_exp" name="start_date" />
                    </td>
                </tr>

                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="end_date">End Date </label>
                    </th>
                    <td>
                        <input type="text" value="" class="date_exp" name="end_date" />
                    </td>
                </tr>

                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="limit_records">Limit Records </label>
                    </th>
                    <td>
                        <input type="number" value="" name="limit_records" />
                    </td>
                </tr>

                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="offset_records">Offset Records </label>
                    </th>
                    <td>
                        <input type="number" value="" name="offset_records" />
                    </td>
                </tr>

            </tbody>
        </table>
        <p class="submit">
            <button class="wps-btn wps-btn-blue" type="submit">
                <span class="text">Export</span>
            </button>
        </p>
    </form>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.date_exp').datepicker({
            dateFormat : 'yy-mm-dd'
        });
    });
</script>