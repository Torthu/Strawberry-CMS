<?php
#_strawberry 1.2

############### �������� �������� ����� �� www (Check www in url)
//if((substr($_SERVER['SERVER_NAME'], 0, 4)) != "www." and !empty($_SERVER['SERVER_ADDR']) and $_SERVER['SERVER_ADDR'] != "127.0.0.1" and !empty($_SERVER['HTTP_HOST']) and $_SERVER['HTTP_HOST'] != "localhost" ) {
//@header('HTTP/1.1 301 Moved Permanently');
//@header ("Location: http://www.".$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]."");
//} else {
############# BEGIN SRAWBERRY 1.2 SYSTEM

$ap = 0; // ��������� ����� ������. (admin panel marker)
### ��������� ��������� ������ (system files include)
include_once 'system/head.php'; // ������� strawberry.
include_once root_directory."/inc/strawberry.inc.php"; // strawberry 1.2.x (c) 2009 mysql cms edition.
### ������ (timer)
$timer = new microTimer;
$timer->start();
### ����� ���������� (output info)
strawberry_out(1); 
// 1 - ����� ������� - � ������ html ����������� ��������� ��������� �� � ����� ������; 0 - ��������� �������.
// 1 - show gzip parametrs; 0 - not show...
############# END STRAWBERRY 1.2 SYSTEM
//}
############### // �������� �������� ����� �� www (Check www in url)

?>