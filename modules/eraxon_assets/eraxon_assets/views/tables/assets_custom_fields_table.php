<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'name',
];
$join = [
//     'LEFT JOIN ' . db_prefix() . 'assets_custom_field AS acf ON acf.assets_category_id = ' . db_prefix() . 'assets_categories.assets_category_id',
];

$sIndexColumn = 'id';
$sTable       = db_prefix().'assets_custom_field';
$filter       = [];
$where        = [];
$statusIds    = [];
$join=[];
    $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row        = [];
    $outputName = '<a href="#">'.$aRow['name'].'</a>';
    $outputName .= '<div class="row-options">';
    if (has_permission('products', '', 'delete')) {
        $outputName .= ' <a href="'.admin_url('eraxon_assets/eraxon_assets_custom_fields/edit/'.$aRow['id']).'" class="_edit">'._l('edit').'</a>';
        $outputName .= '| <a href="'.admin_url('eraxon_assets/eraxon_assets_custom_fields/delete/'.$aRow['id']).'" class="text-danger _delete">'._l('delete').'</a>';
    }
    $outputName .= '</div>';
    $row[]              = $outputName;
    $row[]              = get_catogory_values($aRow['id']);
    $row[]              = get_custom_field_values($aRow['id']);
  
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}