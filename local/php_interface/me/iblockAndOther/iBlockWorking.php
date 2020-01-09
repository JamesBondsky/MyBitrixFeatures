<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
//require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

//в другом файле должно быть:
//require($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/iblockAndOther/iBlockWorking.php");

//добавление значения свойства типа "Список" по коду свойства и ID инфоблока
function addValueInMultipleProperty($ibID, $propCode, $value, $valueXML_ID = false)
{
    if (CModule::IncludeModule("iblock")) {
        //проверяет, есть ли в указанном инфоблоке указанное свойство
        $property = CIBlockProperty::GetByID($propCode, $ibID)->Fetch();
        if (!$property) {
            //pre('В инфоблоке с ID = ' . $ibID . ' нет свойства ' . $propCode);
            return false;
        }
        //проверяет, есть ли уже для указанного свойства указанное значение
        $ar_enum_list = CIBlockProperty::GetPropertyEnum($propCode, array("SORT" => "asc"), array("IBLOCK_ID" => $ibID, 'VALUE' => $value))->Fetch();
        if (!$ar_enum_list) {
            //если код передан, то с кодом, иначе случайный
            if ($valueXML_ID) {
                $PropID = CIBlockPropertyEnum::Add(array('PROPERTY_ID' => $property['ID'], 'VALUE' => $value, 'XML_ID' => $valueXML_ID));
            } else {
                $PropID = CIBlockPropertyEnum::Add(array('PROPERTY_ID' => $property['ID'], 'VALUE' => $value));
            }
            if ($PropID) {
                $ar_enum_list['ID'] = $PropID;
                //pre('Значение ' . $value . ' добавлено в свойство ' . $propCode . ' у инфоблока с ID = ' . $ibID);
            } else {
                //pre('Ошибка при добавлении');
                return false;
            }
        } else {
            //pre('Значение ' . $value . ' уже есть в свойстве ' . $propCode . ' у инфоблока с ID = ' . $ibID);
        }
        return $ar_enum_list['ID'];
    }
}

//удаление значеня из свойства типа "Список" по ID значения списка
function deleteValueFromListTypePropertyByValueID($idOfValue)
{
    if (CModule::IncludeModule("iblock")) {
        CIBlockPropertyEnum::Delete($idOfValue);
    }
}

//возвращает варианты для значения свойства propName типа "Cписок"
function getIdOfPropertyIblock($ibID, $propName)
{
    if (CModule::IncludeModule("iblock")) {
        $ar_enum_list = CIBlockProperty::GetPropertyEnum($propName, array("SORT" => "asc"), array("IBLOCK_ID" => $ibID))->Fetch();
        return $ar_enum_list['ID'];
    } else {
        return false;
    }
}

//установить значение свойства по его коду
//для множественного свойства можно передавать массив вида array('qwe', 'asd')
//если $deleteOtherProperties = true, все значения свойств, кроме указанного, будут стерты
function setPropertyValue($iblockID, $elementID, $propertyCode, $value, $deleteOtherProperties = false)
{
    if (CModule::IncludeModule("iblock")) {
        $arrFields = Array(
            $propertyCode => $value
        );

        if ($deleteOtherProperties) {
            CIBlockElement::SetPropertyValues(
                $elementID,
                $iblockID,
                $arrFields
            );
        } else {
            CIBlockElement::SetPropertyValuesEx(
                $elementID,
                $iblockID,
                $arrFields
            );
        }
    }
}

//получение массива элементов инфоблока с их свойствами
function getListOfElementsWithPropertiesAsArray($ibID, $propsList = false, $propsNeedFields = array('ID', 'NAME', 'VALUE'))
{
    if (CModule::IncludeModule('iblock')) {
        $arFilter = array('IBLOCK_ID' => $ibID, 'ACTIVE' => 'Y');
        $res = CIBlockElement::GetList(array(), $arFilter, false, array(), array());
    } else {
        return false;
    }
    $resArray = array();
    while ($ar_fields = $res->GetNextElement()) {
        $elementID = $ar_fields->GetFields()['ID'];
        $elementTitle = $ar_fields->GetFields()['NAME'];
        $resArray[$elementID]['TITLE'] = $elementTitle;
        $resArray[$elementID]['ID'] = $elementID;
        //$resArray[$elementTitle]['ID'] = $elementID;
        if (!$propsList) {
            $propertiesOfElement = $ar_fields->GetProperties();
            foreach ($propertiesOfElement as $propName => $propertyFields) {
                foreach ($propsNeedFields as $propNeedNameField) {
                    $resArray[$elementID]['PROPS'][$propName][$propNeedNameField] = $propertyFields[$propNeedNameField];
                }
            }
        }
    }
    return $resArray;
}

//получение элемента(ов) инфоблока с заданным именем
function getElementsWithPropertiesByName($ibID, $name, $propsNeedFields = array('ID', 'NAME', 'VALUE'))
{
    if (CModule::IncludeModule('iblock')) {
        $arFilter = array('IBLOCK_ID' => $ibID, 'ACTIVE' => 'Y', 'NAME' => $name);
        $res = CIBlockElement::GetList(array(), $arFilter, false, array(), array());
    } else {
        return false;
    }
    $resArray = array();
    while ($ar_fields = $res->GetNextElement()) {
        $elementID = $ar_fields->GetFields()['ID'];
        $elementTitle = $ar_fields->GetFields()['NAME'];

        $props = array();
        $propertiesOfElement = $ar_fields->GetProperties();
        foreach ($propertiesOfElement as $propName => $propertyFields) {
            foreach ($propsNeedFields as $propNeedNameField) {
                $props[$propName][$propNeedNameField] = $propertyFields[$propNeedNameField];
            }
        }
        $resArray[] = array('ID' => $elementID, 'TITLE' => $elementTitle, 'PROPS' => $props);
    }
    return $resArray;
}

//получение элемента инфоблока по его ID
function getElementWithPropertiesByID($elementID, $skipEmptyValue = false, $propsNeedFields = array('ID', 'NAME', 'VALUE'))
{
    if (CModule::IncludeModule('iblock')) {
        $arFilter = array('ID' => $elementID);
        $res = CIBlockElement::GetList(array(), $arFilter, false, array(), array());
    } else {
        return false;
    }
    $resElement = false;
    while ($ar_fields = $res->GetNextElement()) {
        $elementID = $ar_fields->GetFields()['ID'];
        $elementTitle = $ar_fields->GetFields()['NAME'];

        $props = array();
        $propertiesOfElement = $ar_fields->GetProperties();
        foreach ($propertiesOfElement as $propName => $propertyFields) {
            if($skipEmptyValue && empty($propertyFields['VALUE']))
                continue;

            foreach ($propsNeedFields as $propNeedNameField) {
                $props[$propName][$propNeedNameField] = $propertyFields[$propNeedNameField];
            }
        }
        $resElement = array('ID' => $elementID, 'TITLE' => $elementTitle, 'PROPS' => $props);
    }
    return $resElement;
}

//получение полей элемента по его ID
function getElementFieldsByID($elementID, $skipEmptyValue = false) {
    if (CModule::IncludeModule('iblock')) {
        $arFilter = array('ID' => $elementID);
        $res = CIBlockElement::GetList(array(), $arFilter, false, array(), array());
    } else {
        return false;
    }
    $resElement = false;
    while ($ar_fields = $res->GetNextElement()) {
        $ar_fields = $ar_fields->GetFields();
        foreach ($ar_fields as $name => $value) {
            if($skipEmptyValue && empty($value) || strpos($name,'~') === 0)
                continue;

            $resElement[$name] = $value;
        }
    }
    return $resElement;
}

//получение значения одного поля по ID элемента
function getElementFieldValueByID($elementID, $fieldName) {
    if (CModule::IncludeModule('iblock')) {
        $arFilter = array('ID' => $elementID);
        $arSelect = Array($fieldName);
        $res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
    } else {
        return false;
    }
    //pre($res->GetNextElement(),1 );
    $resElement = false;
    if ($ar_fields = $res->GetNextElement()) {
        $ar_fields = $ar_fields->GetFields();
        $resElement = $ar_fields[$fieldName];
    }
    return $resElement;
}

//добавление элемента в инфоблок с заданным названием (обяз.) и массивом свойств
//пример $propertyArray: array('PROPERTYCODE' => 'VALUE', ...);
function addElementInIblockWithProperties($ibID, $elementName, $propertyArray)
{
    if (CModule::IncludeModule('iblock')) {
        $el = new CIBlockElement();
        $addedElementID = $el->Add(array(
                "IBLOCK_ID" => $ibID,
                "NAME" => $elementName,
                "PROPERTY_VALUES" => $propertyArray)
        );
        return $addedElementID;
    } else {
        return false;
    }
}

//обновление свойств элемента
//незаданные в массиве свойства будут ЗАТЕРТЫ!
function updateElementInIblockWithProperties($elementID, $propertyArray)
{
    if (CModule::IncludeModule('iblock')) {
        $el = new CIBlockElement();
        $res = $el->Update($elementID, array('PROPERTY_VALUES' => $propertyArray));
        return $res;
    } else {
        return false;
    }
}

//получение значений свойства элемента по коду свойства. вариант без ID инфоблока
//для множественного: array()
function getPropertyValueByElementID($elementID, $propertyCode)
{
    if (CModule::IncludeModule('iblock')) {
        $iterator = CIBlockElement::GetList(array(), array('ID' => $elementID));
        $obj = $iterator->GetNextElement();
        $values = $obj->GetProperties()[$propertyCode]['VALUE'];
        return $values;
    }
}

//удаление элемента по его ID
function deleteElementById($elementId)
{
    if (CModule::IncludeModule('iblock')) {
        CIBlockElement::Delete($elementId);
    }
}

function transferAllElemetsFromIblockToOther($idFrom, $idTo)
{
    $elements = getListOfElementsWithPropertiesAsArray($idFrom);

    foreach ($elements as $id => $fields) {
        $name = $fields['TITLE'];
        $allProps = $fields['PROPS'];
        $propsToAdd = array();
        foreach ($allProps as $key => $val) {
            $propsToAdd[$key] = $val['VALUE'];
        }
        addElementInIblockWithProperties($idTo, $name, $propsToAdd);
    }
}

//получение массива значений свойства элемента по коду свойства. вариант с ID инфоблока
//function getPropertyValuesOfElement($ibID, $elementID, $propertyCode) {
//    if(CModule::IncludeModule('iblock')) {
//        $values = array();
//        $res = CIBlockElement::GetProperty($ibID, $elementID, array(), array('CODE' => $propertyCode));
//        while ($ob = $res->GetNext()) {
//            $values[] = $ob['VALUE'];
//        }
//        return $values;
//    } else {
//        return false;
//    }
//}

function translitXmlForEnumFeature($propertyID)
{
    $res = CIBlockProperty::GetPropertyEnum($propertyID);
    $ar_all_values = Array();
    while ($f = $res->Fetch()) {
        $id = $f['ID'];
        $arParams = array("replace_space" => "_", "replace_other" => "_");
        $ar_all_values[$id] = Array('VALUE' => $f['VALUE'], 'XML_ID' => Cutil::translit($f['VALUE'], "ru", $arParams));
    }

    $CIBlockProp = new CIBlockProperty;
    $CIBlockProp->UpdateEnum($propertyID, $ar_all_values);
}