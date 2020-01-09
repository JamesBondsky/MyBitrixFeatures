<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Sale\Location;
use \Bitrix\Main\Service\GeoIp;

CModule::IncludeModule("sale");

$ip = GeoIp\Manager::getRealIp();

$cityName = GeoIp\Manager::getCityName($ip, 'ru');
$idCity = Location\GeoIp::getLocationId($ip, 'ru');
?>
<div class="active">
    <label><?= $cityName . ' (id: ' . $idCity . ')'; ?></label>
</div>
