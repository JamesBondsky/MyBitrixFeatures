<?php
//AddEventHandler('iblock', 'OnAfterIBlockElementAdd', 'CopyDataFromOneElementHandler');
//AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'CopyDataFromOneElementHandler');

function CopyDataFromOneElementHandler(&$arFields)
{
    $offersIblockId = get_iblock_id('offers');
    Log::add('ex72794', '------------------------------');
    //данное условие нужно для того, чтобы сохранение соседних ТП не вызывало это же событие,
    //иначе будет бесконечный цикл
    if (request()->get('ID') == $arFields['ID']) {
        if ($arFields['IBLOCK_ID'] == $offersIblockId) {

            $propertiesArrayToCopy = [
                'ONLINE_EXCLUSIVE',
                'OFFLINE_EXCLUSIVE',
                'DONT_USE_LAST_SIZE',
                'SOLD_OUT',
                'COMING_SOON',
                'COMING_SOON_SHOW_IN_CATALOG',
                'PREVIEW_START_DATE',
                'SALE_START_DATE_VIP',
                'SALE_START_DATE',
                'OBRAZ',
                'DETAIL_TEXT_EN',
                'MORE_PHOTO',
                'MORE_PHOTO_MEN',
                'MORE_PHOTO_WOMEN',
                'MORE_PHOTO_UNISEX',
                'SHOW_IN_CATALOG',
                'SHOW_HORIZONTAL',
                'HORIZONTAL_IMG',
                'HORIZONTAL_SECTIONS',
                'FEED_PHOTO',
                'MORE_PHOTO_GIFTS',
                'SHOW_VIDEO',
                'VIDEO',
                'PREVIEW_VIDEO',
                'VIDEO_WOMEN',
                'VIDEO_MEN',
                'VIDEO_UNISEX'
            ];

            $fieldsArrayToCopy = [
                'DETAIL_TEXT'
            ];

            /** @var Offer $offer */
            $offer = (new Offer())
                ->withFilter(['ID' => $arFields['ID']])
                ->withSelect(
                    array_merge(
                        ['ID', 'PROPERTY_CML2_LINK', 'PROPERTY_TSVET'],
                        $fieldsArrayToCopy,
                        preg_filter('/^/', 'PROPERTY_', $propertiesArrayToCopy)
                    )
                )
                ->getOne();

            $propertiesRes = \CIBlockProperty::GetList([], ['ACTIVE' => 'Y', 'IBLOCK_ID' => $offersIblockId]);

            $propertiesToCopy = [];

            while ($prop = $propertiesRes->GetNext()) {
                $code = $prop['CODE'];
                if (in_array($code, $propertiesArrayToCopy)) {
                    switch ($prop['PROPERTY_TYPE']) {
                        case 'L':
                            $propertiesToCopy[$code] = ['VALUE' => $offer->getPropertyEnumId($code)];
                            break;
                        case 'E':
                        case 'G':
                        case 'S':
                            if(yes($prop['MULTIPLE'])) {
                                $values = $offer->getPropertyValue($code);
                                foreach ($values as $value) {
                                    $propertiesToCopy[$code][] = ['VALUE' => $value];
                                }
                            } else {
                                if ($prop['USER_TYPE'] == 'HTML') {
                                    $propertiesToCopy[$code] = ['VALUE' => $offer->getPropertyValueHTML($code)];
                                } else {
                                    $propertiesToCopy[$code] = ['VALUE' => $offer->getPropertyValue($code)];
                                }
                            }
                            break;
                        case 'F':
                            if(yes($prop['MULTIPLE'])) {
                                $values = $offer->getPropertyValue($code);
                                foreach ($values as $value) {
                                    $propertiesToCopy[$code][] = ['VALUE' => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . CFile::GetPath($value))];
                                }
                            } else {
                                $propertiesToCopy[$code] = ['VALUE' => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . CFile::GetPath($offer->getPropertyValue($code)))];
                            }
                            break;
                    }
                }
            }

            $fieldsToCopy = [];
            foreach ($fieldsArrayToCopy as $fieldArrayToCopy) {
                $fieldsToCopy[$fieldArrayToCopy] = $offer->getField($fieldArrayToCopy);
            }

            if (count($propertiesToCopy) + count($fieldsToCopy)) {
                $productOffers = $offer->getOffersOfThisProductWithThisColor();
                $el = new \CIBlockElement;

                /** @var Offer $productOffer */
                foreach ($productOffers as $productOffer) {
//                    break;
                    if ($productOffer->getId() == $offer->getId())
                        continue;
                    if (count($fieldsToCopy)) {
                        //$res = $el->Update($productOffer->getId(), $fieldsToCopy);
                    }

                    if (count($propertiesToCopy))
                        \CIBlockElement::SetPropertyValuesEx($productOffer->getId(), $offersIblockId, $propertiesToCopy);
//                    break;
                }
            }
        }
    }
}

class Offer extends Entity
{
    protected $iBlockCode = 'offers';

    private static $parents = [];

    public static function getParentId($id)
    {
        if (array_key_exists($id, self::$parents)) {
            return self::$parents[$id];
        }

        $offer = (new self)
            ->withFilter(['ID' => $id])
            ->withSelect([
                'ID',
                'PROPERTY_CML2_LINK',
            ])
            ->getOne();

        $productId = ($offer instanceof Offer) ? $offer->getPropertyValue('CML2_LINK') : 0;

        self::$parents[$id] = $productId;

        return $productId;
    }

    public static function getDetailPageUrl($id)
    {
        $parentId = self::getParentId($id);

        return Product::getDetailPageUrl($parentId);
    }

    public static function getProductNames($ids)
    {
        $offers = (new static)
            ->withFilter(['ID' => $ids])
            ->withSelect([
                'ID',
                'NAME',
                'PROPERTY_CML2_LINK.NAME',
            ])
            ->getMany();

        $result = [];
        /** @var static $offer */
        foreach ($offers as $offer) {
            $result[$offer->getField('ID')] = ($offer->getField('PROPERTY_CML2_LINK_NAME')) ?
                $offer->getField('PROPERTY_CML2_LINK_NAME') :
                $offer->getField('NAME');
        }

        return $result;
    }

    public function getPrices($id)
    {
        $prices = PriceTable::getList([
            'filter' => ['=PRODUCT_ID' => $id],
            'select' => [
                'PRICE',
                'CODE' => 'CATALOG_GROUP.NAME',
            ]
        ])->fetchAll();

        return $prices ? array_combine(array_column($prices, 'CODE'), array_column($prices, 'PRICE')) : [];
    }

    public function getDiscount($id)
    {
        $prices = $this->getPrices($id);

        $base = array_get($prices, 'BASE');
        $retail = array_get($prices, 'RETAIL');

        return !$base || !$retail || $retail >= $base ? 0 : $base - $retail;
    }

    public function byProduct($productId)
    {
        return $this->expandFilter(['PROPERTY_CML2_LINK' => $productId]);
    }

    public function byOrder(Order $order)
    {
        /** @noinspection PhpParamsInspection */
        return $this->byBasket($order->getBasket());
    }

    public function byBasket(Basket $basket)
    {
        $result = [];
        /** @var BasketItem $basketItem */
        foreach ($basket as $basketItem) {
            $result[] = $basketItem->getProductId();
        }

        return $this->expandFilter(['ID' => $result]);
    }

    public function color()
    {
        $product = (new Product())
            ->withFilter(['ID' => $this->parent()])
            ->withSelect(['PROPERTY_COLOR'])
            ->getOne();

        return $product->color();
    }

    public function parent()
    {
        return $this->getPropertyValue('CML2_LINK');
    }

    public function size()
    {
        return $this->getPropertyValue('SIZE');
    }

    public function getBasePrice()
    {
//        return $this->getField('CATALOG_PRICE_' . get_price_type_id('BASE')) ?:
//            $this->getField('CATALOG_PRICE_' . get_price_type_id('RETAIL'));
    }

    public function getOffersOfThisProductWithThisColor($propertyCode = 'TSVET') {
        return (new self)
            ->withSelect(['ID'])
            ->withFilter(['PROPERTY_CML2_LINK' => self::parent(),
                'PROPERTY_' . $propertyCode => $this->getPropertyEnumId($propertyCode)])
            ->getMany();
    }

    public function copyDataToOffersOfThisProductWithThisColor($propertiesArrayToCopy, $fieldsArrayToCopy)
    {
        $funcResult = true;

        $propertiesRes = \CIBlockProperty::GetList([], ['ACTIVE' => 'Y', 'IBLOCK_ID' => self::getIBlockId()]);

        $propertiesToCopy = [];

        while ($prop = $propertiesRes->GetNext()) {
            $code = $prop['CODE'];
            if (in_array($code, $propertiesArrayToCopy)) {
                switch ($prop['PROPERTY_TYPE']) {
                    case 'L':
                        $propertiesToCopy[$code] = ['VALUE' => self::getPropertyEnumId($code)];
                        break;
                    case 'E':
                    case 'G':
                    case 'S':
                        if (yes($prop['MULTIPLE'])) {
                            $values = self::getPropertyValue($code);
                            foreach ($values as $value) {
                                $propertiesToCopy[$code][] = ['VALUE' => $value];
                            }
                        } else {
                            if ($prop['USER_TYPE'] == 'HTML') {
                                $propertiesToCopy[$code] = ['VALUE' => self::getPropertyValueHTML($code)];
                            } else {
                                $propertiesToCopy[$code] = ['VALUE' => self::getPropertyValue($code)];
                            }
                        }
                        break;
                    case 'F':
                        if (yes($prop['MULTIPLE'])) {
                            $values = self::getPropertyValue($code);
                            foreach ($values as $value) {
                                $propertiesToCopy[$code][] = ['VALUE' => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . CFile::GetPath($value))];
                            }
                        } else {
                            $propertiesToCopy[$code] = ['VALUE' => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . CFile::GetPath(self::getPropertyValue($code)))];
                        }
                        break;
                }
            }
        }

        $fieldsToCopy = [];
        foreach ($fieldsArrayToCopy as $fieldArrayToCopy) {
            $fieldsToCopy[$fieldArrayToCopy] = self::getField($fieldArrayToCopy);
        }

        if (count($propertiesToCopy) + count($fieldsToCopy)) {
            $productOffers = self::getOffersOfThisProductWithThisColor();
            $el = new \CIBlockElement;

            /** @var Offer $productOffer */
            foreach ($productOffers as $productOffer) {
//                    break;
                if ($productOffer->getId() == self::getId())
                    continue;
                if (count($fieldsToCopy)) {
                    $res = $el->Update($productOffer->getId(), $fieldsToCopy);
                    if (!$res)
                        $funcResult = false;
                }

                if (count($propertiesToCopy))
                    \CIBlockElement::SetPropertyValuesEx($productOffer->getId(), $productOffer->getIBlockId(), $propertiesToCopy);
                break;
            }
        }
        return $funcResult;
    }
}

//local/php_interface/admin_header.php
?>
<script>
    //функция копирования данных ТП в ТП этого же цвета других размеров
    function copyDataToOffersOfThisProductWithThisColor(element_id) {
        if(confirm('Скопировать данные в ТП данного товара других размеров этого цвета?')) {
            console.log('test');
            console.log(element_id);
            $.ajax({
                type: 'GET',
                url: '/ajax/iblock/copy_data_to_offers_with_this_color/',
                async: false,
                dataType: 'json',
                data: {
                element_id: element_id
                },
                success: function (response) {
                if (response.is_copied) {
                    console.log('copied');
                    alert('Данные скопированы.');
                } else {
                    console.log('failed');
                    alert('При копирование произошла ошибка.');
                }
            }
            });
        }
    }
</script>

<?
AddEventHandler('main', 'OnAdminContextMenuShow', 'IBlockOfferAddCopyButtonHandler');
function IBlockOfferAddCopyButtonHandler(&$items){
    if ($_SERVER['REQUEST_METHOD']=='GET' &&
        $GLOBALS['APPLICATION']->GetCurPage()=='/bitrix/admin/iblock_element_edit.php' &&
        request()->get('IBLOCK_ID') == get_iblock_id('offers') &&
        request()->get('ID') > 0)
    {
        $items[] = array(
            "TEXT"=>"Скопировать данные в ТП других размеров",
            "LINK"=>"javascript:copyDataToOffersOfThisProductWithThisColor(".$_REQUEST['ID'].")",
            "TITLE"=>"Скопировать данные в ТП других размеров",
            "ICON"=>"adm-btn",
        );
    }
}

event_manager()->addEventHandler('extensions', Ajax\Distributor::EXPAND_HANDLERS_EVENT, function () {
    return new EventResult(EventResult::SUCCESS, [
        'iblock' => Ajax\IBlock::class,
    ]);
});


namespace Itgro\Ajax;

use Bitrix\Main\Result;
use Itgro\Entity\Iblock\Offer;
use Itgro\Entity\User\Address as UserAddress;
use Itgro\Ajax\Result\Error;
use Itgro\Ajax\Result\Success;
use Itgro\Log;

class IBlock
{
    function copy_data_to_offers_with_this_color()
    {
        $productId = request()->get('element_id');
        Log::add('ex72794', $productId);

        $propertiesArrayToCopy = [
            'ONLINE_EXCLUSIVE',
            'OFFLINE_EXCLUSIVE',
            'DONT_USE_LAST_SIZE',
            'SOLD_OUT',
            'COMING_SOON',
            'COMING_SOON_SHOW_IN_CATALOG',
            'PREVIEW_START_DATE',
            'SALE_START_DATE_VIP',
            'SALE_START_DATE',
            'OBRAZ',
            'DETAIL_TEXT_EN',
            'MORE_PHOTO',
            'MORE_PHOTO_MEN',
            'MORE_PHOTO_WOMEN',
            'MORE_PHOTO_UNISEX',
            'SHOW_IN_CATALOG',
            'SHOW_HORIZONTAL',
            'HORIZONTAL_IMG',
            'HORIZONTAL_SECTIONS',
            'FEED_PHOTO',
            'MORE_PHOTO_GIFTS',
            'SHOW_VIDEO',
            'VIDEO',
            'PREVIEW_VIDEO',
            'VIDEO_WOMEN',
            'VIDEO_MEN',
            'VIDEO_UNISEX'
        ];

        $fieldsArrayToCopy = [
            'DETAIL_TEXT'
        ];

        /** @var Offer $offer */
        $offer = (new Offer())
            ->withFilter(['ID' => $productId])
            ->withSelect(
                array_merge(
                    ['ID', 'PROPERTY_CML2_LINK', 'PROPERTY_TSVET'],
                    $fieldsArrayToCopy,
                    preg_filter('/^/', 'PROPERTY_', $propertiesArrayToCopy)
                )
            )
            ->getOne();

        $funcResult = $offer->copyDataToOffersOfThisProductWithThisColor($propertiesArrayToCopy, $fieldsArrayToCopy);

        $result = ['is_copied' => $funcResult];

        echo json_encode($result);
        die();
    }
}