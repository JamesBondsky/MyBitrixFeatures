<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Highloadblock\HighloadBlockTable;
CModule::IncludeModule('highloadblock');

//в другом файле должно быть:
//require($_SERVER["DOCUMENT_ROOT"] . "/local/highblocksWorking.php");

//Функция получения экземпляра класса:
function getEntityDataClass($HlBlockId) {
    if (empty($HlBlockId) || $HlBlockId < 1) {
        return false;
    }
    $hlblock = HighloadBlockTable::getById($HlBlockId)->fetch();
    $entity = HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    return $entity_data_class;
}


function addRowInHighloadBlock($hlbID, $name, $code) {
    $entity_data_class = getEntityDataClass($hlbID);
    $result = $entity_data_class::add(array(
        'UF_NAME'         => $name,
        'UF_XML_ID'         => $code,
        //'UF_VALUE'         => '#ffff00',
        //'UF_ACTIVE'   => 'Y'
    ));
}

function printRowsFromHighloadBlock($hlbID)
{
    $entity_data_class = getEntityDataClass($hlbID);
    $rsData = $entity_data_class::getList(array(
        'order' => array('UF_NAME' => 'ASC'),
        'select' => array('*'),
        'filter' => array('!UF_NAME' => false)
    ));
    while ($el = $rsData->fetch()) {
        pre($el);
    }
}

function deleteRowFromHighloadBlock($hlbID, $idForDelete) {
    $entity_data_class = getEntityDataClass($hlbID);
    $entity_data_class::delete($idForDelete);
}

function updateRowInHighloadBlock($hlbID, $idForUpdate, $name, $code) {
    $entity_data_class = getEntityDataClass($hlbID);
    $result = $entity_data_class::update($idForUpdate, array(
        'UF_NAME'         => $name,
        'UF_XML_ID'         => $code,
        //'UF_VALUE'         => '',
        //'UF_ACTIVE'   => '1'
    ));
}
