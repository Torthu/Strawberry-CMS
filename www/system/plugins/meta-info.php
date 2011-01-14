<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../index.php");
exit;
}

/**
 * @package Plugins
 */

/*
Plugin Name:    Мета информация
Plugin URI:       http://strawberry.su/
Description:     Аналог MetaTags, но с базой данных MySQL. Выводит дополнительный заголовок, мета теги keywords и description.<br>Выводить так:<br>title - <code>&lt;title&gt;&lt;?=metainfo('title'); ?&gt;&lt;/title&gt;</code><br>keywords - <code>&lt;meta name="keywords" content="&lt;?=metainfo('keywords'); ?&gt;"&gt;</code><br>description - <code>&lt;meta name="description" content="&lt;?=metainfo('description'); ?&gt;"&gt;</code>
Version: 	    1.1
Application:     Strawberry
Author: 	    Mr.Miksar
Author URI:     mailto:miksar@mail.ru 
*/






add_action('new-advanced-options', 'metainfo_AddEdit');
add_action('edit-advanced-options', 'metainfo_AddEdit');

/**
 * @access private
 */
function metainfo_AddEdit(){
global $id;

$out = '<fieldset id="meta_info_title">
<legend>Meta title ('.t('заголовок').')</legend>
<input style="width:210px;" name="meta_info_title" type="text" value="'.metainfo('title', $id).'">
</fieldset>';

$out .= '<fieldset id="meta_info_keywords">
<legend>Meta keywords ('.t('ключ. слова').')</legend>
'.t('Вводить слова в одну строку через запятую.').'
<textarea style="width:210px;" name="meta_info_keywords">'.metainfo('keywords', $id).'</textarea>
</fieldset>';

$out .= '<fieldset id="meta_info_description">
<legend>Meta description ('.t('описание').')</legend>
'.t('Введите краткое описание того, о чем информирует эта страница.').'
<textarea style="width:210px;" name="meta_info_description">'.metainfo('description', $id).'</textarea>
</fieldset>';

return $out;
}




/**
 * @param string $meta
 * @return string
 */
function metainfo($c="title", $aid=0){
global $cache, $id, $post, $nid;
$nid = !empty($aid) ? intval($aid) : $nid;
if (!empty($nid)){ 
             if ($c=='keywords')  {
             #return $cache->put($post['metakeywords']);
             return $post['metakeywords'];
             } elseif ($c=='description') {
             #return $cache->put($post['metadescription']);
             return $post['metadescription'];
             } else {
             #return $cache->put($post['metatitle']);
             return $post['metatitle'];
             }
} else {
return;
}
}

?>