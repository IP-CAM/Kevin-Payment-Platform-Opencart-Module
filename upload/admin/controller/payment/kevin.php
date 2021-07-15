<?php
/*
* 2020 Kevin. payment  for OpenCart v.2.0.x.x - v.2.2.x.x
* @version 0.1.0.1
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* 
*  @author 2021 kevin. <info@getkevin.eu>
*  @copyright kevin.
*  @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
*/
class ControllerPaymentKevin extends Controller {
	private $error = array(); 
	public function install(){ 
		$this->load->model('payment/kevin');
		$this->model_payment_kevin->install();
	}
/*
	public function uninstall(){
		$this->load->model('payment/kevin');
		$this->model_extension_payment_kevin->uninstall();
	}
*/	
	public function index() {

        $this->load->language('payment/kevin');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('kevin', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$texts = array(
			'text_payment',
			'text_edit',
			'text_extension',
			'text_success',
			'text_kevin',
			'text_right',
			'text_left',
			'text_disabled',
			'text_enabled',
			'text_all_zones',
			'entry_general',
			'entry_order_statuses',
			'entry_instructions',
			'entry_client_id',
			'entry_client_secret',
			'entry_client_company',
			'entry_client_iban',
			'entry_redirect_preferred',
			'entry_image',
			'entry_image_height',
			'entry_image_width',
			'entry_position',
			'entry_bank_name_enabled',
			'entry_kevin_title',
			'entry_instruction_title',
			'entry_kevin_instruction',
			'entry_total',
			'entry_order_status',
			'entry_started_status',
			'entry_completed_status',
			'entry_pending_status',
			'entry_failed_status',
			'entry_geo_zone',
			'entry_status',
			'entry_log',
			'entry_sort_order',
			'error_permission',
			'error_client_id',
			'error_client_secret',
			'error_client_company',
			'error_client_iban',
			'error_client_iban_valid',
			'error_title',
			'help_iban_format',
			'help_bank_name_enbl',
			'help_bank_title',
			'help_total',
			'help_log',
			'help_width',
			'help_height',
			'help_position',
			'button_cancel',
			'button_save'
		);
		
		foreach ($texts as $text) {
			$data[$text] = $this->language->get($text);
		}
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['title' . $language['language_id']])) {
				$data['error_title' . $language['language_id']] = $this->error['title' . $language['language_id']];
			} else {
				$data['error_title' . $language['language_id']] = '';
			}
		}

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/kevin', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/kevin', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['kevin_client_id'])) {
			$data['kevin_client_id'] = $this->request->post['kevin_client_id'];
		} else {
			$data['kevin_client_id'] = $this->config->get('kevin_client_id');
		}
		
		if (isset($this->request->post['kevin_client_secret'])) {
			$data['kevin_client_secret'] = $this->request->post['kevin_client_secret'];
		} else {
			$data['kevin_client_secret'] = $this->config->get('kevin_client_secret');
		}
		
		if (isset($this->request->post['kevin_client_company'])) {
			$data['kevin_client_company'] = $this->request->post['kevin_client_company'];
		} else {
			$data['kevin_client_company'] = $this->config->get('kevin_client_company');
		}
		
		if (isset($this->request->post['kevin_client_iban'])) {
			$data['kevin_client_iban'] = $this->request->post['kevin_client_iban'];
		} else if (!empty($this->config->get('kevin_client_iban'))){
			$data['kevin_client_iban'] = $this->config->get('kevin_client_iban');
		} else {
			$data['kevin_client_iban'] = '';
		}
		
		if (isset($this->request->post['kevin_redirect_preferred'])) {
			$data['kevin_redirect_preferred'] = $this->request->post['kevin_redirect_preferred'];
		} else {
			$data['kevin_redirect_preferred'] = $this->config->get('kevin_redirect_preferred');
		}

		if (isset($this->request->post['kevin_total'])) {
			$data['kevin_total'] = $this->request->post['kevin_total'];
		} else {
			$data['kevin_total'] = $this->config->get('kevin_total'); 
		} 

		foreach ($languages as $language) {
			if (isset($this->request->post['kevin_title' . $language['language_id']])) {
				$data['kevin_title' . $language['language_id']] = $this->request->post['kevin_title' . $language['language_id']];
			} else {
				$data['kevin_title' . $language['language_id']] = $this->config->get('kevin_title' . $language['language_id']);
			} 	
		}
		
		// Image
		if (isset($this->request->post['kevin_image'])) {
			$data['kevin_image'] = $this->request->post['kevin_image'];
		} else {
			$data['kevin_image'] = $this->config->get('kevin_image');
		}
		
		if (isset($this->request->post['kevin_image_height'])) {
			$data['kevin_image_height'] = $this->request->post['kevin_image_height'];
		} else {
			$data['kevin_image_height'] = $this->config->get('kevin_image_height');
		}
		
		if (isset($this->request->post['kevin_image_width'])) {
			$data['kevin_image_width'] = $this->request->post['kevin_image_width'];
		} else {
			$data['kevin_image_width'] = $this->config->get('kevin_image_width');
		}

		$this->load->model('tool/image');
		
		$image_width = !empty($this->config->get('kevin_image_width')) ? $this->config->get('kevin_image_width') : 64;
		$image_height = !empty($this->config->get('kevin_image_height')) ? $this->config->get('kevin_image_height') : 64;

		if (!empty($this->config->get('kevin_image')) && is_file(DIR_IMAGE . $this->config->get('kevin_image'))) {
			$data['thumb'] = $this->model_tool_image->resize($this->config->get('kevin_image'), $image_width, $image_height);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 64, 64);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 64, 64);
		
		if (isset($this->request->post['kevin_position'])) {
			$data['kevin_position'] = $this->request->post['kevin_position'];
		} else {
			$data['kevin_position'] = $this->config->get('kevin_position');
		}
		
		if (isset($this->request->post['kevin_bank_name_enabled'])) {
			$data['kevin_bank_name_enabled'] = $this->request->post['kevin_bank_name_enabled'];
		} else {
			$data['kevin_bank_name_enabled'] = $this->config->get('kevin_bank_name_enabled');
		}
		
		$data['languages'] = array();
		foreach ($languages as $key => $language) {
			if (isset($language['image'])) {
				$data['languages'][$key] = array(
					'language_id' => $language['language_id'],
					'name' => $language['name'],
					'code' => $language['code'],
					'locale' => $language['locale'],
					'image' => $language['image'],
					'directory' => $language['directory'],
					'sort_order' => $language['sort_order'],
					'status' => $language['status'],
					'flag_image' => 'view/image/flags/'. $language['image']
				);
			} else {
				$data['languages'][$key] = array(
					'language_id' => $language['language_id'],
					'name' => $language['name'],
					'code' => $language['code'],
					'sort_order' => $language['sort_order'],
					'status' => $language['status'],
					'flag_image' => 'language/' . $language['code'] . '/' . $language['code'] . '.png'
				);
			}
		}
		
		foreach ($languages as $language) {
			if (isset($this->request->post['kevin_ititle' . $language['language_id']])) {
				$data['kevin_ititle' . $language['language_id']] = $this->request->post['kevin_ititle' . $language['language_id']];
			} else {
				$data['kevin_ititle' . $language['language_id']] = $this->config->get('kevin_ititle' . $language['language_id']);
			} 	

			if (isset($this->request->post['kevin_instruction' . $language['language_id']])) {
				$data['kevin_instruction' . $language['language_id']] = $this->request->post['kevin_instruction' . $language['language_id']];
			} else {
				$data['kevin_instruction' . $language['language_id']] = $this->config->get('kevin_instruction' . $language['language_id']);
			} 
		}
		
		
		if (isset($this->request->post['kevin_geo_zone_id'])) {
			$data['kevin_geo_zone_id'] = $this->request->post['kevin_geo_zone_id'];
		} else {
			$data['kevin_geo_zone_id'] = $this->config->get('kevin_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['kevin_status'])) {
			$data['kevin_status'] = $this->request->post['kevin_status'];
		} else {
			$data['kevin_status'] = $this->config->get('kevin_status');
		}
		
		if (isset($this->request->post['kevin_log'])) {
			$data['kevin_log'] = $this->request->post['kevin_log'];
		} else {
			$data['kevin_log'] = $this->config->get('kevin_log');
		}

       $this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['kevin_started_status_id'])) {
			$data['kevin_started_status_id'] = $this->request->post['kevin_started_status_id'];
		} else {
			$data['kevin_started_status_id'] = $this->config->get('kevin_started_status_id');
		}

		if (isset($this->request->post['kevin_completed_status_id'])) {
			$data['kevin_completed_status_id'] = $this->request->post['kevin_completed_status_id'];
		} else {
			$data['kevin_completed_status_id'] = $this->config->get('kevin_completed_status_id');
		}
		
		if (isset($this->request->post['kevin_pending_status_id'])) {
			$data['kevin_pending_status_id'] = $this->request->post['kevin_pending_status_id'];
		} else {
			$data['kevin_pending_status_id'] = $this->config->get('kevin_pending_status_id');
		}

		if (isset($this->request->post['kevin_failed_status_id'])) {
			$data['kevin_failed_status_id'] = $this->request->post['kevin_failed_status_id'];
		} else {
			$data['kevin_failed_status_id'] = $this->config->get('kevin_failed_status_id');
		}

		if (isset($this->request->post['kevin_sort_order'])) {
			$data['kevin_sort_order'] = $this->request->post['kevin_sort_order'];
		} else {
			$data['kevin_sort_order'] = $this->config->get('kevin_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/kevin.tpl', $data));
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
		if (!$this->user->hasPermission('modify', 'payment/kevin')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (empty($this->request->post['kevin_title' . $language['language_id']]) && empty($this->request->post['kevin_image'])) {
				$this->error['title' . $language['language_id']] = $this->language->get('error_title');
			}
		}

		if (empty($this->request->post['kevin_client_id'])) {
			$this->error['client_id'] = $this->language->get('error_client_id');
		}

		if (empty($this->request->post['kevin_client_secret'])) {
			$this->error['client_secret'] = $this->language->get('error_client_secret');
		}
		
		if (empty($this->request->post['kevin_client_company'])) {
			$this->error['client_company'] = $this->language->get('error_client_company');
		}
	
		if (!empty($this->request->post['kevin_client_iban'])) {
			$validate = $this->checkIBAN($this->request->post['kevin_client_iban']);
			if (!$validate) {
				$this->error['client_iban_valid'] = $this->language->get('error_client_iban_valid');
			}
		}
		
		if (empty($this->request->post['kevin_client_iban'])) {
			$this->error['client_iban_empty'] = $this->language->get('error_client_iban_empty');
		}
		
		return !$this->error;		
	
	}
}
