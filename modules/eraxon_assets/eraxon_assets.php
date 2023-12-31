<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Eraxon Assets
Description: Eraxon Assets
Version: 1.0.0
*/



define('Eraxon_assets', 'eraxon_assets');

hooks()->add_action('admin_init', 'eraxon_assets_init_menu_items');
// Get codeigniter instance
$CI = &get_instance();

// Load module helper file
$CI->load->helper(Eraxon_assets . '/eraxon_assets');

register_activation_hook(Eraxon_assets, 'eraxon_assets_activation_hook');

function eraxon_assets_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__ . '/install.php');
}

define('ASSET_MODULE_UPLOAD_FOLDER', module_dir_path(Eraxon_assets, 'uploads/'));

hooks()->add_action('app_admin_head', 'asset_add_head_components');
function asset_add_head_components()
{
   
    
        $CI = &get_instance();
        echo '<script src="'.module_dir_url('eraxon_assets', 'assets/js/autcomplete.js').'?v='.$CI->app_scripts->core_version().'"></script>';
    
}



hooks()->add_filter('get_upload_path_by_type', 'item_upload_folder', 10, 2);
function item_upload_folder($path, $type)
{
	if ('items' == $type) {
		return ASSET_MODULE_UPLOAD_FOLDER;
	}
	return $path;
}


register_language_files(Eraxon_assets, [Eraxon_assets]);

hooks()->add_action('app_admin_head', 'eraxon_assets_add_head_components');
function eraxon_assets_add_head_components()
{
	// Check module is enable or not (refer install.php)
	if ('1' == get_option('products_enabled')) {
		$CI = &get_instance();
		// echo '<link href="'.module_dir_url('eraxon_assets', 'assets/css/products.css').'?v='.$CI->app_scripts->core_version().'"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url('eraxon_assets', 'assets/js/asset_category.js') . '?v=' . $CI->app_scripts->core_version() . '"></script>';
	}
}
add_option("stock_in_purchase_approval", "", 1);
add_option("stock_loss_approval", "", 1);





function eraxon_assets_init_menu_items()
{
	
		$CI = &get_instance();

		if (has_permission('asset_items', '', 'view')) {

		$CI->app_menu->add_sidebar_menu_item('eraxon_assets_menu', [
			'slug' => "Assets",
			'name' => "Assets",
			'icon' => 'fa fa-file',
			'position' => 9,
		]);
	
	}

		if (has_permission('asset_items', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug' => 'assets_items',
				'name' => "Items",
				'href' => admin_url('eraxon_assets/eraxon_assets_items'),
				'position' => 1,
			]);
		}

		if (has_permission('asset_inventory', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug' => 'assets_inventory',
				'name' => "Inventory",
				'href' => admin_url('eraxon_assets/eraxon_assets_inventory'),
				'position' => 2,
			]);
		}

		if (has_permission('asset-category', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug' => 'assets_categories',
				'name' => "Categories",
				'href' => admin_url('eraxon_assets/eraxon_assets_categories'),
				'position' => 3,
			]);
		}
		//fix permissons 
		if (has_permission('asset-custom_fields', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug' => 'assets_custom_fields',
				'name' => "Custom Fields ",
				'href' => admin_url('eraxon_assets/eraxon_assets_custom_fields'),
				'position' => 4,
			]);
		}

		if (has_permission('asset-purchase', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug' => 'assets_purchase',
				'name' => "Stock In ",
				'href' => admin_url('eraxon_assets/eraxon_assets_stock_in'),
				'position' => 5,
			]);
		}

		if (has_permission('asset-allocation', '', 'view')||has_permission('asset-allocation', '', 'view_own')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug' => 'assets_purchase',
				'name' => "Allocation ",
				'href' => admin_url('eraxon_assets/eraxon_assets_allocation'),
				'position' => 6,
			]);
		}

		if (has_permission('asset-setting', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug' => 'assets_settings',
				'name' => "Settings ",
				'href' => admin_url('eraxon_assets/settings'),
				'position' => 7,
			]);
		}

		if (has_permission('asset-request', '', 'view')||has_permission('asset-request', '', 'view_own')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug' => 'assets_request',
				'name' => "Request Inventory",
				'href' => admin_url('eraxon_assets/eraxon_assets_request_inventory'),
				'position' => 8,
			]);
		}

		if (has_permission('asset-loss', '', 'view')||has_permission('asset-request', '', 'view_own')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug' => 'assets_loss',
				'name' => "Loss Management",
				'href' => admin_url('eraxon_assets/eraxon_assets_loss_management'),
				'position' => 9,
			]);
		}



	}


hooks()->add_action('admin_init', 'eraxon_assets_permissions');

function eraxon_assets_permissions($permissions)
{
    $config = [];
    $config2 = [];
    $config3 = [];
    $config4 = [];


    $config4['capabilities'] = [
                'add_dock'   => 'Add Dock',
                'manage_dock' => 'Manage Dock',
    ];

    $config3['capabilities'] = [
                'change_status'   => 'Change Status',
                'add_notes' => 'Add Notes',
                'edit_leads'   => 'Edit Leads',
                'delete' => _l('permission_delete'),
    ];
    

    $config['capabilities'] = [
                'view_own'   => _l('permission_view_own'),
                'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
                'create' => _l('permission_create'),
                'edit'   => _l('permission_edit'),
                'delete' => _l('permission_delete'),
    ];

     $config2['capabilities'] = [
                'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
                'create' => _l('permission_create'),
				'edit'   => _l('permission_edit'),
                'delete' => _l('permission_delete'),
    ];
	$inventory['capabilities'] = [
		'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
];

    register_staff_capabilities('asset_items',$config2, 'Asset Items' );
    register_staff_capabilities('asset_inventory',$inventory, 'Asset Inventory' );
    register_staff_capabilities('asset-category',$config2, 'Asset Category' );
    register_staff_capabilities('asset-custom_fields',$config2, 'Asset Custom Fields' );
    register_staff_capabilities('asset-purchase',$config2, 'Asset Stock In');
    register_staff_capabilities('asset-allocation',$config, 'Asset Allocation');
    register_staff_capabilities('asset-setting',$inventory, 'Asset Setting');
    register_staff_capabilities('asset-request',$config, 'Asset Request Inventory');
    register_staff_capabilities('asset-loss',$config, 'Asset Loss Management');


}