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
Plugin Name: 	XFields
Description: 	Дополнительные поля.
Version: 		1.0
Application: 	Strawberry
Author: 		SMKiller2
*/

add_filter('template-active', 'xfields_templates', 27);
add_filter('template-full', 'xfields_templates', 27);

function xfields_templates($template){

	$template['xfields'] = t('Например $tpl[\'post\'][\'xfields\'][\'X\'], где "X" это имя поля.');

return $template;
}

add_action('head', 'call_xfields', 27);

function call_xfields(){
global $xfields_db;

    /*
    $xfieldsaction = 'templatereplace';
    $xfieldsinput  = $output;
    $xfieldsid     = $tpl['id'];
    include plugins_directory.'/xfields/core.php';
    $output        = $xfieldsoutput;
    */

    $xfields_data = file(data_directory.'/xfields-data.txt');
    foreach ($xfields_data as $line){
    	$xfields_arr = explode('|>|', $line);

        foreach (explode('||', $xfields_arr[1]) as $xfield){
            list($name, $content) = explode('|', $xfield);

            $xfields_db[$xfields_arr[0]][$name] = $content;
        }
    }
}

add_filter('news-show-generic', 'xfields_parse', 27);

function xfields_parse($tpl){
global $xfields_db;

    $tpl['xfields'] = array();

	if (!empty($xfields_db[$tpl['id']])){
		foreach ($xfields_db[$tpl['id']] as $k => $v){
			$tpl['xfields'][$k] = $v;
		}
	}

return $tpl;
}

add_filter('options', 'xfields_AddToOptions', 27);

function xfields_AddToOptions($options) {

	$options[] = array(
	             'name'     => t('Дополнительные поля'),
	             'url'      => 'plugin=xfields&xfieldsaction=configure',
	             'category' => 'plugin',
	             );

return $options;
}









add_action('plugins','xfields_CheckAdminOptions', 27);
function xfields_CheckAdminOptions(){
global $gplugin;
	if (!empty($gplugin) and $gplugin == 'xfields'){
		xfields_AdminOptions();
	}
}
function xfields_AdminOptions(){
global $PHP_SELF, $cutepath, $_GET, $xfieldsadd, $xfieldsaction, $xfield;
	foreach ($_GET as $k => $v){
		$$k = $v;
	}
	include plugins_directory.'/xfields/core.php';
}









add_action('new-advanced-options', 'admins_xfields', 27);
add_action('edit-advanced-options', 'admins_xfields', 27);

function admins_xfields(){
global $xfield, $id, $mod;
    ob_start();
    $xfieldsaction = 'list';
    $xfieldsadd = ((!empty($mod) and $mod == 'addnews') ? true : false);
    $xfieldsid = ((!empty($mod) and $mod == 'addnews') ? '' : $id);
    include plugins_directory.'/xfields/core.php';
    $xfields = ob_get_clean();
return $xfields;
}









add_action('mass-deleted', 'xfields_delete', 27);
function xfields_delete(){
global $row, $post;
    $xfieldsaction = 'delete';
    $xfieldsid = !empty($row['id']) ? $row['id'] : (!empty($post['id']) ? $post['id'] : 0);
    include plugins_directory.'/xfields/core.php';
}






add_action('new-save-entry', 'call_xfields_Save');
add_action('edit-save-entry', 'call_xfields_Save');
function call_xfields_Save(){
global $id, $xfield;
	$xfieldsid = $id;
	$xfieldsaction = 'init';
    include plugins_directory.'/xfields/core.php';
	$xfieldsaction = 'save';
	include plugins_directory.'/xfields/core.php';
}





add_filter('help-sections', 'xfields_help', 27);

function xfields_help($help_sections){
$help_sections['xfields'] = t('<h1>Дополнительные поля</h1>
<p>Этот модуль не входит в официальную версию CuteNews. Он поставляется как отдельный мод. Однако я решил, что будет крайне удобно, если "Дополнительные поля" (XFields) появятся в русской версии системы.<br><br>
Подключение дополнительных полей осуществляется через меню Настройки &gt; <a href=?mod=xfields&amp;xfieldsaction=configure>Дополнительные поля</a>. Данная функция удобна при публикации вместе со статьей, например, ссылки на первоначальный источник.</p>
<p>Добавить новое поле можно, нажав на ссылку "<a href=?mod=xfields&xfieldsaction=configure&amp;xfieldssubaction=edit&amp;xfieldsindex=1.5>Новое поле</a>". На открывшейся странице Вам необходимо ввести уникальное имя поля, описание поля и содержание поля по умолчанию. Также Вам необходимо выбрать, хотите ли Вы использовать поля по желанию. Т.е. если Вы захотите для какой-нибудь статьи не использовать дополнительное поле, Вам достаточно оставить поле пустым при добавлении/редактировании новости и модуль не будет включаться.</p>
<p>Для того чтобы использовать поле на странице с новостями, Вам необходимо в шаблон добавить переменную [xfvalue_X], где X - значение поля (имя, которое Вы ввели при добавлении нового поля). Также можно использовать связку [xfgiven_X]...[/xfgiven_X].</p>
<p>Пример работы полей:</p>
<p class="code">
<b>1)</b>- Название: stit<br>
&nbsp;&nbsp;- Описание: Источник информации<br>
&nbsp;&nbsp;- Значение по умолчанию: Я<br>
&nbsp;&nbsp;- При желании: да<br>
<b>2)</b>- Название: source<br>
&nbsp;&nbsp;- Описание: Ссылка на источник информации<br>
&nbsp;&nbsp;- Значение по умолчанию: http://server.com/<br>
&nbsp;&nbsp;- При желании: да<br><br>
[xfgiven_source]Источник - &lt;a href=[xfvalue_source] target=_blank&gt;[xfvalue_stit]&lt;/a&gt;.[/xfgiven_source]</p>
<p>Итогом работы будет следующий HTML-код:</p>
<p class="code">Источник - &lt;a href=http://server.com/ target=_blank&gt;Я&lt;/a&gt;.</p>');

return $help_sections;
}
?>