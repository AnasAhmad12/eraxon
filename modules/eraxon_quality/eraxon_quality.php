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
hooks()->add_action('auto_cronjob_run', 'make_distribution_group');
hooks()->add_action('auto_cronjob_run', 'auto_distribution_leads');


register_activation_hook(Eraxon_quality, 'eraxon_quality_activation_hook');

function eraxon_quality_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');

}

register_language_files(Eraxon_quality, [Eraxon_quality]);
$CI = & get_instance();
$CI->load->helper(Eraxon_quality . '/eraxon_quality');




function eraxon_quality_init_menu_items()
{


    $CI = &get_instance();
    // $campaigns= $CI->eraxon_quality_model->get_campaigns();
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
    if(has_permission('qa_department','','qa_manager')){
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

    $CI->app_menu->add_sidebar_children_item('eraxon_qa', [
        'slug' => 'qa_settings',
        'name' => 'Settings',
        'href' => admin_url('eraxon_quality/settings'),
        'position' => 5,
    ]);
      
       $CI->app_menu->add_sidebar_children_item('eraxon_qa', [
            'slug' => 'qa_reports',
            'name' => 'QA Reports',
            'href' => admin_url('eraxon_quality/qa_reports'),
            'position' => 8
        ]);
       
    
    
}
if(has_permission('qa_department','','qa_manager')||has_permission('qa_department','','qa_person')||has_permission('qa_department','','qa_reviewer')){
    $CI->app_menu->add_sidebar_children_item('eraxon_qa', [
        'slug' => 'campaign_sheet',
        'name' => 'Campaigns Sheet',
        'href' => admin_url('eraxon_quality/all_campaign_sheet'),
        'position' => 4,
    ]);
}

    if(has_permission('qa_department','','qa_manager')){
    $CI->app_menu->add_sidebar_children_item('eraxon_qa', [
        'slug' => 'qa_distribution',
        'name' => 'Distribute Leads ',
        'href' => admin_url('eraxon_quality/distribute_leads'),
        'position' => 6,
    ]);
    
}


}


function eraxon_quality_permissions($permissions)
{
    $config = [];

    $config['capabilities'] = [
        'qa_manager' => 'QA Manager',
        'qa_person' => 'QA Person',
        'qa_reviewer' => 'QA Reviewer'
    ];

    register_staff_capabilities('qa_department', $config, 'QA Department');
}

function eraxon_quality_load_js()
{
    $viewuri = $_SERVER['REQUEST_URI'];

}

function eraxon_quality_add_head_components()
{
    $viewuri = $_SERVER['REQUEST_URI'];
    if (!(strpos($viewuri, '/admin/eraxon_quality/all_campaign_sheet') === false) || !(strpos($viewuri, '/admin/eraxon_quality/distribute_leads') === false)) {
        echo '<link href="' . module_dir_url(Eraxon_quality, 'assets/js/chosen.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(Eraxon_quality, 'assets/js/handsontable2.full.min.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri, '/admin/eraxon_quality/all_campaign_sheet') === false)||!(strpos($viewuri, '/admin/eraxon_quality/distribute_leads') === false)) {
        // echo '<script src="' . module_dir_url(Eraxon_quality, 'assets/js/chosen.jquery.js') . '"></script>';
        // echo '<script src="' . module_dir_url(Eraxon_quality, 'assets/js/handsontable-chosen-editor.js') . '"></script>';
        echo '<script src="' . module_dir_url(Eraxon_quality, 'assets/js/handsontable.full.min.js') . '"></script>';
    }
}

function make_distribution_group()
{
    $current_hr = date('G'); //6
    $run_hour = get_option('auto_quality_distribution_group');
  
   if($current_hr==$run_hour)
    {
      
        $job_group_id =  hr_get_position_group_id_by_name('QA');
        $position_ids =  hr_get_list_job_position_by_jobgroupid($job_group_id);
        $staffid_with_daily_targets = hr_get_staff_ids_by_position_ids($position_ids);
        $staffid_with_daily_targets  = json_encode($staffid_with_daily_targets);
        update_option('auto_distribution_staffid_with_daily_targets',$staffid_with_daily_targets);
        update_option('auto_distribution_pending_factor',0); // As per max pending leads value 
  		//update_option('auto_quality_distribution_group','qwdsdqwqw');
   }
   
}

    function move_pointer(&$data,$current)
    {
        $counter = 0;
        $first = 1;
        foreach($data as $key => &$d)
        {
            //var_dump($data['staff_id']);
            if($d['staff_id'] != $current && $d['check-in'] == 1 && $d['myturn'] != 1)
            {   
                $d['myturn'] = 1;
                $d['pointer'] = 'p';
                $data[$key] = $d;
                break;
            }else{
                $counter++;
            }
        }

        if(count($data) == $counter)
        {
            foreach($data as $key2 => &$d2)
            {
                if($first == 1 && $d2['check-in'] == 1 && $d2['myturn'] == 1 && $d2['pointer'] != 'p')
                {
                    $d2['pointer'] = 'p';
                    $data[$key2] = $d2;
                    $first++;
                }else{
					$d2['pointer'] = '';
                    $d2['myturn'] = 0;
                    $data[$key2] = $d2; 
                }
            }
        }
        
    }
   /* function move_pointer(&$data,$current)
    {
        $counter = 0;
        $first = 1;
        $number_of_checkin = 0;
        foreach($data as $key => &$d)
        {
            if($d['check-in'] == 1)
            {
                $number_of_checkin++;
            }
        }
        foreach($data as $key => &$d)
        {
            //var_dump($data['staff_id']);
            if($d['staff_id'] != $current && $d['check-in'] == 1 && $d['myturn'] != 1)
            {   
                $d['myturn'] = 1;
                $d['pointer'] = 'p';
                $data[$key] = $d;
                break;
            }else if($d['check-in'] == 1){
                $counter++;
            }
        }

        if($number_of_checkin == $counter)
        {
            foreach($data as $key2 => &$d2)
            {
                if($d['check-in'] == 1)
                {
                    if($first == 1)
                    {
                        $d2['pointer'] = 'p';
                        $data[$key2] = $d2;
                        $first++;
                    }else{

                        $d2['myturn'] = 0;
                        $data[$key2] = $d2; 
                    }
                }
            }
        }
        
    }*/

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

/*    function auto_distribution_leads()
    {
        $auto_check = get_option('auto_distribution_manual_automatic_check'); 

        if(!$auto_check)
        {
            return;
        }

        $CI = &get_instance();
        $CI->load->model('eraxon_quality/eraxon_quality_model');
        $current_date = date('Y-m-d');
        $start_cron_run_hour = $current_date . ' 00:00:00';
        $current_cron_run_hour = $current_date . ' ' . date('H:i:s');
        $run_hour = 1;//get_timesheets_option('hour_notification_approval_exp');
        $hour_calculate = $CI->eraxon_quality_model->calculateTimeDifferenceInMinutes($start_cron_run_hour, $current_cron_run_hour);

        if (is_numeric($hour_calculate) && ($hour_calculate >= $run_hour)) 
        {

            $qc_staff = get_option('auto_distribution_staffid_with_daily_targets');
            $qc_staff = json_decode($qc_staff,1);
            
            $limit_count = 10;

            //check if staff is present or absent
            $qc_present_staff = $CI->eraxon_quality_model->get_qa_present_staff($qc_staff,$current_date);
            $checkin_counter = 0; 
            foreach($qc_present_staff as $checkin )
            {
                if($checkin['check-in'] == 1)
                {
                    $checkin_counter++;
                }

            }
			$limit_count *= $checkin_counter;
            $unassigned_leads = $CI->eraxon_quality_model->get_unassigned_leads($current_date,$limit_count);
            $changing_factor = get_option('auto_distribution_changing_factor'); // 60

            
           // $pending_factor = get_option('auto_distribution_pending_factor'); // As per max pending leads value
           // var_dump($unassigned_leads);
            if(count($unassigned_leads) > 0)
            {

                foreach($unassigned_leads as $leads)
                {
                  foreach($qc_present_staff as $key => $qc_available_staff)
                  {
                        if($qc_available_staff['check-in'] == 1 && $qc_available_staff['pointer'] == 'p')
                        {
                            if(($qc_available_staff['assigned'] <= $qc_available_staff['daily_target']))
                            {
                                $CI->eraxon_quality_model->assigned_lead_to($qc_available_staff['staff_id'],$leads['id']);
                                //$pending_factor = $CI->eraxon_quality_model->get_number_of_pending_leads($qc_present_staff);
                                if($checkin_counter > 1)
                                {
                                    move_pointer($qc_present_staff,$qc_available_staff['staff_id']);
                                    $qc_present_staff[$key]['pointer'] = '';
                                }
                                
                                $qc_present_staff[$key]['assigned'] += 1;
                                // add notification here
                                break;  
                            }else
                            {
                                if($qc_available_staff['fix'] == 0 )
                                {
                                   
                                        $pending_factor = $CI->eraxon_quality_model->get_number_of_pending_leads($qc_present_staff);
                                        //var_dump($pending_factor);
                                  		if($pending_factor <= 5)
                                        {
                                            $pending_factor = 10;
                                        }

                                        foreach($qc_present_staff as $key2 => $qc_available_staff2)
                                        {
                                           
                                            if(($qc_available_staff2['pending'] < $pending_factor) && ($qc_available_staff2['fix'] == 0 ) && ($qc_available_staff2['check-in'] == 1))
                                            {
                                                $CI->eraxon_quality_model->assigned_lead_to($qc_available_staff2['staff_id'],$leads['id']);
                                                if($checkin_counter > 1)
                                                {
                                                    move_pointer($qc_present_staff,$qc_available_staff2['staff_id']);
                                                    $qc_present_staff[$key2]['pointer'] = '';
                                                }
                                                
                                                $qc_present_staff[$key2]['assigned'] += 1;
                                                // add notification here
                                                break;
                                            }else{

                                                if($checkin_counter > 1)
                                                {
                                                    move_pointer($qc_present_staff,$qc_available_staff2['staff_id']);
                                                    $qc_present_staff[$key2]['pointer'] = '';
                                                }
                                                
                                                    
                                            } 

                                        }
                                       
                                    

                                }
                                
                               break;  
                            }                      
                        }
                    }      
                }

				
                    update_option('auto_distribution_staffid_with_daily_targets',json_encode($qc_present_staff)); 
                
                
            }
        }
    }*/

    function auto_distribution_leads()
    {
        $auto_check = get_option('auto_distribution_manual_automatic_check'); 

        if(!$auto_check)
        {
            return;
        }

        $CI = &get_instance();
        $CI->load->model('eraxon_quality/eraxon_quality_model');
        $current_date = date('Y-m-d');
        $start_cron_run_hour = $current_date . ' 00:00:00';
        $current_cron_run_hour = $current_date . ' ' . date('H:i:s');
        $run_hour = 1;//get_timesheets_option('hour_notification_approval_exp');
        $hour_calculate = $CI->eraxon_quality_model->calculateTimeDifferenceInMinutes($start_cron_run_hour, $current_cron_run_hour);

        if (is_numeric($hour_calculate) && ($hour_calculate >= $run_hour)) 
        {

            $qc_staff = get_option('auto_distribution_staffid_with_daily_targets');
            $qc_staff = json_decode($qc_staff,1);
            
            $limit_count = 10;
            //$priority_set = 0;

            //check if staff is present or absent
            $qc_present_staff = $CI->eraxon_quality_model->get_qa_present_staff($qc_staff,$current_date);
            $checkin_counter = 0; 
            foreach($qc_present_staff as $checkin )
            {
                if($checkin['check-in'] == 1)
                {
                    $checkin_counter++;
                }

            }
            $limit_count *= $checkin_counter;
            $unassigned_leads = $CI->eraxon_quality_model->get_unassigned_leads($current_date,$limit_count);
            
            if(count($unassigned_leads) > 0)
            {

                foreach($unassigned_leads as $leads)
                {
                  foreach($qc_present_staff as $key => $qc_available_staff)
                  {
                        if($qc_available_staff['check-in'] == 1 && $qc_available_staff['pointer'] == 'p')
                        {
                            if(($qc_available_staff['assigned'] <= $qc_available_staff['daily_target']))
                            {
                                $CI->eraxon_quality_model->assigned_lead_to($qc_available_staff['staff_id'],$leads['id']);

                                if($checkin_counter > 1)
                                {
                                    move_pointer($qc_present_staff,$qc_available_staff['staff_id']);
                                    $qc_present_staff[$key]['pointer'] = '';
                                }
                                
                                $qc_present_staff[$key]['assigned'] += 1;
                                break;
                                //$priority_set = 1;
                                // add notification here
                                 
                            }else if($qc_available_staff['fix'] == 0 || $qc_available_staff['fix'] == '0')
                            {
                                $pending_factor = $CI->eraxon_quality_model->get_number_of_pending_leads($qc_present_staff);
                                   

                                    if(($qc_available_staff['pending'] < $pending_factor))
                                    {
                                        $CI->eraxon_quality_model->assigned_lead_to($qc_available_staff['staff_id'],$leads['id']);
                                        if($checkin_counter > 1)
                                        {
                                            move_pointer($qc_present_staff,$qc_available_staff['staff_id']);
                                            $qc_present_staff[$key]['pointer'] = '';
                                        }
                                            $qc_present_staff[$key]['assigned'] += 1;
                                            break;
                                            //$priority_set = 0;
                                            // add notification here
                                               
                                    }else
                                    {

                                        if($checkin_counter > 1)
                                        {
                                            move_pointer($qc_present_staff,$qc_available_staff['staff_id']);
                                            $qc_present_staff[$key]['pointer'] = '';
                                        }
                                                              
                                    }                                     

                        }else
                        {
                            if($checkin_counter > 1)
                            {
                                move_pointer($qc_present_staff,$qc_available_staff['staff_id']);
                                $qc_present_staff[$key]['pointer'] = '';
                            }
                            //$priority_set = 0;
                        }                              
                                                 
                   }/*else
                   {
                        if($checkin_counter > 1)
                        {
                            move_pointer($qc_present_staff,$qc_available_staff['staff_id']);
                            $qc_present_staff[$key]['pointer'] = '';
                        }
                            //$priority_set = 0;
                    } */
                }
              }      
                
                update_option('auto_distribution_staffid_with_daily_targets',json_encode($qc_present_staff)); 

            }
 
        }
    }