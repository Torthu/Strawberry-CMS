<?php

@ignore_user_abort(true);
$is_safe_mode = (ini_get('safe_mode') == "1") ? 1 : 0;
if (!$is_safe_mode && function_exists('set_time_limit')) set_time_limit(0);
$host = (getenv("SERVER_NAME")) ? getenv("SERVER_NAME") : getenv("HTTP_HOST");

header("Expires: Tue, 24 Jun 2009 12:30:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

define("str_update", true);

$ap = ""; // указатель админ панели. (admin panel marker)
### включение системных файлов (system files include)
include_once 'system/head.php'; // система strawberry.
include_once 'system/updates/common/function.php'; // функции update.
include_once 'system/updates/common/lang.php'; // язык update.


if (!empty($_GET['update']) and is_file("system/updates/".$_GET['update'].".php")) {
include "system/updates/link_".$_GET['update'].".php";
$tit = $title;
echo toper();
include "system/updates/".$_GET['update'].".php";
echo boter();

} else {
$tit = _MASTER;
echo toper();
echo _LIST_DOWN."<br>";

$handle = opendir("system/updates/");
while ($file = readdir($handle)){
        if (strtolower(end(explode('.', $file))) == "php" and strstr($file, "link_update_")){
$up_data = $title = $content = $link = "";
include "system/updates/".$file;
echo "<br>- <a href=\"updates.php?update=".$link."\" title=".$title.">".$title."</a> (".$up_data.")<br>".$content."<br>";
$up_data = $title = $content = $link = "";
        }
}

echo boter();

}


?>