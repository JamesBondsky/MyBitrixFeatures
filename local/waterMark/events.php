<?
//событие происходит после добавления элемента в инфоблок
AddEventHandler("iblock",'OnAfterIBlockElementAdd', 'OnAfterIBlockElementAdd');
function OnAfterIBlockElementAdd(&$arElement)
{
    if ($arElement['IBLOCK_ID'] == 31) {
        $idElement = $arElement['ID'];
        $elementInfo = getElementWithPropertiesByID($idElement);
        $photoID = $elementInfo['PROPS']['REAL_PICTURE']['VALUE'];
        if ($photoID) {
            $imageWithMark = addWaterMarkByImageID($photoID);

            $newFile = CFile::MakeFileArray($imageWithMark['src']);
            $newID = CFile::SaveFile($newFile, 'iblock');
            $newFileArray = CFile::GetFileArray($newID);

            CIBlockElement::SetPropertyValueCode($idElement, "REAL_PICTURE", $newFileArray);
            CFile::Delete($photoID);
        }
    }
}
