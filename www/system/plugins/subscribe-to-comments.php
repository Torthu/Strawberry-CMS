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
Plugin Name: 	Подписка на комментарии
Plugin URI:     http://cutenews.ru
Description: 	Отправляет комментария на почту комментатору (человеку оставившему комментарий и подписавшемуся на получение всех дальнейших). Используйте <code>&lt;input type="checkbox" id="sendcomments" name="sendcomments" value="on"&gt;</code> в шаблоне "Форма добавления комментария".
Version: 		1.0
Application: 	Strawberry
Author: 		Лёха zloy и красивый
Author URI:     http://lexa.cutenews.ru
*/

add_filter('allow-add-comment', 'comm_spy', 23);

add_filter('options', 'comm_spy_AddToOptions', 23);
add_action('plugins','comm_spy_CheckAdminOptions', 23);

function comm_spy($output){
global $id, $name, $mail, $commin_story, $config, $sendcomments, $mod;
    $link = parse_url($config['http_home_url']);
    $link = $link['scheme'].'://'.$link['host'].$_SERVER['REQUEST_URI']."-".$mod;

	if (!empty($output)){
		$xfields = new XfieldsData();

    	if (preg_match("/^[\.A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $mail) and !@preg_match("/$mail/i", $xfields->fetch($id, 'comm_spy')) and !@preg_match("/$mail/i", $config['admin_mail']) and $sendcomments == 'on'){
    		$xfields->set($xfields->fetch($id, 'comm_spy').($xfields->fetch($id, 'comm_spy') ? ', ' : '').$name.' <'.$mail.'>', $id, 'comm_spy');
    	   	$xfields->save();
		}

		$tpl = new PluginSettings('CommSpy');

	    if (!$tpl->settings['subj']){
	       	$tpl->settings['subj'] = t('На сайте {page-title} новый комментарий от {author}');
	       	$tpl->save();
	    }

	    if (!$tpl->settings['body']){
	       	$tpl->settings['body'] = t('Здравствуйте.{nl}{nl}Вы подписались на получение новых комментариев с сайта {page-title}. Кто-то оставил там новый комментарий.{nl}{nl}{nl}Комментарий:{nl}----------------{nl}{comment}{nl}{nl}{nl}URL: {link}');
	       	$tpl->save();
	    }

    	$subj = replace_news('admin', $tpl->settings['subj']);
		$subj = str_replace('{page-title}', $config['home_title'], $subj);
		$subj = str_replace('{page-link}', $config['http_home_url'], $subj);
		$subj = str_replace('{link}', $link, $subj);
		$subj = str_replace('{author}', $name, $subj);
		$subj = str_replace('{mail}', $mail, $subj);
		$subj = str_replace('{mails}', $xfields->fetch($id, 'comm_spy'), $subj);

		$body = replace_news('admin', $tpl->settings['body']);
		$body = str_replace('{page-title}', $config['home_title'], $body);
		$body = str_replace('{page-link}', $config['http_home_url'], $body);
		$body = str_replace('{link}', $link, $body);
		$body = str_replace('{author}', $name, $body);
		$body = str_replace('{mail}', $mail, $body);
		$body = str_replace('{comment}', $commin_story, $body);
		$body = str_replace('{mails}', $xfields->fetch($id, 'comm_spy'), $body);


		if ($xfields->fetch($id, 'comm_spy')){
			straw_mail($xfields->fetch($id, 'comm_spy'), $subj, $body);
		}

		return true;
	}
}







function comm_spy_AddToOptions($options) {

	$options[] = array(
	             'name'     => t('Шаблон уведомления о комментарии'),
	             'url'      => 'plugin=comm_spy',
	             'category' => 'plugin',
	             );

return $options;
}




function comm_spy_CheckAdminOptions() {
global $gplugin;
	if (!empty($gplugin) and $gplugin == 'comm_spy'){
		comm_spy_AdminOptions();
	}
}



function comm_spy_AdminOptions(){
global $action;
    $tpl = new PluginSettings('CommSpy');

    if (!$tpl->settings['subj']){
       	$tpl->settings['subj'] = t('На сайте {page-title} новый комментарий от {author}');
       	$tpl->save();
    }
    if (!$tpl->settings['body']){
    	$tpl->settings['body'] = t('Здравствуйте.{nl}{nl}Вы подписались на получение новых комментариев с сайта {page-title}. Кто-то оставил там новый комментарий.{nl}{nl}{nl}Комментарий:{nl}----------------{nl}{comment}{nl}{nl}{nl}URL: {link}');
       	$tpl->save();
    }

    if (!empty($action) and $action == 'save'){
    	$tpl->settings['subj'] = replace_news('add', $_POST['tpl_subj']);
    	$tpl->settings['body'] = replace_news('add', $_POST['tpl_body']);
    	$tpl->save();
    	msg('info', t('Изменения сохранены'), t('Изменения были успешно сохранены'), 'javascript:history.go(-1)');
    }

    $subj = $tpl->settings['subj'];
    $body = $tpl->settings['body'];

    echoheader('options', t('Шаблон уведомления о комментарии'));
?>

<form method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="2" class="panel">
<tr>
    <td width="85"><b>{page-title}</b></td>
    <td>&#151; <?php echo t('название сайта'); ?></td>
</tr><tr>
    <td><b>{page-link}</b></td>
    <td>&#151; <?php echo t('ссылка на сайт'); ?></td>
</tr><tr>
    <td><b>{link}</b></td>
    <td>&#151; <?php echo t('ссылка на новость'); ?></td>
</tr><tr>
    <td><b>{author}</b></td>
    <td>&#151; <?php echo t('автор комментария'); ?></td>
</tr><tr>
    <td><b>{mail}</b></td>
    <td>&#151; <?php echo t('e-mail автора комментария'); ?></td>
</tr><tr>
    <td><b>{comment}</b></td>
    <td>&#151; <?php echo t('комментарий'); ?></td>
</tr><tr>
    <td><b>{mails}</b></td>
    <td>&#151; <?php echo t('e-mail подписавшихся'); ?></td>
</tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td>
<p><?php echo t('Заголовок:'); ?><br><input type="text" name="tpl_subj" value="<?php echo htmlspecialchars(replace_news('admin', $subj)); ?>" style="width:100%;">
<p><?php echo t('Шаблон текста сообщения:'); ?><br><textarea name="tpl_body" style="width:100%; height:250px;"><?php echo htmlspecialchars(replace_news('admin', $body)); ?></textarea>
</td></tr>
<tr><td align="right"><input type="submit" name="submit" value=" Сохранить "></td></tr>
</table>

<input type="hidden" name="plugin" value="comm_spy">
<input type="hidden" name="action" value="save">
</form>

<?php
	echofooter();
}
?>