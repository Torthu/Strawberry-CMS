<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../index.php");
exit;
}

/**
 * @package Plugins
 * @access private
 */

/*
Plugin Name: РНР для новостей и шаблонов
Plugin URI:     http://www.xs4all.nl/~cvdtak/phpintemplates_1.2.htm
Description:	Плагин позволяет вам выполнять php в новостях и шаблонах. Будьте осторожны с плагином!
Version: 	1.0
Application: 	Strawberry
Author:			cvdtak
Author URI:		http://www.xs4all.nl/~cvdtak/
*/





add_filter('news-entry', 'php_in_template');
function php_in_template($output){
 $output = preg_replace_callback('/<\\?php(.*?)\\?>/is', 'phpTemplateEval', $output);
 return $output;
}










function phpTemplateEval($match){
global $tpl, $users, $usergroups;

	$match[1] = replace_news('admin', $match[1]);
	$match[1] = str_replace('<br>', '', $match[1]);

	ob_start();
	if (!empty($usergroups[$users[$tpl['post']['_']['author']]['usergroup']]['access']) and $usergroups[$users[$tpl['post']['_']['author']]['usergroup']]['access'] == 'full'){
		eval($match[1]);
	} else {
		echo htmlspecialchars($match[1]);
	}
	return ob_get_clean();
}
?>