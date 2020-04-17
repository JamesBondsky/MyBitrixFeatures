<?
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
            if(!empty($curOffer))
                $offers[] = ' ['.$curOffer[1].']';
            else
                $offers[] = '';
        }

        $ii = 0;

        while ($arProps = $dbBasketItems->Fetch()) {
            //ПЕРЕМНОЖАЕМ КОЛИЧЕСТВО НА ЦЕНУ
            $priceOne = round($arProps['PRICE'], 2);
            $summ = $arProps['QUANTITY'] * $arProps['PRICE'];
            //СОБИРАЕМ В СТРОКУ ТАБЛИЦЫ
            $strCustomOrderList .=  "<tr><td><a href='https://klampi.ru" . $arProps['DETAIL_PAGE_URL'] . "'>" . $arProps['NAME'] . $offers[$ii] . "</a></td><td>" . $priceOne . " руб." . "</td><td>" . $arProps['QUANTITY'] . "</td><td>" . $summ . " руб." . "</td></tr>";
            $ii++;
        }

        //ОБЪЯВЛЯЕМ ПЕРЕМЕННУЮ ДЛЯ ПИСЬМА
        $arFields["ORDER_TABLE_ITEMS"] = "<table class='colored_table_fix' width='100%' border='1'><tbody>" .
            "<tr><td>Товар</td><td>Цена за шт.</td><td>Кол-во</td><td>Общая цена</td></tr>" . $strCustomOrderList .
            "</tbody></table>";

        $order_props = CSaleOrderPropsValue::GetOrderProps($orderID);
        while ($arProps = $order_props->Fetch()) {
            //контактный телефон
            if ($arProps['ORDER_PROPS_ID'] == 3) {
                $arFields['ME_PHONE'] = $arProps['VALUE'];
            }
            //Адрес
            if ($arProps['ORDER_PROPS_ID'] == 7) {
                $arFields['ME_ADDRESS'] = $arProps['VALUE'];
            }
        }
    }
}