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
Plugin Name: 	Закладки
Plugin URI:     http://cutenews.ru
Description: 	Добавляет новость в закладки. Используйте <code>$bookmark = true;</code> перед инклудом show_news.php.
Version: 		2.2
Application: 	Strawberry
Author: 		Лёха zloy и красивый
Author URI:     http://lexa.cutenews.ru
*/

// добавляем филтр постов
add_filter('news-where', 'bookmark_check');
function bookmark_check(){
global $bookmark;
$where = !empty($bookmark) ? "AND a.bookmark = 1" : "";
return $where;
}

// добавляем форму к добавлению и редактированию постов
add_action('new-advanced-options', 'bookmark_AddEdit', 3);
add_action('edit-advanced-options', 'bookmark_AddEdit', 3);
function bookmark_AddEdit(){
global $post;
return '<fieldset id="bookmark"><legend>'.t('Закладки').'</legend><label for="bookmark"><input type="checkbox" id="bookmark" name="bookmark" value="1" '.(!empty($post['bookmark']) ? ' checked="checked"' : '').'>&nbsp;'.t('Добавить в закладки').'</label></fieldset>';
}

// всю жизнь пройдя до половины, я очутился в сумрачном лесу // v1.1.1
// кругом стеной стоят осины, но с гордостью свой крест несу // v1.2.x
?>