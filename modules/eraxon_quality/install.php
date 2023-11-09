<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'qa_status')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "qa_status` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(150) NOT NULL,
      `isactive` int(10) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

//ALTER TABLE `tblqa_status` ADD `qadefault` INT(10) NOT NULL AFTER `isactive`;

// if (!$CI->db->field_exists('qadefault', db_prefix() . 'qa_status')) {
//     $CI->db->query('ALTER TABLE `' . db_prefix() . 'qa_status`
//         ADD COLUMN `qadefault` INT(10) DEFAULT `0` AFTER `isactive`;');
// }

if (!$CI->db->table_exists(db_prefix() . 'qa_review_status')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "qa_review_status` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(150) NOT NULL,
      `isactive` int(10) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

// if (!$CI->db->field_exists('qadefault', db_prefix() . 'qa_review_status')) {
//     $CI->db->query('ALTER TABLE `' . db_prefix() . 'qa_review_status`
//         ADD COLUMN `qadefault` INT(10) DEFAULT `0` AFTER `isactive`;');
// }


if (!$CI->db->table_exists(db_prefix() . 'qa_lead')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "qa_lead` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `complete_lead` text,
      `forwardable_comments` text,
      `qa_comments` text,
      `rejection_comments` text,
      `lead_status` varchar(150),
      `qa_status` varchar(150),
      `review_status` varchar(150),
      `lead_type` int(11),
      `assigned_staff` int(11),
      `lead_id` int(11),
      `lead_date` datetime  ,
      `qc_date` datetime,
      `review_date` datetime ,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'qa_campaign_column ')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "qa_campaign_column` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `camp_type_id` int(11) NOT NULL,
      `column` text NOT NULL, 
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}