<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'allownce')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "allowance` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(300) NOT NULL,
      `type` varchar(50) NOT NULL,
      `amount` decimal(50,4) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'deductions')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "deductions` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(300) NOT NULL,
      `type` varchar(50) NOT NULL,
      `amount` decimal(50,4) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}


if (!$CI->db->table_exists(db_prefix() . 'targets')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "targets` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(250) NOT NULL,
      `target` decimal(50,4) NOT NULL,
      `bonus` decimal(50,4) NOT NULL,
      `status` varchar(50) NOT NULL,
      `accumulative_bonus` decimal(50,4) NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'bonuses')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "bonuses` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(300) NOT NULL,
      `amount` decimal(50,4) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'salary_details_to_attendance')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "salary_details_to_attendance` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `salary_details_id` int NOT NULL,
      `presents` int(11) NOT NULL,
      `absents` int(11) NOT NULL,
      `paid_leaves` int(11) NOT NULL,
      `half_days` int(11) NOT NULL,
      `sandwitch` int(11) NOT NULL,
      `late` INT(11) NOT NULL,
      `late_amount` DECIMAL(50,4) NOT NULL,
      `basic_salary` decimal(50,3) NOT NULL,
      `absent_amount` decimal(50,3) NOT NULL,
      `half_days_amount` decimal(50,3) NOT NULL,
      `total_amount` decimal(50,4) NOT NULL,
      `created_at` datetime NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'salary_details_to_deductions')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "salary_details_to_deductions` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `salary_details_id` int(11) NOT NULL,
      `deduction_id` int(11) NOT NULL,
      `deduction_amount` decimal(50,4) NOT NULL,
      `total_amount` decimal(50,4) NOT NULL,
      `created_at` datetime NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'salary_details_to_allowances')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "salary_details_to_allowances` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
      `salary_details_id` int(11) NOT NULL,
      `allowance_id` int(11) NOT NULL,
      `allowance_amount` decimal(50,4) NOT NULL,
      `total_amount` decimal(50,4) NOT NULL,
      `created_at` datetime NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'salary_details_to_adjustments')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "salary_details_to_adjustments` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `salary_details_id` int(11) NOT NULL,
      `name` varchar(300) NOT NULL,
      `type` varchar(50) NOT NULL,
      `amount` decimal(50,4) NOT NULL,
      `created_at` datetime NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}


if (!$CI->db->table_exists(db_prefix() . 'salary_details')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "salary_details` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `employee_id` int(11) NOT NULL,
      `basic_salary` decimal(50,4) NOT NULL,
      `total_allowances` decimal(50,4) NOT NULL,
      `total_deductions` decimal(50,4) NOT NULL,
      `total_attendance` decimal(50,4) NOT NULL,
      `total_halfdays` decimal(50,4) NOT NULL,
      `total_late` decimal(50,4) DEFAULT NULL,
      `tax` decimal(50,4) NOT NULL,
      `gross_salary` decimal(50,4) NOT NULL,
      `net_salary` decimal(50,4) NOT NULL,
      `status` varchar(50) NOT NULL,
      `ack_status` varchar(50) NOT NULL,
      `date` date NOT NULL,
      `created_at` datetime NOT NULL,
      `updated_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
      `employee_timesheet` text,
      `sandwitch_timesheet` text,
      `deduct_transaction_id` int(11) DEFAULT NULL,
      `deposit_transaction_id` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'job_allowance_deduction')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "job_allowance_deduction` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `jobid` int(11) NOT NULL,
      `allowance_id` int(11) NOT NULL,
      `deduction_id` int(11) NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'bonus_slip')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "bonus_slip` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `performance_bonus` decimal(50,4) NOT NULL,
      `bonus` decimal(50,4) NOT NULL,
      `accumulative_bonus` decimal(10,0) NOT NULL,
      `employee_id` int(11) NOT NULL,
      `total_bonus` decimal(50,4) NOT NULL,
      `created_at` datetime NOT NULL,
      `month_year` date NOT NULL,
      `leads_achieved` int(11) NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'bonus_slip_bonus')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "bonus_slip_bonus` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `bonus_id` int(11) NOT NULL,
      `bonus_name` varchar(300) NOT NULL,
      `bonus_amount` decimal(50,4) NOT NULL,
      `bonus_slip_id` int(11) NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'bonus_slip_target_bonus')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "bonus_slip_target_bonus` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `leads_achieved` int(11) NOT NULL,
      `target_name` varchar(300) NOT NULL,
      `target_leads` int(11) NOT NULL,
      `target_bonus` decimal(50,4) NOT NULL,
      `accumulative_bonus` decimal(10,0) NOT NULL,
      `bonus_slip_id` int(11) NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'general_bonuses')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "general_bonuses` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `bonus_id` int(11) NOT NULL,
      `created_at` timestamp NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'employee_bonuses')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "employee_bonuses` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `employee_id` int(11) NOT NULL,
      `bonus_id` int(11) NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'eraxon_payroll_exception')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "eraxon_payroll_exception` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `exceptions` varchar(50) NOT NULL,
      `time_start_work` varchar(50) NOT NULL,
      `time_end_work` varchar(50) NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

