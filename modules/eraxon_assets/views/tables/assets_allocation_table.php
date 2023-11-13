<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'id',
    'staff_id',
    'allocation_date',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'assets_allocation_master';
$filter = [];
$statusIds = [];
$join = [
    // 'LEFT JOIN '.db_prefix().'assets_categories ON '.db_prefix().'assets_categories.assets_category_id='.db_prefix().'assets_items_master.assets_category_id',
];

if (has_permission('asset_allocation', '', 'view_own') && !is_admin() ) {
    $where = [
        'where staff_id = '.get_staff_user_id(),
    ];
    }
    else{
        $where = [];
    }

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);
$output = $result['output'];
$rResult = $result['rResult'];
$CI = &get_instance();


foreach ($rResult as $aRow) {
    $row        = [];
    $outputName = "";
    $row[] = $aRow['id'];
    //Have to Detail page 
    $row[] = '<a href="' . admin_url('eraxon_assets/eraxon_assets_allocation/staff_allocated_items/' . $aRow['id']) . '">' . get_staff_full_name($aRow['staff_id']) . ' (' . get_custom_field_value($aRow['staff_id'], 'staff_pseudo', 'staff', true) . ')</a>';
    $row[] = $aRow['allocation_date'];


    if (has_permission('asset-allocation', '', 'edit')) {

        $outputName .= ' <a href="' . admin_url('eraxon_assets/eraxon_assets_allocation/edit/' . $aRow['id']) . '" class="_edit">' . _l('edit') . '</a>';
    }
    if (has_permission('asset-allocation', '', 'delete')) {
    $outputName .= '| <a href="' . admin_url('eraxon_assets/eraxon_assets_allocation/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $row[]= $outputName;

    $output['aaData'][] = $row;
}

