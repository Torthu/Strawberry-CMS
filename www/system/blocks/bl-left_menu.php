<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}

$bl_out = "
» <a href=\"".way("".$config['home_page'])."\" title=\"".t('Главная страница сайта')."\">".t('Главная')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=news")."\" title=\"".t('Новости')."\">".t('Новости')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=useful")."\" title=\"".t('Полезности')."\">".t('Полезности')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=strawberry")."\" title=\"".t('Краткое описание Strawberry 1.2')."\">".t('Про Strawberry 1.2')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=download")."\" title=\"".t('Скачать Strawberry')."\">".t('Архив версий')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=myrobo")."\" title=\"".t('Strawberry`s knowledge base about robots')."\">".t('База знаний о роботах')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=gb")."\" title=\"".t('Отзывы о сайте')."\">".t('Гостевая книга')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=account&amp;act=users")."\" title=\"".t('Список пользователей сайта')."\">".t('Пользователи')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=search")."\" title=\"".t('Поиск на сайте')."\">".t('Поиск')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=sitemap")."\" title=\"".t('Развернутая карта сайта')."\">".t('Карта сайта')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=callback")."\" title=\"".t('Напишите нам письмо')."\">".t('Обратная связь')."</a><br>
<hr />
» <a href=\"http://update.strawberry.su/svn.php\" title=\"".t('SVN Strawberry 1.2')."\">".t('Strawberry SVN project')."</a><br>
» <a href=\"http://strawberry.goodgirl.ru/forum/topic/3446/\" title=\"".t('Тема strawberry 1.2 на официальном сайте')."\">".t('Форум')." Strawberry 1.2.x</a><br>
» <a href=\"http://forum.cutenewsru.com/viewtopic.php?f=15&amp;t=6707\" title=\"".t('Тема strawberry 1.2 на')." CuteNews.Ru\">".t('На форуме')." CuteNews.Ru</a><br>
<hr />
» <a href=\"http://strawberry.goodgirl.ru/\" title=\"".t('Официальный сайт оригинального Strawberry')."\">Strawberry 1.1.1</a><br>
» <a href=\"http://strawberry.goodgirl.ru/docs/\" title=\"".t('Официальная документация для Strawberry 1.1.1')."\">".t('Документация для Strawberry 1.1.1')."</a><br>
» <a href=\"http://strawberry.goodgirl.ru/forum/\" title=\"".t('Официальный форум')."\">".t('Форум')."</a><br>
» <a href=\"http://strawberry.goodgirl.ru/forum/topic/1/\" title=\"".t('F.A.Q.')."\">".t('F.A.Q.')." - ".t('форум')."</a><br>
<hr />
» <a href=\"http://strawberry.goodgirl.ru/forum/topic/3542/\" title=\"".t('Выбор хостинга - обзор пользователей')."\">".t('Обзор хостинга')."</a><br>";

?>