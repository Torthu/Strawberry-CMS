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
Plugin Name: 	јвто—сылки
Plugin URI:          http://strawberry.su
Description: 	«амен€ет в тексте неактивные адреса на активные ссылки.
Version: 		1.2
Application: 	Strawberry
Author: 	        Strawberry
*/

add_filter('news-entry-content', 'InsertLinks', '5');
add_filter('news-comment-content', 'InsertLinks', '5');

function InsertLinks($in_text, $hook){
$nofollow = ((!empty($hook) and $hook == 'news-comment-content') ? ' rel="nofollow"' : ''); //если ссылка в комментари€х, то переход недоступен
  if (!is_array($in_text)) {
    $in_text = " ".$in_text;
    $in[] = "#([\n| |{n}|<br>])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is";
    $out[] = "\\1<a href=\"\\2\" target=\"_blank\"".$nofollow.">\\2</a>";
    $in[] = "#([\n| |{n}|<br>])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is";
    $out[] = "\\1<a href=\"http://\\2\" target=\"_blank\"".$nofollow.">\\2</a>";
    $in[] = "#([\n| |{n}])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i";
    $out[] = "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>";
    $in_text = preg_replace($in, $out, $in_text);
    $in_text = substr($in_text, 1);
  }
return $in_text;
}
?>