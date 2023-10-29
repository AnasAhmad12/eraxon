<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'purchase_date',
    'status',
    'total',
];
$sIndexColumn = 'id';
$sTable       = db_prefix().'assets_stock_table_master';
$filter       = [];
$where        = [];
$statusIds    = [];
$join         = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);
$output  = $result['output'];
$rResult = $result['rResult'];
$CI      = &get_instance();

$CI->load->model(['currencies_model']);
$base_currency = $CI->currencies_model->get_base_currency();

function get_status_label($status)
{
    $html = '';
    if ($status == 0) {
        $html .= '<span class="label label-danger  s-status invoice-status-2">Pending</span>';
    }elseif ($status == 1 ) {
        $html .= '<span class="label label-warning  s-status invoice-status-2">Approved</span>';
    }

    return $html;
}


foreach ($rResult as $aRow) {
    $options='';

    $row        = [];
    $outputName=$aRow['purchase_date'];
    
    if (has_permission('assets-stock-in', '', 'delete')) { 
        $options .= '<a href="'.admin_url('eraxon_assets/eraxon_assets_stock_in/delete/'.$aRow['id']).'"class="btn btn-danger")">Delete</a> &nbsp;'; 
    }
    if (has_permission('assets-stock-in', '', 'delete')) { 
        $options .= '<a href="'.admin_url('eraxon_assets/eraxon_assets_stock_in/edit/'.$aRow['id']).'"class="btn btn-success")">Edit</a> &nbsp;'; 
    }

    $row[]              = $aRow['id'];
    $row[]              = $outputName;
    $row[]              = get_status_label($aRow['status']);
    $row[]              = $aRow['total']. " ".$base_currency->name;
    $row[]              = $options;
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
} 
