<?php  defined('BASEPATH') or exit('No direct script access allowed');


if (!$CI->db->table_exists(db_prefix() . 'eraxon_team')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "eraxon_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teamname` varchar(250) NOT NULL,
  `team` text NOT NULL,
  `created_datetime` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}