<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Eraxon DNC
Description: Eraxon DNC Module (Check DNC Numbers via Scrub API)
Version: 1.0.0
Author: Anas Ahmad
*/

define('Eraxon_dnc', 'eraxon_dnc');

register_activation_hook(Eraxon_dnc,'eraxon_dnc_activation_hook');

function eraxon_dnc_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__.'/install.php');
}

register_language_files(Eraxon_dnc,[Eraxon_dnc]);

hooks()->add_action('admin_init','eraxon_dnc_init_menu_items');

function eraxon_dnc_init_menu_items()
{
    $CI = &get_instance();

    if(has_permission('dnc_check','','view') || has_permission('dnc_check','','view_own') || is_admin())
    {
         $CI->app_menu->add_sidebar_menu_item('er_dnc', [
                'collapse' => true,
                'name'     => 'DNC Checker',
                'position' => 58,
                'icon'     => 'fa fa-file-contract',
                'badge'    => [],
            ]);

        $CI->app_menu->add_sidebar_children_item('er_dnc', [
                'slug'     => 'dnc_checker',
                'name'     => 'Checker',
                'href' => admin_url('eraxon_dnc/manage_dnc'),
                'position' => 1,
                'badge'    => [],
            ]);
         $CI->app_menu->add_sidebar_children_item('er_dnc', [
                'slug'     => 'dnc_manage',
                'name'     => 'Manage',
                'href' => admin_url('eraxon_dnc/all_dnc_requests'),
                'position' => 1,
                'badge'    => [],
            ]);
   }
	 
 }

hooks()->add_action('admin_init', 'eraxon_dnc_permissions');

function eraxon_dnc_permissions()
{
    $config = [];

    $config['capabilities'] = [
                'view_own'   => _l('permission_view_own'),
                'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
    ];

    register_staff_capabilities('dnc_check',$config, 'DNC Checker' );
}

