<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
define('BX_COMPRESSION_DISABLED',true);
//свои мини функции в глобальном неймспейсе
require_once(__DIR__ . '/me/functions.php');

require_once(__DIR__ . '/me/timeDateWorking.php');

//файл с константами
require_once(__DIR__ . '/progress/constants.php');

//подключение файла работы с weather api
//require_once(__DIR__ . '/progress/weatherApiWorking.php');

//подключение файла работы с API гисметео
require_once(__DIR__ . '/progress/weatherAndGeo/gisMeteoAPI.php');

//файл, который складывает UTM метки (при наличии) в $_SESSION
require_once(__DIR__ . '/progress/utmToSession.php');

//подключение файла работы с youtube api
require_once(__DIR__ . '/progress/youTubeApiWorking.php');

//require_once(__DIR__ . '/progress/weatherWindow.php');

