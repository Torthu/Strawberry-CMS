<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}
 $nf = ''; // файл, на который должны указывать ссылки(если отличный от установленного файла в конфигураци€х системы)(например: news.php)
$mi = $modul; // им€ модул€, который отвечает за вывод новостей. (например: news)

$bl_out = (function_exists('cn_calendar')) ? cn_calendar($nf, $mi) : t(' алендарь неактивен');

?>