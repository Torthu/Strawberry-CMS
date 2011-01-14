<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}


$ytown = "27553"; // Номер вашего города в списке GisMeteo
$gism = "http://informer.gismeteo.ru/html/informer.php?codepg=windows-1251&index=".$ytown."%CD%E8%E6%ED%E8%E9%20%CD%EE%E2%E3%EE%F0%EE%E4&par=4&domen=RU&inflang=ru&vieinf=bgst&p=1&w=1&tblstl=gmtbl&tdttlstl=gmtdttl&tdtext=gmtdtext";


if (!readserver($gism)){
$bl_out .= t('Погодный сервер временно недоступен'); 
} else {

$bl_out = "<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr><td>";
$math = @file_get_contents($gism);
$math = str_replace("document.write(\"", "", $math);
$math = str_replace("\");", "", $math);
$math = str_replace(" />", ">", $math);
$math = str_replace("http://www.gismeteo.RU", "index.php?mod=weather", $math);
$math = str_replace("GISMETEO.RU", "<small align=\"right\">Прогноз на 5 дней.</small>", $math);
$math = str_replace("Нижний Новгород", "", $math);
$math = str_replace("<td", "\n<td align=\"center\"", $math);
$math = str_replace("http://informer.gismeteo.ru/html/images/logo.gif", "images/0.gif", $math);
$math = str_replace("/towns/27553.htm", "", $math);
$math = str_replace('http://informer.gismeteo.ru/html/images/bg', 'images/blocks/weather', $math);
$math = str_replace("13", "0", $math);
$math = str_replace("20", "0", $math);
$math = str_replace("32", "50", $math);
$math = str_replace('\"', '"', $math);
$bl_out .= $math;
$bl_out .= "</td></tr></table>";
} 


?>