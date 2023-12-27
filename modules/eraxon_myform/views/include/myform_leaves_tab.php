<?php
$table_data = array(
  _l('id'),
  '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="table_registration_leave"><label></label></div>',
  _l('Subject'),
  _l('name'),
  _l('start_time'),
  _l('end_time'),
  _l('approver'),
  _l('Follower'),
  _l('reason'),
  _l('Type'),
  _l('status'),
  _l('date_created'),
  _l('options'),
);
render_datatable($table_data, 'table_registration_leave',
  array('customizable-table'),
  array(
    'id' => 'table_registration_leave',
    'data-last-order-identifier' => 'table_registration_leave',
    'data-default-order' => get_table_last_order('table_registration_leave'),
  )); ?>