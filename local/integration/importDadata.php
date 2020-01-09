<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("sale");

use Bitrix\Sale\Delivery\DeliveryLocationTable;

define('RUSSIA_ID', 3143);
define('DADATA_TOKEN', 'ac539c9965e25c46ed1a99575ad45eaf4eb3bd67');
define('DADATA_SECRET', '76d43e27aaf0408a4a7d2127f0ea18c6491b08f2');

class DadataClient
{
    private $base_url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs";
    private $token;
    private $handle;
    private $secret;

    function __construct($token, $secret)
    {
        $this->token = $token;
        $this->secret = $secret;
    }

    public function init()
    {
        $this->handle = curl_init();
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->handle, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Token " . $this->token,
            "X-Secret:" . $this->secret
        ));
        curl_setopt($this->handle, CURLOPT_POST, 1);
    }

    /**
     * See https://dadata.ru/api/outward/ for details.
     */
    public function findById($type, $fields)
    {
        $url = $this->base_url . "/findById/$type";
        return $this->executeRequest($url, $fields);
    }

    /**
     * See https://dadata.ru/api/geolocate/ for details.
     */
    public function geolocate($lat, $lon, $count = 10, $radius_meters = 100)
    {
        $url = $this->base_url . "/geolocate/address";
        $fields = array(
            "lat" => $lat,
            "lon" => $lon,
            "count" => $count,
            "radius_meters" => $radius_meters
        );
        return $this->executeRequest($url, $fields);
    }

    /**
     * See https://dadata.ru/api/iplocate/ for details.
     */
    public function iplocate($ip)
    {
        $url = $this->base_url . "/iplocate/address?ip=" . $ip;
        return $this->executeRequest($url, $fields = null);
    }

    /**
     * See https://dadata.ru/api/suggest/ for details.
     */
    public function suggest($type, $fields)
    {
        $url = $this->base_url . "/suggest/$type";
        return $this->executeRequest($url, $fields);
    }

    public function createQueryWithUrl($url, $fields)
    {
        return $this->executeRequest($url, $fields);
    }

    public function close()
    {
        curl_close($this->handle);
    }

    private function executeRequest($url, $fields)
    {
        curl_setopt($this->handle, CURLOPT_URL, $url);
        if ($fields != null) {
            curl_setopt($this->handle, CURLOPT_POST, 1);
            curl_setopt($this->handle, CURLOPT_POSTFIELDS, json_encode($fields));
        } else {
            curl_setopt($this->handle, CURLOPT_POST, 0);
        }
        $result = curl_exec($this->handle);
        $result = json_decode($result, true);
        return $result;
    }
}

function readCSV($file)
{
    if (file_exists($file)) {
        if (($fp = fopen($file, "r")) !== false) {
            while (($data = fgetcsv($fp, 0, ";")) !== false) {
                $list[] = $data;
            }
            fclose($fp);
            return $list;
        } else {
            print_r('Не удалось открыть файл ' . $file . '<br>');
        }
    } else {
        print_r('Не удалось найти файл ' . $file . '<br>');
    }
}

function justDadataQuery($str)
{
    $dadata = new DadataClient(DADATA_TOKEN, DADATA_SECRET);
    $dadata->init();
    $fields = array("query" => $str, "count" => 1);
    $result = $dadata->suggest("address", $fields);
    print_r($result);
    $dadata->close();
}


function addFromCsvList($list, $serviceID)
{
    $dadata = new DadataClient(DADATA_TOKEN, DADATA_SECRET);
    $dadata->init();
//    $fields = array("query" => 'пермь', "count" => 1);
//    $result = $dadata->suggest("address", $fields);
//    print_r($result);
//    return;

    $arKladrsOfLocation = array();

    foreach ($list as $arcity) {
        $city = $arcity[0];
        $serviceIDfromFile = $arcity[1];
        $fields = array("query" => $city, "count" => 1);
        $result = $dadata->suggest("address", $fields);
        $city_kladr = $result['suggestions'][0]['data']['city_kladr_id'];
        $cityName = $result['suggestions'][0]['data']['city'];
        $cityType = $result['suggestions'][0]['data']['city_type_full'];

        array_push($arKladrsOfLocation, $city_kladr);

        $cityID = isLocationByCodeExist($city_kladr);
        if ($cityID === false) {
            $region_kladr = $result['suggestions'][0]['data']['region_kladr_id'];

            $regionID = isLocationByCodeExist($region_kladr);
            $regionName = $result['suggestions'][0]['data']['region'];

            if ($regionID === false) {
                //добавляем регион
                $regionType = $result['suggestions'][0]['data']['region_type_full'];
                //print_r($regionType);
                $regionID = addLocation($region_kladr, RUSSIA_ID, $regionName, CUtil::translit($regionName, 'ru'), $regionType);
            } else {
                print_r('Местоположение ' . $regionName . ' уже есть в базе<br>');
            }

            //добавляем город в этот регион
            //$regionID = isLocationByCodeExist($region_kladr);
            //print_r($regionID.'<br>');
            addLocation($city_kladr, $regionID, $cityName, CUtil::translit($cityName, 'ru'), $cityType, $serviceID, $serviceIDfromFile);
        } else {
            updateLocation($cityID, $serviceID, $serviceIDfromFile);
            print_r('Местоположение ' . $cityName . ' уже есть в базе.<br>');
        }
    }
    $dadata->close();
    return $arKladrsOfLocation;
}

function isLocationByCodeExist($locCode)
{
    $res = CSaleLocation::GetList(array(), array("CODE" => $locCode), false, false, array('ID'));
    if ($loc = $res->fetch()) {
        //print_r($loc['ID']);
        return $loc['ID'];
    } else
        return false;
}

function addLocation($code, $parentID, $ruName, $enName, $regionName, $serviceID = 0, $serviceIDfromFile = '')
{
    $serviceField = array();
    //$sameServiceForDelete = array();
    if ($serviceID != 0) {
        $serviceField = array(
            'SERVICE_ID' => $serviceID, // ID сервиса
            'XML_ID' => $serviceIDfromFile // значение
        );
//        $sameServiceForDelete = array(
//            'SERVICE_ID' => $serviceID,
//            'REMOVE' => 'Y'
//        );
    }
    $res = \Bitrix\Sale\Location\LocationTable::add(array(
        'CODE' => $code,
        'SORT' => '100', // приоритет показа при поиске
        'PARENT_ID' => $parentID, // ID родительского местоположения
        'TYPE_ID' => chooseLocationType($regionName), // ID типа
        'NAME' => array( // языковые названия
            'ru' => array(
                'NAME' => $ruName
            ),
            'en' => array(
                'NAME' => $enName
            ),
        ),
        'EXTERNAL' => array( // значения внешних сервисов
//            array(
//                'SERVICE_ID' => 1, // ID сервиса
//                'XML_ID' => '163000' // значение
//            ),
            //$sameServiceForDelete,
            $serviceField
        )
    ));
    if ($res->isSuccess()) {
        print_r('Местоположение ' . $ruName . ' с ID = ' . $res->getId() . ' добавлено.<br>');
        return $res->getId();
    } else {
        print_r($res->getErrorMessages());
        print_r('<br>');
    }
}

function updateLocation($idLoc, $serviceID = 0, $serviceIDfromFile = '')
{
    $serviceField = array();
    if ($serviceID != 0) {
        $serviceField = array(
            'SERVICE_ID' => $serviceID, // ID сервиса
            'XML_ID' => $serviceIDfromFile // значение
        );
    }
    $res = \Bitrix\Sale\Location\LocationTable::update($idLoc, array(
        'SORT' => '100', // приоритет показа при поиске
        'EXTERNAL' => array( // значения внешних сервисов
            $serviceField
        )
    ));
    if ($res->isSuccess()) {
        return $res->getId();
    } else {
        print_r($res->getErrorMessages());
        print_r('<br>');
    }
}

//function chooseType($regionName)
//{
//    switch (mb_strtolower($regionName)) {
//        case 'страна':
//            return 1;
//        case 'округ':
//            return 2;
//        case 'город':
//            return 5;
//
//        case 'район области':
//            return 4;
//        case 'село':
//            return 6;
//        case 'улица':
//            return 7;
////        case 'край':
////            return 8;
//        default:
//        case 'область':
//            return 3;
//    }
//}


function chooseLocationType($regionName)
{
    $res = \Bitrix\Sale\Location\TypeTable::getList(array(
        'filter' => array('=NAME_RU' => $regionName, '=NAME.LANGUAGE_ID' => 'RU'),
        'select' => array('TYPE_ID' => 'ID', 'NAME_RU' => 'NAME.NAME')
    ));
    return $res->fetch()['TYPE_ID'];
}

function addLocationsToDelivery($deliveryId, $arLocations)
{
    if ($deliveryId > 0) {
        $arLocation = array();
        $arLocation["L"] = $arLocations;  // L - для местоположений , G - для типов местоположений
        DeliveryLocationTable::resetMultipleForOwner($deliveryId, $arLocation);
    }
}

$list = readCSV('../../integration/data/delivery2.csv');
$arLocation = addFromCsvList($list, 6);
addLocationsToDelivery(17, $arLocation);

$list = readCSV('../../integration/data/delivery1.csv');
$arLocation = addFromCsvList($list, 5);
addLocationsToDelivery(16, $arLocation);
