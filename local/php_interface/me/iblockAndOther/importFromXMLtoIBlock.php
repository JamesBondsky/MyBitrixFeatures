<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/local/dev/iBlockWorking.php");

#       1 часть
//$ex = new SoapClient('https://api.n11.com/ws/CityService.wsdl');
//$cities = $ex->GetCities();
//pre($cities);

#      2 часть
define('FILE', '../integration/data/data.xml');

$el = new CIBlockElement();

if (file_exists(FILE)) {
    $xml = simplexml_load_file(FILE);
    $i = 0;
    foreach ($xml->product as $product) {
        foreach ($product->SYKNO->VARIANT as $var)
            addValueInMultipleProperty(BLK_ID_INFOBLOCK_PRODUCTS, 'SYKNO', $var['VALUE'], $var);

        foreach ($product->VIKRASKA->VARIANT as $var)
            addValueInMultipleProperty(BLK_ID_INFOBLOCK_PRODUCTS, 'VIKRASKA', $var['VALUE'], $var);


        $mainfields = [
            "NAME" => $product->NAME,
            "CODE" => $product->CODE,
            "IBLOCK_ID" => BLK_ID_INFOBLOCK_PRODUCTS,
            "IBLOCK_SECTION_ID" => $product->SECTION_ID,
            "ACTIVE" => "Y",
            "DETAIL_TEXT" => $product->DESCRIPTION,
            //"PROPERTY_VALUES" => $prodprops,
        ];

        if ($idprod = $el->Add($mainfields)) {
            $mainfields = [
                "ID" => $idprod,
                "QUANTITY" => 0
            ];
            echo "Добавлено с ID = " . $idprod . "\n";
            CCatalogProduct::Add($mainfields);

            $offers = $product->OFFERS;

            foreach ($offers->OFFER as $offer) {
                $offerprops = [
                    "CML2_LINK" => $idprod,
                    "ART" => $offer->ART,
                    "VES" => (int)$offer->VES,
                    "QTY_LEGS" => (int)$offer->QTY_LEGS,
                ];

                $offerfields = [
                    "NAME" => $product->NAME.$offer->SIZE_FIELD.$offer->GAME_TYPE.$offer->TABLE_MATERIAL.
                        $offer->QTY_LEGS.$offer->VES.$offer->ART,
                    "IBLOCK_ID" => BLK_ID_INFOBLOCK_OFFERS,
                    "ACTIVE" => "Y",
                    "PROPERTY_VALUES" => $offerprops,
                ];

                if ($offid = $el->Add($offerfields)) {
                    $offerfields = [
                        "ID" => $offid,
                        "QUANTITY" => 50,
                    ];
                    echo "Оффер добавлен с ID = " . $offid . "\n";
                    CCatalogProduct::Add($offerfields);

                    //CPrice::Update($offid, 100);
                }
            }
        }
        else {
            echo "Не удалось добавить.\n";
        }

        if ($i++ > 5)
            break;
        //break;
    }
} else {
    exit('Не удалось открыть файл.');
}

