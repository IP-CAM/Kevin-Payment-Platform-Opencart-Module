<?php
/*
* 2020 Kevin payment  for OpenCart v.2.3.x.x  
* @version 0.1.2.3
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* 
*  @author 2020 kevin. <info@getkevin.eu>
*  @copyright kevin.
*  @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
*/
class ModelExtensionPaymentKevin extends Model {
	
	public function uninstall(){  
		//$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kevin_order`;");	
	}
    
	public function install(){  
		
		$check_payment_id = $this->db->query("SELECT DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_PREFIX . "kevin_order' AND COLUMN_NAME = 'payment_id'");
		if ($check_payment_id->num_rows && ($check_payment_id->row['DATA_TYPE'] == 'int' || $check_payment_id->row['DATA_TYPE'] == 'INT') ) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "kevin_order` MODIFY COLUMN `payment_id`	varchar(128) DEFAULT NULL");
		}

		//modify the length of the data type in the table column to display the payment method logo
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` MODIFY COLUMN `payment_method`	varchar(256) NOT NULL");
		
		$query = $this->db->query("DESC `" . DB_PREFIX . "kevin_order` order_status_id");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "kevin_order` ADD `order_status_id` int(11) NOT NULL AFTER statusGroup ");
		}
		
		//$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kevin_order`;");
		$this->db->query("
		CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kevin_order` (
		`kevin_order_id` int(11) NOT NULL AUTO_INCREMENT,
		`order_id` int(11) NOT NULL,
		`payment_id` varchar(128) DEFAULT NULL,
		`ip_address` varchar(64) DEFAULT NULL,
		`currency_code` CHAR(3) NOT NULL,
		`total` DECIMAL( 10, 2 ) NOT NULL,
		`status` varchar(10) DEFAULT NULL,
		`statusGroup` varchar(10) DEFAULT NULL,
		`order_status_id` int(11) NOT NULL,
		`date_added` DATETIME NOT NULL,
		`date_modified` DATETIME NOT NULL,
		PRIMARY KEY (`kevin_order_id`),
		KEY `order_id` (`order_id`),
		KEY `payment_id` (`payment_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
	}
}