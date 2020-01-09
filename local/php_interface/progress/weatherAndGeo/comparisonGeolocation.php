<?php
define('DEBUG_THIS', 0);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/iblockAndOther/iBlockWorking.php");

function getLocationGismeteoByIP($ip)
{
    $curl = curl_init('https://api.gismeteo.net/v2/search/cities/?ip=' . $ip);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "X-Gismeteo-Token: " . '5db84753b4c043.65499687',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $json_response = curl_exec($curl);
    curl_close($curl);
    $responseObj = json_decode($json_response, true);
    return $responseObj;
}

function getLocationIpApiByIP($ip)
{
    $response = json_decode(file_get_contents("http://ip-api.com/json/" . $ip . '?lang=ru'), true);

    if ($response['status'] == 'success'/* && $response['countryCode'] == 'RU'*/) {
        return $response;
    } else {
        return false;
    }
}

function getCityStandartByIP($ip) {
    return \Bitrix\Main\Service\GeoIp\Manager::getCityName($ip, 'ru');
}

function getAllLocations() {
    $elements = getListOfElementsWithPropertiesAsArray(48);
    $ip = $_SERVER["REMOTE_ADDR"];

    foreach ($elements as $id => $element) {
        if(in_array($ip, $element))
            return;
    }

    $gmResp = getLocationGismeteoByIP($ip);
    $gmCity = $gmResp['response']['name'];

    $ipapiResp = getLocationIpApiByIP($ip);
    $ipapiCity = $ipapiResp['city'];

    $standartCity = getCityStandartByIP($ip);

    $props = array('GISMETEO_LOC' => $gmCity, 'IPAPI_LOC' => $ipapiCity, 'STANDART_LOC' => $standartCity);

    addElementInIblockWithProperties(48, $ip, $props);
}


