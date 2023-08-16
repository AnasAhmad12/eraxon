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

//Profile Performance Tab

hooks()->add_filter('hr_profile_tab_name', 'myform_add_tab_name', 20);
hooks()->add_filter('hr_profile_tab_content', 'myform_add_tab_content', 20);
hooks()->add_action('hr_profile_load_js_file', 'myform_load_js_file');

/**
 * myform add tab name
 * @param  [type] $row  
 * @param  [type] $aRow 
 * @return [type]       
 */
function myform_add_tab_name($tab_names)
{
    $tab_names[] = 'performance';
    return $tab_names;
}


/**
 * myform add tab content
 * @param  [type] $tab_content_link 
 * @return [type]                   
 */
function myform_add_tab_content($tab_content_link)
{
    if(!(strpos($tab_content_link, 'hr_record/includes/performance') === false)){
        $tab_content_link = 'eraxon_myform/include/myform_performance_tab_content';
  }
    
    return $tab_content_link;
}

/**
 * myform load js file
 * @param  [type] $group_name 
 * @return [type]             
 */
function myform_load_js_file($group_name)
{
     
    echo  require 'modules/eraxon_myform/assets/js/performance_js.php';

}






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

    
    $CI->app_menu->add_sidebar_children_item('myforms', [
        'slug'     => 'pro_advance_salary',
        'name'     => 'Advance Salary',
        'href' => admin_url('eraxon_myform/advance_salary'),
        'position' => 3,
        'badge'    => [],
    ]);


    $CI->app_menu->add_sidebar_children_item('myforms', [
        'slug'     => 'pro_other_forms',
        'name'     => 'Other Forms',
        'href' => admin_url('eraxon_myform/others'),
        'position' => 4,
        'badge'    => [],
    ]);


     $CI->app_menu->add_sidebar_menu_item('teamlead', [
        'collapse' => true,
        'name'     => 'Team Lead',
        'position' => 52,
        'icon'     => 'fa fa-file-contract',
        'badge'    => [],
    ]);

    if(has_permission('teamlead','','add_dock'))
    {
         $CI->app_menu->add_sidebar_children_item('teamlead', [
            'slug'     => 'team_lead_menu1',
            'name'     => 'Add Docks',
            'href' => admin_url('eraxon_myform/team_lead_manage_dock'),
            'position' => 4,
            'badge'    => [],
        ]);
    }
    if(has_permission('teamlead','','manage_dock'))
    {
      $CI->app_menu->add_sidebar_children_item('teamlead', [
        'slug'     => 'team_lead_menu2',
        'name'     => 'Manage Docks',
        'href' => admin_url('eraxon_myform/manage_docks'),
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
                'view_own'   => _l('permission_view_own'),
                'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
                'create' => _l('permission_create'),
                'edit'   => _l('permission_edit'),
                'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('leads_caps',$config3, 'Leads Sub Capabilities' );
    register_staff_capabilities('other_form',$config, 'Other Forms' );
    register_staff_capabilities('advance_salary',$config2, 'Advance Salary' );
    register_staff_capabilities('teamlead',$config4, 'Team Lead' );
   

}