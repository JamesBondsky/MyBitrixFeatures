<?

if (!function_exists('pre')) {
    function pre($var, $die = false)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
        if ($die)
            die('Debug in PRE');
    }
}

define("USER_ID", 1);
define("TOKEN", "qejepblc1luf56wx");
define("METHOD_API", "imbot.message.add");

//if (isset($_POST)) {
    $data = $_POST['myData'];
    //$res['result1'] = $_POST['test'];
    //echo json_encode($res);

    $pars = array(
        "CLIENT_ID" => 1,
        'auth' => 'local.5f06f520b9e6d9.53820020',
        'DIALOG_ID' => 1,
        'MESSAGE'   => 'Привет! Я Докладун, докладываю все как есть.',
        "ATTACH"    => array(
            array('MESSAGE' => '[send=что горит]Что горит?[/send]'),
        ),
    );

//$pars = array(
//    "TASKID" => 3654,
//    "ITEMID" => 2602
//);

    $queryUrl = "https://volchok.bitrix24.ru/rest/" . USER_ID . "/" . TOKEN . "/" . METHOD_API;
    $queryData = http_build_query($pars);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));

    $result = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($result, 1);

    //pre($result);

    if (array_key_exists('error', $result))
        $resStr = $result['error_description'];
    else
        $resStr = "Время добавлено";

    $res['result'] = $resStr;
    echo json_encode($res);
//}