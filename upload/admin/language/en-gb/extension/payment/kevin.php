<?php
/*
* 2020 Kevin. payment  for OpenCart v.3.0.x.x  
* @version 0.2.1.4
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
// Heading
$_['heading_title']                = 'Kevin. Payment';

// Text 
$_['text_payment']                 = 'Payment';
$_['text_edit']                    = 'Edit Kevin. payment module';
$_['text_extension']               = 'Extensions';
$_['text_success']                 = 'Success: You have modified Kevin module details!';
$_['text_kevin']                   = '<a href="https://www.getkevin.eu/" target="_blank"><img src="view/image/payment/kevin.png" alt="Kevin" title="Kevin" style="border: 0px solid #ffffff; height: 30px;" /></a>';
$_['text_right']                   = 'Right';
$_['text_left']                    = 'Left';

// Entry
$_['entry_general']                = 'General';
$_['entry_order_statuses']         = 'Order Statuses';
$_['entry_instructions']           = 'Payment Instructions';
$_['entry_client_id']              = 'Client Id: ';
$_['entry_client_secret']          = 'Client Secret: ';
$_['entry_client_company']         = 'Client Company Name: ';
$_['entry_client_iban']            = 'Client Accaunt No.: ';
$_['entry_redirect_preferred']     = 'Redirect Preferred';
$_['entry_image']                  = 'Payment Kevin Logo Image';
$_['entry_image_height']           = 'Max Image Height in px';
$_['entry_image_width']            = 'Max Image Width in px';
$_['entry_position']               = 'Payment Logo Position';
$_['entry_bank_name_enabled']      = 'Bank/Card Name';
$_['entry_kevin_title']            = 'Payment Method Title';
$_['entry_instruction_title']      = 'Instruction Title';
$_['entry_kevin_instruction']      = 'Instruction on the confirm step. HTML not supported';
$_['entry_total']                  = 'Total: ';
$_['entry_order_status']           = 'Order Status:';
$_['entry_started_status']         = 'Started';
$_['entry_completed_status']       = 'Complete';
$_['entry_pending_status']         = 'Pending';
$_['entry_failed_status']          = 'Failed';
$_['entry_geo_zone']               = 'Geo Zone:';
$_['entry_status']                 = 'Status:';
$_['entry_log']                    = 'Kevin log:';
$_['entry_sort_order']             = 'Sort Order:';

// Error
$_['error_permission']             = 'Warning: You do not have permission to modify payment Kevin!';
$_['error_client_id']              = 'Client Id Required!';
$_['error_client_secret']          = 'Client Secret Required!';
$_['error_client_company']         = 'Client Company Name Required!';
$_['error_client_iban_empty']      = 'Client Account No. Required!';
$_['error_client_iban_valid']      = 'Client Account No. not valid!';
$_['error_bcmod']                  = 'Not possible validate Account No. because PHP Module "bcmath" are not installed on your server!  Please install "bcmath" module, or ask your server provider to install "bcmath" module.';
$_['error_title']                  = 'Payment Title, or Payment logo Required!';

// Help
$_['help_iban_format']             = 'Account No. format For Lithuania should be two letters and 18 numbers. Example: LT599386327515536498.';
$_['help_bank_name_enbl']          = 'Enable bank/card name on checkout page.';
$_['help_bank_title']              = 'You can only add a bank icon instead of a Payment Method Title.';
$_['help_total']                   = 'The checkout total the order must reach before this payment method becomes active.';
$_['help_log']                     = ' If enabled kevin_payment.log file you can find in you opencart instalation /storage/logs.';
$_['help_width']                   = 'Set the payment logo image  max width in px for &quot;payment method&quot; in checkout payment step. Image height will be changed proportionally.';
$_['help_height']                  = 'Set the payment logo image  max height in px for &quot;payment method&quot; in checkout payment step. Image width will be changed proportionally.';
$_['help_position']                = 'Position of the payment method logo next to the payment method name.';