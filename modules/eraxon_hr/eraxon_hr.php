<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Eraxon Hr
Description: Eraxon HR and Payroll Module
Version: 1.0.0
*/


define('Eraxon_hr', 'eraxon_hr');

hooks()->add_action('admin_init','eraxon_hr_init_menu_items');


register_activation_hook(Eraxon_hr,'eraxon_hr_activation_hook');

function eraxon_hr_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__.'/install.php');
}

register_language_files(Eraxon_hr,[Eraxon_hr]);

function eraxon_hr_init_menu_items()
{
	if(is_admin())
	{
		$CI = &get_instance();

		$CI->app_menu->add_sidebar_menu_item('eraxon_hr_menu',[
			'name' => _l('eraxon_hr_menu'),
			'href'  => admin_url('eraxon_hr'),
			'icon'  => 'fa fa-file',
			'position' => 5,
		]);
	}
}

hooks()->add_action('admin_init', 'eraxon_hr_permissions');

function eraxon_hr_permissions($permissions)
{
    $config = [];

    $config['capabilities'] = [
            'send_mass_emails'   => 'Send Mass Emails',
            'create_templates'   => 'Create Mail Templates',
    ];

    register_staff_capabilities(
        'prefix-mass-emails', 
        $config, 
        _l('prefix_mass_emails')
    );
}