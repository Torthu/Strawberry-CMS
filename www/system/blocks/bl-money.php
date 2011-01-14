<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}
# Укажите свой кошелёк
$wmz = "Z251356712799";
$wmr = "R384153503405";


$t1 = t('Пожертвование для хорошего проекта');
$t2 = t('WebMoney Keeper должен быть включен.');


$bl_out = "<font class=\"c7\">".t('Рублевки и зелень можно слать сюда').":
<br><font class=\"c4\">WMR</font> ".$wmr."
<br><font class=\"c4\">WMZ</font> ".$wmz."
</font>";


$bl_out .= "<br><br><form action=\"wmk:payto\" style=\"margin: 1px; padding: 0pt;\" method=\"get\">
".t('Пожертвование на кошелек').": <br>
<center>
<b>".$wmz."</b><br>
<input name=\"Amount\" value=\"10\" size=\"4\" type=\"text\"> <input value=\"OK\" type=\"submit\" class=\"fbutton\">
</center>
<input name=\"Purse\" value=\"$wmz\" type=\"hidden\">
<input name=\"Desc\" value=\"$t1\" type=\"hidden\">
<input name=\"BringToFront\" value=\"Y\" type=\"hidden\">
</form><br>
*<i>$t2</i>";

?>