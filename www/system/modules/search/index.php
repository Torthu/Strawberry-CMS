<?php 
if (!defined("str_modul")) {
	header("Location: ../../../index.php");
	exit;
}
### Параметры модуля
$tit = t("Поиск"); 

otable();
echo "<div class=\"text\">".t('Поиск ведется в тематических категориях сайта. Размер искомого слова не менее трех символов!')."</div><br>";
$number = 5;
$template = 'search';
include root_directory.'/show_search.php';
echo on_page($modul);
ctable();
?>