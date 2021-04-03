<?php

function getUploadedImagesMediaLibrary($subdir = 'medialibrary') {
    $mediaLibrary = array();

    $res = CFile::GetList();
    while($res_arr = $res->GetNext()) {
        if (strpos($res_arr["SUBDIR"], $subdir) !== false) {
            $mediaLibrary[$res_arr['ID']]['ORIGINAL'] = $res_arr["ORIGINAL_NAME"];
            $mediaLibrary[$res_arr['ID']]['FILE'] = $res_arr["FILE_NAME"];
        }
    }
    return $mediaLibrary;
}

function getImageDataByFileName($imagePathName, $mediaLibrary = false, $subdir = 'medialibrary')
{
    if(!$mediaLibrary) {
        $mediaLibrary = getUploadedImagesMediaLibrary($subdir);
    }

    $info = pathinfo($imagePathName);
    $image = $info['basename'];

    foreach ($mediaLibrary as $idIm => $item) {
        if ($item['ORIGINAL'] == $image) {
            //$file = CFile::GetByID($idIm)->Fetch();
            //$image = '/upload/' . $file['SUBDIR'] . '/' . $file['FILE_NAME'];
            $image = CFile::GetPath($idIm);
            break;
        }
    }
    return CFile::MakeFileArray($image);
}