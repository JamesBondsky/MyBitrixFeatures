<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
//require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

//$this->setFrameMode(true);
//коды свойств, которые надо выводить в скролле
//define('ARRAY_OF_NEED_PROPERTIES', array('PRODUCTIVITY', 'PPU_SPRAY', 'PPU_POURING', 'PM_SPRAY', 'MASS', 'MOTOR',
//    'REDUCTOR', 'POWER_MOTOR', 'POTR_POWER', 'CONTROL_MOTOR', 'RATIO_A_B', 'SMOOTH_START', 'CONTROL_PRODUCTIVITY',
//    'PRESSURE_FEED', 'PRESSURE_AIR', 'COSUMPTION_AIR', 'LENGTH_HOUSES', 'HOSES_AUTOHEATING', 'TANKS_VOLUME',
//    'TANKS_AUTOHEATING', 'SPRAY_GUN_TYPE', 'DIMENSIONS', 'COMPLETENESS', 'WARRANTY'));

//достаем название города из параметров элмента
$cityName = $arParams['CITY_NAME'];
$sessStartTime = $arParams['SESS_TIME'];
$firstTimeShow = $arParams['FIRST_TIME_SHOW'];

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
    $hidden = $arParams['HIDDEN'];
    ?>

    <div class="weatherClose" id="weatherClose" hidden>
        <button class="btn-lg2 btn2" onclick="openWindow()">Развернуть</button>
    </div>

    <?
    if ($temp < 0)
        $temp = str_replace('-', '−', round($temp));
    elseif ($temp > 0)
        $temp = '+' . $temp;
    ?>
    <div id="weatherWindow" class="weatherWindow" hidden>
        <div class="form-body2">
            <div class="weather">
                <label class="cityTitle">Ваш город: <?= $cityName ?></label><br>
                <div class="cityInfo">
                    <label class="temperature">Температура: <?= $temp ?> °C</label>
                </div>
                <div class="gisMeteo">
                    <a href="https://www.gismeteo.ru/" target="_blank"><img
                                title="Данные о погоде предоставлены сервисом Гисметео"
                                src="/upload/gismeteo.jpeg" width="64px"
                                height="13.3"></a>
                </div>
                <label class="detail"><?= $detailText ?></label>
                <!--                <div class="alert alert-info">-->
                <!--                    Чтобы узнать какая установка подойдет именно вам, ответьте на несколько <a-->
                <!--                                href="https://qiuz.progress-pk.ru/" target="_blank">вопросов</a>.-->
                <!--                </div>-->
                <div class="my-order-block">
                    <div class="text">
                        <div style="display: inline-block; width: 25%; text-align: center">
                            <i class="svg inline  svg-inline-order colored"
                               aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="47px" height="55px" viewBox="0 0 47 55"
                                     style="margin-top: 20%">
                                    <defs>
                                        <style>.oscls-1 {
                                                fill: #222;
                                                fill-rule: evenodd
                                            }</style>
                                    </defs>
                                    <path class="oscls-1"
                                          d="M34.5,55A12.5,12.5,0,1,1,47,42.5,12.5,12.5,0,0,1,34.5,55Zm0-23A10.5,10.5,0,1,0,45,42.5,10.5,10.5,0,0,0,34.5,32ZM33.757,46.647c-0.018.021-.024,0.047-0.044,0.066a1.026,1.026,0,0,1-1.427,0c-0.02-.02-0.026-0.046-0.044-0.066l-3.956-3.956a0.993,0.993,0,0,1,1.4-1.4L33,44.6l6.309-6.309a0.993,0.993,0,0,1,1.4,1.4ZM41,28a1,1,0,0,1-1-1V5H32.859A3.991,3.991,0,0,1,29,8H13A3.991,3.991,0,0,1,9.141,5H2V48H19a1,1,0,0,1,0,2H2a2,2,0,0,1-2-2V5A2,2,0,0,1,2,3H9.141A3.991,3.991,0,0,1,13,0H29a3.991,3.991,0,0,1,3.859,3H40a2,2,0,0,1,2,2V27A1,1,0,0,1,41,28ZM29,2H13a2,2,0,0,0,0,4H29A2,2,0,0,0,29,2Zm2,24H11a1,1,0,0,1,0-2H31A1,1,0,0,1,31,26Zm0-10H11a1,1,0,0,1,0-2H31A1,1,0,0,1,31,16Zm0,5H11a1,1,0,0,1,0-2H31A1,1,0,0,1,31,21ZM10,30a1,1,0,0,1,1-1H21a1,1,0,0,1,0,2H11A1,1,0,0,1,10,30Z"></path>
                                </svg>
                            </i>
                        </div>
                        <div style="display: inline-block; width: 75%; float: right">
                            <label>Чтобы узнать какая установка подойдет именно вам, ответьте на несколько <a
                                        href="https://qiuz.progress-pk.ru/?utm_source=progress-pk.ru&utm_medium=referral&utm_campaign=form_weather"
                                        target="_blank"
                                        onclick="ym('9945598', 'reachGoal', 'WEATHER_QUIZ');/*yaCounter9945598.reachGoal('WEATHER_QUIZ');*/">вопросов</a>.
                            </label>
                        </div>
                    </div>
                </div>

            </div>


            <div style="text-align: center">
                <!--                <form action="">-->
                <button id="buttonHide" class="btn-lg2 btn2" onclick="closeWindow();">Свернуть</button>
                <!--                </form>-->
            </div>
        </div>
    </div>

    <script>
        function test() {
            document.getElementById('buttonHide').textContent = 'asd';
        }

        document.onload = onload(<?=$hidden?>, <?=$firstTimeShow?>);
        //$(document).ready(function () {
        //});

        function startTimer(sessStart) {
            let timeNow = Math.floor(Date.now() / 1000);
            if (timeNow - sessStart < <?=$arParams['TIME_TO_SHOW']?>) {
                sleep(1000).then(() => {
                    console.log(timeNow - sessStart);
                    startTimer(sessStart,);
                });
            } else {
                document.getElementById('weatherWindow').hidden = false;
                document.getElementById('weatherClose').hidden = true;
                console.log('send Yandex Counter');
                ym('9945598', 'reachGoal', 'OPEN_WEATHER_WINDOW');
            }
        }

        function onload(hidden, firstTimeShow) {
            if (hidden) {
                document.getElementById('weatherWindow').hidden = true;
                document.getElementById('weatherClose').hidden = false;
            } else {
                if (firstTimeShow) {
                    startTimer(<?=$sessStartTime?>, firstTimeShow);
                } else {
                    document.getElementById('weatherWindow').hidden = false;
                    document.getElementById('weatherClose').hidden = true;
                }
            }
        }
    </script>
<? } ?>