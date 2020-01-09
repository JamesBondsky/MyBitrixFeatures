<?

namespace Sale\Handlers\Delivery;

use Bitrix\Sale\Delivery\CalculationResult;
use Bitrix\Sale\Delivery\Services\Base;

//CModule::IncludeModule("sale");

class CustomHandler extends Base
{
//    public static function getClassTitle()
//    {
//        return 'Доставка по весу!!!';
//    }
//
//    public static function getClassDescription()
//    {
//        return 'Доставка, стоимость которой зависит только от веса отправления!!';
//    }
//
//    public function calculateConcrete(\Bitrix\Sale\Shipment $shipment)
//    {
//        $result = new CalculationResult();
//        $price = floatval($this->config["MAIN"]["PRICE"]);
//        pre($this);
//        $weight = floatval($shipment->getWeight()) / 1000;
//
//        $result->setDeliveryPrice(roundEx($price * $weight, 2));
//        $result->setPeriodDescription('1 день');
//
//        return $result;
//    }
//
//    protected function getConfigStructure()
//    {
//        return array(
//            "MAIN" => array(
//                "TITLE" => 'Настройка обработчика',
//                "DESCRIPTION" => 'Настройка обработчика',"ITEMS" => array(
//                    "PRICE" => array(
//                        "TYPE" => "NUMBER",
//                        "MIN" => 0,
//                        "NAME" => 'Стоимость доставки за грамм'
//                    )
//                )
//            )
//        );
//    }
//
//    public function isCalculatePriceImmediately()
//    {
//        return true;
//    }
//
//    public static function whetherAdminExtraServicesShow()
//    {
//        return true;
//    }

    protected static $isCalculatePriceImmediately = true;
    protected static $whetherAdminExtraServicesShow = false;

    public function __construct(array $initParams)
    {
        parent::__construct($initParams);
    }

    public static function getClassTitle()
    {
        return 'Моя кастомная доставка';
    }

    public static function getClassDescription()
    {
        return 'Это моя кастомная доставка!!';
    }

    public function isCalculatePriceImmediately()
    {
        return self::$isCalculatePriceImmediately;
    }

    public static function whetherAdminExtraServicesShow()
    {
        return self::$whetherAdminExtraServicesShow;
    }

    protected function getConfigStructure()
    {
        $result = array(
            'MAIN' => array(
                'TITLE' => 'Основные',
                'DESCRIPTION' => 'Основные настройки',
                'ITEMS' => array(
                    'API_KEY' => array(
                        'TYPE' => 'STRING',
                        'NAME' => 'Ключ API',
                    ),
                )
            )
        );
        return $result;
    }

    function getLocationByCode($locCode)
    {
        $res = \CSaleLocation::GetList(array(), array("CODE" => $locCode, "LID" => LANGUAGE_ID), false, false, array('CITY_NAME'));
        if ($loc = $res->fetch()) {
            //print_r($loc['ID']);
            return $loc['CITY_NAME'];
        } else
            return false;
    }

    protected function calculateConcrete(\Bitrix\Sale\Shipment $shipment = null)
    {
        // Какие-то действия по получению стоимости и срока...

        $order = $shipment->getCollection()->getOrder(); // заказ
        $props = $order->getPropertyCollection();
        $cityName = $this->getLocationByCode($props->getDeliveryLocation()->getValue());
        $priceOrder = $order->getPrice();

        $delivPrice = 0;
        if (!($priceOrder > 5000 && mb_strtolower($cityName) === 'москва')) {
            if (strlen($cityName) % 2 == 0) {
                $delivPrice = 500;
            }
            else
                $delivPrice = 800;
        }

        $result = new \Bitrix\Sale\Delivery\CalculationResult();
        $result->setDeliveryPrice(
            roundEx(
                $delivPrice,
                SALE_VALUE_PRECISION
            )
        );

        //$locationCode = $props->getDeliveryLocation()->getValue(); // местоположение

        //pre($props);
        //$result = new \Bitrix\Sale\Delivery\CalculationResult();
        //$result->setPeriodDescription('4-7 days');
        //$result->addError(new Bitrix\Main\Error("Данный сервис недоступен для выбранного местоположения"));
        return $result;
    }

    public function isCompatible(\Bitrix\Sale\Shipment $shipment)
    {
        $calcResult = self::calculateConcrete($shipment);
        //pre($shipment);
        return $calcResult->isSuccess();
    }

    protected static function extractParams(\Bitrix\Sale\Shipment $shipment)
    {
        $someShipmentParams = array();

        // Получаем товары в корзине:
        foreach ($shipment->getShipmentItemCollection() as $shipmentItem) {
            /** @var \Bitrix\Sale\BasketItem $basketItem - запись в корзине*/
            $basketItem = $shipmentItem->getBasketItem();
            // ...
        }

        // Получаем информацию о заказе:
        /** @var \Bitrix\Sale\ShipmentCollection $collection - коллекция всех отгрузок в заказе */
        $collection = $shipment->getCollection();
        /** @var \Bitrix\Sale\Order $order - объект заказа*/
        $order = $collection->getOrder();

        // Получаем выбранные оплаты:
        /** @var \Bitrix\Sale\Payment $payment - объект оплаты */
        foreach($order->getPaymentCollection() as $payment) {
            /** @var int $paySystemId - ID способа оплаты*/
            $paySystemId = $payment->getPaymentSystemId();
            // ...
            $someShipmentParams["paySystem"] = $paySystemId;
        }
        //...

        return $someShipmentParams;
    }

    public static function check($shipmentParams, $restrictionParams, $deliveryId = 0)
    {
        if (intval($deliveryId) <= 0) {
            return true;
        }
        pre($shipmentParams);

        return $restrictionParams["MY_PARAM_NUMBER"] == $shipmentParams["paySystem"];
    }
}