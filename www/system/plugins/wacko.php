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
Plugin Name: 	Wacko форматирование
Plugin URI:     http://cutenews.ru
Description: 	Wacko форматирование. <br>Не рекомендуется использовать. Возможна нестабильная работа.
Version: 		1.0
Application: 	Strawberry
Author:         Wacko systems
*/

add_filter('new-advanced-options', 'wacko_help', 26);
add_filter('edit-advanced-options', 'wacko_help', 26);

function wacko_help($location){
?>

<div class="wacko_help"><a onclick="window.open('plugins/wacko/docs/english/format.html', '_WikiHelp', 'height=420,resizable=yes,scrollbars=yes,width=410');return false;" href="plugins/wacko/docs/english/format.html"><?php echo t('Помощь по wacko разметке'); ?></a></div>

<?php
return $location;
}

add_action('head', 'wacko', 26);

function wacko($output){
global $wacko;

    include plugins_directory.'/wacko/classes/WackoFormatter.php';
    $wacko = new WackoFormatter();
}

add_filter('news-entry-content', 'wacko_formater', 26);
add_filter('news-comment-content', 'wacko_formater', 26);

function wacko_formater($output){
global $wacko;

	$output = $wacko->format($output);
	$output = trim($output);

return $output;
}
?>