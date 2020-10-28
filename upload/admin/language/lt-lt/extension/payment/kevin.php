<?php
/*
* 2020 Kevin. payment  for OpenCart v.3.0.x.x  
* @version 0.1.3.10
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
*  @author 2020 kevin. <info@getkevin.eu>
*  @copyright kevin.
*  @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
*/
// Heading
$_['heading_title']                = 'Kevin. Mokėjimai';

// Text 
$_['text_payment']                 = 'Mokėjimai';
$_['text_edit']                    = 'Redaguoti Kevin. mokėjimo modulį';
$_['text_extension']               = 'Moduliai';
$_['text_success']                 = 'Sėkmė: Jūs modifikavote Kevin modulį!';
$_['text_kevin']                   = '<a href="https://www.getkevin.eu/" target="_blank"><img src="view/image/payment/kevin.png" alt="Kevin" title="Kevin" style="border: 0px solid #ffffff; height: 30px;" /></a>';
$_['text_right']                   = 'Dešinėje';
$_['text_left']                    = 'Kairėje';

// Entry
$_['entry_general']                = 'Pagrindiniai';
$_['entry_order_statuses']         = 'Užsakymų statusai';
$_['entry_instructions']           = 'Mokėjimo instrukcijos';
$_['entry_client_id']              = 'Kliento Id: ';
$_['entry_client_secret']          = 'Kliento slapptas kodas: ';
$_['entry_client_company']         = 'Kliento įmonės pavadinimas: ';
$_['entry_client_iban']            = 'Kliento Sąskaitos Nr.: ';
$_['entry_redirect_preferred']     = 'Peradresuoti pirmenybė';
$_['entry_image']                  = 'Kevin Logotipas';
$_['entry_image_height']           = 'Maksimalus logotipo aukštis px';
$_['entry_image_width']            = 'Maksimalus logotipo plotis px';
$_['entry_position']               = 'Logotipo vieta';
$_['entry_bank_name_enabled']      = 'Bank pavadinimas';
$_['entry_kevin_title']            = 'Mokėjimo būdo pavadinimas';
$_['entry_instruction_title']      = 'Instrukcijos pavadinimas';
$_['entry_kevin_instruction']      = 'Instrukcija užsakymo patvirtinimo žingsnyje. Nepalaiko HTML.';
$_['entry_total']                  = 'Viso: ';
$_['entry_order_status']           = 'Užsakymo statusas:';
$_['entry_started_status']         = 'Pradėtas';
$_['entry_completed_status']       = 'Baigtas';
$_['entry_pending_status']         = 'Laukiama';
$_['entry_failed_status']          = 'Nepavykęs';
$_['entry_geo_zone']               = 'Geo Zona:';
$_['entry_status']                 = 'Statusas:';
$_['entry_log']                    = 'Kevin log:';
$_['entry_sort_order']             = 'Eilės tvarka:';

// Error
$_['error_permission']             = 'Įspėjimas: Jūs neturite leidimo redaguoti mokėjimo modulio Kevin!';
$_['error_client_id']              = 'Kliento Id būtinas!';
$_['error_client_secret']          = 'Kliento slaptas kodas būtinas!';
$_['error_client_company']         = 'Kliento įmonės pavadinimas būtinas!';
$_['error_client_iban']            = 'Kliento sąskaitos Nr. būtinas!';
$_['error_client_iban_valid']      = 'Kliento sąskaitos Nr. Negalioja!';
$_['error_title']                  = 'Mokėjimo būdo pavadinimas, arba banko logo būtinas!';

// Help
$_['help_iban_format']             = 'Sąskaitos Nr. formatas Lietuvai turi būti dvi raidės ir 18 skaičių. Pvz: LT599386327515536498.';
$_['help_bank_name_enbl']          = 'Rodyti galimų bankų pavadinimus atsiskaitymo puslapyje.';
$_['help_bank_title']              = 'Vietoje mokėjimo būdo pavadinimo galite pridėti tik banko logotipą.';
$_['help_total']                   = 'Atsiskaitymo suma, kada atsiskaitymo galimybė bus aktyvuota.';
$_['help_log']                     = 'Jeigu Kevin įjungtas, kevin_payment.log failą rasite: /storage/logs.';
$_['help_width']                   = 'Nustatykite maksimalų logotipo plotį px  &quot;Mokėjimo metodui&quot; atsiskaitymo mokėjimo pasirinkimo žingsnyje. Logotipo aukštis bus pakeistas proporcingai.';
$_['help_height']                  = 'Nustatykite maksimalų logotipo aukštį px  &quot;Mokėjimo metodui&quot; atsiskaitymo mokėjimo pasirinkimo žingsnyje. Logotipo plotis bus pakeistas proporcingai.';
$_['help_position']                = 'Mokėjimo būdo logotipo vieta šalia mokėjimo metodo pavadinimo.';