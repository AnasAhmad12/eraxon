<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'wallet')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "wallet` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `staff_id` int(11) NOT NULL,
      `total_balance` decimal(50,4) NOT NULL DEFAULT '0.0000',
      `last_balance` decimal(50,4) NOT NULL DEFAULT '0.0000',
      `status` int(10) NOT NULL,
      `updated_datetime` datetime NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'wallet_transaction')) 
{
    $CI->db->query('CREATE TABLE `' . db_prefix() . "wallet_transaction` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `wallet_id` int(11) NOT NULL,
      `amount_type` varchar(150) NOT NULL,
      `amount` decimal(50,4) NOT NULL,
      `in_out` VARCHAR(10) NOT NULL,
      `created_datetime` datetime NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}


