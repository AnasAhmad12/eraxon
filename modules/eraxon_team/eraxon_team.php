<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Eraxon Team
Description: Eraxon Team Module (Team Creations, Live Lead Board)
Version: 1.0.0
Author: Anas Ahmad
*/
define('Eraxon_team', 'eraxon_team');


register_activation_hook(Eraxon_team,'eraxon_team_activation_hook');

function eraxon_team_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__.'/install.php');
}

register_language_files(Eraxon_team,[Eraxon_team]);


hooks()->add_action('admin_init','eraxon_team_init_menu_items');

function eraxon_team_init_menu_items()
{
    $CI = &get_instance();
	
	 //Team
    $CI->app_menu->add_sidebar_menu_item('er_team', [
        'collapse' => true,
        'name'     => 'Teams',
        'position' => 52,
        'icon'     => 'fa fa-people-group',
        'badge'    => [],
    ]);

     $CI->app_menu->add_sidebar_children_item('er_team', [
        'slug'     => 'eraxon_team',
        'name'     => 'Teams',
        'href'     => admin_url('eraxon_team/teams'),
        'position' => 2,
        'badge'    => [],
    ]);

    
    $CI->app_menu->add_sidebar_children_item('er_team', [
        'slug'     => 'er_lead_board',
        'name'     => 'Lead Board',
        'href' => admin_url('eraxon_team/leadboard'),
        'position' => 3,
        'badge'    => [],
    ]);

  
 }


/*hooks()->add_action('admin_init', 'eraxon_myform_permissions');

function eraxon_myform_permissions($permissions)
{
    $config = [];
    $config2 = [];
    $config3 = [];


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

   

}*/
