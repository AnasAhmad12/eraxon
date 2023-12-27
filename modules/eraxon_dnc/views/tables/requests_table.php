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
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where,['id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = []; 

    $row[]              = get_staff_full_name($aRow['id_staff']);
    $row[]              = $aRow['phonenumber'];
    $row[]              = $aRow['result'];
    if(has_permission('dnc_check','','view') || is_admin())
    {
    $row[]              = '<div class="tw-flex tw-items-center tw-space-x-3">
                            <a href="#" onclick="edit_as_request(this,'.$aRow['id'].'); return false"
                                        data-result="'.$aRow['result'].'" 
                                        class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" data-hide-from-client="0" >
                                    <i class="fa-regular fa-pen-to-square fa-lg"></i>
                            </a>
                            </div>';
    }
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
