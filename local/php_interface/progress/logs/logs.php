<?
define('FILE_NAME', 'logsLeads.log');

$f = fopen(FILE_NAME, "a");
fprintf($f, "%s\r\n", date("d.m.Y H:i:s").': '.$_POST['log']);
fclose($f);