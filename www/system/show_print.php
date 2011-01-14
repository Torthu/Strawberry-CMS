<?php
/**
 * @package Show
 * @access private
 */
$ap = "1";
#_strawberry
include_once 'head.php';
if (!defined("str_adm")) { header("Location: ../index.php"); exit; }


// выносим ссылки в скобки
add_filter('news-entry-content', 'link_to_text');
function link_to_text($output){
$output = preg_replace('/<a href=(\\\"|"|\'{0,1})(.*?)(\\1)(.*?)>(.*?)<\/a>/i', '\\5 ( <span class="link">\\2</span> )', $output);
#if((substr($output, 0, 7)) != "http://") $output = str_replace("class=\"link\">", "class=\"link\">".$config['http_home'], $output);
return $output;
}


// запрещаем мен€ть шаблон кроме как через переменную $template
add_filter('unset-template', 'unset_template');
function unset_template($files){
$files[] = basename($_SERVER['PHP_SELF']);
return $files;
}


// выводмим на печать
ob_start();
$template = 'system/print';
$number = 1;
$category = ( !empty($_POST['category']) ? $_POST['category'] : ( !empty($_GET['category']) ? $_GET['category'] : '' ));
include root_directory.'/show_news.php';
$out_print = ob_get_clean();
$out_print = str_replace('okno', 'iframe', str_replace('screept', 'script', $out_print));
echo $out_print;
?>