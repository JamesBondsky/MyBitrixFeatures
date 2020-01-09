<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

//проверяет, нужно ли обновлять температуру для города
//1. если город есть в cityInfo запишется инфа о нем
//если время вышло, вернет true
//если время не вышло, вернет false
//2. если города нет, вернет true, а cityInfo = false
function isNeedUpdateWeatherForCity($ibID, $cityName, &$cityInfo)
{
    $elements = getListOfElementsWithPropertiesAsArray($ibID);

    $isExistCity = false;
    //проверяем, есть ли уже такой город
    foreach ($elements as $elementId => $fields) {
        if (in_array($cityName, $fields)) {
            $isExistCity = true;
            break;
        }
    }

    if ($isExistCity) {
        $cityInfo = $fields;
        //если город уже есть в инфоблоке
        $secondsInDay = 86400;
        //количество дней на хранение
        $countDays = 0;
        //берем дату, которая указана в записи
        $cityWrotedTime = $fields['PROPS']['DATE']['VALUE'];

        //смотрим разницу текущего времени и записанной даты
        if (time() - strtotime($cityWrotedTime) > $secondsInDay * $countDays || $fields['PROPS']['TEMP']['VALUE'] == '') {
            //если она больше указанного времени, значит надо обновить
            return true;
        } else {
            return false;
        }
    } else {
        $cityInfo = false;
        return true;
    }
}

function translitString($cityName)
{
    $arParams = array("replace_space" => "_", "replace_other" => "-");
    $cityName = Cutil::translit($cityName, "ru", $arParams);
    return $cityName;
}