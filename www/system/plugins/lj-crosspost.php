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
Plugin Name:	Дублирование с LJ
Plugin URI:     http://cutenews.ru
Description:	Кросспостинг (дубляж сообщения) постов в <a href="http://www.livejournal.com">ЖЖ</a> и его теги.<ul style="margin-top: 3px;margin-bottom: 3px;margin-left: 30px;"><li><img src="./admin/images/user.gif" align="absmiddle">пользователь <code>&lt;lj user="юзер"&gt;</code><li><img src="./admin/images/comm.gif" align="absmiddle">камъюнити <code>&lt;lj comm="камъюнити"&gt;</code><li><img src="./admin/images/synd.gif" align="absmiddle">синдикат <code>&lt;lj synd="синдикат"&gt;</code><li>кат <code>&lt;lj-cut&gt;&lt;/lj-cut&gt;</code> или <code>&lt;lj-cut text="кат"&gt;&lt;/lj-cut&gt;</code></ul>
Application: 	Strawberry
Author:			Лёха zloy и красивый
Author URI:		http://lexa.cutenews.ru
*/

add_action('new-save-entry', 'CuteNews2LJ');
add_action('edit-save-entry', 'CuteNews2LJ');

add_action('xmlrpc-new-save-entry', 'CuteNews2LJ_Start');
add_action('xmlrpc-edit-save-entry', 'CuteNews2LJ_Start');

add_filter('options', 'CuteNews2LJ_AddToOptions');
add_action('plugins','CuteNews2LJ_CheckAdminOptions');

add_action('new-advanced-options', 'CuteNews2LJ_AddEdit', 999);
add_action('edit-advanced-options', 'CuteNews2LJ_AddEdit', 999);

add_filter('news-entry','CuteNews2LJ_tags');
add_filter('news-comment','CuteNews2LJ_tags');

function CuteNews2LJ_AddEdit(){
global $mod, $id;

    $xfields = new XfieldsData();

	if ($xfields->fetch($id, 'itemid') or $mod == 'addnews'){
		return '<fieldset id="lj"><legend>'.t('Кросспостинг в ЖЖ').'</legend><label for="cn2lj"><input type="checkbox" id="cn2lj" name="cn2lj" value="on" checked="checked">&nbsp;'.($mod == 'addnews' ? t('Опубликовать эту новость в ЖЖ?') : t('Отредактировать эту новость в ЖЖ?')).'</label>';
	}
}

function CuteNews2LJ(){

	if ($_POST['cn2lj'] == 'on'){
		CuteNews2LJ_Start();
	}
}

function CuteNews2LJ_Start(){
global $member, $title, $short_story, $url, $full_story, $added_time, $mod, $id, $category;

	#---------------------------------------------------------------------------

	if ($member['lj_username'] and $member['lj_password']){
		$username = $member['lj_username'];
		$password = $member['lj_password'];
	}

    // Логин
	//$username = '';
	// Пароль
	//$password = '';

	// В какой журнал постить записи
	// по умолчанию он совпадает с логином
	$usejournal = '';

	#---------------------------------------------------------------------------

	if (!$username and !$password){
		return;
	}

    include config_file;
    include_once root_directory.'/inc/xmlrpc.inc.php';

    $methodName = (($mod == 'addnews') ? 'postevent' : 'editevent');
    $xfields    = new XfieldsData();
    $itemid     = $xfields->fetch($id, 'itemid');
    $tpl        = new PluginSettings('CN2LJ');

    if (!$tpl->settings['title']){
       $tpl->settings['title'] = '{title}';
       $tpl->save();
    }

    if (!$tpl->settings['story']){
       $tpl->settings['body'] = '{story}{nl}{nl}<p style="text-align: right;"><a href="{link}" style="color: #666;font-size: 9px;" title="&laquo;{title}&raquo;">{hometitle}</a>';
       $tpl->save();
    }

    $find = array('{hometitle}', '{homelink}', '{title}', '{link}', '{story}');
    $repl = array($config['home_title'], $config['http_home_url'], $title, straw_get_link(array('id' => $id, 'date' => $added_time, 'title' => $title, 'category' => $category, 'url' => $url)), $short_story);

	$arr['username']    = $username;
	$arr['hpassword']   = md5($password);
	$arr['subject']     = replace_news('admin', str_replace($find, $repl, $tpl->settings['title']));
	$arr['event']       = replace_news('admin', str_replace($find, $repl, $tpl->settings['story']));
	$arr['lineendings'] = 'unix';
	$arr['ver']         = 1;
	$arr['itemid']      = $itemid;
	$arr['usejournal']  = ($usejournal ? $usejournal : $username);

	if ($added_time){
	    $arr['year'] = date('Y', $added_time);
	    $arr['mon']  = date('m', $added_time);
	    $arr['day']  = date('d', $added_time);
	    $arr['hour'] = date('H', $added_time);
	    $arr['min']  = date('i', $added_time);
	}

	$lj = XMLRPC_request('www.livejournal.com', '/interface/xmlrpc', 'LJ.XMLRPC.'.$methodName, array(XMLRPC_prepare($arr)), 'PHP XMLRPC 1.0');

    if ($methodName == 'postevent'){
		$xfields->set($lj[1]['itemid'], $id, 'itemid');
		$xfields->set($lj[1]['anum'], $id, 'anum');
		$xfields->save();
	}
}

function CuteNews2LJ_AddToOptions($options){

	$options[] = array(
		         'name'     => t('Шаблон поста в ЖЖ'),
		         'url'      => 'plugin=lj',
		         'category' => 'plugin',
		         );

return $options;
}

function CuteNews2LJ_CheckAdminOptions(){

	if ($_GET['plugin'] == 'lj'){
		CuteNews2LJ_AdminOptions();
	}
}

function CuteNews2LJ_AdminOptions(){

    $tpl = new PluginSettings('CN2LJ');

	echoheader('options', t('Шаблон поста в ЖЖ'));

    if (!$tpl->settings['title']){
       $tpl->settings['title'] = '{title}';
       $tpl->save();
    }

    if (!$tpl->settings['story']){
       $tpl->settings['story'] = '{story}{nl}{nl}<p style="text-align: right;"><a href="{link}" style="color: #666;font-size: 9px;" title="&laquo;{title}&raquo;">{hometitle}</a>';
       $tpl->save();
    }

	if ($_POST['title'] or $_POST['story']){
		$tpl->settings['title'] = replace_news('add', $_POST['title']);
        $tpl->settings['story'] = replace_news('add', $_POST['story']);
        $tpl->save();
?>

<?php echo t('Шаблон отображения поста в ЖЖ сохранён.'); ?>
<p><a href="javascript:history.go(-1)"><?php echo t('Назад'); ?></a>

<?php
		echofooter();
		exit;
	}
?>

<table width="350" border="0" cellspacing="2" cellpadding="2" class="panel">
<tr>
<td width="100"><b>{hometitle}</b>
<td>- <?php echo t('название сайта'); ?>
<tr>
<td><b>{homelink}</b>
<td>- <?php echo t('ссылка на сайт'); ?>
<tr>
<td><b>{link}</b>
<td>- <?php echo t('ссылка на новость на сайте'); ?>
<tr>
<td><b>{title}</b>
<td>- <?php echo t('заголовок'); ?>
<tr>
<td><b>{story}</b>
<td>- <?php echo t('тело новости (короткая новости)'); ?>
<tr>
<td><b><?php echo t('новая строка'); ?></b>
<td>- <?php echo t('заменяется на &lt;br /&gt;'); ?>
</table>

<br /><br />
<form method="post" action="?plugin=lj">
<p><?php echo t('Заголовок'); ?><br /><input type="text" name="title" value="<?php echo htmlspecialchars(replace_news('admin', $tpl->settings['title'])); ?>" style="width: 250px;">
<p><?php echo t('Новость'); ?><br /><textarea name="story" rows="15" cols="74"><?php echo htmlspecialchars(replace_news('admin', $tpl->settings['story'])); ?></textarea>
<p><input type="submit" name="submit" value=" <?php echo ('Сохранить'); ?> ">
</form>

<?php
	echofooter();
}

function CuteNews2LJ_tags(){
global $output, $row, $config, $allow_full_story, $xfields;

    if (!is_object($xfields)){
    	$xfields = new XfieldsData();
    }

	$itemid = $xfields->fetch($row['id'], 'itemid');
	$anum   = $xfields->fetch($row['id'], 'anum');
	$itemid = ($anum ? ($itemid * 256 + $anum) : $itemid);
	$output = str_replace('{lj-itemid}', ($itemid ? $itemid : ''), $output);
	$output = preg_replace('/\[lj-link( user=(\\\"|"|\'{0,1})(.*?)(\\2))?\](.*?)\[\/lj-link\]/i', ($itemid ? '<a href="http://\\3.livejournal.com/'.$itemid.'.html">\\5</a>' : ''), $output);
	$output = preg_replace('/<lj (.*?)=(\\\"|"|\'{0,1})(.*?)(\\2)>/i', '<a href="http://\\3.livejournal.com/profile/"><img height="17" src="'.$config['http_script_dir'].'/admin/images/\\1.gif" width="17" align="absmiddle" border="0" alt="[info]"></a><a href="http://\\3.livejournal.com/">\\3</a>', $output);

	preg_match_all('/(<(lj-cut)( text=(\\\"|"|\'{0,1})(.*?)(\\4))?>)(.*?)(<\/\\2>)/i', $output, $matches);

	if ($allow_full_story){
		for ($i = 0; $i < count($matches[7]); $i++){
			$output = str_replace($matches[7][$i], '<a name="cutid'.($i+1).'"></a>'.$matches[7][$i], $output);
		}
	} else {
        for ($i = 0; $i < count($matches[7]); $i++){
        	$output = str_replace($matches[7][$i], '<span class="cutid">(&nbsp;[link=#cutid'.($i+1).']'.($matches[5][$i] ? $matches[5][$i] : 'Read More...').'[/link]&nbsp;)</span>', $output);
        }
	}

	$output = preg_replace('/<lj-cut(.*?)>(.*?)<\/lj-cut>/i', '\\2', $output);

return $output;
}
?>