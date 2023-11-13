<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Eraxon Ticket
Description: Eraxon Ticket Module (Internal Ticketing System)
Version: 1.0.0
Author: Anas Ahmad
*/
define('Eraxon_ticket', 'eraxon_ticket');


register_activation_hook(Eraxon_team,'eraxon_ticket_activation_hook');

function eraxon_ticket_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__.'/install.php');
}

register_language_files(Eraxon_ticket,[Eraxon_ticket]);


hooks()->add_action('admin_init','eraxon_ticket_init_menu_items');

function eraxon_ticket_init_menu_items()
{
    $CI = &get_instance();
	
	 //Team
    $CI->app_menu->add_sidebar_menu_item('er_ticket', [
        'collapse' => true,
        'name'     => 'Internal Support',
        'position' => 52,
        'icon'     => 'fa fa-people-group',
        'badge'    => [],
    ]);

     $CI->app_menu->add_sidebar_children_item('er_ticket', [
        'slug'     => 'eraxon_ticket',
        'name'     => 'Ticket',
        'href'     => admin_url('eraxon_ticket'),
        'position' => 2,
        'badge'    => [],
    ]);

    
  /*  $CI->app_menu->add_sidebar_children_item('er_ticket', [
        'slug'     => 'er_lead_board',
        'name'     => 'Lead Board',
        'href' => admin_url('eraxon_team/leadboard'),
        'position' => 3,
        'badge'    => [],
    ]);
*/
  
 }