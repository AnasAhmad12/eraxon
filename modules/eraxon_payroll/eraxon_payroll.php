<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Eraxon Payroll
Description: Eraxon Payroll Module (Allownces, Deductions)
Version: 1.0.0
Author: Waqar Hussain
*/

define('Eraxon_payroll', 'eraxon_payroll');

hooks()->add_action('admin_init','eraxon_payroll_init_menu_items');


register_activation_hook(Eraxon_payroll,'eraxon_payroll_activation_hook');

function eraxon_payroll_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__.'/install.php');
       
}

register_language_files(Eraxon_payroll,[Eraxon_payroll]);

function eraxon_payroll_init_menu_items()
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
    if(has_permission('my_salary_slip','','view_own') || is_admin())
    {
     $CI->app_menu->add_sidebar_menu_item('er_mypayroll', [
            'collapse' => true,
            'name'     => 'Staff Payroll',
            'position' => 53,
            'icon'     => 'fa fa-file-contract',
            'badge'    => [],
        ]);

    $CI->app_menu->add_sidebar_children_item('er_mypayroll', [
            'slug'     => 'pro_salary',
            'name'     => 'My Salary',
            'href' => admin_url('eraxon_payroll/my_salary'),
            'position' => 1,
            'badge'    => [],
        ]);
    }
	 // My Forms
     if(has_permission('hr_permission','','view') || is_admin())
    {
        $CI->app_menu->add_sidebar_menu_item('er_payroll', [
            'collapse' => true,
            'name'     => 'HR Payroll',
            'position' => 53,
            'icon'     => 'fa fa-file-contract',
            'badge'    => [],
        ]);
        
    
        $CI->app_menu->add_sidebar_children_item('er_payroll', [
            'slug'     => 'pro_',
            'name'     => 'Allownces',
            'href' => admin_url('eraxon_payroll/allownces'),
            'position' => 2,
            'badge'    => [],
        ]);
    
        $CI->app_menu->add_sidebar_children_item('er_payroll', [
            'slug'     => 'pro_deductions',
            'name'     => 'Deductions',
            'href' => admin_url('eraxon_payroll/deductions'),
            'position' => 3,
            'badge'    => [],
        ]);
    
        $CI->app_menu->add_sidebar_children_item('er_payroll', [
            'slug'     => 'pro_targets',
            'name'     => 'Targets',
            'href' => admin_url('eraxon_payroll/targets'),
            'position' => 4,
            'badge'    => [],
        ]);

         $CI->app_menu->add_sidebar_children_item('er_payroll', [
            'slug'     => 'era_pro_settings',
            'name'     => 'Salary Settings',
            'href' => admin_url('eraxon_payroll/salary_settings'),
            'position' => 5,
            'badge'    => [],
        ]);
    
        $CI->app_menu->add_sidebar_children_item('er_payroll', [
            'slug'     => 'pro_generate_salary_slip',
            'name'     => 'Generate Salary Slip',
            'href' => admin_url('eraxon_payroll/generate_salary_slip'),
            'position' => 6,
            'badge'    => [],
        ]);
        
        $CI->app_menu->add_sidebar_children_item('er_payroll', [
            'slug'     => 'pro_bonuses',
            'name'     => 'Bonuses',
            'href' => admin_url('eraxon_payroll/bonuses'),
            'position' => 7,
            'badge'    => [],
        ]);

        $CI->app_menu->add_sidebar_children_item('er_payroll', [
            'slug'     => 'pro_bonuses_settings',
            'name'     => 'Bonuses Settings',
            'href' => admin_url('eraxon_payroll/bonuses_settings'),
            'position' => 8,
            'badge'    => [],
        ]);

        $CI->app_menu->add_sidebar_children_item('er_payroll', [
            'slug'     => 'pro_generate_bonus_slip',
            'name'     => 'Generate Bonuses Slip',
            'href' => admin_url('eraxon_payroll/generate_bonus_slip'),
            'position' => 9,
            'badge'    => [],
        ]);
    }
 }

 hooks()->add_action('admin_init', 'eraxon_payroll_permissions');

function eraxon_payroll_permissions($permissions)
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
    $config3['capabilities'] = [
        'view_own'   => _l('permission_view_own'),
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    $config4['capabilities'] = [
        'view_own'   => _l('permission_view_own'),
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    $config5['capabilities'] = [
        'view_own'   => _l('permission_view_own'),
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    $config6['capabilities'] = [
        
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        /*'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),*/
    ];
    $config7['capabilities'] = [
        'view_own'   => _l('permission_view_own'),
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        
    ];
    register_staff_capabilities('allownces',$config, 'Allownces' );
    register_staff_capabilities('deductions',$config2, 'Deductions' );
    register_staff_capabilities('targets',$config3, 'Targets' );
    register_staff_capabilities('bonuses',$config3, 'Bonuses' );
    register_staff_capabilities('generate_salary_slip',$config3, 'Generate Salary Slip' );
    register_staff_capabilities('hr_permission',$config6, 'Payroll HR Permissions' );
    register_staff_capabilities('my_salary_slip',$config7, 'My Salary' );
}