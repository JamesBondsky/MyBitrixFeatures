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

if (!function_exists('BITGetDeclNum')) {
    /**
     * Возврат окончания слова при склонении
     *
     * Функция возвращает окончание слова, в зависимости от примененного к ней числа
     * Например: 5 товаров, 1 товар, 3 товара
     *
     * @param int $value - число, к которому необходимо применить склонение
     * @param array $status - массив возможных окончаний
     * @return mixed
     */
    function BITGetDeclNum($value = 1, $status = array('', 'а', 'ов'))
    {
        $array = array(2, 0, 1, 1, 1, 2);
        return $status[($value % 100 > 4 && $value % 100 < 20) ? 2 : $array[($value % 10 < 5) ? $value % 10 : 5]];
    }
}

if (!function_exists('checkFormFieldForAutocomplete')) {
    /**
     * Проверяет поля форм на аттрибут autocomplete
     *
     * Функция возвращает код для аттрибута autocomplete или false.
     *
     * @param string $fieldName - название поля формы
     * @param string $pattern - в этот параметр запищется маска, которая должна быть указана в аттрибуте pattern у input
     * @return mixed
     */
    function checkFormFieldForAutocomplete($fieldName = '', &$pattern = '') {
        switch ($fieldName) {
            case "CLIENT_NAME":
                $pattern = false;
                return "name";
            case "PHONE":
                $pattern = "\+7 \([0-9]{3}\) [0-9]{3} [0-9]{2}-[0-9]{2}";
                return "phone";
            default:
                return false;
        }
    }
}