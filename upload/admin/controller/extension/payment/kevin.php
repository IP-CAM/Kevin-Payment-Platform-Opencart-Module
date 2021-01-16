<?php
/*
* 2020 Kevin. payment  for OpenCart v.3.0.x.x  
* @version 0.1.3.13
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
class ControllerExtensionPaymentKevin extends Controller {
	private $error = array(); 
	public function install(){ 
		$this->load->model('extension/payment/kevin');
		$this->model_extension_payment_kevin->install();
	}

	public function uninstall(){
		$this->load->model('extension/payment/kevin');
		$this->model_extension_payment_kevin->uninstall();
	}
	
	public function index() {

        $this->load->language('extension/payment/kevin');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_kevin', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['title' . $language['language_id']])) {
				$data['error_title'][$language['language_id']] = $this->error['title' . $language['language_id']];
			} else {
				$data['error_title'][$language['language_id']] = '';
			}
		}
		/*
		if (isset($this->error['title'])) {
			$data['error_title_logo'] = $this->error['title_logo'];
		} else {
			$data['error_title_logo'] = '';
		}
		*/

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['client_id'])) {
			$data['error_client_id'] = $this->error['client_id'];
		} else {
			$data['error_client_id'] = '';
		}

		if (isset($this->error['client_secret'])) {
			$data['error_client_secret'] = $this->error['client_secret'];
		} else {
			$data['error_client_secret'] = '';
		}
		
		if (isset($this->error['client_company'])) {
			$data['error_client_company'] = $this->error['client_company'];
		} else {
			$data['error_client_company'] = '';
		}
		
		if (isset($this->error['client_iban_empty'])) {
			$data['error_client_iban_empty'] = $this->error['client_iban_empty'];
		} else {
			$data['error_client_iban_empty'] = '';
		}

		if (isset($this->error['client_iban_valid'])) {
			$data['error_client_iban_valid'] = $this->error['client_iban_valid'];
		} else {
			$data['error_client_iban_valid'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/kevin', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/kevin', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_kevin_client_id'])) {
			$data['payment_kevin_client_id'] = $this->request->post['payment_kevin_client_id'];
		} else {
			$data['payment_kevin_client_id'] = $this->config->get('payment_kevin_client_id');
		}
		
		if (isset($this->request->post['payment_kevin_client_secret'])) {
			$data['payment_kevin_client_secret'] = $this->request->post['payment_kevin_client_secret'];
		} else {
			$data['payment_kevin_client_secret'] = $this->config->get('payment_kevin_client_secret');
		}
		
		if (isset($this->request->post['payment_kevin_client_company'])) {
			$data['payment_kevin_client_company'] = $this->request->post['payment_kevin_client_company'];
		} else {
			$data['payment_kevin_client_company'] = $this->config->get('payment_kevin_client_company');
		}
		
		if (isset($this->request->post['payment_kevin_client_iban'])) {
			$data['payment_kevin_client_iban'] = $this->request->post['payment_kevin_client_iban'];
		} else if (!empty($this->config->get('payment_kevin_client_iban'))){
			$data['payment_kevin_client_iban'] = $this->config->get('payment_kevin_client_iban');
		} else {
			$data['payment_kevin_client_iban'] = '';
		}
		
		if (isset($this->request->post['payment_kevin_redirect_preferred'])) {
			$data['payment_kevin_redirect_preferred'] = $this->request->post['payment_kevin_redirect_preferred'];
		} else {
			$data['payment_kevin_redirect_preferred'] = $this->config->get('payment_kevin_redirect_preferred');
		}

		if (isset($this->request->post['payment_kevin_total'])) {
			$data['payment_kevin_total'] = $this->request->post['payment_kevin_total'];
		} else {
			$data['payment_kevin_total'] = $this->config->get('payment_kevin_total'); 
		} 

		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->request->post['payment_kevin_title' . $language['language_id']])) {
				$data['payment_kevin_title'][$language['language_id']] = $this->request->post['payment_kevin_title' . $language['language_id']];
			} else {
				$data['payment_kevin_title'][$language['language_id']] = $this->config->get('payment_kevin_title' . $language['language_id']);
			} 	
		}
		
		// Image
		if (isset($this->request->post['payment_kevin_image'])) {
			$data['payment_kevin_image'] = $this->request->post['payment_kevin_image'];
		} else {
			$data['payment_kevin_image'] = $this->config->get('payment_kevin_image');
		}
		
		if (isset($this->request->post['payment_kevin_image_height'])) {
			$data['payment_kevin_image_height'] = $this->request->post['payment_kevin_image_height'];
		} else {
			$data['payment_kevin_image_height'] = $this->config->get('payment_kevin_image_height');
		}
		
		if (isset($this->request->post['payment_kevin_image_width'])) {
			$data['payment_kevin_image_width'] = $this->request->post['payment_kevin_image_width'];
		} else {
			$data['payment_kevin_image_width'] = $this->config->get('payment_kevin_image_width');
		}

		$this->load->model('tool/image');
		
		$image_width = !empty($this->config->get('payment_kevin_image_width')) ? $this->config->get('payment_kevin_image_width') : 64;
		$image_height = !empty($this->config->get('payment_kevin_image_height')) ? $this->config->get('payment_kevin_image_height') : 64;

		if (!empty($this->config->get('payment_kevin_image')) && is_file(DIR_IMAGE . $this->config->get('payment_kevin_image'))) {
			$data['thumb'] = $this->model_tool_image->resize($this->config->get('payment_kevin_image'), $image_width, $image_height);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 64, 64);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 64, 64);
		
		if (isset($this->request->post['payment_kevin_position'])) {
			$data['payment_kevin_position'] = $this->request->post['payment_kevin_position'];
		} else {
			$data['payment_kevin_position'] = $this->config->get('payment_kevin_position');
		}
		
		if (isset($this->request->post['payment_kevin_bank_name_enabled'])) {
			$data['payment_kevin_bank_name_enabled'] = $this->request->post['payment_kevin_bank_name_enabled'];
		} else {
			$data['payment_kevin_bank_name_enabled'] = $this->config->get('payment_kevin_bank_name_enabled');
		}
		
		$data['languages'] = $languages;
		
		foreach ($languages as $language) {
			if (isset($this->request->post['payment_kevin_ititle' . $language['language_id']])) {
				$data['payment_kevin_ititle'][$language['language_id']] = $this->request->post['payment_kevin_ititle' . $language['language_id']];
			} else {
				$data['payment_kevin_ititle'][$language['language_id']] = $this->config->get('payment_kevin_ititle' . $language['language_id']);
			} 	

			if (isset($this->request->post['payment_kevin_instruction' . $language['language_id']])) {
				$data['payment_kevin_instruction'][$language['language_id']] = $this->request->post['payment_kevin_instruction' . $language['language_id']];
			} else {
				$data['payment_kevin_instruction'][$language['language_id']] = $this->config->get('payment_kevin_instruction' . $language['language_id']);
			} 
		}
		
		
		if (isset($this->request->post['payment_kevin_geo_zone_id'])) {
			$data['payment_kevin_geo_zone_id'] = $this->request->post['payment_kevin_geo_zone_id'];
		} else {
			$data['payment_kevin_geo_zone_id'] = $this->config->get('payment_kevin_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_kevin_status'])) {
			$data['payment_kevin_status'] = $this->request->post['payment_kevin_status'];
		} else {
			$data['payment_kevin_status'] = $this->config->get('payment_kevin_status');
		}
		
		if (isset($this->request->post['payment_kevin_log'])) {
			$data['payment_kevin_log'] = $this->request->post['payment_kevin_log'];
		} else {
			$data['payment_kevin_log'] = $this->config->get('payment_kevin_log');
		}

       $this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['payment_kevin_started_status_id'])) {
			$data['payment_kevin_started_status_id'] = $this->request->post['payment_kevin_started_status_id'];
		} else {
			$data['payment_kevin_started_status_id'] = $this->config->get('payment_kevin_started_status_id');
		}

		if (isset($this->request->post['payment_kevin_completed_status_id'])) {
			$data['payment_kevin_completed_status_id'] = $this->request->post['payment_kevin_completed_status_id'];
		} else {
			$data['payment_kevin_completed_status_id'] = $this->config->get('payment_kevin_completed_status_id');
		}
		
		if (isset($this->request->post['payment_kevin_pending_status_id'])) {
			$data['payment_kevin_pending_status_id'] = $this->request->post['payment_kevin_pending_status_id'];
		} else {
			$data['payment_kevin_pending_status_id'] = $this->config->get('payment_kevin_pending_status_id');
		}

		if (isset($this->request->post['payment_kevin_failed_status_id'])) {
			$data['payment_kevin_failed_status_id'] = $this->request->post['payment_kevin_failed_status_id'];
		} else {
			$data['payment_kevin_failed_status_id'] = $this->config->get('payment_kevin_failed_status_id');
		}

		if (isset($this->request->post['payment_kevin_sort_order'])) {
			$data['payment_kevin_sort_order'] = $this->request->post['payment_kevin_sort_order'];
		} else {
			$data['payment_kevin_sort_order'] = $this->config->get('payment_kevin_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/kevin', $data));
	}
	
	public function checkIBAN($iban) {
		if (preg_match('/[^A-Za-z0-9]/', $iban)) {
			return false;
		}
		$iban = strtolower(str_replace(' ','',$iban));
		$Countries = array('al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24);
		$Chars = array('a'=>10,'b'=>11,'c'=>12,'d'=>13,'e'=>14,'f'=>15,'g'=>16,'h'=>17,'i'=>18,'j'=>19,'k'=>20,'l'=>21,'m'=>22,'n'=>23,'o'=>24,'p'=>25,'q'=>26,'r'=>27,'s'=>28,'t'=>29,'u'=>30,'v'=>31,'w'=>32,'x'=>33,'y'=>34,'z'=>35);
		
		if (array_key_exists(substr($iban,0,2), $Countries) && strlen($iban) == $Countries[substr($iban,0,2)]) {

			$MovedChar = substr($iban, 4).substr($iban,0,4);
			$MovedCharArray = str_split($MovedChar);
			$NewString = "";

			foreach($MovedCharArray as $key => $value){
				if(!is_numeric($MovedCharArray[$key])){
					$MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
				}
				$NewString .= $MovedCharArray[$key];
			}
			if(bcmod($NewString, '97') == 1) {
				return true;
			}
		}
		return false;
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/kevin')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (empty($this->request->post['payment_kevin_title' . $language['language_id']]) && empty($this->request->post['payment_kevin_image'])) {
				$this->error['title' . $language['language_id']] = $this->language->get('error_title');
			}
		}
		/*
		if (empty($this->request->post['payment_kevin_title' . $language['language_id']]) && empty($this->request->post['payment_kevin_image'])) {
			$this->error['title_logo'] = $this->language->get('error_title_logo');
		}
		*/

		if (empty($this->request->post['payment_kevin_client_id'])) {
			$this->error['client_id'] = $this->language->get('error_client_id');
		}

		if (empty($this->request->post['payment_kevin_client_secret'])) {
			$this->error['client_secret'] = $this->language->get('error_client_secret');
		}
		
		if (empty($this->request->post['payment_kevin_client_company'])) {
			$this->error['client_company'] = $this->language->get('error_client_company');
		}
	
		if (!empty($this->request->post['payment_kevin_client_iban'])) {
			$validate = $this->checkIBAN($this->request->post['payment_kevin_client_iban']);
			if (!$validate) {
				$this->error['client_iban_valid'] = $this->language->get('error_client_iban_valid');
			}
		}
		
		if (empty($this->request->post['payment_kevin_client_iban'])) {
			$this->error['client_iban_empty'] = $this->language->get('error_client_iban_empty');
		}
		
		return !$this->error;		
	}
}
