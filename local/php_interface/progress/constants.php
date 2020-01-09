<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
//массив UTM-меток
define('UTM_ARRAY', array('utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'yclid'));

//массив соответствий кодов свойств форм к ID полей в CRM
//define('FIELDSCODE_TO_CRMID', array(
//    'YCLID' => 'UF_CRM_1571925771',
//    'PRODUCT' => 'UF_CRM_1571987287',
//    'NEED_PRODUCT' => 'UF_CRM_1571987287',
//    )
//);

//URL для вебхука
//define('URL_TO_WEBHOOK', 'https://progress-pk.bitrix24.ru/rest/9/uwwz4cyofuoz1uys/crm.lead.add.json');

//API Key для Ютюба
define('API_KEY_YOUTUBE', 'AIzaSyBOXpfczkIVP_NsoAaRL8sFRPBYwViEry8');

//ID инфоблока к компонентам для калькулятора
define('ID_IBLOCK_TO_COMPONENTS_FOR_CALCULATOR', 45);

//регулярное выражение для получение главного хоста из ссылки
//define('REGEX_PATTERN_GET_MAINHOST_ONLY', '/https?:\/\/(?:www.)?([^\s]*)\.([^\s\/]*)\/{0,1}/');

define('REGEX_PATTERN_GET_MAINHOST_ONLY', '/(?:https?:\/\/)?(?:www\.)?([^\s]*)\.([^\s\/]*)\/{0,1}[^\s]*/');

//API key для Яндекс Геокодер
define('API_KEY_YANDEX_GEOCODER', 'd4e45a11-df50-41e8-a959-c31a4ede0ec6');

//API key для Яндекс Погоды
define('API_KEY_YANDEX_WEATHER', '03c4322a-69c9-4d69-a463-79800a6583f3');

//API key для Гисметео
define('API_KEY_GISMETEO', '5db84753b4c043.65499687');

//ID инфоблока для записи Погоды
define('WEATHER_IBLOCK_ID', 50);