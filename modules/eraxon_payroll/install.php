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
      `id` int NOT NULL AUTO_INCREMENT,
      `salary_details_id` int NOT NULL,
      `paid` int NOT NULL,
      `leaves` int NOT NULL,
      `total_leaves` varchar(50) NOT NULL,
      `basic_salary` decimal(50,3) NOT NULL,
      `sandwitch` int NOT NULL,
      `half_leaves` varchar(50) NOT NULL,
      `total_amount` decimal(50,4) NOT NULL,
      `total_half_leaves_amount` decimal(50,4) NOT NULL,
      `created_at` datetime NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'salary_details_to_deductions')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "salary_details_to_deductions` (
      `id` int NOT NULL AUTO_INCREMENT,
      `salary_details_id` int NOT NULL,
      `deduction_id` int NOT NULL,
      `deduction_amount` decimal(50,4) NOT NULL,
      `total_amount` decimal(50,4) NOT NULL,
      `created_at` datetime NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'salary_details_to_allowances')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "salary_details_to_allowances` (
      `id` int NOT NULL AUTO_INCREMENT,
      `salary_details_id` int NOT NULL,
      `allowance_id` int NOT NULL,
      `allowance_amount` decimal(50,4) NOT NULL,
      `total_amount` decimal(50,4) NOT NULL,
      `created_at` datetime NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'salary_details')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "salary_details` (
      `id` int NOT NULL AUTO_INCREMENT,
      `employee_id` int NOT NULL,
      `basic_salary` decimal(50,4) NOT NULL,
      `total_allowances` decimal(50,4) NOT NULL,
      `total_deductions` decimal(50,4) NOT NULL,
      `total_attendance` decimal(50,4) NOT NULL,
      `total_halfdays` decimal(50,4) NOT NULL,
      `tax` decimal(50,4) NOT NULL,
      `gross_salary` decimal(50,4) NOT NULL,
      `net_salary` decimal(50,4) NOT NULL,
      `status` varchar(50) NOT NULL,
      `ack_status` varchar(50) NOT NULL,
      `date` date NOT NULL,
      `created_at` datetime NOT NULL,
      `updated_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
      `created_by` varchar(50) NOT NULL,
      `updated_by` varchar(50) NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'job_allowance_deduction')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "job_allowance_deduction` (
      `id` int NOT NULL AUTO_INCREMENT,
      `jobid` int NOT NULL,
      `allowance_id` int NOT NULL,
      `deduction_id` int NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}