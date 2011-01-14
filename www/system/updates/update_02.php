<?php
 if (!defined("str_update")) die("Access dinaed");

$categ = "";

echo "Это обновление позволит использовать более быструю версию плагина bookmark. <br><br>Если Вы устанавливали и использовали систему заново, то это обновление не нужно делать. Если вы переносите или обновляете старую базу, то это обновление необходимо.<br><br>Не закрывайте окно до окончания обновления.<br><br>";

if (!empty($_POST['action']) and $_POST['action'] == "do") {
echo "<br><center><strong>ЗАПУСК!</strong></center><br>";
###################################################################

$db->sql_query("ALTER TABLE ".$config['dbprefix']."news ADD `bookmark` INT(1) NOT NULL");

###################################################################
echo "<br><center><strong>ОБНОВЛЕНИЕ ВЫПОЛНЕНО!</strong></center>";
} else {
###################################################################

echo "<center><form method=\"post\" action=\"\"><input type=\"hidden\" name=\"action\" value=\"do\"><input type=\"submit\" value=\" ВЫПОЛНИТЬ ОБНОВЛЕНИЕ \"></form></center>";

###################################################################
}

?>