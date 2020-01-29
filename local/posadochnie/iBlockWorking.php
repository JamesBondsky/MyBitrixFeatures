<?
//получение массива элементов инфоблока с их свойствами
function getListOfElementsWithPropertiesAsArray($ibID, $filter = array(), $propsList = false, $propsNeedFields = array('ID', 'NAME', 'VALUE'))
{
    if (CModule::IncludeModule('iblock')) {
        $arFilter = array('IBLOCK_ID' => $ibID, 'ACTIVE' => 'Y');
        $arFilter = array_merge($arFilter, $filter);
        $res = CIBlockElement::GetList(array('SORT' => 'ASC'), $arFilter, false, array(), array());
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