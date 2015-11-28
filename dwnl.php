<?php
include_once('../../../wp-load.php');

if(!class_exists('WCSExport')){ return; }

$ermsArr = array();

$arrNMess = array();
if(!empty($_POST['wcs_export_csv_ord'])){
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
            $ermsArr[] = 1;
        }
    }
    else if(!empty($startDate)){
        //Seems invalid date format.
        $arrNMess[] = array(
            'type'=>'error',
            'mess'=>'Invalid date format for start date. Date format should be YYYY-mm-dd',
        );
        $ermsArr[] = 2;
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
            $ermsArr[] = 3;
        }
    }
    else if(!empty($endDate)){
        //Seems invalid date format.
        $arrNMess[] = array(
            'type'=>'error',
            'mess'=>'Invalid date format for end date. Date format should be YYYY-mm-dd',
        );
        $ermsArr[] = 4;
    }

    if(!empty($_POST['limit_records'])){
        if($_POST['limit_records']<=0){
            $arrNMess[] = array(
                'type'=>'error',
                'mess'=>'If limit records set, make sure it should be greater than zero.',
            );
            $ermsArr[] = 5;
        }
    }

    if(empty($_POST['order_status'])){
        $arrNMess[] = array(
            'type'=>'error',
            'mess'=>'Select order status.',
        );
        $ermsArr[] = 6;
    }

    if(!empty($_POST['offset_records'])){
        if($_POST['offset_records']<0){
            $arrNMess[] = array(
                'type'=>'error',
                'mess'=>'If offset records set, make sure it should be greater than or equal to zero.',
            );
            $ermsArr[] = 7;
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
            $ermsArr[] = 8;
        }
        else if($resp['status'] == 'success'){
            if(!empty($resp['rows'])){
                $fileNm = WCSExport::createCsvFile($resp['rows']);
                $fc = file_get_contents($fileNm);
                header("Content-type: text/csv");
                header("Content-Disposition: attachment; filename=orders-export-".date('Y_m_d_H_i_s').".csv");
                header("Pragma: no-cache");
                header("Expires: 0");
                echo $fc;
                unlink($fileNm);
                exit;
            }
        }

    }
}
else{
    $arrNMess[] = array(
        'type'=>'error',
        'mess'=>'Please try again later.',
    );
    $ermsArr[] = 9;
}

$erms = implode(',',$ermsArr);
header('Location: '.site_url().'/wp-admin/admin.php?page=wcs-export&erms='.$erms);exit;
?>