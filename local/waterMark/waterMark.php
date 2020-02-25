<?

define('URL_TO_WATERMARK', '/fotogalereya/logo-emkosti-105-cm-ё---квадрат-ё800.png');
define('SIZE', 'medium'); //real, big, medium, small
define('ALPHA_LEVEL', 50);
define('POSITION', 'bc'); //tl,tc,tr,ml,mc,mr,bl,bc,br

function addWaterMarkByImageID($imgID){
    $arWaterMark = Array(

        array(
            "name" => "watermark",
            "position" => POSITION,
            "type" => "image",
            "size" => SIZE,
            "file" => $_SERVER["DOCUMENT_ROOT"].URL_TO_WATERMARK,
            "fill" => "exact",
            "alpha_level" => ALPHA_LEVEL
        )
    );

    $img = CFile::GetFileArray($imgID);
    list($width, $height, $type, $attr) = getimagesize($img);
    $photo = CFile::ResizeImageGet($img, array('width' => $width, 'height' => $height), BX_RESIZE_PROPORTIONAL, true, $arWaterMark);
    return $photo;
}


function addWaterMarkByImageArray(&$arImg){
    $arWaterMark = Array(
        array(
            "name" => "watermark",
            "position" => POSITION,
            "type" => "image",
            "size" => SIZE,
            "file" => $_SERVER["DOCUMENT_ROOT"].URL_TO_WATERMARK,
            "fill" => "exact",
            "alpha_level" => ALPHA_LEVEL
        )
    );

    $photo = CFile::ResizeImageGet($arImg, array('width' => $arImg['WIDTH'], 'height' => $arImg['HEIGHT']), BX_RESIZE_PROPORTIONAL, true, $arWaterMark);
    $result = $arImg;
    $result['HEIGHT'] = $photo['height'];
    $result['WIDTH'] = $photo['width'];
    $result['FILE_SIZE'] = $photo['size'];
    $result['SRC'] = $photo['src'];
    $result['UNSAFE_SRC'] = $photo['src'];
    $result['SAFE_SRC'] = $photo['src'];

    return $result;
}
