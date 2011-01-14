<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}

$bl_out = "<b>IP:</b> ".whois_ip()."<br>";
$bl_out .= "<screept type=\"text/javascript\" language=\"JavaScript\">";
$bl_out .= "document.write(\"<b>".t('Браузер').":</b> \" + navigator.appName + \"<br>\");";
$bl_out .= "document.write(\"<b>".t('Имя').":</b> \" + navigator.appCodeName + \"<br>\");";
$bl_out .= "document.write(\"<b>".t('Версия').":</b> \" + navigator.appVersion + \"<br>\");";
$bl_out .= "document.write(\"<b>".t('Платформа').":</b> \" + navigator.platform+\"<br>\");";
$bl_out .= "document.write(\"<b>".t('Монитор').":</b> \"+screen.width+\" х \"+screen.height+\"<br>\");";
$bl_out .= "</screept>";

?>