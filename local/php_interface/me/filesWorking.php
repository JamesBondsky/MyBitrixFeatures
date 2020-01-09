<?php
use Bitrix\Main\IO,
    Bitrix\Main\Application;

function readContentFromFileByBitrix($path) {
    $file = new IO\File(Application::getDocumentRoot() . $path);
    if($file->isExists()) {
        $content = $file->getContents();
        return $content;
    } else {
        return false;
    }
}

function writeNewContentToFileByBitrix($path, $content) {
    $file = new IO\File(Application::getDocumentRoot() . $path);
    $file->putContents($content); // Записать содержимое в файл с заменой
}

function writeAddContentToFileByBitrix($path, $content) {
    $file = new IO\File(Application::getDocumentRoot() . $path);
    $file->putContents($content, IO\File::APPEND); // Записать содержимое в файл с заменой
}