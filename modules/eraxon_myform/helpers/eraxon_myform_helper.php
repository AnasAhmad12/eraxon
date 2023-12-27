<?php
defined('BASEPATH') or exit('No direct script access allowed');

function advance_salary_helper($staffid)
{
    $CI = &get_instance();
    $CI->load->model('eraxon_myform/eraxon_myform_model');
    $data['advance_salary'] = $CI->eraxon_myform_model->get_advance_salary(false, $staffid);
    return $data;
}

function others_form_helper($staffid)
{
    $CI = &get_instance();
    $CI->load->model('eraxon_myform/eraxon_myform_model');
    $data['other_requests'] = $CI->eraxon_myform_model->get_other_requests(false,$staffid);
    return $data;
}

