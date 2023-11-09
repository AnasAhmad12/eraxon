<?php

defined('BASEPATH') or exit('No direct script access allowed');

//Item Catgories 
	if (!$CI->db->table_exists(db_prefix() . 'assets_categories')) {
		$CI->db->query('CREATE TABLE `' . db_prefix() . "assets_categories` (
			`assets_category_id` INT(11) NOT NULL AUTO_INCREMENT,
			`assets_category_name` VARCHAR(250) NOT NULL,
			`assets_category_parent` VARCHAR(250) NULL DEFAULT 0,
			`assets_category_description` TEXT NOT NULL,
			PRIMARY KEY (`assets_category_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

	}
//Item Custom Fields  
	if (!$CI->db->table_exists(db_prefix() . 'assets_custom_field')) {
		$CI->db->query('CREATE TABLE `' . db_prefix() . 'assets_custom_field` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`assets_category_id` INT(11) NOT NULL,
			`name` VARCHAR(250) NOT NULL,
			`description` TEXT NULL DEFAULT NULL,
			PRIMARY KEY (`id`),
			FOREIGN KEY (assets_category_id) REFERENCES `' . db_prefix() . 'assets_categories`(`assets_category_id`) ON DELETE CASCADE
		) ENGINE = InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
	}

	// Item Custom Field Values 
	if (!$CI->db->table_exists(db_prefix() . 'assets_custom_field_values')) {
		$CI->db->query('CREATE TABLE `' . db_prefix() . 'assets_custom_field_values` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`assets_custom_field_id` INT(11) NOT NULL,
			`value` VARCHAR(250) NOT NULL,
			`value_order` INT(11) NOT NULL,
			`description` VARCHAR(250) NULL DEFAULT NULL,
			PRIMARY KEY (`id`),
			FOREIGN KEY (assets_custom_field_id) REFERENCES `' . db_prefix() . 'assets_custom_field`(`id`) ON DELETE CASCADE
		) ENGINE = InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
	}

// Items Master Table 
	if (!$CI->db->table_exists(db_prefix() . 'assets_items_master')) {
		$CI->db->query('CREATE TABLE `' . db_prefix() . 'assets_items_master` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`assets_category_id` INT(11) NOT NULL,
			`item_name` VARCHAR(200) NOT NULL,
			`item_sr_no` VARCHAR(200) NULL DEFAULT NULL,
			`item_description` VARCHAR(200) NOT NULL,
			`item_image` VARCHAR(200) NULL DEFAULT NULL,
			PRIMARY KEY (`id`),
			FOREIGN KEY (assets_category_id) REFERENCES `' . db_prefix() . 'assets_categories`(`assets_category_id`) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

		}

// Items Extra  
	if (!$CI->db->table_exists(db_prefix() . 'assets_items_custom_fields')) {
		$CI->db->query('CREATE TABLE `' . db_prefix() . 'assets_items_custom_fields` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`custom_field_id` INT(11) NULL DEFAULT NULL,
			`custom_field_value_id` INT(11) NULL DEFAULT NULL,
	     	`item_id` INT(11) NOT NULL,
			`custom_field_name` VARCHAR(200) NOT NULL,
			`value` VARCHAR(200) NOT NULL,
			PRIMARY KEY (`id`),
			FOREIGN KEY (item_id) REFERENCES `' . db_prefix() . 'assets_items_master`(`id`) ON DELETE CASCADE		
		) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
	
		}

	if (!$CI->db->table_exists(db_prefix() . 'assets_stock_table')) {
		$CI->db->query('CREATE TABLE `' . db_prefix() . 'assets_stock_table` (
			`stock_id` INT(11) NOT NULL AUTO_INCREMENT,
			`item_id` INT(11) NOT NULL,
			`stock_in_master_id` INT(11) NOT NULL,
			`serial_number` TEXT ,
			`purchase_price` DECIMAL(10, 2) NOT NULL,
			`quantity_added` INT NOT NULL,
			`last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`stock_id`),
			FOREIGN KEY (`item_id`) REFERENCES `' . db_prefix() . 'assets_items_master`(`id`) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
	}

	if (!$CI->db->table_exists(db_prefix() . 'assets_stock_table_master')) {
		$CI->db->query('CREATE TABLE `' . db_prefix() . 'assets_stock_table_master` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`purchase_date` DATE NOT NULL,
			`total` DECIMAL(10, 2) NOT NULL,
			`status` INT NOT NULL DEFAULT "1",
			`invoice_image` VARCHAR(200) DEFAULT NULL,
			`invoice_number` TEXT DEFAULT NULL,
			`last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			`datecreated` DATETIME NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
	}
	                
	
