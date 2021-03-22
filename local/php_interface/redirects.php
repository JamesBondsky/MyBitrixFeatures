<?
$redirect = array(
    "/catalog/himiya-i-inventar-dlya-uborki/bumazhnaya-produktsiya/" => "/catalog/bumazhnaya-produktsiya/",
);

if (isset($redirect[$_SERVER['REQUEST_URI']])) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . /*$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] .*/ $redirect[$_SERVER['REQUEST_URI']]);
    die;
}

//pre($_SERVER);