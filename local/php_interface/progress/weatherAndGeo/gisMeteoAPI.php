<?
define('DEBUG_THIS', 0);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}

require_once(__DIR__ . '/generalOfWeather.php');
//файл с методами работы с инфоблоками
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/iblockAndOther/iBlockWorking.php");

//отсылает cURL запрос на указанный URL
function sendCurlAndGetResponseObj($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "X-Gismeteo-Token: " . API_KEY_GISMETEO,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $json_response = curl_exec($curl);
    curl_close($curl);
    $responseObj = json_decode($json_response, true);

    if ($responseObj['meta']['code'] != 200)
        return false;

    return $responseObj;
}

//получить геоинформацию по IP
function getLocationInfoByIP($ip)
{
    $url = 'https://api.gismeteo.net/v2/search/cities/?ip=' . $ip;
    $locationInfo = sendCurlAndGetResponseObj($url);
    return $locationInfo;
}

//получить погоду по ID города
function getWeatherByCityID($cityID)
{
    $url = 'https://api.gismeteo.net/v2/weather/current/' . $cityID . '/';
    $weatherInfo = sendCurlAndGetResponseObj($url);
    return $weatherInfo;
}

//получаем актуальную погоду для города пользователя
//при успешном выполнении возвращает имя города
function getActualWeather()
{
    //получаем IP пользователя
    $ip = $_SERVER["REMOTE_ADDR"];
    //определяем его геолокацию
    $locInfo = getLocationInfoByIP($ip);
    //достаем код страны
    $countryCode = $locInfo['response']['country']['code'];
    //если код не RU, выходим
    if ($countryCode != 'RU')
        return false;
    //достаем название города
    $cityName = $locInfo['response']['name'];
    //проверяем, нужно ли обновлять/добавлять город
    if(!isNeedUpdateWeatherForCity(WEATHER_IBLOCK_ID, $cityName, $cityInfo))
        return $cityName;
    //достаем ID города
    $cityID = $locInfo['response']['id'];
    //получаем погоду по ID города
    $weatherInfo = getWeatherByCityID($cityID);
    //если с ней что-то плохо, выходим
    if(!$weatherInfo)
        return false;
    //получаем текущую дату
    $date = date("d.m.Y H:i:s");
    //получаем температуру из погоды, округляя ее до целого значения
    $temp = round($weatherInfo['response']['temperature']['air']['C']);

    //провеяем есть ли уже такой город
    if($cityInfo) {
        //если есть
        //получаем все IP из него
        $ips = $cityInfo['PROPS']['USER_IP']['VALUE'];
        //если такого в списке нет, то добавляем
        if (!in_array($ip, $ips)) {
            $ips[] = $ip;
        }
        //устанавливаем необходимые свойства
        $props = array('CITY' => translitString($cityName), 'DATE' => $date,
            'TEMP' => $temp, 'USER_IP' => $ips, 'GISMETEO_ID' => $cityID);
        //обновляем
        updateElementInIblockWithProperties($cityInfo['ID'], $props);
        return $cityName;
    } else {
        //если такого города не было
        //устанавливаем необходимые свойства
        $props = array('CITY' => translitString($cityName), 'DATE' => $date,
            'TEMP' => $temp, 'USER_IP' => $ip, 'GISMETEO_ID' => $cityID);
        //добавляем город в инфоблок
        addElementInIblockWithProperties(WEATHER_IBLOCK_ID, $cityName, $props);
        return $cityName;
    }
}