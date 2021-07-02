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

//событие добавляет в шаблон отправки почты "новый заказ" поля адрес, телефон, а также делает
//список товаров в виде удобной таблицы, а не просто списком как по умолчанию
AddEventHandler("sale", "OnOrderNewSendEmail", "ModifyOrderSaleMails");
function ModifyOrderSaleMails($orderID, &$eventName, &$arFields)
{
    if (CModule::IncludeModule("sale") && CModule::IncludeModule("iblock")) {
        //СОСТАВ ЗАКАЗА РАЗБИРАЕМ SALE_ORDER НА ЗАПЧАСТИ
        $dbBasketItems = CSaleBasket::GetList(
            array(),
            array("ORDER_ID" => $orderID),
            false,
            false,
            array("PRODUCT_ID", "ID", "NAME", "QUANTITY", "PRICE", "DETAIL_PAGE_URL")
        );


        $tovars = explode('руб.', $arFields['ORDER_LIST']);
        $offers = array();
        foreach ($tovars as $tovar) {
            preg_match('/.*\[(.*)\].*/', $tovar, $curOffer);
            if (!empty($curOffer))
                $offers[] = ' [' . $curOffer[1] . ']';
            else
                $offers[] = '';
        }

        $ii = 0;

        while ($arProps = $dbBasketItems->Fetch()) {
            //ПЕРЕМНОЖАЕМ КОЛИЧЕСТВО НА ЦЕНУ
            $priceOne = round($arProps['PRICE'], 2);
            $summ = $arProps['QUANTITY'] * $arProps['PRICE'];
            //СОБИРАЕМ В СТРОКУ ТАБЛИЦЫ
            $strCustomOrderList .= "<tr><td><a href='https://stroy-k.ru" . $arProps['DETAIL_PAGE_URL'] . "'>" . $arProps['NAME'] . $offers[$ii] . "</a></td><td>" . $priceOne . " руб." . "</td><td>" . $arProps['QUANTITY'] . "</td><td>" . $summ . " руб." . "</td></tr>";
            $ii++;
        }

        //ОБЪЯВЛЯЕМ ПЕРЕМЕННУЮ ДЛЯ ПИСЬМА
        $arFields["ORDER_TABLE_ITEMS"] = "<table class='colored_table_fix' width='100%' border='1'><tbody>" .
            "<tr><td>Товар</td><td>Цена за шт.</td><td>Кол-во</td><td>Общая цена</td></tr>" . $strCustomOrderList .
            "</tbody></table>";

        $order_props = CSaleOrderPropsValue::GetOrderProps($orderID);

        while ($arProps = $order_props->Fetch()) {
            //контактный телефон
            if ($arProps['CODE'] == 'PHONE') {
                $arFields['ME_PHONE'] = $arProps['VALUE'];
            }
            //Адрес
            if ($arProps['CODE'] == 'ADDRESS') {
                $arFields['ME_ADDRESS'] = $arProps['VALUE'];
            }
        }

        //достаем комметарий к заказу (а на турбостраницах туда заносится адрес)
        $arOrder = CSaleOrder::GetByID($orderID);
//        writeToLog($arFields);
        if (is_array($arOrder)) {
            $arFields['ME_USER_COMMENT'] = $arOrder['USER_DESCRIPTION'];
        }
    }
}

AddEventHandler("iblock", "OnAfterIBlockElementAdd", "OnAfterIBlockElementAddUpdate1C");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "OnAfterIBlockElementAddUpdate1C");

function OnAfterIBlockElementAddUpdate1C($arFields)
{
    $arItem = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID']))->GetNextElement();

    $rsElementsBrands = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 12), false, false, array("ID", "NAME", "PROPERTY_XML_ID_1C"));

    $brands = array();
    while ($brand = $rsElementsBrands->Fetch()) {
        //ulogging1($brand);
        $brands[$brand['PROPERTY_XML_ID_1C_VALUE']] = array('ID' => $brand['ID'], 'NAME' => $brand['NAME']);
    }

    $getFields = $arItem->GetFields();
    $arProp = $arItem->GetProperties();

    foreach ($arProp['CML2_TRAITS']['DESCRIPTION'] as $in => $traitDesc) {
        if ($traitDesc == 'Бренд') {
            $brandIndex = $in;
            break;
        }
    }

    if (isset($brandIndex)) {
        $brandAr = explode(';', $arProp['CML2_TRAITS']['VALUE'][$brandIndex]);

        if ($brandAr) {
            $thisBrand = $brands[$brandAr[1]];

            if ($thisBrand) {
                CIBlockElement::SetPropertyValuesEx($getFields['ID'], 17, array("BRAND" => $thisBrand['ID']));
            } else {
                $params = array(
                    "max_len" => "100",
                    "change_case" => "L",
                    "replace_space" => "_",
                    "replace_other" => "_",
                    "delete_repeat_replace" => "true",
                    "use_google" => "false",
                );

                $addFields = array(
                    "ACTIVE" => "Y",
                    "IBLOCK_ID" => 12,
                    "NAME" => $brandAr[0],
                    "CODE" => CUtil::translit($brandAr[0], "ru", $params),
                    "PROPERTY_VALUES" => array("XML_ID_1C" => $brandAr[1])
                );

                $oElement = new CIBlockElement();
                $idElement = $oElement->Add($addFields);

                CIBlockElement::SetPropertyValuesEx($getFields['ID'], 17, array("BRAND" => $idElement));
            }
        }
    }


    //OZON
    $ozonID = $arProp["CML2_ARTICLE"]["VALUE"];
    $ozonKf = $arProp["OZON_KF_COUNT"]["VALUE"];

    if ($ozonID && $ozonKf) {
        $ar_res = CCatalogProduct::GetByID($getFields['ID']);
        //$quantity = $ar_res['MEASURE'] == 5 ? $ar_res['QUANTITY'] : 0;
        ulogging1($ar_res['QUANTITY']);

        $quantity = floor($ar_res['QUANTITY'] / $ozonKf);
        ulogging1($quantity);

        $clientId = '146687'; //айди шопа
        $apiKey = 'fa07c654-bd2e-40ed-8557-73311540e0e8'; // ключ апи

        $method = '/v1/product/import/stocks'; //метод запроса

        $data = '{"stocks": [{"offer_id": "' . $ozonID . '","stock": ' . $quantity . '}]}';

        $result = post($clientId, $apiKey, $method, $data);
    }
}

//фунция для с работы с API
function post($clientId, $apiKey, $method, $data)
{
    $url = 'http://api-seller.ozon.ru' . $method;
    $headers = array(
        'Content-Type: application/json',
        'Host: api-seller.ozon.ru',
        'Client-Id: ' . $clientId,
        'Api-Key: ' . $apiKey
    );
    $ch = curl_init();
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => $headers
    );
    curl_setopt_array($ch, $options);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

function ulogging1($input, $logname = 'debug', $dt = false)
{
    $endLine = "\r\n"; #PHP_EOL не используется, т.к. иногда это нужно конфигурировать это

    $fp = fopen($_SERVER["DOCUMENT_ROOT"] . '/upload/logs/' . $logname . '.txt', "a+");

    if (is_string($input)) {
        $writeStr = $input;
    } else {
        $writeStr = print_r($input, true);
    }

    if ($dt) {
        fwrite($fp, date('d.m.Y H:i:s') . $endLine);
    }

    fwrite($fp, $writeStr . $endLine);

    fclose($fp);
    return true;
}