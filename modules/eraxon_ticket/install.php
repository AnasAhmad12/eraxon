<?php  defined('BASEPATH') or exit('No direct script access allowed');


if (!$CI->db->table_exists(db_prefix() . 'eraxon_tickets')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "eraxon_tickets` (
  `ticketid` int(11) NOT NULL AUTO_INCREMENT,
  `creater_id` int(11) NOT NULL,
  `creater_name` varchar(150) NOT NULL,
  `department` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `assigned_id` int(11) NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`ticketid`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'eraxon_tickets_replies')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "eraxon_tickets_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,  	
  `ticketid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(250) NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'eraxon_tickets_attachments')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "eraxon_tickets_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticketid` int(11) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `added_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'eraxon_tickets_priority')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "eraxon_tickets_priority` (
  `priorityid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`priorityid`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'eraxon_tickets_status')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "eraxon_tickets_status` (
  `statusid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `statuscolor` varchar(10) NOT NULL,
  PRIMARY KEY (`priorityid`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}