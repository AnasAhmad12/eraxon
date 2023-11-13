<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id_staff',
    'phonenumber',
    'result',
];

if (has_permission('dnc_check', '', 'view_own') && !is_admin()) {
$where = [
    'where '.db_prefix().'dnc_request.id_staff = '.get_staff_user_id(),
];
}else{
    $where = [];
}

$sIndexColumn = 'id';
$sTable       = db_prefix().'dnc_request';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = []; 

    $row[]              = get_staff_full_name($aRow['id_staff']);
    $row[]              = $aRow['phonenumber'];
    $row[]              = $aRow['result'];
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
