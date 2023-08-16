<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*if (!$CI->db->table_exists(db_prefix() . 'goals')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "goals` (
  `id` int(11) NOT NULL,
  `subject` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `goal_type` int(11) NOT NULL,
  `contract_type` int(11) NOT NULL DEFAULT '0',
  `achievement` int(11) NOT NULL,
  `notify_when_fail` tinyint(1) NOT NULL DEFAULT '1',
  `notify_when_achieve` tinyint(1) NOT NULL DEFAULT '1',
  `notified` int(11) NOT NULL DEFAULT '0',
  `staff_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

CREATE TABLE [dbo].[EmployeeLoan](
  [EmployeeLoanMapID] [uniqueidentifier] NOT NULL,
  [EmployeeId] [uniqueidentifier] NOT NULL,
  [Amount] [decimal](12, 2) NOT NULL,
  [LoanDate] [datetime] NOT NULL,
  [LoanTitle] [varchar](100) NULL,
  [Description] [varchar](max) NULL,
  [ApprovedBy] [varchar](150) NULL,
  [TotalMonths] [int] NULL,
  [CreatedDate] [datetime] NOT NULL,
  [CreatedBy] [uniqueidentifier] NULL,
  [ModifiedBy] [uniqueidentifier] NULL,
  [ModifiedDate] [datetime] NOT NULL,
  [IsActive] [bit] NOT NULL,
  [IsComplete] [bit] NOT NULL,
 CONSTRAINT [PK_EmployeeLoanMap] PRIMARY KEY CLUSTERED 



*/

if (!$CI->db->table_exists(db_prefix() . 'advance-salary')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "advance-salary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_staff` int(11) NOT NULL,
  `reason` text NOT NULL,
  `amount` DECIMAL(15,2)  NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `amount_needed_date` DATE NOT NULL,
  `requested_datetime` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'other-requests')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "other-requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_staff` int(11) NOT NULL,
  `request_type` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `requested_datetime` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'docks')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "docks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dock_name` varchar(150) NOT NULL,
  `amount` decimal(50,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'teamlead_docks')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "teamlead_docks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `dock_id` INT(11) NOT NULL,
  `dock_name` varchar(150) NOT NULL,
  `dock_amount` decimal(50,4) NOT NULL,
  `added_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}