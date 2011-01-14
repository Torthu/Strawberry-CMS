<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}

if (!empty($is_logged_in)) {

$bl_out = "<center class=\"c4\">".t('Здравствуйте').", <b class=\"c2\" title=\"".t('Вы пользователь')." №".$member['id']."\">".$member['name']."</b>!<br>".t('Авторизация выполнена')."!</center><br>
".t('Вы можете').":<br>
» <a href=\"".way("system/index.php")."\" target=\"_blank\" title=\"".t('Панель управления')."\">".t('войти в панель')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=account&amp;act=profil")."\" title=\"".t('Настроить свой профиль')."\">".t('настроить профиль')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=news&amp;act=add")."\" title=\"".t('Добавить свою новость')."\">".t('добавить новость')."</a><br>
» <a href=\"".way("".$config['home_page']."?mod=logout")."\" title=\"".t('Выход')."\">".t('выйти из системы')."</a>";

} else {

$bl_out = lform();

}
?>