<?php //require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>
    <link href="/local/css/weather.css" type="text/css" rel="stylesheet"/>
<?
$this->createFrame()->begin("Загрузка");
//коды свойств, которые надо выводить в скролле
define('ARRAY_OF_NEED_PROPERTIES', array('PRODUCTIVITY', 'PPU_SPRAY', 'PPU_POURING', 'PM_SPRAY', 'MASS', 'MOTOR',
    'REDUCTOR', 'POWER_MOTOR', 'POTR_POWER', 'CONTROL_MOTOR', 'RATIO_A_B', 'SMOOTH_START', 'CONTROL_PRODUCTIVITY',
    'PRESSURE_FEED', 'PRESSURE_AIR', 'COSUMPTION_AIR', 'LENGTH_HOUSES', 'HOSES_AUTOHEATING', 'TANKS_VOLUME',
    'TANKS_AUTOHEATING', 'SPRAY_GUN_TYPE', 'DIMENSIONS', 'COMPLETENESS', 'WARRANTY'));

//достаем название города из параметров элмента
$cityName = $arParams['CITY_NAME'];
$sessStartTime = $arParams['SESS_TIME'];

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
        //$ustID = $tempVar['PROPS']['USTANOVKA']['VALUE'];

        $elementID = $tempVar['ID'];
        break;
    }
}

if ($elementID) {
    //достаем описание для текущей температуры
    $detailText = getElementFieldValueByID($elementID, "DETAIL_TEXT");

    //достаем свойства установки по ID
    //$facility = getElementWithPropertiesByID($ustID, true);
    //$facilityProps = $facility['PROPS'];
    //достаем поля установки
    //$facilityFields = getElementFieldsByID($ustID, true);

    //название установки
    //$facilityName = $facilityFields['NAME'];
    //ссылка на детальную страницу
    //$facilityLink = $facilityFields['DETAIL_PAGE_URL'];

    //смотрим свернуто ли окошко у пользователя
    $hidden = $_SESSION['hidden'];
    if ($hidden == '') {

    }
    ?>

    <div class="weatherClose" id="weatherClose" hidden <? /*if ($hidden == 0) echo 'hidden'*/ ?>>
        <button class="btn-lg2 btn2" onclick="openWindow()">Развернуть</button>
    </div>

    <?
    if ($temp < 0)
        $temp = str_replace('-', '−', round($temp));
    elseif ($temp > 0)
        $temp = '+' . $temp;
    ?>
    <div id="weatherWindow" class="weatherWindow" hidden <? /* if ($hidden == 1) echo 'hidden' */ ?>>
        <div class="form-body2">
            <div class="weather">
                <label class="cityTitle">Ваш город: <?= $cityName ?></label>
                <label class="temperature">Температура: <?= $temp ?> °C</label>
                <a href="https://www.gismeteo.ru/" target="_blank"><img align="right"
                                                                        title="Данные о погоде предоставлены сервисом Гисметео"
                                                                        src="/upload/gismeteo.jpeg" width="64px"
                                                                        height="13.3"></a>
                <label class="detail"><?= $detailText ?></label>
                <label class="detail">Чтобы узнать какая установка подойдет именно вам, ответьте на несколько <a
                            href="https://qiuz.progress-pk.ru/" target="_blank">вопросов</a>.</label>
            </div>


            <div style="text-align: center">
                <button class="btn-lg2 btn2" onclick="closeWindow()">Свернуть</button>
            </div>
        </div>
    </div>

    <script>
        onload(<?=$hidden?>);

        //onloadAsync();

        // async function onloadAsync(sessStart) {
        //     while (true) {
        //         //     sleep(1000).then(() => {
        //         let timeNow = Math.floor(Date.now() / 1000);
        //         //console.log(timeNow);
        //         //console.log(sessStart);
        //         console.log(timeNow - sessStart);
        //         if (timeNow - sessStart > 30)
        //             break;
        //         //     });
        //     }
        // }


        function startTimer(sessStart) {
            //return new Promise(function (resolve, reject) {
            let timeNow = Math.floor(Date.now() / 1000);
            if (timeNow - sessStart < 30) {
                sleep(1000).then(() => {
                    console.log(timeNow - sessStart);
                    startTimer(sessStart);
                });
            } else
                sleep(500).then(() => {
                    document.getElementById('weatherWindow').hidden = false;
                    document.getElementById('weatherClose').hidden = true;
                });

            //});
        }

        function onload(hidden) {
            if (hidden) {
                sleep(500).then(() => {
                    document.getElementById('weatherWindow').hidden = true;
                    document.getElementById('weatherClose').hidden = false;
                });
            } else {
                // sleep(3000).then(() => {
                //     document.getElementById('weatherWindow').hidden = false;
                // });
                //onloadAsync(<?//=$sessStartTime?>//);
                startTimer(<?=$sessStartTime?>);
            }
        }

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
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