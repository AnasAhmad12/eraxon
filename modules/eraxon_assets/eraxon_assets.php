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

hooks()->add_action('admin_init','eraxon_assets_init_menu_items');
// Get codeigniter instance
$CI = &get_instance();

// Load module helper file
$CI->load->helper(Eraxon_assets.'/eraxon_assets');

register_activation_hook(Eraxon_assets,'eraxon_assets_activation_hook');

function eraxon_assets_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__.'/install.php');
}

define('ASSET_MODULE_UPLOAD_FOLDER', module_dir_path(Eraxon_assets, 'uploads/'));


hooks()->add_filter('get_upload_path_by_type', 'item_upload_folder', 10, 2);
function item_upload_folder($path, $type)
{
    if ('items' == $type) {
        return ASSET_MODULE_UPLOAD_FOLDER;
    }
    return $path;
}


register_language_files(Eraxon_assets,[Eraxon_assets]);

hooks()->add_action('app_admin_head', 'eraxon_assets_add_head_components');
function eraxon_assets_add_head_components()
{
    // Check module is enable or not (refer install.php)
    if ('1' == get_option('products_enabled')) {
        $CI = &get_instance();
        // echo '<link href="'.module_dir_url('eraxon_assets', 'assets/css/products.css').'?v='.$CI->app_scripts->core_version().'"  rel="stylesheet" type="text/css" />';
        echo '<script src="'.module_dir_url('eraxon_assets', 'assets/js/asset_category.js').'?v='.$CI->app_scripts->core_version().'"></script>';
    }
}
add_option("stock_in_purchase_approval", "", 1);
add_option("stock_loss_approval", "", 1);





function eraxon_assets_init_menu_items()
{
	if(is_admin())
	{
		$CI = &get_instance();

		$CI->app_menu->add_sidebar_menu_item('eraxon_assets_menu',[
			'slug' => "Assets",
			'name' => "Assets",
			'icon'  => 'fa fa-file',
			'position' => 5,
		]);


		if (has_permission('asset-items', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug'     => 'assets_items',
				'name'     => "Items",
				'href'     => admin_url('eraxon_assets/eraxon_assets_items'),
				'position' => 1,
			]);
		}

		if (has_permission('asset-items', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug'     => 'assets_inventory',
				'name'     => "Inventory",
				'href'     => admin_url('eraxon_assets/eraxon_assets_inventory'),
				'position' => 2,
			]);
		}

		if (has_permission('asset-category', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug'     => 'assets_categories',
				'name'     => "Categories",
				'href'     => admin_url('eraxon_assets/eraxon_assets_categories'),
				'position' => 3,
			]);
		}
			//fix permissons 
		if (has_permission('asset-custom_fields', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug'     => 'assets_custom_fields',
				'name'     => "Custom Fields ",
				'href'     => admin_url('eraxon_assets/eraxon_assets_custom_fields'),
				'position' => 4,
			]);
		}

		if (has_permission('asset-purchase', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug'     => 'assets_purchase',
				'name'     => "Stock In ",
				'href'     => admin_url('eraxon_assets/eraxon_assets_stock_in'),
				'position' => 5,
			]);
		}
														
		if (has_permission('asset-purchase', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug'     => 'assets_purchase',
				'name'     => "Allocation ",
				'href'     => admin_url('eraxon_assets/eraxon_assets_allocation'),
				'position' => 6,
			]);
		}

		if (has_permission('asset-purchase', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug'     => 'assets_settings',
				'name'     => "Settings ",
				'href'     => admin_url('eraxon_assets/settings'),
				'position' => 7,
			]);
		}

		if (has_permission('asset-purchase', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug'     => 'assets_request',
				'name'     => "Request Inventory",
				'href'     => admin_url(''),
				'position' => 8,
			]);
		}

		if (has_permission('asset-purchase', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('eraxon_assets_menu', [
				'slug'     => 'assets_loss',
				'name'     => "Loss Management",
				'href'     => admin_url('eraxon_assets/eraxon_assets_loss_management'),
				'position' => 9,
			]);
		}



	}
}

hooks()->add_action('admin_init', 'eraxon_assets_permissions');



function eraxon_assets_permissions($permissions)
{
    $config = [];

    $config['capabilities'] = [
		'view'   => "View Assets Categories",
        'create' => "Create Assets Categories",
        'edit'   =>  "Edit Assets Categories",
        'delete' => "Delete Assets Categories",
    ];

    register_staff_capabilities(
        'asset-category', 
        $config, 
		'Assets Categories'
    );
}