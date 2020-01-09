<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\EventManager;

$event_handler = EventManager::getInstance();

#запрет добавления элементов инфоблока со словом КАКТУС
$event_handler->addEventHandler(
    "iblock",
    "OnBeforeIBlockElementAdd",
    array(
        "\Skillbox\Elements",
        "onAdd"
    )
);


$event_handler->addEventHandler(
    'sale',
    'onSaleDeliveryRestrictionsClassNamesBuildList',
    'myDeliveryRestrictions'
);

function myDeliveryRestrictions()
{
    return new \Bitrix\Main\EventResult(
        \Bitrix\Main\EventResult::SUCCESS,
        array(
            'MyRestriction' => '/local/php_interface/include/sale_delivery/custom/restriction.php',
        )
    );
}