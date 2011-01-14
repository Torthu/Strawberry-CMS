<?php
$ap = 1;
### подключаем функции
include_once 'system/head.php';
if (!empty($nid) and is_numeric($nid)) {
### вывод текста в печатной форме
include root_directory.'/show_print.php'; // главный файл print
} else {
@header('HTTP/1.1 301 Moved Permanently');
@header ('Location: '.$config['home_page']);
}
?>