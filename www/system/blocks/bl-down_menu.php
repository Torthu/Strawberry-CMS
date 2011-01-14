<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}

$bl_out = "<div class=\"block\">
<a class=\"c1\" href=\"".way($config['home_page'])."\" title=\"".t('Главная страница сайта')."\">".t('Главная')."</a> | 
<a class=\"c1\" href=\"".way($config['home_page']."?mod=about")."\" title=\"".t('Описание этого сайта')."\">".t('Про Ресурс')."</a> | 
<a class=\"c1\" href=\"http://strawberry.goodgirl.ru/\" title=\"".t('Официальный сайт оригинального Strawberry')."\">Strawberry 1.1.1</a> | 
<a class=\"c1\" href=\"http://strawberry.goodgirl.ru/docs/\" title=\"".t('Официальная документация')."\">".t('Документация')."</a> | 
<a class=\"c1\" href=\"http://strawberry.goodgirl.ru/forum/\" title=\"".t('Официальный форум')."\">".t('Форум')."</a> | 
<a class=\"c1\" href=\"http://strawberry.goodgirl.ru/forum/topic/3446/\" title=\"".t('Тема strawberry 1.2 на официальном сайте')."\">".t('Форум Strawberry 1.2.x')."</a>
</div>";
?>