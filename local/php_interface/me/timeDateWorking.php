<?
function getCurrentDateTime($format = 'd.m.Y H:i:s')
{
    $timeNow = date($format);
    return $timeNow;
}

function timeToTimestamp($time = 'now')
{
    $date = new DateTime($time);
    return $date->getTimestamp();
}

function getDifferenceTimeFromNow($time) {
    $timeNow = timeToTimestamp();
    return $timeNow - timeToTimestamp($time);
}