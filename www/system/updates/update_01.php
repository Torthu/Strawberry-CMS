<?php
 if (!defined("str_update")) die("Access dinaed");

$categ = "";

echo "Это обновление перезапишет записи новостей относительно их категорий. Это нужно в связи с новой выборкой новостей из базы данных. <br><br>Если Вы устанавливали и использовали систему заново, то это обновление не нужно делать. Если вы переносите или обновляете старую базу, то это обновление необходимо.<br><br>На выполнение обновления может потребоваться достаточно много времени (в зависимости от количества ваших записей). Не закрывайте окно до окончания обновления.<br><br>";

if (!empty($_POST['action']) and $_POST['action'] == "do") {
echo "<br><center><strong>ЗАПУСК!</strong></center><br>";
$arr_query = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news");
$a=0;
while($query[] = $db->sql_fetchrow($arr_query)) {

   if (!empty($query[$a]['category'])) {
   
echo (!empty($_POST['result']) ? "Запись <b>№ ".$query[$a]['id']."</b> в категории(ях) <b>".$query[$a]['category']."</b><br>" : "");
$categ = $query[$a]['category'];
if (!empty($query[$a]['category']) and (substr($query[$a]['category'], 0, 1) != "," or substr($query[$a]['category'], -1) != ",")) {
if (substr($query[$a]['category'], 0, 1) != ",") $categ = ",".$categ;
if (substr($query[$a]['category'], -1) != ",") $categ = $categ.",";
$db->sql_query("UPDATE ".$config['dbprefix']."news SET category='".$categ."' WHERE id='".$query[$a]['id']."'");
echo (!empty($_POST['result']) ? "Меняем категорию(ии) на <b>".$categ."</b><hr>" : "");
} else {
echo (!empty($_POST['result']) ? "<i>Обновление не требуется!</i><hr>" : "");
}

   } else {

echo (!empty($_POST['result']) ? "<strong><font color=\"red\">ВНИМАНИЕ! В записи № ".$query[$a]['id']." не указана категория!</font></strong><hr>" : "");

   }
$a++;
$categ = "";
}


echo "<br><center><strong>ОБНОВЛЕНИЕ ВЫПОЛНЕНО!</strong></center>";

} else {

echo "<center><form method=\"post\" action=\"\"><input type=\"hidden\" name=\"action\" value=\"do\"><input type=\"checkbox\" name=\"result\" value=\"1\"> Вывести результаты изменений? (может замедлить работу браузера!)<br><input type=\"submit\" value=\" ВЫПОЛНИТЬ ОБНОВЛЕНИЕ \"></form></center>";

}

?>