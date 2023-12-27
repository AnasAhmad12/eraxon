<?php
defined('BASEPATH') or exit('No direct script access allowed');



$aColumns     = ['assets_category_name', 'assets_category_description'];
$sIndexColumn = 'assets_category_id';
$sTable       = db_prefix().'assets_categories';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['assets_category_id']);
$output       = $result['output'];
$rResult      = $result['rResult'];


foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); ++$i) {
        $_data = '<a href="#" data-toggle="modal" data-target="#product_category_modal" data-id="'.$aRow['assets_category_id'].'">'.$aRow[$aColumns[$i]].'</a>';
        $row[] = $_data;
    }
    $options            = icon_btn('#', 'fa fa-pencil-square', 'btn-default', ['data-toggle' => 'modal', 'data-target' => '#asset_category_modal', 'data-id' => $aRow['assets_category_id']]);
    $row[]              = $options .= icon_btn('eraxon_assets/eraxon_assets_categories/delete_category/'.$aRow['assets_category_id'], 'fa fa-remove', 'btn-danger _delete');
    $output['aaData'][] = $row;
}
