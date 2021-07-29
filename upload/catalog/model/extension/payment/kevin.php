<?php
/*
* 2020 Kevin. payment  for OpenCart v.3.0.x.x  
* @version 0.2.0.8
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
use Kevin\Client;
class ModelExtensionPaymentKevin extends Model {
	
	private $lib_version = '0.3'; 
	private $plugin_version = '0.2.0.8';
	
	public function getMethod($address, $total) {
		
		$this->load->language('extension/payment/kevin');
		require_once dirname(dirname(dirname(__DIR__))) . '/model/extension/payment/kevin/vendor/autoload.php';
		$clientId = $this->config->get('payment_kevin_client_id');
		$clientSecret = $this->config->get('payment_kevin_client_secret');
		$options = [
			'error'                 => 'array',
			'version'               => $this->lib_version,
			'pluginVersion'         => $this->plugin_version,
			'pluginPlatform'        => 'OpenCart',
			'pluginPlatformVersion' => strval(VERSION)
		];

		$kevinClient = new Client($clientId, $clientSecret, $options);

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_kevin_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_kevin_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_kevin_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$current_country_code = $address['iso_code_2'];
		$this->session->data['iso_code_2'] = $address['iso_code_2'];
		
		$contries = $kevinClient->auth()->getCountries();
		//$countryCodes = array("LT", "LV", "EE");
		$countryCodes = $contries['data'];
		if (in_array($current_country_code, $countryCodes) && $status) {
			$status = true;
		} else {
			$status = false;
		}
		
		$method_data = array();
		$this->load->model('localisation/language');
		$current_language = $this->config->get('config_language_id') ;
		if (!empty($this->config->get('payment_kevin_image_width'))) {
			$logo_width = 'max-width:' . $this->config->get('payment_kevin_image_width') . 'px';
		} else {
			$logo_width = 'width: auto';
		}
		if (!empty($this->config->get('payment_kevin_image_height'))) {
			$logo_height = 'max-height: ' . $this->config->get('payment_kevin_image_height') . 'px;';
		} else {
			$logo_height = 'height: auto;';
		}
		
		
		if (is_file(DIR_IMAGE . $this->config->get('payment_kevin_image'))) {
			$kevin_image = '<img src="' . $this->config->get('config_url') . 'image/' . $this->config->get('payment_kevin_image') . '" title="' . $this->config->get('payment_kevin_title' . $current_language) . '" style="margin-top: -3px;' . $logo_height . $logo_width . '"/>&nbsp;&nbsp;';
		} else {
			$kevin_image = '';
		}
		
		if ($this->config->get('payment_kevin_position') == 'right') {
			$title =  $this->config->get('payment_kevin_title' . $current_language) . '&nbsp;&nbsp;' . $kevin_image;
		} else {
			$title =  $kevin_image . $this->config->get('payment_kevin_title' . $current_language);
		}
		
		if ($status) {
			$method_data = array(
				'code'       => 'kevin',
				'title'      => $title,
				'terms'      => '',
				'sort_order' => $this->config->get('payment_kevin_sort_order')				
			);
		}
		return $method_data;
	}

	public function addKevinOrder($order_info, $init_payment, $ip_address, $order_status_id, $total, $payment_method, $bank_id) {
		
		if ($payment_method == 'card' && isset($init_payment['cardStatus'])) {
			$kevin_payment_status = $init_payment['cardStatus'];
		} else if ($payment_method == 'bank' && isset($init_payment['bankStatus'])) {
			$kevin_payment_status = $init_payment['bankStatus'];
		} else if (isset($init_payment['hybridStatus'])) {
			$kevin_payment_status = $init_payment['hybridStatus'];
		} else {
			$kevin_payment_status = '';
		}
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "kevin_order` SET 
		`order_id` = '" . (int)$order_info['order_id'] . "',
		`payment_id` = '" . $this->db->escape($init_payment['id']) . "',
		`bank_id` = '" . $this->db->escape($bank_id) . "',
		`status` = '" . $this->db->escape($kevin_payment_status) . "',
		`payment_method` = '" . $this->db->escape($payment_method) . "',
		`statusGroup` = '" . $this->db->escape($init_payment['statusGroup']) . "',
		`order_status_id` = '" . (int)$order_status_id . "',
		`ip_address` = '" . $this->db->escape($ip_address) . "',
		`date_added` = now(),
		`date_modified` = now(), 
		`currency_code` = '" . $this->db->escape($order_info['currency_code']) . "', 
		`total` = '" . (float)$total . "'");
	}

	public function updateConfirmKevinOrder($payment_id, $payment_status, $order_status_id, $payment_method) {
		/**/
		if ($payment_method == 'card' && isset($payment_status['cardStatus'])) {
			$kevin_payment_status = $payment_status['cardStatus'];
		} else if ($payment_method == 'bank' && isset($payment_status['bankStatus'])) {
			$kevin_payment_status = $payment_status['bankStatus'];
		} else if (isset($payment_status['hybridStatus'])) {
			$kevin_payment_status = $payment_status['hybridStatus'];
		} else {
			$kevin_payment_status = 'conf';
		}
		
		$this->db->query("UPDATE `" . DB_PREFIX . "kevin_order` SET 
		`status` = '" . $this->db->escape($kevin_payment_status) . "',
		`statusGroup` = '" . $this->db->escape($payment_status['group']) . "',
		`order_status_id` = '" . (int)$order_status_id . "',
		`date_modified` = now() 
         WHERE `payment_id` = '" . $this->db->escape($payment_id) . "'");
	}
	
	public function updateWebhookKevinOrder($payment_id, $payment_status, $order_status_id, $payment_method) {
		/**/
		if ($payment_method == 'card' && isset($payment_status['cardStatus'])) {
			$kevin_payment_status = $payment_status['cardStatus'];
		} else if ($payment_method == 'bank' && isset($payment_status['bankStatus'])) {
			$kevin_payment_status = $payment_status['bankStatus'];
		} else if (isset($payment_status['hybridStatus'])) {
			$kevin_payment_status = $payment_status['hybridStatus'];
		} else {
			$kevin_payment_status = 'whook';
		}
		
		$this->db->query("UPDATE `" . DB_PREFIX . "kevin_order` SET 
		`status` = '" . $this->db->escape($kevin_payment_status) . "',
		`statusGroup` = '" . $this->db->escape($payment_status['group']) . "',
		`order_status_id` = '" . (int)$order_status_id . "',
		`date_modified` = now() 
         WHERE `payment_id` = '" . $this->db->escape($payment_id) . "'");
	}

	public function getKevinOrders($payment_id) {
		$kevin_order = $this->db->query("SELECT * FROM `" . DB_PREFIX . "kevin_order` WHERE `payment_id` = '" . $this->db->escape($payment_id) . "'");
		if ($kevin_order->num_rows) {
			return $kevin_order->row;
		} else {
			return false;
		}
	}
}
	

