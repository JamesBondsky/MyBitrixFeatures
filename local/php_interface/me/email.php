<?php

// Check if it is Ajax
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest')
{ 
	die('Error: Request error [1]');
}
define('CRM_HOST', 'b24-1t8n0i.bitrix24.ru'); // Ваш домен CRM системы
define('CRM_PORT', '443'); // CRM server port
define('CRM_PATH_ADD', '/crm/configs/import/lead.php');
include('data.php');

// Emails From and To
$my_email = 'ПолимерПроПлюс <zakaz@glavpolimer.ru>';
$my_email_to = 'zakaz@glavpolimer.ru';
$my_email_to2 = 'nasibulin.bulat@gmail.com';

// Data from Form
$form = array();

/* Fields */
$form['name'] = (isset($_POST['name']) && $_POST['name'] ? trim($_POST['name']) : false);
$form['phone'] = (isset($_POST['phone']) ? trim($_POST['phone']) : false);
$form['target'] = (isset($_POST['target']) ? trim($_POST['target']) : false);
$form['quiz_type'] = (isset($_POST['quiz_type']) ? trim($_POST['quiz_type']) : false);
$form['quiz_size'] = (isset($_POST['quiz_size']) ? trim($_POST['quiz_size']) : false);
$form['quiz_water'] = (isset($_POST['quiz_water']) ? trim($_POST['quiz_water']) : false);
$form['quiz_comments'] = (isset($_POST['quiz_comments']) ? trim($_POST['quiz_comments']) : false);

$form['source'] = (isset($_POST['source']) ? trim($_POST['source']) : false);

if (strpos($form['name'], '@') !== false)
{
	$form['email'] = $form['name'];
	$form['name'] = false;
}

$test_str = 'Тип погреба: ' . $form['quiz_type'] . '; Размер: ' . $form['quiz_size'] . '; Грунтовые воды: ' . $form['quiz_water'] . '; Пожелания: ' . $form['quiz_comments'] . ';';
/* \Fields */

/* UTM */
$utm_data = (isset($_POST['utm_data']) && is_array($_POST['utm_data']) ? $_POST['utm_data'] : array());
$utm_data_string = 'UTM-метки: ' . "\n";

foreach ($utm_data as $utm_data_key => $utm_data_value) {
	$utm_key_name = '';

	switch ($utm_data_key) {
		case 'utm_source':
			$utm_key_name = 'Рекламная система (utm_source): '.$utm_data_value."\n";
			break;

		case 'utm_medium':
			$utm_key_name = 'Тип трафика (utm_medium): '.$utm_data_value."\n";
			break;

		case 'utm_campaign':
			$utm_key_name = 'Рекламная кампания (utm_campaign): '.$utm_data_value."\n";
			break;

		case 'utm_content':
			$utm_key_name = 'Содержание (utm_content): '.$utm_data_value."\n";
			break;

		case 'utm_term':
			$utm_key_name = 'Ключевое слово (utm_term): '.$utm_data_value."\n";
			break;
	}

	$utm_data_string .= $utm_key_name;
}
/* \UTM */

/* Advert Data */
$ad_data = (isset($_POST['ad_data']) && is_array($_POST['ad_data']) ? $_POST['ad_data'] : array());
$ad_data_string = 'Параметры рекламных систем: ' . "\n";

foreach ($ad_data as $ad_data_key => $ad_data_value) {
	$ad_key_name = '';

	switch ($ad_data_key) {
		case 'ad_source_type':
			$ad_key_name = 'Тип площадки: '.$ad_data_value."\n";
			break;

		case 'ad_placement':
			$ad_key_name = 'Площадка: '.$ad_data_value."\n";
			break;

		case 'ad_position':
			$ad_key_name = 'Позиция: '.$ad_data_value."\n";
			break;

		case 'ad_keyword':
			$ad_key_name = 'Ключевое слово: '.$ad_data_value."\n";
			break;

		case 'ad_matchtype':
			$ad_key_name = 'Соответствие поисковой фразе: '.$ad_data_value."\n";
			break;

		case 'ad_position_type':
			$ad_key_name = 'Размещение: '.$ad_data_value."\n";
			break;

		case 'ad_creative':
			$ad_key_name = 'Уникальный идентификатор объявления: '.$ad_data_value."\n";
			break;

		case 'ad_device':
			$ad_key_name = 'Устройство: '.$ad_data_value."\n";
			break;

		case 'ad_devicemodel':
			$ad_key_name = 'Марка и модель устройства: '.$ad_data_value."\n";
			break;

		case 'ad_target':
			$ad_key_name = 'Категория размещения объявления: '.$ad_data_value."\n";
			break;
	}

	$ad_data_string .= $ad_key_name;
}
/* \Advert Data */

/* Referral Data */
$ref_data = (isset($_POST['ref_data']) && is_array($_POST['ref_data']) ? $_POST['ref_data'] : array());
$ref_data_string = 'Реф. хвосты: ' . "\n";

foreach ($ref_data as $ref_data_key => $ref_data_value) {
	$ref_data_string .= $ref_data_value . "\n";
}
/* \Referral Data */

// Message Headers
$headers           = 'From: ' . $my_email . "\r\n" .
		             'Content-type: text/plain; charset=utf-8' . "\r\n" .
		             'X-Mailer: PHP/' . phpversion();

// Subject and Message for reply
$autoreply_subject = 'Заявка на сайте «ППП»';
$autoreply_message = 'Спасибо за заявку! Мы свяжемся с вами' . "\n\n" .
				     'С уважением, команда «ППП».';

// Subject and Message for Managers
$manager_subject   = '[ППП.LP] Поступила новая заявка ' . ($form['name'] ? $form['name'] : 'Квиз лендинг погреб');
$manager_message   = 'Поступила новая заявка: ' . "\n\n" .
						 ($form['quiz_type'] ? 'Данные опросника: ' . "\n" . $test_str . "\n" : '') .
				     'Имя клиента: ' .     ($form['name'] ? $form['name'] : '---') . "\n" .
				     'Телефон клиента: ' . ($form['phone'] ? $form['phone'] : '---') . "\n" .
				     'Кнопка: ' . ($form['source'] ? $form['source'] : '---') . "\n" .
                                     'Target формы: ' . ($form['target'] ? $form['target'] : '---') . "\n" .
				      $utm_data_string . "\n" .
				      $ad_data_string . "\n" .
				      $ref_data_string . "\n" .
				     'IP клиента: ' . $_SERVER['REMOTE_ADDR'] . "\n\n";

$crm_message  = ($form['quiz_type'] ? 'Данные опросника: ' . "\n" . $test_str . "\n" : '') .
				     'Кнопка: ' . ($form['source'] ? $form['source'] : '---') . "\n" .
				     'IP клиента: ' . $_SERVER['REMOTE_ADDR'] . "\n\n";

$crm_name_array = explode(' ', $form['name']);

$postData = array(
	'TITLE' => 'Заявка с сайта ППП.LP',
	'NAME' => (isset($crm_name_array[0]) ? $crm_name_array[0] : ''),
	'LAST_NAME' => (isset($crm_name_array[1]) ? $crm_name_array[1] : ''),
	'PHONE_WORK' => str_replace(array('(', ')', ' ', '-'), '', $form['phone']),
	'CURRENCY_ID' => 'RUB',
	'SOURCE_ID' => 'WEB',
	'SOURCE_DESCRIPTION' => (isset($utm_data['utm_term']) ? $utm_data['utm_term'] : ''),
	//'ASSIGNED_BY_ID' => 54,
	'COMMENTS' => $crm_message,
	// UTM-данные
	//'UF_CRM_1531568275' => (isset($utm_data['utm_source']) ? $utm_data['utm_source'] : ''),
	//'UF_CRM_1531568313' => (isset($utm_data['utm_medium']) ? $utm_data['utm_medium'] : ''),
	//'UF_CRM_1531568340' => (isset($utm_data['utm_campaign']) ? $utm_data['utm_campaign'] : ''),
	//'UF_CRM_1531568374' => (isset($utm_data['utm_content']) ? $utm_data['utm_content'] : ''),
	//'UF_CRM_1531568402' => (isset($utm_data['utm_term']) ? $utm_data['utm_term'] : ''),
	// Данные рекламных систем
	//'UF_CRM_1444981526' => (isset($ad_data['ad_source_type']) ? $ad_data['ad_source_type'] : ''), // Тип площадки (ad_source_type):
	//'UF_CRM_1444981548' => (isset($ad_data['ad_placement']) ? $ad_data['ad_placement'] : ''), // Площадка (ad_placement):
	//'UF_CRM_1444981562' => (isset($ad_data['ad_position']) ? $ad_data['ad_position'] : ''), // Позиция (ad_position):
	//'UF_CRM_1444981575' => (isset($ad_data['ad_keyword']) ? $ad_data['ad_keyword'] : ''), // Ключевое слово (ad_keyword):
	//'UF_CRM_1444981593' => (isset($ad_data['ad_matchtype']) ? $ad_data['ad_matchtype'] : ''), // Соответствие поисковой фразе (ad_matchtype):
	//'UF_CRM_1444981609' => (isset($ad_data['ad_position_type']) ? $ad_data['ad_position_type'] : ''), // Размещение (ad_position_type):
	//'UF_CRM_1444981624' => (isset($ad_data['ad_creative']) ? $ad_data['ad_creative'] : ''), // Уникальный идентификатор объявления (ad_creative):
	//'UF_CRM_1444981637' => (isset($ad_data['ad_device']) ? $ad_data['ad_device'] : ''), // Устройство (ad_device):
	//'UF_CRM_1444981650' => (isset($ad_data['ad_devicemodel']) ? $ad_data['ad_devicemodel'] : ''), // Марка и модель устройства (ad_devicemodel):
	//'UF_CRM_1444981664' => (isset($ad_data['ad_target']) ? $ad_data['ad_target'] : ''), // Категория размещения объявления (ad_target):
	// Реф. хвосты
	//'UF_CRM_1444981680' => (isset($ref_data['phrase']) ? $ref_data['phrase'] : ''), // Ключи
	//'UF_CRM_1444981694' => (isset($ref_data['referer']) ? $ref_data['referer'] : ''), // Источник
	//'UF_CRM_1456307727' => (isset($ga_cid) ? $ga_cid : ''), //cid
);

// append authorization data
$postData['LOGIN'] = $crm_login;
$postData['PASSWORD'] = $crm_password;

$fp = fsockopen("ssl://".$crm_host, CRM_PORT, $errno, $errstr, 30);
if ($fp)
{
	// prepare POST data
	$strPostData = '';
	foreach ($postData as $key => $value)
		$strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);

	// prepare POST headers
	$str = "POST ".CRM_PATH_ADD." HTTP/1.0\r\n";
	$str .= "Host: ".$crm_host."\r\n";
	$str .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$str .= "Content-Length: ".strlen($strPostData)."\r\n";
	$str .= "Connection: close\r\n\r\n";

	$str .= $strPostData;

	// send POST to CRM
	fwrite($fp, $str);

	// get CRM headers
	$result = '';
	while (!feof($fp))
	{
		$result .= fgets($fp, 128);
	}
	fclose($fp);

	// cut response headers
	$response = explode("\r\n\r\n", $result);

	$output = '<pre>'.print_r($response[1], 1).'</pre>';
	// echo $output;
	// print_r($output);
	// print_r($postData);
}
else
{
	//echo 'Connection Failed! '.$errstr.' ('.$errno.')';
}

// Sending Emails
if (isset($manager_message))
{
		$result_sending = false;

		// Sending Email to Manager
		if (mail($my_email_to, $manager_subject, $manager_message, $headers))
		{
            mail($my_email_to2, $manager_subject, $manager_message, $headers);
			$result_sending = true;
		}

		// Sending Email to Client
		//if (isset($form['email']) && filter_var($form['email'], FILTER_VALIDATE_EMAIL))
		//{
		//	mail($form['email'], $autoreply_subject, $autoreply_message, $headers);
		//}

		//if ($result_sending)
		//{
			// Well done!
			echo 'sended';
				
		//}
		//else
		//{
			// Problem :(
			//echo 'problems / 1';
		//}
}
else
{
	// Problem :(
	echo 'problems / 2';
}
?>