<?php
#_strawberry
if (!defined("str_block")) {
	header("Location: ../../index.php");
	exit;
}

$bl_out = "<a href=\"".way("rss.xml")."\"><img border=\"0\" src=\"".way("images/icons/rss.gif")."\" alt=\"".t('RSS поток новостей сайта')."\" title=\"".t('RSS поток новостей сайта')."\"></a> <a href=\"".way("atom.xml")."\"><img border=\"0\" src=\"".way("images/icons/atom.gif")."\" alt=\"".t('Поток новостей сайта по системе ATOM')."\" title=\"".t('Поток новостей сайта по системе ATOM')."\"></a> <a href=\"http://mgcorp.ru/fructoza/index.php\"><img border=\"0\" src=\"".way("images/icons/fructoza.gif")."\" alt=\"".t('Радио Fructoza')."\" title=\"".t('Новости Strawberry и хорошая музыка в прямом эфире!')."\"></a>";

?>