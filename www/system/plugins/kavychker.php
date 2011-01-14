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
Plugin Name:	Кавычкер
Plugin URI:		http://spectator.ru/technology/php/quotation_marks_stike_back
Description:	        «Правильные» кавычки - замена "" на «».
Version:		1.1
Application: 	Strawberry
Author:		Дмитрий Смирнов
Author URI:		http://nudnik.ru
*/

add_filter('news-entry-content','kavychker', 201);
add_filter('news-comment-content','kavychker', 201);

function kavychker($content){ // Смирновский «Кавычкер»
// Copyright (c) Spectator.ru
if (!empty($content) and !is_array($content)) {
	$content = stripslashes($content);

	// замена кавычек в html-тэгах на символ "¬"
	$a[] = "/<([^>]*)>/es";
	$b[] = "'<'.str_replace ('\\\"', '¬','\\1').'>'";

	// замена кавычек внутри <code> на символ "¬"
	$a[] = "/<code>(.*?)<\/code>/es";
	$b[] = "'<code>'.str_replace ('\\\"', '¬','\\1').'</code>'";
	$a[] = "/<code>(.*?)<\/code>/es";
	$b[] = "'<code>'.str_replace ('\\\'', '*¬','\\1').'</code>'";

	// расстановка кавычек: кавычка, перед которой идет ( или > или пробел = начало слова,
	// кавычка, после которой не идет пробел = это конец слова.
	$a[] = "[&quot;]";
	$b[] = '"';
	$a[] = "/([>(\s])(\")([^\"]*)([^\s\"(])(\")/";
	$b[] = "\\1«\\3\\4»";

$content = preg_replace($a, $b, $content);

	// что, остались в тексте нераставленные кавычки? значит есть вложенные!
	if (stristr($content, '"')) {

	// расставляем оставшиеся кавычки (еще раз).
	$aa[] = "/([>(\s])(\")([^\"]*)([^\s\"(])(\")/";
	$bb[] = "\\1«\\3\\4»";
$content = preg_replace($aa, $bb, $content);
	// расставляем вложенные кавычки
	// видим: комбинация из идущих двух подряд открывающихся кавычек без закрывающей
	// значит, вторая кавычка - вложенная. меняем ее и идущую после нее, на вложенную (132 и 147)
	 while (preg_match ("/(«)([^»]*)(«)/", $content)) { 
	$a[] = "/(«)([^»]*)(«)([^»]*)(»)/"; 
	$b[] = "\\1\\2&#132;\\4&#147;"; 
	 }
$content = preg_replace($aa, $bb, $content);
	// конец вложенным кавычкам
	}

	// кавычки снаружи
	$aaa[] = "/\<a\s+href([^>]*)\>\s*\«([^<^«^»]*)\»\s*\<\/a\>/";
	$bbb[] = "&#171;<a href\\1>\\2</a>&#187;";

$content = preg_replace($aaa, $bbb, $content);
	// расстанавливаем правильные коды, тире и многоточия
	$trans = array
	(
		"\xAB" => '&laquo;',
		"\xBB" => '&raquo;',
		"\x93" => '&bdquo;',
		"\x94" => '&ldquo;',
		'...'  => '&hellip;',
		'(c)'  => '&copy;',
		'(C)'  => '&copy;',
		'(r)'  => '&reg;',
		'(R)'  => '&reg;',
		'(tm)' => '&trade;',
		'(TM)' => '&trade;',
		"'"    => '&#146;' #апостроф
	);
	$content = strtr($content, $trans);

	// тире в начале строки (диалоги)
	$aaaa[] = '/([>|\s])- /'; 
	$bbbb[] = "\\1&#151; ";
$content = preg_replace($aaaa, $bbbb, $content);

	// меняем  "¬" обратно на кавычки
	$content = str_replace('*¬','\'', $content);
	$content = str_replace('¬','"', $content);

	//предлоги вместе со словом (слово не переносится на другую строку отдельно от предлога)
	 $aaaaa[] = "/ (.) (.)/"; // вообще на сиськи похоже ))
	 $bbbbb[] = " \\1 \\2";

	// дефисы
	$aaaaa[] = "/(\s[^- >]*)-([^ - >]*\s)/";
	$bbbbb[] = "\\1-\\2";
	
$content = addslashes(preg_replace($aaaaa, $bbbbb, $content));

}
return $content;
}
?>
