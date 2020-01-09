<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

session_start();

if (isset($_REQUEST['utm_source']) && !empty($_REQUEST['utm_source'])) {
    foreach (UTM_ARRAY as $utm) {
        if (isset($_REQUEST[$utm]) && !empty($_REQUEST[$utm])) {
            $_SESSION[$utm] = strip_tags($_REQUEST[$utm]);
        }
    }
} else {
    if (empty($_SESSION['utm_source'])) {
        $referer = $_SERVER['HTTP_REFERER'];
        preg_match(REGEX_PATTERN_GET_MAINHOST_ONLY, $referer, $found);
        $currentServer = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
        preg_match(REGEX_PATTERN_GET_MAINHOST_ONLY, $currentServer, $found2);

        if (trim($found[1]) != '' && $found[1] != $found2[1] && $found[1] != 'progress-pk') {
            $_SESSION['utm_source'] = $found[1] . '_organic';
            $_SESSION['utm_medium'] = 'organic';
        } else {
            $_SESSION['utm_source'] = 'direct';
            $_SESSION['utm_medium'] = 'direct';
        }
    }
}