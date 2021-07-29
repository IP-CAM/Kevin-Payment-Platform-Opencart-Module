<?php
/*
* 2020 Kevin. payment  for OpenCart v.2.3.x.x  
* @version 0.2.0.4
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
use Kevin\Client;
class ControllerExtensionPaymentKevin extends Controller {

    private $type = 'payment';
    private $name = 'kevin';
	private $lib_version = '0.3'; 
	private $plugin_version = '0.2.0.4';
	
    public function index() {	
	//	date_default_timezone_set('Europe/Vilnius');		
		require_once dirname(dirname(dirname(__DIR__))) . '/model/extension/payment/kevin/vendor/autoload.php';
		$clientId = $this->config->get('kevin_client_id');
		$clientSecret = $this->config->get('kevin_client_secret');

		$options = [
			'error'                 => 'array',
			'version'               => $this->lib_version,
			'pluginVersion'         => $this->plugin_version,
			'pluginPlatform'        => 'OpenCart',
			'pluginPlatformVersion' => strval(VERSION)
		];

		$kevinClient = new Client($clientId, $clientSecret, $options);

		$this->load->model('checkout/order');
        $this->load->model('extension/payment/kevin');
        $this->load->language('extension/payment/kevin');
		$this->load->model('localisation/language');
		
		$texts = array(
			'text_title',
			'text_order_info',
			'text_loading',
			'text_payment_method',
			'text_status',
			'text_status_group',
			'text_payment_id',
			'text_card_name',
			'error_currency',
			'error_bank',
			'error_kevin_payment',
			'error_kevin_payment_id',
			'button_confirm'
		);
	
		foreach ($texts as $text) {
			$data[$text] = $this->language->get($text);
		}
		
		$current_language = $this->config->get('config_language_id');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		if(!$order_info) {
			$order_info['total'] = 0;
			$order_info['currency_code'] = $this->config->get('config_currency');
			$order_info['currency_value'] = 1;
		}
		
		$total = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false, false);
		
		$data['kevin_instr_title'] = $this->config->get('kevin_ititle' . $current_language);
	//	$data['kevin_instr'] =  html_entity_decode($this->config->get('kevin_instruction' . $current_language));
		$data['kevin_instr'] =  $this->config->get('kevin_instruction' . $current_language);

		if (isset($this->session->data['iso_code_2'])) {
			$current_country_code = $this->session->data['iso_code_2'];
		} else {
			$current_country_code = $order_info['payment_iso_code_2'];
		}
		
		$contries = $kevinClient->auth()->getCountries();
		//$countryCodes = array("LT", "LV", "EE");
		$countryCodes = $contries['data'];

		$country_code = ['countryCode' => $current_country_code];
		
		$banks = $kevinClient->auth()->getBanks($country_code);

		$payment_methods = $kevinClient->auth()->getPaymentMethods();
		
		if (isset($banks['error']['code'])) {
			$data['error_bank_missing'] = $banks['error']['description'] . ' Error code: ' . $banks['error']['code'] . '. Please try another payment method.';
		} else {
			$data['error_bank_missing'] = '';
		}

		$bank_ids = array();

		$data['text_sandbox_alert'] = '';
		foreach ($banks['data'] as $bank) {
			if ($bank['isSandbox']) {
				$data['text_sandbox_alert'] = 'This payment method is set to Sandbox mode. Only for test payments. Real payments is not available!';
				break;
			} 
		}
		
		if (file_exists(DIR_APPLICATION . 'controller/extension/payment/kevin_image/credit_card_icon.png')) {
            $data['credit_card_icon'] = $this->config->get('config_url') . 'catalog/controller/extension/payment/kevin_image/credit_card_icon.png';
        } else {
            $data['credit_card_icon'] = '';
        }
	//	$data['credit_card_icon'] = $this->config->get('kevin_card_image');
		
		$data['payment_methods'] = $payment_methods['data'];
		$data['banks'] = $banks['data'];
		//echo '<pre>';	print_r($data['banks']); echo '</pre>';
		$data['action'] = $this->url->link('extension/payment/kevin/redirect');
		$data['bank_name_enable'] = $this->config->get('kevin_bank_name_enabled');
	
        $order_id = $order_info['order_id'];
        $currency = $order_info['currency_code'];
		$data['currency'] = $currency;
		if ($currency != 'EUR') {
			$data['text_error_currency'] = $this->language->get('error_currency');
		} 
		
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

		return $this->load->view('extension/' . $this->type . '/' . $this->name, $data);
    }

	public function redirect() {
	//	date_default_timezone_set('Europe/Vilnius');
		if (isset($this->request->post['bankId']) && isset($this->request->post['payment_method'])) {
			$bank_id = $this->request->post['bankId'];
			$payment_method = $this->request->post['payment_method'];
		} else if (isset($this->request->get['bankId']) && isset($this->request->get['payment_method'])) {
			$bank_id = $this->request->get['bankId'];
			$payment_method = $this->request->get['payment_method'];
		} else {
			$bank_id = '';
			$payment_method = '';
			$this->session->data['error'] = "Data for payment is missing! Please try again, or choose another payment method.";
			$this->KevinLog($this->session->data['error']);
			$this->response->redirect($this->url->link('checkout/cart'));
		}
		//echo '<pre>id:';	print_r($payment_method.'-'.$bank_id); echo '</pre>'; die;
		require_once dirname(dirname(dirname(__DIR__))) . '/model/extension/payment/kevin/vendor/autoload.php';
		
		$clientId = $this->config->get('kevin_client_id');
		$clientSecret = $this->config->get('kevin_client_secret');

		$options = [
			'error'                 => 'array',
			'version'               => $this->lib_version,
			'pluginVersion'         => $this->plugin_version,
			'pluginPlatform'        => 'OpenCart',
			'pluginPlatformVersion' => strval(VERSION)
		];

		$kevinClient = new Client($clientId, $clientSecret, $options);
		$this->load->model('checkout/order');
        $this->load->model('extension/payment/kevin');
		$this->load->language('extension/payment/kevin');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		if (!$order_info) {
			$this->KevinLog('Order ID: ' . $this->session->data['order_id'] . ' missing in DB. ');
			$this->session->data['error'] = 'Order ID: ' . $this->session->data['order_id'] . ' missing in database. If you want to order the desired products, please try again.';
			unset($this->session->data['order_id']);
			$this->response->redirect($this->url->link('checkout/cart'));
		}
				
		$ip_address = $order_info['ip'];

		$order_id = (int)$order_info['order_id'];
		$order_status_id = $order_info['order_status_id'];
		
		if(!$order_info) {
			$order_info['total'] = 0;
			$order_info['currency_code'] = $this->config->get('config_currency');
			$order_info['currency_value'] = 1;
		}
		
		$total = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false, false);
	
			
		// Vendor logo can be added to the payment confirmation, if Kevin API will support it.
        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
            $vendor_logo = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo') . ' ';
        } else {
            $vendor_logo = '';
        }
		//('<img src="' . $vendor_logo . '" style="height: 32px width: auto;" />')
		$confirm_url = $this->url->link('extension/payment/kevin/confirm');
        $webhook_url = $this->url->link('extension/payment/kevin/webhook');
		
		if (!empty($this->config->get('kevin_redirect_preferred') && $this->config->get('kevin_redirect_preferred') == 1)) {
			$redirect_preferred = true;
		} else {
			$redirect_preferred = false;
		}
		
		if (!empty($this->customer->getEmail())) {
			$customer_email = $this->customer->getEmail();
		} else if (!empty($this->session->data['guest']['email'])) {
			$customer_email = $this->session->data['guest']['email'];
		} else {
			$customer_email = '';
		}
		if ($payment_method == 'bank') {
			$payment_attr = [
				'paymentMethodPreferred'  => $payment_method,
				'redirectPreferred'       => $redirect_preferred,
				'bankId'                  => $bank_id,
				'Redirect-URL'            => $confirm_url,
				'Webhook-URL'             => $webhook_url,
				'endToEndId'              => strval($order_id),
				'description'             => sprintf('Order ID %s', $order_id),
				'informationUnstructured' => sprintf('Order ID %s', $order_id),
				'currencyCode'            => $order_info['currency_code'],
				'amount'                  => number_format((float)$total, 2, '.', ''),
				'bankPaymentMethod'       => [
					'endToEndId'          	=> strval($order_id),
					'creditorName'          => $this->config->get('kevin_client_company'),
					'creditorAccount'       => [
						'iban'              	=> $this->config->get('kevin_client_iban')
					],
				],
			];

			if (!empty($customer_email)) {
				$payment_attr['identifier'] = ['email' => $customer_email];
			}

		} else if ($payment_method == 'card') {
			$payment_attr = [
				'paymentMethodPreferred'  => $payment_method, 
				'redirectPreferred'       => '1',
				'Redirect-URL'            => $confirm_url,
				'Webhook-URL'             => $webhook_url,
				'endToEndId'              => strval($order_id),
				'description'             => sprintf('Order ID %s', $order_id),
				'informationUnstructured' => sprintf('Order ID %s', $order_id),
				'currencyCode'            => $order_info['currency_code'],
				'amount'                  => number_format((float)$total, 2, '.', ''),
				'bankPaymentMethod'       => [
					'endToEndId'          	=> strval($order_id),
					'creditorName'          => $this->config->get('kevin_client_company'),
					'creditorAccount'       => [
						'iban'              	=> $this->config->get('kevin_client_iban')
					],
				],
				'cardPaymentMethod'      => [

				]
			];

			if (!empty($customer_email)) {
				$payment_attr['identifier'] = ['email' => $customer_email];
			}
		} else {
			$log_data = 'Answer on Redirect Kevin... No payment options using this payment method available.';
			$this->KevinLog($log_data);
			$this->session->data['error'] = 'No payment options using this payment method available.';
			$this->response->redirect($this->url->link('checkout/cart'));
		}
	

		$init_payment = $kevinClient->payment()->initPayment($payment_attr);
		//echo '<pre>response:';	print_r($init_payment); echo '</pre>'; die;	
		if (!empty($init_payment['id'])) {
			$payment_id = $init_payment['id'];
		} else {
			$log_data = 'Answer on Redirect Kevin... '  . $init_payment['error']['description'] . ' Code: '  . $init_payment['error']['code'] . '.';
			$this->KevinLog($log_data);
			$this->session->data['error'] = $this->language->get('error_kevin_payment') . ' Code: '. $init_payment['error']['code'];
			$this->response->redirect($this->url->link('checkout/cart'));
			$payment_id = 0;
		}
	
		$this->model_extension_payment_kevin->addKevinOrder($order_info, $init_payment, $ip_address, $order_status_id, $total, $payment_method, $bank_id);

		$get_payment_attr = ['PSU-IP-Address' => $ip_address];
		$get_payment = $kevinClient->payment()->getPayment($payment_id, $get_payment_attr);

		/*log*/
		$log_data = 'Answer on Redirect Kevin... Payment Method: ' . $payment_method . '; Payment ID: ' . $payment_id . '; Order ID: ' . $order_id . '; Payment Status: ' . $get_payment['statusGroup'] . '; Total: ' . $get_payment['amount'] . $get_payment['currencyCode'] . '; Bank ID: ' . $bank_id . '.';
		$this->KevinLog($log_data);
		
		$current_country_code = $order_info['payment_iso_code_2'];
		$lang_code = $this->language->get('code');
		$available_lang = array('en', 'lt', 'lv', 'ee', 'fi', 'se', 'ru');
		if (in_array($lang_code, $available_lang)) {
			$lang = $lang_code;
		} else {
			$lang = 'en';
		}
		
		//header('Location:' . $init_payment['confirmLink'] . '&amp;lang=' . $lang);
		$this->response->redirect($init_payment['confirmLink'] . '&amp;lang=' . $lang);
	}

    public function confirm() {
		//date_default_timezone_set('Europe/Vilnius');
		unset($this->session->data['error']);

		require_once dirname(dirname(dirname(__DIR__))) . '/model/extension/payment/kevin/vendor/autoload.php';
		
		$clientId = $this->config->get('kevin_client_id');
		$clientSecret = $this->config->get('kevin_client_secret');
		$options = [
			'error'                 => 'array',
			'version'               => $this->lib_version,
			'pluginVersion'         => $this->plugin_version,
			'pluginPlatform'        => 'OpenCart',
			'pluginPlatformVersion' => strval(VERSION)
		];

		$kevinClient = new Client($clientId, $clientSecret, $options);
  
        $this->language->load('extension/payment/kevin');
        $this->load->model('checkout/order');
        $this->load->model('extension/payment/kevin');

		if (isset($this->request->get['paymentId'])) {
            $payment_id = $this->request->get['paymentId'];
        } elseif (isset($_POST['paymentId'])) {
            $payment_id = $_POST['paymentId'];
        } else {
            $payment_id = '';
			$log_data = 'On the order confirm Payment ID not received from Kevin\'s server!';
        }
		
		if (!$payment_id) {
			$this->KevinLog($log_data);
			$this->session->data['error'] = $this->language->get('error_kevin_payment_id');
			$this->response->redirect($this->url->link('checkout/cart'));
		}
		
		$order_query = $this->model_extension_payment_kevin->getKevinOrders($payment_id);
	
        if ($order_query) {
            $order_id = $order_query['order_id'];
			$payment_method = $order_query['payment_method'];
			$old_status_id = $order_query['order_status_id'];
        } else {
            $order_id = 0;
			$payment_method = '';
			$this->session->data['error'] = 'An error occurred. Order Session have been ended! Please try again.';
			$this->KevinLog($this->session->data['error']);
			$this->response->redirect($this->url->link('checkout/cart'));
        }
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		$ip_address = $order_info['ip'];
		$payment_status_attr = ['PSU-IP-Address' => $ip_address];
		$get_payment_status = $kevinClient->payment()->getPaymentStatus($payment_id, $payment_status_attr);

		switch ($get_payment_status['group']) {
			case 'started':
				$new_status_id = $this->config->get('kevin_started_status_id');
				$new_status = $get_payment_status['group'];
				break;
			case 'pending':
				$new_status_id = $this->config->get('kevin_pending_status_id'); 
				$new_status = $get_payment_status['group'];
				break;
			case 'completed':
				$new_status_id = $this->config->get('kevin_completed_status_id');
				$new_status = $get_payment_status['group'];
				break;
			case 'failed':
				$new_status_id = $this->config->get('kevin_failed_status_id');
				$new_status = $get_payment_status['group'];
				break;
			default:
				$new_status_id = null;
				$new_status = $get_payment_status['group'];
		}

		if (!$new_status_id) {
			$this->session->data['error'] = 'An error occurred. On response not received any status group.' . $get_payment_status['error']['code'] . ' ' . $get_payment_status['error']['description'];
			$this->KevinLog($this->session->data['error']);
			$this->response->redirect($this->url->link('checkout/cart'));
		}
		
		/*log*/
		$log_data = 'Answer on Confirm Kevin... Payment Method: ' . $payment_method . '; Payment ID: ' . $payment_id . '; Order ID: ' . $order_info['order_id'] . '; Payment Status: ' . $new_status  . '.';
		
		if ($old_status_id != $new_status_id && $order_info['order_id'] == $this->session->data['order_id']) {
			$this->KevinLog($log_data);
			$this->model_extension_payment_kevin->updateConfirmKevinOrder($payment_id, $get_payment_status, $new_status_id, $payment_method);
			$payment_status = true;
		} else {
			$payment_status = false;
		}
		
		/*validate order*/
		$order_query = $this->model_extension_payment_kevin->getKevinOrders($payment_id);
		
		if (!empty($order_query['bank_id']) && $order_query['bank_id'] != 'card') {
			$bank_id = ' (' . $order_query['bank_id'] . ')';
		} else {
			$bank_id = '';
		}

		$comment = sprintf($this->language->get('text_kevin_payment_method'), ucfirst($order_query['payment_method'])) . $bank_id . "\n";
		$comment .= sprintf($this->language->get('text_status'),  ucfirst($order_query['status'])) . "\n";
		$comment .= sprintf($this->language->get('text_status_group'),  ucfirst($order_query['statusGroup'])) . "\n";
		$comment .= sprintf($this->language->get('text_payment_id'),  $order_query['payment_id']);

        if ($new_status == 'completed') {
            $order_status_id = $this->config->get('kevin_completed_status_id');
			if ($payment_status) {
				$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true);
			}	
			$this->response->redirect($this->url->link('checkout/success'));
		} else if ($new_status == 'pending') {
			$order_status_id = $this->config->get('kevin_pending_status_id');
			if ($payment_status) {
				$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true);
			}
			$this->response->redirect($this->url->link('checkout/success'));
        } else if ($new_status == 'failed') {
			$order_status_id = $this->config->get('kevin_failed_status_id');
			if ($payment_status) {
				$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true);
			}
			$this->response->redirect($this->url->link('checkout/failure'));
        } else {
			$this->session->data['error'] = $this->language->get('error_kevin_payment') . $get_payment_status['error']['code'] . ' ' . $get_payment_status['error']['description'];
			$this->response->redirect($this->url->link('checkout/cart'));
		}
    }

    public function webhook() {
		//date_default_timezone_set('Europe/Vilnius');
        $this->language->load('extension/payment/kevin');
        $this->load->model('checkout/order');
        $this->load->model('extension/payment/kevin');
		
		$webhook_data = file_get_contents('php://input');
		
		$this->KevinLog('Answer from Kevin on Webhook:' . $webhook_data);//rasom i log ka gaunam is webhook
		
		if ($webhook_data !== false && !empty($webhook_data)) {
			$get_payment_status = json_decode($webhook_data, true);
		} else {
			$this->KevinLog('Payment status not received from the remote server!');
			die();
		}
		
		$payment_id = $get_payment_status['id'];
		
		require_once dirname(dirname(dirname(__DIR__))) . '/model/extension/payment/kevin/vendor/autoload.php';
		
		$clientId = $this->config->get('kevin_client_id');
		$clientSecret = $this->config->get('kevin_client_secret');
		$options = [
			'error'                 => 'array',
			'version'               => $this->lib_version,
			'pluginVersion'         => $this->plugin_version,
			'pluginPlatform'        => 'OpenCart',
			'pluginPlatformVersion' => strval(VERSION)
		];

		$kevinClient = new Client($clientId, $clientSecret, $options);

		/*
		switch ($get_payment_status['statusGroup']) {
			case 'started':
				$new_status_id = $this->config->get('kevin_started_status_id');
				$new_status = $get_payment_status['statusGroup'];
				break;
			case 'pending':
				$new_status_id = $this->config->get('kevin_pending_status_id'); 
				$new_status = $get_payment_status['statusGroup'];
				break;
			case 'completed':
				$new_status_id = $this->config->get('kevin_completed_status_id');
				$new_status = $get_payment_status['statusGroup'];
				break;
			case 'failed':
				$new_status_id = $this->config->get('kevin_failed_status_id');
				$new_status = $get_payment_status['statusGroup'];
				break;
			default:
				$new_status_id = null;
				$new_status = $get_payment_status['statusGroup'];
		}
		*/
		
		$order_query = $this->model_extension_payment_kevin->getKevinOrders($payment_id);
	
        if ($order_query) {
            $order_id = $order_query['order_id'];
			$payment_method = $order_query['payment_method'];
			$old_status_id = $order_query['order_status_id'];
        } else {
            $order_id = 0;
			$payment_method = '';
			$this->KevinLog('An error occurred. The order have been dleted from database.');
			die();
        }

		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		// checking payment status
		$ip_address = $order_info['ip'];
		$payment_status_attr = ['PSU-IP-Address' => $ip_address];
		$get_payment_status = $kevinClient->payment()->getPaymentStatus($payment_id, $payment_status_attr);

		switch ($get_payment_status['group']) {
			case 'started':
				$new_status_id = $this->config->get('kevin_started_status_id');
				$new_status = $get_payment_status['group'];
				break;
			case 'pending':
				$new_status_id = $this->config->get('kevin_pending_status_id'); 
				$new_status = $get_payment_status['group'];
				break;
			case 'completed':
				$new_status_id = $this->config->get('kevin_completed_status_id');
				$new_status = $get_payment_status['group'];
				break;
			case 'failed':
				$new_status_id = $this->config->get('kevin_failed_status_id');
				$new_status = $get_payment_status['group'];
				break;
			default:
				$new_status_id = null;
				$new_status = $get_payment_status['group'];
		}

		$log_data = 'Answer on WebHook Kevin... Payment Method: ' . $payment_method . '; Payment ID: ' . $payment_id . '; Order ID: ' . $order_info['order_id'] . '; Payment Status: ' . $new_status . '.';
		
		//$old_status_id = $order_info['order_status_id'];
		
		if ($old_status_id != $new_status_id && $order_info['order_id'] == $order_id) {
			$this->KevinLog($log_data);
			$this->model_extension_payment_kevin->updateWebhookKevinOrder($payment_id, $get_payment_status, $new_status_id, $payment_method);
			$payment_status = true;
		} else {
			$payment_status = false;
		}
		
		$order_query = $this->model_extension_payment_kevin->getKevinOrders($payment_id);

		if (!empty($order_query['bank_id']) && $order_query['bank_id'] != 'card') {
			$bank_id = ' (' . $order_query['bank_id'] . ')';
		} else {
			$bank_id = '';
		}

		$comment = sprintf($this->language->get('text_kevin_payment_method'), ucfirst($order_query['payment_method'])) . $bank_id . "\n";
		$comment .= sprintf($this->language->get('text_status'),  ucfirst($order_query['status'])) . "\n";
		$comment .= sprintf($this->language->get('text_status_group'),  ucfirst($order_query['statusGroup'])) . "\n";
		$comment .= sprintf($this->language->get('text_payment_id'),  $order_query['payment_id']);

		if  ($new_status == 'completed' && $payment_status){
			$order_status_id = $this->config->get('kevin_completed_status_id');
			$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true);
			
		} else if ($new_status == 'pending' && $payment_status) {
			$order_status_id = $this->config->get('kevin_pending_status_id');
			$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true);

        } else if ($new_status == 'failed' && $payment_status) {
			$order_status_id = $this->config->get('kevin_failed_status_id');
			$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true);

		} 
    }
		
	/*log*/
	public function KevinLog($log_data) {
		if ($this->config->get('kevin_log')) {
            $kevin_log = new Log('kevin_payment.log');
            $kevin_log->write($log_data);
		} else { 
			null; 
		}
	}
}
