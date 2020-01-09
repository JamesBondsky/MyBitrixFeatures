<? //require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

//надо, чтобы достать UTM метки, сохраненные в SESSION
session_start();

//массив UTM-меток
define('UTM_ARRAY', array('utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'yclid'));

//массив соответствий кодов свойств форм к ID полей в CRM
define('FIELDSCODE_TO_CRMID', array(
        'YCLID' => 'UF_CRM_1571925771',
        'PRODUCT' => 'UF_CRM_1571987287',
        'NEED_PRODUCT' => 'UF_CRM_1571987287',
    )
);
//URL для вебхука
define('URL_TO_WEBHOOK', 'https://progress-pk.bitrix24.ru/rest/9/uwwz4cyofuoz1uys/crm.lead.add.json');

if (isset($_POST)) {
    $data = $_POST['myData'];
    //формируем параметры для создания лида в переменной $queryData
    $fields = array();
    $fieldsStr = '';

    $fields['TITLE'] = 'Заявка с формы ' . $_POST['titleForm'];
    $fieldsStr = $fieldsStr.'TITLE: '.$_POST['titleForm'].';';

    foreach (UTM_ARRAY as $utm) {
        if (isset($_SESSION[$utm]) && !empty($_SESSION[$utm])) {
            if (!array_key_exists(mb_strtoupper($utm), FIELDSCODE_TO_CRMID)) {
                $fields[mb_strtoupper($utm)] = strip_tags($_SESSION[$utm]);
                $fieldsStr = $fieldsStr.mb_strtoupper($utm).': '.strip_tags($_SESSION[$utm]).'; ';
            } else {
                $code = FIELDSCODE_TO_CRMID[mb_strtoupper($utm)];
                $fields[$code] = strip_tags($_SESSION[$utm]);
                $fieldsStr = $fieldsStr.$code.': '.strip_tags($_SESSION[$utm]).'; ';
            }
        }
    }

    foreach ($data as $key => $value) {
        if (!array_key_exists($key, FIELDSCODE_TO_CRMID)) {
            $regex = preg_split("/[\s_]+/", $key);
            if (mb_strtolower($regex[0]) == 'phone' || mb_strtolower($regex[0]) == 'email') {
                if (mb_strtolower($regex[0]) == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    //$f = fopen("logs/logsLeads.log", "a");
                    //fprintf($f, "%s\r\n", date("d.m.Y H:i:s") . ': Некорректный email: ' . $value);
                    //fclose($f);
                    $value = '';
                }
                $fields[mb_strtoupper($regex[0])] = Array(
                    "n0" => Array(
                        "VALUE" => $value,
                        "VALUE_TYPE" => mb_strtoupper($regex[1]),
                    ),
                );
            } else {
                $fields[mb_strtoupper($key)] = $value;
            }
            $fieldsStr = $fieldsStr.mb_strtoupper($key).': '.$value.'; ';
        } else {
            $code = FIELDSCODE_TO_CRMID[$key];
            $fields[$code] = $value;
            $fieldsStr = $fieldsStr.$code.': '.$value.'; ';
        }
    }
    //ответственный
    $fields['ASSIGNED_BY_ID'] = '1';
    $fieldsStr = $fieldsStr.'ASSIGNED_BY_ID: 1;';

    $queryData = http_build_query(array(
        'fields' => $fields,
        'params' => array("REGISTER_SONET_EVENT" => "Y")
    ));
    //обращаемся к Битрикс24 при помощи функции curl_exec
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => URL_TO_WEBHOOK,
        CURLOPT_POSTFIELDS => $queryData,
    ));
    $result = curl_exec($curl);

    curl_close($curl);
    $result = json_decode($result, 1);

    if (array_key_exists('error', $result)) {
        $logStr = 'Ошибка при отправке cUrl: '. $result['error_description'].'; Данные: '.$fieldsStr;
    } else {
        $logStr = 'cUrl отправлен. Данные: '.$fieldsStr;
    }
    $res['result'] = $logStr;
    echo json_encode($res);
}