<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Eraxon Wallet
Description: Eraxon Wallet Module (generate wallet and wallet widget for the staff and store all types of transaction such as Dock, KIOSK, and Salary )
Version: 1.0.0
Author: Anas Ahmad
*/
define('Eraxon_wallet', 'eraxon_wallet');

register_payment_gateway('eraxon_wallet_gateway', Eraxon_wallet);

register_activation_hook(Eraxon_wallet,'eraxon_wallet_activation_hook');

function eraxon_wallet_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__.'/install.php');
   
}

register_language_files(Eraxon_wallet,[Eraxon_wallet]);


hooks()->add_action('admin_init','eraxon_wallet_init_menu_items');

function eraxon_wallet_init_menu_items()
{
    $CI = &get_instance();
	
	 //Team
    $CI->app_menu->add_sidebar_menu_item('er_wallet', [
        'collapse' => true,
        'name'     => 'Wallet',
        'position' => 52,
        'icon'     => 'fa fa-wallet',
        'badge'    => [],
    ]);

     $CI->app_menu->add_sidebar_children_item('er_wallet', [
        'slug'     => 'eraxon_my_wallet',
        'name'     => 'My Wallet',
        'href'     => admin_url('eraxon_wallet/my_wallet'),
        'position' => 2,
        'badge'    => [],
    ]);

    
    /*$CI->app_menu->add_sidebar_children_item('er_wallet', [
        'slug'     => 'eraxon_my_transaction',
        'name'     => 'My Transactions',
        'href' => admin_url('eraxon_wallet/my_transaction'),
        'position' => 3,
        'badge'    => [],
    ]);*/

  
 }


hooks()->add_action('admin_init', 'eraxon_wallet_permissions');

function eraxon_wallet_permissions($permissions)
{
    $config = [];
    $config2 = [];
    //$config3 = [];


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

     /*$config3['capabilities'] = [
                'change_status'   => 'Change Status',
                'add_notes' => 'Add Notes',
                'edit_leads'   => 'Edit Leads',
                'delete' => _l('permission_delete'),
    ];*/
    
    register_staff_capabilities('my_wallet',$config, 'My Wallet' );
    register_staff_capabilities('wallet_transactions',$config2, 'Wallet Transactions' );
   // register_staff_capabilities('advance_salary',$config2, 'Advance Salary' );

   

}
