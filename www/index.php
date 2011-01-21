<?php
#_strawberry 1.2

############### Проверим вводимый адрес на www (Check www in url)
//if((substr($_SERVER['SERVER_NAME'], 0, 4)) != "www." and !empty($_SERVER['SERVER_ADDR']) and $_SERVER['SERVER_ADDR'] != "127.0.0.1" and !empty($_SERVER['HTTP_HOST']) and $_SERVER['HTTP_HOST'] != "localhost" ) {
//@header('HTTP/1.1 301 Moved Permanently');
//@header ("Location: http://www.".$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]."");
//} else {
############# BEGIN SRAWBERRY 1.2 SYSTEM

$ap = 0; // указатель админ панели. (admin panel marker)
### включение системных файлов (system files include)
include_once 'system/head.php'; // система strawberry.
include_once root_directory."/inc/strawberry.inc.php"; // strawberry 1.2.x (c) 2009 mysql cms edition.
### таймер (timer)
$timer = new microTimer;
$timer->start();
### вывод информации (output info)
strawberry_out(1); 
// 1 - режим отладки - В нижнем html комментарии выводится параметры до и после сжатия; 0 - отключить отладку.
// 1 - show gzip parametrs; 0 - not show...
############# END STRAWBERRY 1.2 SYSTEM
//}
############### // Проверим вводимый адрес на www (Check www in url)

?>