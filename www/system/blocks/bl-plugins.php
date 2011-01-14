<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}
$pointer = "» ";
$av_plug = "";
$active_plugins = active_plugins();
$available_plugins = available_plugins();

foreach ($active_plugins as $namep => $actp) {
  if ($actp) {

   foreach ($available_plugins as $nameap => $actap) {
     if ($actap['file'] == $namep) {
       $av_plug .= "<div title=\"".t('Автор').": ".$actap['author'].". -nl- ".t('Версия').": ".$actap['version'].". -nl- ".t('Файл').": ".$actap['file'].".\" style=\"cursor: pointer;\">".$pointer.$actap['name']."</div>";
     }
   }

  }
}

$bl_out = t('В текущий момент на сайте активны следующие плагины:').$av_plug;
?>