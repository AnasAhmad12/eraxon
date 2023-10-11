<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'dnc_request')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "dnc_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_staff` int(11) NOT NULL,
  `phonenumber` varchar(150) NOT NULL,
  `result` varchar(100) NOT NULL,
  `json_query` text  NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
