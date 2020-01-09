<?php //require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>
    <link href="/local/css/weather.css" type="text/css" rel="stylesheet"/>
<?
//коды свойств, которые надо выводить в скролле
define('ARRAY_OF_NEED_PROPERTIES', array('PRODUCTIVITY', 'PPU_SPRAY', 'PPU_POURING', 'PM_SPRAY', 'MASS', 'MOTOR',
    'REDUCTOR', 'POWER_MOTOR', 'POTR_POWER', 'CONTROL_MOTOR', 'RATIO_A_B', 'SMOOTH_START', 'CONTROL_PRODUCTIVITY',
    'PRESSURE_FEED', 'PRESSURE_AIR', 'COSUMPTION_AIR', 'LENGTH_HOUSES', 'HOSES_AUTOHEATING', 'TANKS_VOLUME',
    'TANKS_AUTOHEATING', 'SPRAY_GUN_TYPE', 'DIMENSIONS', 'COMPLETENESS', 'WARRANTY'));

//достаем название города из параметров элмента
$cityName = $arParams['CITY_NAME'];
//получаем запись в инфоблоке с этим именем (городом)
$res = getElementsWithPropertiesByName(WEATHER_IBLOCK_ID, $cityName);
//и достаем температуру
$temp = $res[0]['PROPS']['TEMP']['VALUE'];

//получаем все записи из инфоблока соответствий температур и установок
$allTemps = getListOfElementsWithPropertiesAsArray(53);

//выбираем запись, где температура попадает в диапазон
foreach ($allTemps as $tempVar) {
    $tempFrom = $tempVar['PROPS']['TEMP_FROM']['VALUE'];
    $tempTo = $tempVar['PROPS']['TEMP_TO']['VALUE'];
    if ($temp >= $tempFrom && $temp <= $tempTo) {
        //ID установки
        $ustID = $tempVar['PROPS']['USTANOVKA']['VALUE'];
        break;
    }
}

if ($ustID) {
    //достаем свойства установки по ID
    $facility = getElementWithPropertiesByID($ustID, true);
    $facilityProps = $facility['PROPS'];
    //достаем поля установки
    $facilityFields = getElementFieldsByID($ustID, true);

    //название установки
    $facilityName = $facilityFields['NAME'];
    //ссылка на детальную страницу
    $facilityLink = $facilityFields['DETAIL_PAGE_URL'];

    //смотрим свернуто ли окошко у пользователя
    $hidden = $_SESSION['hidden'];
    if($hidden == '') {

    }
    ?>

    <div class="weatherClose" id="weatherClose" <?if ($hidden == 0) echo 'hidden'?>>
        <button class="btn-lg2 btn2" onclick="openWindow()">Развернуть</button>
    </div>

    <div id="weatherWindow" class="weatherWindow" <?/* if ($hidden == 1) echo 'hidden' */?>>
        <div class="form-body2">
            <div class="weather">
                <label class="cityTitle">Ваш город: <?= $cityName ?></label>
                <label class="temperature">Температура: <?= str_replace('-', '−', round($temp)) ?> °C</label>
                <a href="https://www.gismeteo.ru/" target="_blank"><img align="right"
                                                                        title="Данные о погоде предоставлены сервисом Гисметео"
                                                                        src="/upload/gismeteo.jpeg" width="64px"
                                                                        height="13.3"></a>
            </div>

            <div class="catalog item-views table catalog_table_2" data-slice="Y">
                <div class="item sliced" style="height: 310px;">
                    <div class="inner-wrap" style="padding: 20px;">
                        <div class="image" style="height: 150px">
                            <a href="<?= $facilityLink ?>">
                                <img class="img-responsive"
                                     src="<?= CFile::GetPath($facilityFields['PREVIEW_PICTURE']) ?>"
                                     alt="<?= $facilityName ?>" title="<?= $facilityName ?>">
                            </a>
                        </div>
                        <div class="text">
                            <div class="cont" style="height: 26px;">
                                <div class="cont_inner" style="height: 180px;">
                                    <div class="title" style="height: 20px;">
                                        <a href="<?= $facilityLink ?>"
                                           class="dark-color"> <?= $facilityName ?> </a></div>

                                    <div class="props_wrapper chars">
                                        <div class="props_inner scrollbar char-wrapp" style="height: 150px">
                                            <table class="props_table">
                                                <tbody>
                                                <?
                                                foreach ($facilityProps as $propName => $prop) {
                                                    if (!is_array($prop['VALUE']) && in_array($propName, ARRAY_OF_NEED_PROPERTIES)) {
                                                        ?>
                                                        <tr class="char">
                                                            <td class="char_name"><span><?= $prop['NAME'] ?></span>
                                                            </td>
                                                            <td class="char_value"><span><?= $prop['VALUE'] ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <?
                                                                }
                                                            }
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row foot" style="margin-top: 10px">
                                            <div class="price">
                                                <div class="price_new">
                                                    <span class="price_val"><?= $facilityProps['PRICE']['VALUE'] ?></span>
                                                </div>
                                                <div class="price_old">
                                                    <span class="price_val"><?= $facilityProps['PRICEOLD']['VALUE'] ?></span>
                                                </div>
                                            </div>
                                            <span class="btn btn-default animate-load" style="margin-top:16px;" data-event="jqm"
                                                  data-param-id="18" data-autoload-product="<?= $facilityName ?>"
                                                  data-name="order_product">Заказать</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

            <div style="text-align: center">
                <button class="btn-lg2 btn2" onclick="closeWindow()">Свернуть</button>
            </div>
        </div>
    </div>

    <script>

        onload(<?=$hidden?>);

        function onload(hidden) {
            if (hidden) {
                document.getElementById('weatherWindow').hidden = true;
            }
        }

        function closeWindow() {
            document.getElementById('weatherWindow').hidden = true;
            document.getElementById('weatherClose').hidden = false;
            sendAjaxSession(1);
        }

        function openWindow() {
            document.getElementById('weatherWindow').hidden = false;
            document.getElementById('weatherClose').hidden = true;
            sendAjaxSession(0);
        }

        function sendAjaxSession(value) {
            $.ajax({
                url: "/local/php_interface/progress/ajax/setSession.php",
                type: 'POST',
                dataType: 'json',
                data: {field: 'hidden', value: value},
            });
        }
    </script>
    <!--<a href="#weather">Открыть модальное окно</a>-->
<? } ?>