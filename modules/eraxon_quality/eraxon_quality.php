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

hooks()->add_action('admin_init','eraxon_quality_init_menu_items');
hooks()->add_action('admin_init', 'eraxon_quality_permissions');
hooks()->add_action('app_admin_footer', 'eraxon_quality_load_js');
hooks()->add_action('app_admin_head', 'eraxon_quality_add_head_components');
hooks()->add_action('before_cron_run', 'auto_distribution_cronjob');


register_activation_hook(Eraxon_quality,'eraxon_quality_activation_hook');

function eraxon_quality_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__.'/install.php');
       
}

register_language_files(Eraxon_quality,[Eraxon_quality]);

$CI = & get_instance();
$CI->load->helper(Eraxon_quality . '/eraxon_quality');

function eraxon_quality_init_menu_items()
{
    $CI = &get_instance();
	
 // if(has_permission('my_salary_slip','','view_own') || is_admin())
  //  {
     $CI->app_menu->add_sidebar_menu_item('eraxon_qa', [
            'collapse' => true,
            'name'     => 'QA Module',
            'position' => 60,
            'icon'     => 'fa fa-flag',
            'badge'    => [],
        ]);

    $CI->app_menu->add_sidebar_children_item('eraxon_qa', [
            'slug'     => 'qa_status',
            'name'     => 'QA Status',
            'href' => admin_url('eraxon_quality/qa_status'),
            'position' => 1
        ]);
    $CI->app_menu->add_sidebar_children_item('eraxon_qa', [
            'slug'     => 'qa_reviewer_status',
            'name'     => 'QA Reviewer Status',
            'href' => admin_url('eraxon_quality/qa_reviewer_status'),
            'position' => 2,
        ]);
    $CI->app_menu->add_sidebar_children_item('eraxon_qa', [
            'slug'     => 'qa_set_column',
            'name'     => 'Set Campaign Column',
            'href' => admin_url('eraxon_quality/manage_column'),
            'position' => 3,
        ]);
   // }

 }


function eraxon_quality_permissions($permissions)
{
    $config = [];

    $config['capabilities'] = [
                'view_own'   => _l('permission_view_own'),
                'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
                'create' => _l('permission_create'),
                'edit'   => _l('permission_edit'),
                'delete' => _l('permission_delete'),
    ];

     register_staff_capabilities('eraxon_qa',$config, 'QA Compatabiliy' );
}

function eraxon_quality_load_js() 
{
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, '/admin/eraxon_quality/campaign_column') === false)) {
		echo '<script src="' . module_dir_url(Eraxon_quality, 'assets/js/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(Eraxon_quality, 'assets/js/handsontable-chosen-editor.js') . '"></script>';
		echo '<script src="' . module_dir_url(Eraxon_quality, 'assets/js/handsontable.full.min.js') . '"></script>';
	}
}

function eraxon_quality_add_head_components() 
{
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, '/admin/eraxon_quality/campaign_column') === false)) {
		echo '<link href="' . module_dir_url(Eraxon_quality, 'assets/js/chosen.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(Eraxon_quality, 'assets/js/handsontable2.full.min.css') . '"  rel="stylesheet" type="text/css" />';
	}
}

 function make_distribution_group()
{
    $job_group_id =  hr_get_position_group_id_by_name('QA');
    $position_ids =  hr_get_list_job_position_by_jobgroupid($job_group_id);
    $staffid_with_daily_targets = hr_get_staff_ids_by_position_ids($position_ids);
    $staffid_with_daily_targets  = json_encode($staffid_with_daily_targets);
    update_option('auto_distribution_staffid_with_daily_targets',$staffid_with_daily_targets);
    update_option('auto_distribution_changing_factor',4); // 60
    update_option('auto_distribution_pending_factor',0); // As per max pending leads value 
}

 function move_pointer(&$data,$current)
    {
        
        foreach($data as $key => &$d)
        {
            //var_dump($data['staff_id']);
            if($d['staff_id'] != $current && $d['check-in'] == 1)
            {
                $d['pointer'] = 'p';
                $data[$key] = $d;
                break;
            }
        }
        
    }

function check_distribution($data)
    {
        $changing_factor = get_option('auto_distribution_changing_factor');

        foreach($data as $cell)
        {
            if($cell['assigned'] <= $changing_factor)
            {
                return true;
            }
        }
        return false;
    }

function auto_distribution_leads()
    {
        //$CI = &get_instance();
        //$CI->load->model('eraxon_quality/eraxon_quality_model');
        $current_date = date('Y-m-d');
        $qc_staff = get_option('auto_distribution_staffid_with_daily_targets');
        $qc_staff = json_decode($qc_staff,1);
         var_dump($qc_staff);

        //check if staff is present or absent
        $qc_present_staff = $this->eraxon_quality_model->get_qa_present_staff($qc_staff,$current_date);
        var_dump($qc_present_staff);

        $unassigned_leads = $this->eraxon_quality_model->get_unassigned_leads($current_date);
        $changing_factor = get_option('auto_distribution_changing_factor'); // 60

        
       // $pending_factor = get_option('auto_distribution_pending_factor'); // As per max pending leads value
        var_dump($unassigned_leads);
        if(count($unassigned_leads) > 0)
        {

            foreach($unassigned_leads as $leads)
            {
              foreach($qc_present_staff as $key => $qc_available_staff)
                {
                    if($qc_available_staff['check-in'] == 1 && $qc_available_staff['pointer'] == 'p')
                    {
                        if($qc_available_staff['assigned'] <= $changing_factor)
                        {
                            $this->eraxon_quality_model->assigned_lead_to($qc_available_staff['staff_id'],$leads['id']);
                            $this->move_pointer($qc_present_staff,$qc_available_staff['staff_id']);
                            $qc_present_staff[$key]['pointer'] = '';
                            $qc_present_staff[$key]['assigned'] += 1;
                            //var_dump($qc_available_staff);
                            break;  
                        }else
                        {
                            
                            if($this->check_distribution($qc_present_staff))
                            {
                                $this->move_pointer($qc_present_staff,$qc_available_staff['staff_id']);
                                $qc_present_staff[$key]['pointer'] = '';

                            }else
                            {
                                $pending_factor = $this->eraxon_quality_model->get_number_of_pending_leads($qc_present_staff);
                                var_dump($pending_factor);

                                foreach($qc_present_staff as $key2 => $qc_available_staff2)
                                {
                                    //update_option('auto_distribution_pending_factor',$pending_factor);
                                    //check pending factor
                                   
                                    if($qc_available_staff2['pending'] < $pending_factor)
                                    {
                                        $this->eraxon_quality_model->assigned_lead_to($qc_available_staff2['staff_id'],$leads['id']);
                                        $this->move_pointer($qc_present_staff,$qc_available_staff2['staff_id']);
                                        $qc_present_staff[$key2]['pointer'] = '';
                                        $qc_present_staff[$key2]['assigned'] += 1;
                                        break;
                                    }else{
                                        $this->move_pointer($qc_present_staff,$qc_available_staff2['staff_id']);
                                        $qc_present_staff[$key2]['pointer'] = '';
                                            
                                    } 

                                }
                                break;
                            }
                        }                      
                    }
                }      
            }


            update_option('auto_distribution_staffid_with_daily_targets',json_encode($qc_present_staff)); 
        }
        
    }

function auto_distribution_cronjob(){
    $CI = &get_instance();
    $CI->load->model('eraxon_quality/eraxon_quality_model');
    $current_date = date('Y-m-d');
    $start_cron_run_hour = $current_date . ' 00:00:00';
    $current_cron_run_hour = $current_date . ' ' . date('H:i:s');
    $run_hour = 2;//get_timesheets_option('hour_notification_approval_exp');
    if (is_numeric($run_hour)) {
        $hour_calculate = $CI->eraxon_quality_model->calculateTimeDifferenceInMinutes($start_cron_run_hour, $current_cron_run_hour);
        if (is_numeric($hour_calculate) && ($hour_calculate >= $run_hour)) {
            $data = array('testcronjobdate',date('Y-m-d H:i:s'));
            $CI->timesheets_model->testinsertcron($data);
        }
    }
    return;
}