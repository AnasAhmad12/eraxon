<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Eraxon Quality
Description: Eraxon Quality Module for Quality Assurance Team
Version: 1.0.0
Author: Anus Ahmad
*/

define('Eraxon_quality', 'eraxon_quality');

hooks()->add_action('admin_init', 'eraxon_quality_init_menu_items');
hooks()->add_action('admin_init', 'eraxon_quality_permissions');
hooks()->add_action('app_admin_footer', 'eraxon_quality_load_js');
hooks()->add_action('app_admin_head', 'eraxon_quality_add_head_components');


register_activation_hook(Eraxon_quality, 'eraxon_quality_activation_hook');

function eraxon_quality_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');

}

register_language_files(Eraxon_quality, [Eraxon_quality]);





function eraxon_quality_init_menu_items()
{


    $CI = &get_instance();
    $campaigns=$CI->db->get(db_prefix() . 'leads_type')->result();


    // if(has_permission('my_salary_slip','','view_own') || is_admin())
    //  {
    $CI->app_menu->add_sidebar_menu_item('eraxon_qa', [
        'collapse' => true,
        'name' => 'QA Module',
        'position' => 60,
        'icon' => 'fa fa-flag',
        'badge' => [],
    ]);

    $CI->app_menu->add_sidebar_children_item('eraxon_qa', [
        'slug' => 'qa_status',
        'name' => 'QA Status',
        'href' => admin_url('eraxon_quality/qa_status'),
        'position' => 1
    ]);
    $CI->app_menu->add_sidebar_children_item('eraxon_qa', [
        'slug' => 'qa_reviewer_status',
        'name' => 'QA Reviewer Status',
        'href' => admin_url('eraxon_quality/qa_reviewer_status'),
        'position' => 2,
    ]);
    $CI->app_menu->add_sidebar_children_item('eraxon_qa', [
        'slug' => 'qa_set_column',
        'name' => 'Set Campaign Column',
        'href' => admin_url('eraxon_quality/manage_column'),
        'position' => 3,
    ]);
    foreach ($campaigns as $i => $c) {

        $CI->app_menu->add_sidebar_children_item('eraxon_qa', [
            'slug' => 'qa_campain' . $c->name,
            'name' => $c->name,
            'href' => admin_url('eraxon_quality/get_campaign_sheet/' . $c->id),
            'position' => $i,
        ]);
    }
    // }

}


function eraxon_quality_permissions($permissions)
{
    $config = [];

    $config['capabilities'] = [
        'view_own' => _l('permission_view_own'),
        'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('eraxon_qa', $config, 'QA Compatabiliy');
}

function eraxon_quality_load_js()
{
    $viewuri = $_SERVER['REQUEST_URI'];

}

function eraxon_quality_add_head_components()
{
    $viewuri = $_SERVER['REQUEST_URI'];
    if (!(strpos($viewuri, '/admin/eraxon_quality/get_campaign_sheet') === false)) {
        echo '<link href="' . module_dir_url(Eraxon_quality, 'assets/js/chosen.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(Eraxon_quality, 'assets/js/handsontable2.full.min.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri, '/admin/eraxon_quality/get_campaign_sheet') === false)) {
        // echo '<script src="' . module_dir_url(Eraxon_quality, 'assets/js/chosen.jquery.js') . '"></script>';
        // echo '<script src="' . module_dir_url(Eraxon_quality, 'assets/js/handsontable-chosen-editor.js') . '"></script>';
        echo '<script src="' . module_dir_url(Eraxon_quality, 'assets/js/handsontable.full.min.js') . '"></script>';
    }
}