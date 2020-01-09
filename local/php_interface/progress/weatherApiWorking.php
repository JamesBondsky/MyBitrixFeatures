<?
define('DEBUG_THIS', 1);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}

//файл с методами работы с инфоблоками
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/iblockAndOther/iBlockWorking.php");

//файл с методами работы с файлами битриксовыми методами
//require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/filesWorking.php");

//путь к файлу с городами
//define('CITIES_JSON_FILE', '/local/php_interface/progress/data/cityWeather.json');

function getGeoDataByIP($ip = false)
{
    if (!$ip) {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    $response = json_decode(file_get_contents("http://ip-api.com/json/" . $ip . '?lang=ru'), true);

    if ($response['status'] == 'success' && $response['countryCode'] == 'RU') {
        return $response;
    } else {
        return false;
    }
}

//function sendCurlMaps($cityName)
//{
//    $curl = curl_init('https://geocode-maps.yandex.ru/1.x/?format=json&apikey=' . API_KEY_YANDEX_GEOCODER . '&kind=locality&results=1&geocode=' . urlencode($cityName));
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//    $json_response = curl_exec($curl);
//    curl_close($curl);
//    $responseObj = json_decode($json_response);
//    return $responseObj;
//}

//function getCoordinatsAsArrayFromYandex($cityName)
//{
//    $responseObj = sendCurlMaps($cityName);
//    $countryCode = $responseObj->response->GeoObjectCollection->featureMember[0]->GeoObject->
//    metaDataProperty->GeocoderMetaData->Address->country_code;
//
//    if ($countryCode != 'RU')
//        return false;
//
//    $coordStr = $responseObj->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
//
//    preg_match('/([\d.]*) ([\d.]*)/', $coordStr, $coordinats);
//
//    $arrayCoord = array('lat' => $coordinats[2], 'lon' => $coordinats[1]);
//    return $arrayCoord;
//}

//отправка запроса на Яндекс.Погоду с координатами
function sendCurlWeatherYandex($lat, $lon)
{
    $curl = curl_init('https://api.weather.yandex.ru/v1/informers?lat=' . $lat . '&lon=' . $lon);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "X-Yandex-API-Key: " . API_KEY_YANDEX_WEATHER,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $json_response = curl_exec($curl);
    curl_close($curl);
    $responseObj = json_decode($json_response);
    return $responseObj;
}

function gisMeteoGetCityID($ip)
{
//    $curl = curl_init('https://api.gismeteo.net/v2/weather/current/?latitude=' . $lat . '&longitude=' . $lon . '&limit=1');
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

function gisMeteoGetWeather($cityID)
{
//    $curl = curl_init('https://api.gismeteo.net/v2/weather/current/?latitude=' . $lat . '&longitude=' . $lon . '&limit=1');
    $curl = curl_init('https://api.gismeteo.net/v2/weather/current/' . $cityID . '/');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "X-Gismeteo-Token: " . '5db84753b4c043.65499687',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $json_response = curl_exec($curl);
    curl_close($curl);
    $responseObj = json_decode($json_response, true);
    return $responseObj;
}

function gisMeteoGetTemperatureOnly($savedCity = array())
{
    $cityID = $savedCity['PROPS']['GISMETEO_ID']['VALUE'];
    $cityName = $savedCity['TITLE'];

    if (!$cityID) {
        $ip = $_SERVER["REMOTE_ADDR"];
        $responseGismeteoIP = gisMeteoGetCityID($ip);
        $cityID = $responseGismeteoIP['response']['id'];
        $cityName = $responseGismeteoIP['response']['name'];
        //$countryCode = $responseGismeteoIP['response']['country']['code'];
    }

    $gismeteoWeather = gisMeteoGetWeather($cityID);
    $result['city'] = $cityName;
    $result['temp'] = $gismeteoWeather['response']['temperature']['air']['C'];
    $result['cityID'] = $cityID;
    return $result;
}

//получение температуры по координатам от Яндекс.Погоды
function getTemperatureByCoordinats($lat, $lon)
{
    $responseObj = sendCurlWeatherYandex($lat, $lon);
    $temp = $responseObj->fact->temp;
    return $temp;
}

//function checkCityInJsonFileOLD($cityName)
//{
//    $file = file_get_contents('data/cityWeather.json');  // Открыть файл data.json
//    $citiesArray = json_decode($file, true);        // Декодировать в массив
//    if (array_key_exists($cityName, $citiesArray)) {
//        pre(strtotime($citiesArray[$cityName]['date']));
//    } else {
//        pre($cityName);
//        $coordArray = getCoordinatsAsArray($cityName);
//        if ($coordArray) {
//            $temp = getTemperatureByCoordinats($coordArray);
//            if ($temp != null) {
//                $citiesArray[$cityName]['temp'] = $temp;
//            }
//            $citiesArray[$cityName]['date'] = date("d.m.Y H:i:s");
//            file_put_contents('data/cityWeather.json', json_encode($citiesArray));  // Перекодировать в формат и записать в файл.
//        }
//    }
//}

//function getCityNameAndLocationsFromGeoData($geoData)
//{
//
//    if ($geoData and isset($geoData->ip->city)) {
//        $city = (array)$geoData->ip->city;
//        $region = (array)$geoData->ip->region;
//        if (isset($city[0])) {
//            $city = $city[0];
//        }
//        if (isset($region[0])) {
//            $region = $region[0];
//        }
//    }
//    return $geoData;
//}

//транслирование строки из русского в английский
//function translitStringRef(&$cityName)
//{
//    $arParams = array("replace_space" => "_", "replace_other" => "-");
//    $cityName = Cutil::translit($cityName, "ru", $arParams);
//}

function translitString($cityName)
{
    $arParams = array("replace_space" => "_", "replace_other" => "-");
    $cityName = Cutil::translit($cityName, "ru", $arParams);
    return $cityName;
}

//главная функция: считывание файла с городами, проверка, есть ли текущий город (по IP) в нем
//если нет, то получение температуры для него и запись обратно
//function checkCityInJsonFile()
//{
//    //$file = file_get_contents('data/cityWeather.json');  // Открыть файл data.json
//    $content = readContentFromFileByBitrix(CITIES_JSON_FILE);
//    $content = false;
//    if ($content) {
//        $citiesArray = json_decode($content, true); // Декодировать в массив
//    } else {
//        $citiesArray = array();
//    }
//
//    writeNewContentToFileByBitrix('/local/php_interface/progress/logs/logsCity.log', json_last_error());
//
//    if ($citiesArray) {
//
//        $geoData = getGeoDataByIP();
//        if ($geoData) {
//            $cityName = $geoData['city'];
//            if (array_key_exists($cityName, $citiesArray)) {
//                //pre($citiesArray);
//            } else {
//                $coordArray = array('lat' => $geoData['lat'], 'lon' => $geoData['lon']);
//                if ($coordArray) {
////                $temp = getTemperatureByCoordinats($geoData['lat'], $geoData['lon']);
////                if ($temp != null) {
////                    $citiesArray[$cityName]['temp'] = $temp;
////                }
//                    $citiesArray[$cityName]['date'] = date("d.m.Y H:i:s");
//                    //file_put_contents('data/cityWeather.json', json_encode($citiesArray));  // Перекодировать в формат и записать в файл.
//                    //writeNewContentToFileByBitrix(CITIES_JSON_FILE, json_encode($citiesArray));
//                }
//            }
//        }
//    }
//}

function checkCitiesInIBlock($ibID)
{
    //получаем геоданные
    $geoData = getGeoDataByIP();

    //если с геоданными все норм
    if ($geoData) {
        //город
        $cityName = $geoData['city'];

        //получаем все города из инфоблока
        $elements = getListOfElementsWithPropertiesAsArray($ibID);

        $isExistCity = false;
        //проверяем, есть ли уже такой город
        foreach ($elements as $elementId => $fields) {
            if (in_array($cityName, $fields)) {
                $isExistCity = true;
                break;
            }
        }

        if ($_SERVER["REMOTE_ADDR"] != '141.0.182.58') {
            if ($isExistCity) {
                //если город уже есть в инфоблоке
                $secondsInDay = 86400;
                //количество дней на хранение
                $countDays = 0.3;
                //берем дату, которая указана в записи
                $cityWrotedTime = $fields['PROPS']['DATE']['VALUE'];

                $needUpdate = false;

                //смотрим разницу текущего времени и записанной даты
                if (time() - strtotime($cityWrotedTime) > $secondsInDay * $countDays || $fields['PROPS']['TEMP']['VALUE'] == '') {
                    //если она больше указанного количества дней
                    //получаем температуру от API
                    //$temp = getTemperatureByCoordinats($geoData['lat'], $geoData['lon']);

                    $res = gisMeteoGetTemperatureOnly($fields);
                    $temp = round($res['temp']);
                    $gisMeteoID = $res['cityID'];
                    $date = date("d.m.Y H:i:s");
                    $needUpdate = true;

                    //ставим новую температуру и новую дату
                    //setPropertyValue($ibID, $elementId, 'TEMP', $temp);
                    //setPropertyValue($ibID, $elementId, 'DATE', date("d.m.Y H:i:s"));
                }

                //получаем список всех IP пользователей
                $ips = $fields['PROPS']['USER_IP']['VALUE'];
                //если такого в списке нет, то добавляем
                if (!in_array($geoData['query'], $ips)) {
                    $ips[] = $geoData['query'];
                    $needUpdate = true;
                }
                //если время обновлять температуру не пришло, ставим ту, которая есть
                if (!$temp) {
                    $temp = $fields['PROPS']['TEMP']['VALUE'];
                    $date = $fields['PROPS']['DATE']['VALUE'];
                    $gisMeteoID = $fields['PROPS']['GISMETEO_ID']['VALUE'];
                }
                //другой вариант: обновить запись, но тогда надо все свойства обязательно указывать
                if ($needUpdate) {
                    $props = array('CITY' => translitString($cityName), 'DATE' => $date,
                        'TEMP' => $temp, 'USER_IP' => $ips, 'GISMETEO_ID' => $gisMeteoID);
                    updateElementInIblockWithProperties($elementId, $props);
                }
            } else {
                //если города в списке нет
                //получаем температуру от API
                //$temp = getTemperatureByCoordinats($geoData['lat'], $geoData['lon']);
                $res = gisMeteoGetTemperatureOnly();
                $cityName = $res['cityName'];
                $temp = round($res['temp']);
                $gisMeteoID = $res['cityID'];

                //устанавливаем необходимые свойства
                $props = array('CITY' => translitString($cityName), 'DATE' => date("d.m.Y H:i:s"),
                    'TEMP' => $temp, 'USER_IP' => $geoData['query'], 'GISMETEO_ID' => $gisMeteoID);
                //добавляем город в инфоблок
                addElementInIblockWithProperties($ibID, $cityName, $props);
            }
        }
    }
}