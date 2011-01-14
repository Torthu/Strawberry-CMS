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
Plugin Name:	Формат поста
Plugin URI:  	http://www.strawberry.su/
Description:	        Выбор разных форматов вывода новостей при публикации/редактировании. MySQL база. Исправлены некоторые ошибки при ВакоТегах.
Version: 		1.3
Application: 	Strawberry
Author: 		David Carrington, Mr.Miksar
Author URI:		http://www.brandedthoughts.co.uk
*/



define('FS_FORMAT_XFIELD', 'fs_format');
define('FS_DEFAULT_FORMAT', 'html_with_br');

add_action('edit-advanced-options', 'fs_FormatSelectBox');
add_action('new-advanced-options', 'fs_FormatSelectBox');

add_filter('news-entry-content', 'fs_ApplyFormat', 1000);
add_filter('news-comment-content', 'fs_ApplyFormat', 1000);


/* Default formats */
$GLOBALS['fs_formats']['text']         = 'fs_Plain';
$GLOBALS['fs_formats']['text_with_br'] = 'fs_Plain_br';
$GLOBALS['fs_formats']['html']         = 'fs_HTML';
$GLOBALS['fs_formats']['html_with_br'] = 'fs_HTML_br';



function fs_FormatDropDownOptions(){
global $fs_formats, $story, $post;
$desc = array();
$format = (!empty($story['format']) ? $story['format'] : (!empty($post['format']) ? $post['format'] : FS_DEFAULT_FORMAT));
$buffer = "";
	if (!empty($fs_formats)){
		$desc['text'] = t('Текст');
		$desc['text_with_br'] = t('Текст с переносом строк');
		$desc['html'] = t('HTML');
		$desc['html_with_br'] = t('HTML с переносом строк');
		foreach ($fs_formats as $fs_name => $fs_function){
		$buffer .= '<option value="'.$fs_name.'"'.( ($format == $fs_name ? ' selected="selected"' : '')).'>'.$desc[$fs_name].'</option>';
		}
	}
return $buffer;
}



function fs_FormatSelectBox($hook){
$buffer = '<fieldset id="news_format"><legend>'.t('Формат').'</legend><select name="fs_format" id="cboFS_Format" style="width:210px;">'.fs_FormatDropDownOptions().'</select></fieldset>';
return $buffer;
}



function fs_ApplyFormat($content, $hook){
global $row, $fs_formats;
$format = (!empty($_POST['fs_format'])) ? $_POST['fs_format'] : (!empty($row['format']) ? $row['format'] : $fs_formats[FS_DEFAULT_FORMAT]);
	// Get the function name
$format_function = !empty($fs_formats[$format]) ? $fs_formats[$format] : $fs_formats[FS_DEFAULT_FORMAT];
if (empty($format_function) or !function_exists($format_function)){
 $format_function = $fs_formats[FS_DEFAULT_FORMAT];
}
	// Run the formatting function
$content = $format_function($content);
return $content;
}



function fs_Plain($content){
$content = htmlspecialchars($content);
$content = str_replace('{nl}', '', $content);
return $content;
}



function fs_Plain_br($content){
$content = htmlspecialchars($content);
$content = str_replace('{nl}', '<br>', $content);
return $content;
}



function fs_HTML($content){
$content = str_replace('{nl}', '', $content);
return $content;
}



function fs_HTML_br($content) {
$active_plugins = loadarray(active_plugins_file);
$content = str_replace('{nl}', '<br>', $content);
if (!empty($active_plugins['wacko.php'])) {
//$content = str_replace('', '', $content);
$content = str_replace(array('><br><table','><br><tr','><br><td','><br></tr','><br></td','><br></table','table><br>'), array('><table','><tr','><td','></tr','></td','></table','table>'), $content);
}
return $content;
}
?>