<?
/*
 * Полезные и простые функции для работы с PHP. Намеренно объявляются в глобальном namespace
 * */
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

if (!function_exists('vd')) {
	function vd($var, $die = false)
	{
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
		if ($die)
			die('Debug in VD');
	}
}

if (!function_exists('writeEvent')) {
	function writeEvent($dump)
	{
		ulogging($dump, 'writeEvent', true);
	}
}

if (!function_exists('ulogging')) {
	/*
	 * ВНИМАНИЕ! Перед использованием создать папку logs в upload и дать права на записать в папку
	 * */
	function ulogging($input, $logname = 'debug', $dt = false)
	{
		$endLine = "\r\n"; #PHP_EOL не используется, т.к. иногда это нужно конфигурировать это

		$fp = fopen($_SERVER["DOCUMENT_ROOT"] . '/upload/logs/' . $logname . '.txt', "a+");

		if (is_string($input)) {
			$writeStr = $input;
		} else {
			$writeStr = print_r($input, true);
		}

		if ($dt) {
			fwrite($fp, date('d.m.Y H:i:s') . $endLine);
		}

		fwrite($fp, $writeStr . $endLine);

		fclose($fp);
		return true;
	}
}
if (!function_exists('deleteParamFromURL')) {
    function deleteParamFromURL($url, $parName)
    {
        preg_match('/(.*)[?]?' . $parName . '=[0-9]*[&]?(.*)/', $url, $res);
        $resUrl = $res[1] . $res[2];
        if (substr($resUrl, strlen($resUrl) - 5, 5) == '&amp;')
            $resUrl = substr($resUrl, 0, strlen($resUrl) - 5);
        return $resUrl;
    }
}

if (!function_exists('getPageParam')) {
    function getPageParam($paramName)
    {
        $val = \Bitrix\Main\Context::getCurrent()->getRequest()->get($paramName);
        return $val;
    }
}