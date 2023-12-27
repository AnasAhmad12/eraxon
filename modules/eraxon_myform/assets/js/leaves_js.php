<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>

	$(function () {
		var table_registration_leave = $('table.table-table_registration_leave');
		  var table_additional_timesheets  = $('table.table-table_additional_timesheets');

		var requisitionServerParams = {
			"status_filter": "[name='status_filter[]']",
			"rel_type_filter": "[name='rel_type_filter[]']",
			"chose": "[name='chose']",
			"department_filter": "[name='department_filter[]']",
		};

		var table_contract = $('.table-table_contract');
		var staffid = <?php echo $staffid; ?>;
		initDataTable(table_registration_leave, admin_url + 'timesheets/table_registration_leave/'+staffid, [1], [1], requisitionServerParams, [1, 'asc',]);
		
	});




</script>