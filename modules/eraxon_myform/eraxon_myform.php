<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Eraxon My Form
Description: Eraxon Form Module (leaves, Loan, Advance salary)
Version: 1.0.0
Author: Anas Ahmad
*/


define('Eraxon_myform', 'eraxon_myform');

hooks()->add_action('admin_init','eraxon_myform_init_menu_items');


register_activation_hook(Eraxon_myform,'eraxon_myform_activation_hook');

function eraxon_myform_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__.'/install.php');
}

register_language_files(Eraxon_myform,[Eraxon_myform]);

function eraxon_myform_init_menu_items()
{
    $CI = &get_instance();
	// if(is_admin())
	// {
	// 	$CI = &get_instance();

	// 	$CI->app_menu->add_sidebar_menu_item('eraxon_hr_menu',[
	// 		'name' => _l('eraxon_hr_menu'),
	// 		'href'  => admin_url('eraxon_hr'),
	// 		'icon'  => 'fa fa-file',
	// 		'position' => 5,
	// 	]);
	// }

	 // My Forms
    $CI->app_menu->add_sidebar_menu_item('myforms', [
        'collapse' => true,
        'name'     => 'My Forms',
        'position' => 52,
        'icon'     => 'fa fa-file-contract',
        'badge'    => [],
    ]);

     $CI->app_menu->add_sidebar_children_item('myforms', [
        'slug'     => 'pro_leave',
        'name'     => 'Leaves',
        'href' => admin_url('timesheets/requisition_manage'),
        'position' => 2,
        'badge'    => [],
    ]);

    if((has_permission('advance_salary','','view') && has_permission('advance_salary','','view_own')) || is_admin()){ 
    $CI->app_menu->add_sidebar_children_item('myforms', [
        'slug'     => 'pro_advance_salary',
        'name'     => 'Advance Salary',
        'href' => admin_url('eraxon_myform/advance_salary'),
        'position' => 3,
        'badge'    => [],
    ]);
}
if((has_permission('other_form','','view') && has_permission('other_form','','view_own')) || is_admin()){ 
    $CI->app_menu->add_sidebar_children_item('myforms', [
        'slug'     => 'pro_other_forms',
        'name'     => 'Other Forms',
        'href' => admin_url('eraxon_myform/others'),
        'position' => 4,
        'badge'    => [],
    ]);
 }
}

hooks()->add_action('admin_init', 'eraxon_myform_permissions');

function eraxon_myform_permissions($permissions)
{
    $config = [];
    $config2 = [];

    $config['capabilities'] = [
                'view_own'   => _l('permission_view_own'),
                'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
                'create' => _l('permission_create'),
                'edit'   => _l('permission_edit'),
                'delete' => _l('permission_delete'),
    ];
     $config2['capabilities'] = [
                'view_own'   => _l('permission_view_own'),
                'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
                'create' => _l('permission_create'),
                'edit'   => _l('permission_edit'),
                'delete' => _l('permission_delete'),
    ];
    register_staff_capabilities('other_form',$config, 'Other Forms' );
    register_staff_capabilities('advance_salary',$config, 'Advance Salary' );
}