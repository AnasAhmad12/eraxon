<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'item_image',
    'item_name',
    'item_id',
    'qty',
    'serial_number',
    'price'
    //  db_prefix().'assets_available_inventory.*',
];
$sIndexColumn = 'item_id';
$sTable       = db_prefix().'assets_available_inventory';
$filter       = [];
$where        = [];
$statusIds    = [];
$join         = [
    'JOIN '.db_prefix().'assets_items_master ON '.db_prefix().'assets_items_master.id='.db_prefix().'assets_available_inventory.item_id',
];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];
$CI      = &get_instance();

$CI->load->model(['currencies_model']);
$base_currency = $CI->currencies_model->get_base_currency();


foreach ($rResult as $aRow) {
    $row        = [];
    $outputName = '<a href="#">'.$aRow['item_name'].'</a>';

    $row[]              =$aRow['item_id'];
    $row[]              = "<img src='".module_dir_url('eraxon_assets', 'uploads')."/{$aRow['item_image']}' class='img-thumbnail img-responsive zoom' onerror=\"this.src='".module_dir_url('eraxon_assets', 'uploads')."/image-not-available.png'\">";
    $row[]              = $outputName;
    $row[]              = $aRow['serial_number'];
    $row[]              = $aRow['qty'];
    $row[]              = $aRow['price'];
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}

