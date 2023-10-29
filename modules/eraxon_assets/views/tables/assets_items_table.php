<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'item_name',
    'item_image',
    'item_description',

];
$sIndexColumn = 'id';
$sTable       = db_prefix().'assets_items_master';
$filter       = [];
$where        = [];
$statusIds    = [];
$join         = [
    // 'LEFT JOIN '.db_prefix().'assets_categories ON '.db_prefix().'assets_categories.assets_category_id='.db_prefix().'assets_items_master.assets_category_id',
];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','assets_category_id']);
$output  = $result['output'];
$rResult = $result['rResult'];
$CI      = &get_instance();

$CI->load->model(['currencies_model']);
$base_currency = $CI->currencies_model->get_base_currency();


foreach ($rResult as $aRow) {
    $row        = [];
    $outputName = '<a href="#">'.$aRow['item_name'].'</a>';
    $outputName .= '<div class="row-options">';
    if (has_permission('products', '', 'delete')) {
        $outputName .= ' <a href="'.admin_url('eraxon_assets/eraxon_assets_items/edit/'.$aRow['id']).'" class="_edit">'._l('edit').'</a>';
        $outputName .= '| <a href="'.admin_url('eraxon_assets/eraxon_assets_items/delete/'.$aRow['id']).'" class="text-danger _delete">'._l('delete').'</a>';
    }
    $outputName .= '</div>';
    $row[]              = $outputName;
    $row[]              = "<img src='".module_dir_url('eraxon_assets', 'uploads')."/{$aRow['item_image']}' class='img-thumbnail img-responsive zoom' onerror=\"this.src='".module_dir_url('eraxon_assets', 'uploads')."/image-not-available.png'\">";
    $row[]              = $aRow['item_description'];
    $row[]              = get_catogory_values_by_id($aRow['assets_category_id']);
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}

