<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}

#####
function get_content() {
  $date = date("d/m/Y");
  $link = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=".$date;
  $fd = @file_get_contents($link);
  $text="";
if (!$fd) {
$text .= "";
} else {
while (!feof ($fd)) $text .= fgets($fd, 4096);
}
return $text;
} 
#####
function get_content2() {
  $date2 = date("d/m/Y",time()-86400);
  $link2 = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=".$date2;
  $fd = @file_get_contents($link2);
  $text="";
  if (!$fd){
$text .= "";
  } else {
while (!feof ($fd)) $text .= fgets($fd, 4096);
  }
return $text;
 }
#####

$acontent = get_content();
$acontent2 = get_content2();


if ($acontent and $acontent2) {
  $pattern = "#<Valute ID=\"([^\"]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)#i";
  preg_match_all($pattern, $acontent, $out, PREG_SET_ORDER);
#$avstdoll = "";
$fsterl = "";
$belrub = "";
#$datkron = "";
$dollar = "";
$euro = "";
#$islkron = "";
#$kaztenge = "";
#$kandoll = "";
#$kituan = "";
$ukrgriv = "";
#$shvfrank = "";
$japien = "";


  foreach($out as $cur)
{
#    if($cur[2] == 036) $avstdoll = str_replace(",",".",$cur[4]);
if($cur[2] == 826) $fsterl      = str_replace(",",".",$cur[4]);
if($cur[2] == 974) $belrub    = str_replace(",",".",$cur[4]);
#    if($cur[2] == 208) $datkron  = str_replace(",",".",$cur[4]);
if($cur[2] == 840) $dollar     = str_replace(",",".",$cur[4]);
if($cur[2] == 978) $euro       = str_replace(",",".",$cur[4]);
#    if($cur[2] == 352) $islkron   = str_replace(",",".",$cur[4]);
#    if($cur[2] == 398) $kaztenge= str_replace(",",".",$cur[4]);
#    if($cur[2] == 124) $kandoll   = str_replace(",",".",$cur[4]);
#    if($cur[2] == 156) $kituan     = str_replace(",",".",$cur[4]);
if($cur[2] == 980) $ukrgriv   = str_replace(",",".",$cur[4]);
#if($cur[2] == 756) $shvfrank= str_replace(",",".",$cur[4]);
if($cur[2] == 392) $japien   = str_replace(",",".",$cur[4]);
}
##################
  preg_match_all($pattern, $acontent2, $out, PREG_SET_ORDER);
#$avstdoll2 = "";
$fsterl2 = "";
$belrub2 = "";
#$datkron2 = "";
$dollar2 = "";
$euro2 = "";
#$islkron2 = "";
#$kaztenge2 = "";
#$kandoll2 = "";
#$kituan2 = "";
$ukrgriv2 = "";
#$shvfrank2 = "";
$japien2 = "";

###
foreach($out as $cur)
{
#    if($cur[2] == 036) $avstdoll2  = str_replace(",",".",$cur[4]);
if($cur[2] == 826) $fsterl2             = str_replace(",",".",$cur[4]);
if($cur[2] == 974) $belrub2           = str_replace(",",".",$cur[4]);
#    if($cur[2] == 208) $datkron2   = str_replace(",",".",$cur[4]);
if($cur[2] == 840) $dollar2            = str_replace(",",".",$cur[4]);
if($cur[2] == 978) $euro2              = str_replace(",",".",$cur[4]);
#    if($cur[2] == 352) $islkron2    = str_replace(",",".",$cur[4]);
#    if($cur[2] == 398) $kaztenge2= str_replace(",",".",$cur[4]);
#    if($cur[2] == 124) $kandoll2   = str_replace(",",".",$cur[4]);
#    if($cur[2] == 156) $kituan2     = str_replace(",",".",$cur[4]);
if($cur[2] == 980) $ukrgriv2          = str_replace(",",".",$cur[4]);
#    if($cur[2] == 756) $shvfrank2 = str_replace(",",".",$cur[4]);
if($cur[2] == 392) $japien2           = str_replace(",",".",$cur[4]);
}
###
}


$date = date("Y - m - d");
$baks = "<img src=\"images/blocks/course/icon_dollar.gif\" border=\"0\">&nbsp;&nbsp;";
$yoro = "<img src=\"images/blocks/course/icon_evro.gif\" border=\"0\">&nbsp;&nbsp;";
$unkwn = "<img src=\"images/bullet1.gif\" border=\"0\">&nbsp;&nbsp;";
$mup = "<img src=\"images/blocks/course/icon_up.gif\" border=\"0\">&nbsp;&nbsp;";
$mdown = "<img src=\"images/blocks/course/icon_dn.gif\" border=\"0\">&nbsp;&nbsp;";



if (!$acontent or !$acontent2) {

$bl_out .= t('Данные с центрального банка временно недоступны');

} else {

$bl_out .= "<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" class=\"bgcolor4\"><tr><th colspan=\"3\">Курс валют на ".RusDate($date).":</th></tr>";
  $bl_out .= "<tr><td width=\"20%\" align=\"right\">$baks</td><td width=\"45%\" align=\"center\"><u> Доллар </u></td><td width=\"35%\">&nbsp;&nbsp;$dollar ";
  if ($dollar < $dollar2){
  $bl_out .= "$mdown";
  }else{
  $bl_out .= "$mup";
  }
$bl_out .= "</td></tr><tr><td align=\"right\">$yoro</td><td align=\"center\"><u> Евро </u></td><td>&nbsp;&nbsp;$euro ";
  if ($euro < $euro2){
  $bl_out .= "$mdown";
  }else{
  $bl_out .= "$mup";
  }
####### Дополнительно
$bl_out .= "</td></tr><tr><td align=\"right\">$unkwn</td><td align=\"center\"><u> Фунт Стерлингов </u></td><td>&nbsp;&nbsp;$fsterl ";
  if ($fsterl < $fsterl2){
  $bl_out .= "$mdown";
  }else{
  $bl_out .= "$mup";
  }
$bl_out .= "</td></tr><tr><td align=\"right\">$unkwn</td><td align=\"center\"><u> Японские Иены </u></td><td>&nbsp;&nbsp;$japien ";
  if ($japien < $japien2){
  $bl_out .= "$mdown";
  }else{
  $bl_out .= "$mup";
  }
$bl_out .= "</td></tr><tr><td align=\"right\">$unkwn</td><td align=\"center\"><u> Гривны </u></td><td>&nbsp;&nbsp;$ukrgriv ";
  if ($ukrgriv < $ukrgriv2){
  $bl_out .= "$mdown";
  }else{
  $bl_out .= "$mup";
  }
# $bl_out .= "</td></tr><tr><td align=\"right\">$unkwn</td><td align=\"center\"><u> Шведский франк </u></td><td>&nbsp;&nbsp;$shvfrank ";
 #  if ($shvfrank < $shvfrank2){
 #  $bl_out .= "$mdown";
 #  }else{
 #  $bl_out .= "$mup";
 #  }
$bl_out .= "</td></tr><tr><td align=\"right\">$unkwn</td><td align=\"center\"><u> Белорусские рубли </u></td><td>&nbsp;&nbsp;$belrub ";
  if ($belrub < $belrub2){
  $bl_out .= "$mdown";
  }else{
  $bl_out .= "$mup";
  }
####### // Дополнительно
$bl_out .="</td></tr></table>";

}

function RusDate($date) {
$newDate=explode("-",$date);
if ($newDate[1]==01) {$month="Января";}
elseif ($newDate[1]==02) {$month="Февраля";}
elseif ($newDate[1]==03) {$month="Марта";}
elseif ($newDate[1]==04) {$month="Апреля";}
elseif ($newDate[1]==05) {$month="Мая";}
elseif ($newDate[1]==06) {$month="Июня";}
elseif ($newDate[1]==07) {$month="Июля";}
elseif ($newDate[1]==08) {$month="Августа";}
elseif ($newDate[1]==09) {$month="Сентября";}
elseif ($newDate[1]==10) {$month="Октября";}
elseif ($newDate[1]==11) {$month="Ноября";}
elseif ($newDate[1]==12) {$month="Декабря";}
return $newDate[2]." ".$month." ".$newDate[0];
}


?>