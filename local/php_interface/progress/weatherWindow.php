<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
//if ($_SERVER['REMOTE_ADDR'] == '141.0.182.58' || $_SERVER['REMOTE_ADDR'] == '88.200.215.228') {
//if (CModule::IncludeModule("fileman") && !CLightHTMLEditor::IsMobileDevice()) {
    function check_mobile_device() {
        $mobile_agent_array = array('ipad', 'iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        foreach ($mobile_agent_array as $value) {
            if (strpos($agent, $value) !== false) return true;
        }
        return false;
    }

	//if (!check_mobile_device() && (CModule::IncludeModule("fileman") && !CLightHTMLEditor::IsMobileDevice())) {
        session_start();

        $firstTimeShow = 0;

        if (empty($_SESSION['userCity'])) {
            $cityName = getActualWeather();
            $_SESSION['userCity'] = $cityName;
            $timeNow = getCurrentDateTime();
            $_SESSION['sessionStart'] = $timeNow;
            $_SESSION['hidden'] = 0;
            $firstTimeShow = 1;
        } else {
            $cityName = $_SESSION['userCity'];
        }


        if ($cityName && !empty($cityName)) {
            preg_match('/\/([a-z]*)\/[^\s]*/', $APPLICATION->GetCurPage(), $papka);
            if ($papka[1] != 'bitrix' && $papka[1] == 'test') {
                $APPLICATION->IncludeComponent(
                    "me:weatherWindow",
                    "",
                    Array(
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "CITY_NAME" => $cityName,
                        "SESS_TIME" => timeToTimestamp($_SESSION['sessionStart']),
                        "FIRST_TIME_SHOW" => $firstTimeShow,
                        "HIDDEN" => $_SESSION['hidden'],
                        "TIME_TO_SHOW" => 5,
                    )
                );
            }
        }
		//}
//}
//}