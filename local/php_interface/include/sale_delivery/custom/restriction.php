<?php

class MyRestriction extends Bitrix\Sale\Delivery\Restrictions\Base
{
    public static function getClassTitle()
    {
        return 'ограничение на сумму размеров';
    }

    public static function getClassDescription()
    {
        return 'Моё собственное ограничение для службы доставки на сумму размеров';
    }

    protected static function extractParams(\Bitrix\Sale\Shipment $shipment)
    {
        $someShipmentParams = array();

        // Получаем товары в корзине:
        foreach ($shipment->getShipmentItemCollection() as $shipmentItem) {
            $basketItem = $shipmentItem->getBasketItem();
            array_push($someShipmentParams, $basketItem);
        }
        return $someShipmentParams;

//        $dbRes = \Bitrix\Sale\Basket::getList([
//            'select' => ['NAME', 'QUANTITY'],
//            'filter' => [
//                '=FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
//                '=ORDER_ID' => null,
//                '=LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
//                '=CAN_BUY' => 'Y',
//            ]
//        ]);
//        while ($item = $dbRes->fetch())
//        {
//            array_push($someShipmentParams, $item);
//        }
//        return $someShipmentParams;
    }

    public static function getParamsStructure($deliveryId = 0)
    {
        $result =  array(
            'OGRSUMSIZE' => array(
                'LABEL' => 'Суммарное ограничение на размеры',
                'TYPE' => 'NUMBER',
                'NAME' => 'Суммарное ограничение на размеры',
            ),
        );
        return $result;
    }

    public static function check($shipmentParams, array $restrictionParams, $deliveryId = 0)
    {
        $ogrsum = $restrictionParams['OGRSUMSIZE'];
        foreach ($shipmentParams as $item) {
            $id = $item->getProductId();
            $ar_res = CCatalogProduct::GetByID($id);
            $width = $ar_res['WIDTH'];
            $height = $ar_res['HEIGHT'];
            $length = $ar_res['LENGTH'];

            $sumSize = $width + $height + $length;

            if ($sumSize > $ogrsum)
                return false;
        }
        return true;
    }
}