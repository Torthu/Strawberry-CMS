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
Plugin Name:	Тип поста
Plugin URI: 	http://cutenews.ru/
Description:	  Позволяет выбрать тип сообщения: опрос (<code>$type = 'poll';</code>), страница (<code>$type = 'page';</code>)(отличается от обычной новости многоуровневостью), чистый PHP, запароленный пост.
Version: 		0.2
Application: 	Strawberry
Author: 		Лёха zloy и красивый
Author URI:     http://lexa.cutenews.ru
*/


add_action('edit-advanced-options', 'postType_AddEdit', 18);
add_action('new-advanced-options', 'postType_AddEdit', 18);
function postType_AddEdit(){
global $id, $post, $member;
    $types = array('' => '...', 'poll' => t('Опрос'), 'page' => t('Страница'), 'private' => t('Запароленный'));
    if (is_admin()){
    	$types['php'] = t('PHP');
    }
    $buffer  = '<fieldset id="post_type"><legend>'.t('Тип поста').'</legend>';
    $buffer .= makeDropDown($types, 'type" onChange="this.value == \'page\' ? _getElementById(\'postparent\').style.display = \'\' : _getElementById(\'postparent\').style.display = \'none\'; this.value == \'private\' ? _getElementById(\'postpassword\').style.display = \'\' : _getElementById(\'postpassword\').style.display = \'none\';', (!empty($post['type']) ? $post['type'] : ""));
    $buffer .= '<select size="1" id="postparent" name="postparent" style="display: '.(((!empty($post['type']) and $post['type'] != 'page') or empty($post['type'])) ? 'none' : '').';width:100px;" title="'.t('Родитель').'"><option value="0">...</option>';
    if (!empty($post['parent'])){
       $buffer .= page_get_tree('-&nbsp;', '<option value="{id}"[php]tmp_page_selected({id}, '.$post['parent'].')[/php]>{prefix}{title}</option>', false);
    } else {
       $buffer .= page_get_tree('-&nbsp;', '<option value="{id}">{prefix}{title}</option>', false);
    }
    $buffer .= '</select>';
    $buffer .= '<br /><input id="postpassword" name="postpassword" type="text" value="'.(!empty($post['password']) ? $post['password'] : "").'" '.((is_admin() or $post['author']==$member['username']) ? "" : "disabled").'" style="display: '.(((!empty($post['type']) and $post['type'] != 'private') or empty($post['type'])) ? 'none' : '').'" title="'.t('Пароль').'">';
    $buffer .= '</fieldset>';
return $buffer;
}











add_action('new-save-entry', 'postType_saveType', 18);
add_action('edit-save-entry', 'postType_saveType', 18);

function postType_saveType(){
global $db, $config, $pt_values;
$pt_values = array('type' => $_POST['type'], 'password' => '', 'parent' => '', 'level' => '');
    if (!empty($_POST['type']) and $_POST['type'] == 'page'){
    	if (!empty($_POST['postparent'])) {
    	  $ptq = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news WHERE id=".intval($_POST['postparent'])." LIMIT 0,1");
    	  $pt = $db->sql_fetchrow($ptq);
    	}
    	$pt_values['parent'] = $_POST['postparent'];
    	$pt_values['level']  = !empty($pt['level']) ? ($pt['level'] + 1) : 1;
    } elseif (!empty($_POST['type']) and $_POST['type'] == 'private'){
	$pt_values['password'] = $_POST['postpassword'];
    }
}






add_filter('news-where', 'postType_where', 18);
function postType_where($where){
global $type, $where;

    if (!empty($type) and $type == 'page'){
		$where[] = "a.type = 'page'";
    } elseif (!empty($type) and $type == 'poll'){
		$where[] = "a.type = 'poll'";
    } elseif (!empty($type) and $type == 'news'){
		$where[] = "a.type != 'page'";
		$where[] = "a.type != 'poll'";
    } else {
		$where[] = "a.type != 'page'";
    }

return $where;
}






add_filter('constructor-variables', 'postType_constructor', 18);
function postType_constructor($variables) {
$variables['type'] = array('string', makeDropDown(array(t('новости и опросы'), 'news' => t('новости'), 'poll' => t('опросы'), 'page' => t('страницы')), 'type'));
return $variables;
}






add_action('head', 'postType_define', 18);
function postType_define(){
global $cache, $type, $_pages, $db, $config;
    if (!$_pages = $cache->unserialize('_pages')){
    	$ptq = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news WHERE type='page' ");
    	  while ($row = $db->sql_fetchrow($ptq)) {
	     $_pages[$row['id']] = $row;
	  }
	  $_pages = $cache->serialize($_pages);
    }
	unset($type, $_GET['type']);
return;
}






add_filter('unset', 'postType_typeUnset', 18);
function postType_typeUnset($unset){
$unset[] = 'type';
return $unset;
}





add_filter('cute-get-link', 'postType_put_link', 18);
function postType_put_link($output){
    if (!empty($output['arr']['parent'])) {
    	$output['link'] = str_replace('{title}', page_get_link($output['arr']['id']), $output['link']);
    }
return $output;
}





add_filter('htaccess-rules-replace', 'postType_rules_replace', 18);
function postType_rules_replace($output){
global $config, $_pages;
    if (!empty($config['mod_rewrite']) and !empty($_pages)){
        foreach ($_pages as $row){
        	if (!empty($row['parent'])){
            	$page[] = page_get_link($row['id']);
            }
        }
        if (!empty($page)){
            $output = str_replace('{title}', '('.implode('|', $page).'|[_0-9a-z-]+)', $output);
        }
    }
return $output;
}





add_action('head', 'postType_idClean', 18);
function postType_idClean(){
global $id;
	if (!is_numeric($id) and chicken_dick(strstr($id, '/'))){
		$id = $_GET['id'] = end($id = explode('/', chicken_dick($id)));
	}
}






add_action('head', 'postType_updatePoll', 18);
function postType_updatePoll(){
global $db, $config;

    if (!empty($_POST['poll'])){
        foreach ($_POST['poll'] as $pid => $vid) {
        	if (empty($_COOKIE[$config['cookie_prefix'].'cnpostpoll'.$pid])) {
        		straw_setcookie('cnpostpoll'.$pid, 'voted', (time() + 3600 * 24), '/');

   	  $ptq = $db->sql_query("SELECT * FROM ".$config['dbprefix']."story WHERE post_id='".$pid."' LIMIT 0,1");
    	  $row = $db->sql_fetchrow($ptq);
    	  $vote = array();
    	  $poll = array();

	            foreach (explode('{nl}', $row['short']) as $k => $v){
	                $v_arr    = explode('{', $v);
	                $vote[$k] = (int)$v_arr[1];
	                $poll[$k] = $v_arr[0];
	            }

	            foreach ($poll as $k => $v){
	                if ($k == $vid){
	                    $vote[$k] = ($vote[$k] + 1);
	                }
	                $story[] = $poll[$k].'{'.$vote[$k].'}';
	            }

	            if(!empty($story)) $db->sql_query("UPDATE ".$config['dbprefix']."story SET short='".implode("{nl}", $story)."' WHERE post_id='".$pid."' ");
	        }
        }

        header('Location: '.$_SERVER['REQUEST_URI']);
        exit;
    }
}








add_filter('news-show-generic', 'postType_makePoll', 18);
function postType_makePoll($tpl){
global $config;

	if (!empty($tpl['type']) and $tpl['type'] == 'poll'){
	    $short = $full = "";
	    $votes = 0;
	    $vote = array();
	    $poll = array();
    	  
	    foreach (explode('{nl}', $tpl['short']) as $k => $v){
              $v_arr     = explode('{', $v);
              $vote[$k]  = (int)$v_arr[1];
              $poll[$k]  = $v_arr[0];
              $votes    += $vote[$k];
	    }
	    
	    foreach ($poll as $k => $v){
	      $short .= '<label for="poll['.$tpl['id'].']['.$k.']"><input name="poll['.$tpl['id'].']" type="radio" id="poll['.$tpl['id'].']['.$k.']" value="'.$k.'">'.$poll[$k].'</label><br />';
	      $full  .= '<div><b>'.$poll[$k].'</b></div><div style="width: '.@round($vote[$k] / ($votes / 100)).'%;" class="straw_poll">'.@round($vote[$k] / ($votes / 100)).'%</div>';
	    }
	    
	    $tpl['short-story'] = (!empty($_COOKIE[$config['cookie_prefix'].'cnpostpoll'.$tpl['id']]) ? $full : '<form name="cnpostpoll" method="post">'.$short.'<input type="submit" value=" '.t('Ок').' "></form>');
	    $tpl['full-story']  = $full;
	}

return $tpl;
}







add_filter('news-show-generic', 'postType_parsePHP', 18);
function postType_parsePHP($tpl){

	if (!empty($tpl['type']) and $tpl['type'] == 'php'){
	    $evalshort = replace_news('admin', str_replace(array('<br />','<br/>','<br>'), '', $tpl['short']));
	    $evalfull  = replace_news('admin', str_replace(array('<br />','<br/>','<br>'), '', $tpl['full']));

        if (!empty($evalshort)){
	        ob_start();
	        eval($evalshort);
	        $tpl['short-story'] = ob_get_clean();
	    }

        if (!empty($evalfull)){
	        ob_start();
	        eval($evalfull);
	        $tpl['full-story'] = ob_get_clean();
	    }
	}

return $tpl;
}






add_action('head', 'postType_updatePrivate', 18);
function postType_updatePrivate(){
    if (!empty($_POST['cnpostpassword'])){
    	straw_setcookie('cnpostpassword'.intval($_POST['passtopost']), cheker($_POST['cnpostpassword']), (time() + 3600 * 24 * 365), '/');
        header('Location: '.$_SERVER['REQUEST_URI']);
        exit;
    }
}





add_filter('news-show-generic', 'postType_makePrivate', 18);
function postType_makePrivate($tpl){
global $config;
 if (!empty($tpl['type']) and $tpl['type'] == 'private') {
 
   if ((empty($_COOKIE[$config['cookie_prefix'].'cnpostpassword'.$tpl['id']]) or (!empty($_COOKIE[$config['cookie_prefix'].'cnpostpassword'.$tpl['id']]) and $_COOKIE[$config['cookie_prefix'].'cnpostpassword'.$tpl['id']] != $tpl['password'])) and !is_admin()) {
        $tpl['short-story'] = (is_admin() ? t('ЗАПАРОЛЕННЫЙ ПОСТ')."<br>" : "").'<form method="post">'.t('Введите пароль:').'<br><input name="cnpostpassword" type="password" value="'.t('Введите пароль').'" autocomplete="off" onfocus="javascript:this.value=(this.value==\''.t('Введите пароль').'\')?\'\':this.value;" onblur="javascript:this.value=(this.value==\'\')?\''.t('Введите пароль').'\':this.value;"> <input name="passtopost" type="hidden" value="'.$tpl['id'].'"><input type="submit" value=" '.t('Ок').' "></form>';
        $tpl['full-story']  =  (!empty($tpl['full-story']) ? $tpl['short-story'] : '');
   } else {
        $tpl['short-story'] = (is_admin() ? "<div class=\"admin\">".t('ЗАПАРОЛЕННЫЙ ПОСТ')."</div><br>" : "").$tpl['short-story'];
        $tpl['full-story']  =  (!empty($tpl['full-story']) ? (is_admin() ? t('ЗАПАРОЛЕННЫЙ ПОСТ')."<br>" : "").$tpl['full-story'] : $tpl['short-story']);
   }

 }
return $tpl;
}





#-------------------------------------------------------------------------------

function page_get_link($id, $link = ''){
global $_pages;

    if (!empty($_pages[$id]['url'])){
        $link = $_pages[$id]['url'].(!empty($link) ? '/'.$link : '');
    }

    if (!empty($_pages[$id]['parent'])){
        $link = page_get_link($_pages[$id]['parent'], $link);
    }

return chicken_dick($link);
}





function page_get_title($id, $separator = ' &raquo; ', $title = ''){
global $_pages;

    if (!empty($_pages[$id]['title'])){
        $title = $_pages[$id]['title'].(!empty($title) ? $separator.$title : '');
    }

    if (!empty($_pages[$id]['parent'])){
        $title = page_get_title($_pages[$id]['parent'], $separator, $title);
    }

return chicken_dick($title);
}




function page_get_tree($prefix = '', $tpl = '{name}', $no_prefix = true, $id = 0, $level = 0, $johnny_left_teat = ''){
global $db, $config;
    $level++;
    	$ptq = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news WHERE parent='".$id."' AND type='page' ORDER BY id DESC ");
    	  while ($row = $db->sql_fetchrow($ptq)) {
		$pref = (!empty($prefix) ? (!empty($no_prefix) ? preg_replace('/('.preg_quote($prefix, '/').'{1})$/i', '', str_repeat($prefix, $level)) : str_repeat($prefix, $level)) : '');
		$find = array('/{id}/i', '/{title}/i', '/{parent}/i', '/{url}/i', '/{prefix}/i', '/\[php\](.*?)\[\/php\]/ie');
		$repl = array($row['id'], $row['title'], $row['parent'], $row['url'], $pref, '\\1');
		$johnny_left_teat .= $pref.preg_replace($find, $repl, $tpl);
		$johnny_left_teat  = page_get_tree($prefix, $tpl, $no_prefix, $row['id'], $level, $johnny_left_teat);
	}
return $johnny_left_teat;
}




function tmp_page_selected($id, $parent){
	if (!empty($id) and !empty($parent) and $id == $parent){
		return ' selected';
	}
}
?>