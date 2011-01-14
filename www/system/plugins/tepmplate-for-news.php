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
Plugin Name:	Индивидуальный шаблон
Plugin URI:     http://cutenews.ru
Description:	Новость может быть с любым шаблоном. MySQL
Version:		1.2
Application: 	Strawberry
Author:			Лёха zloy и красивый
Author URI:		http://lexa.cutenews.ru
*/

// Cartman find you and kill you!

add_action('new-advanced-options', 'change_template', 24);
add_action('edit-advanced-options', 'change_template', 24);

function change_template(){
global $id, $post;
$result  = array('' => '...');
$handle  = opendir(templates_directory);
while ($file = readdir($handle)){
 if (file_exists(templates_directory.'/'.$file.'/active.tpl') and file_exists(templates_directory.'/'.$file.'/full.tpl')){
$result[$file] = $file;
 }
}
$result  = '<fieldset id="news_template"><legend>'.t('Шаблон').'</legend>'.makeDropDown($result, 'template', (!empty($post['template']) ? $post['template'] : "")).'</fieldset>';
return $result;
}


add_filter('news-show-generic', 'apply_template', 24);

function apply_template($array){
global $tpl, $row, $static, $post;
$template = $row['template'];

    if (
    $template and
    !$static and
    !strstr($_SERVER['PHP_SELF'], 'rss.php')
    and
    !strstr($_SERVER['PHP_SELF'], 'print.php')
    and
    !is_file(templates_directory.'/'.$template)
    and
    is_dir(templates_directory.'/'.$template)
    ) {
$tpl['template'] = $template;
}
return $array;
}
?>