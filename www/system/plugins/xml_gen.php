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
Plugin Name:	Генератор XML из RSS
Plugin URI:		http://strawberry.su/
Description:	        Генерирование xml файла в корне сайта с содержанием rss ленты. Генерация выбирается при добавлении и редактировании новости.<br />По умолчанию генерация всегда включена!<br /><i>Плагин полезен в случае отсутствия ModRewrite в конфигурации сервера.</i>
Version:		1.0
Application: 	Strawberry
Author: 		Mr.Miksar
Author URI:        mailto:miksar@mail.ru
*/


add_action('new-save-entry', 'xml_gen', 28);
add_action('edit-save-entry', 'xml_gen', 28);
add_action('mass-deleted', 'xml_gen', 28);

function xml_gen($hook) {
global $config;
if (!empty($_POST['cat'])) {
$folder = straw_parse_url($config['http_home']);
$folder_file = $folder['abs'];
$where_mas = array();
$where_mas = explode(",", $config['rss_cat']);
$xml_go = "";
foreach ($where_mas as $val) {
if (!empty($_POST['cat'])) {
  $cat_xml_go = $_POST['cat'];
  while(list($key, $vall) = each($cat_xml_go)) {
    if ($val == $key) { $xml_go="1"; break; }
    $key = "";
    $vall = "";
  }
}
}

if (!empty($xml_go)) {
$this_write = $folder_file."g_rss.xml";
$rss_feed = @file_get_contents($config['http_home']."/rss.php");
$file = @fopen($this_write,"w");
fwrite($file,$rss_feed);
@fclose($file);
}

}
return;
}

?>