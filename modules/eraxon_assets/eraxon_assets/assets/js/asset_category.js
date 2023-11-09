"use strict";

window.addEventListener('load',function() {

   	appValidateForm($('#asset-category-modal'), {
        assets_category_name: 'required',
        assets_category_description: 'required'
	}, manage_asset_category);

	$('#asset_category_modal').on('show.bs.modal', function(e) {
		var invoker = $(e.relatedTarget);
		var group_id = $(invoker).data('id');
		$('#asset_category_modal .add-title').removeClass('hide');
		$('#asset_category_modal .edit-title').addClass('hide');
		$('#asset_category_modal input[name="assets_category_id"]').val('');
		$('#asset_category_modal input[name="assets_category_name"]').val('');
		$('#asset_category_modal :input[name="assets_category_description"]').val('');
		if (typeof(group_id) !== 'undefined') {
			$('#asset_category_modal input[name="assets_category_id"]').val(group_id);
			$('#asset_category_modal .add-title').addClass('hide');
			$('#asset_category_modal .edit-title').removeClass('hide');
			$('#asset_category_modal input[name="assets_category_name"]').val($(invoker).parents('tr').find('td').eq(0).text());
			$('#asset_category_modal :input[name="assets_category_description"]').val($(invoker).parents('tr').find('td').eq(1).text());
		}
	});
});

function manage_asset_category(form) {
	var data = $(form).serialize();
	var url = form.action;
	$.post(url, data).done(function(response) {
		response = JSON.parse(response);
		if (response.success == true) {
			if($.fn.DataTable.isDataTable('.table-asset-category')){
				$('.table-asset-category').DataTable().ajax.reload();
			}
			alert_float('success', response.message);
			$('#asset_category_modal').modal('hide');
		} else {
			alert_float('danger', response.message);
		}
	});
	return false;
}