<?php

defined('BASEPATH') or exit('No direct script access allowed');


function get_order_status_label($status)
{
    $html = '';
    if ($status == 1) {
        $html .= '<span class="label label-danger  s-status invoice-status-2">Undelivered</span>';
    }elseif ($status == 2) {
        $html .= '<span class="label label-success  s-status invoice-status-2">Delivered</span>';
    }elseif ($status == 3) {
        $html .= '<span class="label label-warning  s-status invoice-status-2">Cancel</span>';
    }

    return $html;
}
$aColumns = [
    'id',
    'clientid',//get_sql_select_client_company(),
    'order_date',
    'total',
    'order_type',
    'status',
];
$join = [
    'LEFT JOIN '.db_prefix().'staff ON '.db_prefix().'staff.staffid = '.db_prefix().'order_master.clientid',
    //'LEFT JOIN '.db_prefix().'invoices ON '.db_prefix().'invoices.id = '.db_prefix().'order_master.invoice_id',
];
if (has_permission('kiosk', '', 'view_own') && !is_admin()) {
$where = [
    'where '.db_prefix().'order_master.clientid = '.get_staff_user_id(),
];
}else{
    $where = [];
}

$sIndexColumn = 'id';
$sTable       = db_prefix().'order_master';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where , [
    db_prefix().'order_master.id',
    db_prefix().'order_master.clientid',
    //'deleted_customer_name',
]);
$output  = $result['output'];
$rResult = $result['rResult'];
$CI      = &get_instance();
$CI->load->model(['currencies_model']);
$base_currency = $CI->currencies_model->get_base_currency();
$options = '';




foreach ($rResult as $aRow) {
    $options = '';

    if (has_permission('kiosk', '', 'delivery_status') && $aRow['status'] != 2) 
    {
        $options .= '<button class="btn btn-success" onclick="delivered(this,'.$aRow['id'].')">Delivered</button> &nbsp;';
    }
   /* if (has_permission('POS', '', 'edit')) 
    {
        if($aRow['order_type']=="POS"){
        $options .=  '<a href="'.admin_url('products/pos/edit_pos/'.$aRow['id']).'" type="button" class="btn btn-success">'."Edit".'</a> &nbsp;';
        }
    }*/
    if (has_permission('POS', '', 'delete')) 
    {
        if($aRow['order_type']=="POS"){
        $options .=  '<a type="button" onclick="pos_delete(this,'.$aRow['id'].')" class="btn btn-success">'."Delete".'</a> &nbsp;';
        }
    }

   /* if (has_permission('kiosk', '', 'view_own') && $aRow['status'] != 2) 
    {
        $options .= '<button class="btn btn-warning" onclick="cancel(this,'.$aRow['id'].')">Cancel</button>&nbsp;';
    }*/
    if (has_permission('kiosk', '', 'delete') && $aRow['status'] != 2) 
    {
        $options .= '<button class="btn btn-danger" onclick="order_delete(this,'.$aRow['id'].')">Delete</button>';
    }
    $row   = [];
    $row[] = '<a href="'.admin_url('products/kiosk/staff_invoice/'.$aRow['id']).'" target="_blank">'.$aRow['id'].'</a>';
    //if (empty($aRow['deleted_customer_name'])) {n
        // $row[] = '<a href="'.admin_url('products/kiosk/staff_invoice/'.$aRow['id']).'">'.get_staff_full_name($aRow['clientid']).' ('.get_custom_field_value($aRow['clientid'],'staff_pseudo','staff',true).')</a>';
    //} else {
     //   $row[] = $aRow['deleted_customer_name'];
   // }

   if ($aRow['clientid']==0) {
    $row[] = '<a href="'.admin_url('products/kiosk/staff_invoice/'.$aRow['id']).'">'."Customer".'</a>';
    } else {
        $row[] = '<a href="'.admin_url('products/kiosk/staff_invoice/'.$aRow['id']).'">'.get_staff_full_name($aRow['clientid']).' ('.get_custom_field_value($aRow['clientid'],'staff_pseudo','staff',true).')</a>';

    }

    $row[]              = _d($aRow['order_date']);
    $row[]              = app_format_money($aRow['total'], $base_currency->name);
    $row[]              = $aRow['order_type'];
    $row[]              = get_order_status_label($aRow['status']);
    $row[]              = $options;
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
