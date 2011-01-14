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
Plugin Name: 	TrackBack
Plugin URI:     http://cutenews.ru
Description: 	Если вы не знаете, что это такое используйте <a href="http://www.yandex.ru/yandsearch?rpt=rad&text=TrackBack">Яндекс</a>.
Version: 		1.0
Application: 	Strawberry
Author: 		Лёха zloy и красивый
Author URI:     http://lexa.cutenews.ru
*/

add_action('new-advanced-options', 'trackback_AddEdit', 25);
add_action('edit-advanced-options', 'trackback_AddEdit', 25);

function trackback_AddEdit(){
global $id;

    $xfields = new XfieldsData();
    $return  = '<fieldset id="trackbacks"><legend>'.t('TrackBacks').'</legend><textarea name="ping" title="'.t('Одна строка - один УРЛ').'">'.$xfields->fetch($id, 'ping').'</textarea></fieldset>'.($xfields->fetch($id, 'pinged') ? '<fieldset><legend>'.t('Отпинговано').'</legend><textarea disabled>'.replace_news('admin', $xfields->fetch($id, 'pinged')).'</textarea></fieldset>' : '');

return $return;
}











add_action('new-save-entry', 'trackback_send', 25);
add_action('edit-save-entry', 'trackback_send', 25);

function trackback_send(){
global $id, $added_time, $member_db, $title, $category, $url, $short_story, $ping, $PHP_SELF, $config;

	include config_file;

	$sendfrom = parse_url($config['http_script_dir']);

    foreach (explode("\r\n", $ping) as $sendto){
		trackback_request($sendfrom['host'], $sendto, 'blog_name='.$config['home_title'].'&url='.straw_get_link(array('id' => $id, 'date' => $added_time, 'author' => (!empty($member['username']) ? $member['username'] : 'guest'), 'title' => $title, 'category' => $category, 'url' => $url)).'&title='.$title.'&excerpt='.replace_news('show', '[...] '.substr($short_story, 0, 100).' [...]').'&charset='.$config['charset']);
	}

	$xfields = new XfieldsData();
	$pinged  = $xfields->fetch($id, 'pinged');
	$xfields->set(replace_news('add', (!empty($ping) ? $pinged."\r\n".$ping : $pinged)), $id, 'pinged');
	$xfields->deletefield($id, 'ping');
	$xfields->save();
}















add_filter('options', 'trackback_AddToOptions', 25);

function trackback_AddToOptions($options) {
$count = 0;
	include xfields_file;

	if (!empty($array)){
	    foreach ($array as $arr){
	    
	        if (!empty($arr['trackback'])){
	            $count += count($arr['trackback']);
	        }
	        
	    }
	}

	$options[] = array(
				 'name'     => t('TrackBacks (%count)', array('count' => (!empty($count) ? $count : 0))),
				 'url'      => 'plugin=trackback',
				 'category' => 'plugin',
				 );

return $options;
}

add_action('plugins','trackback_CheckAdminOptions', 25);











function trackback_CheckAdminOptions(){
global $gplugin;
	if (!empty($gplugin) and $gplugin == 'trackback'){
		trackback_AdminOptions();
	}
}










function trackback_AdminOptions(){
global $db, $config, $PHP_SELF;

	$xfields = new XFieldsData();

	if (!empty($_POST['select_trackbacks'])){
	   foreach ($_POST['select_trackbacks'] as $time => $id){
		if (!empty($_POST['add'])){
				$trackback = $xfields->fetch($id, 'trackback');
				$trackback = $trackback[$time];
				
	            $db->sql_query("INSERT INTO ".$config['dbprefix']."comments VALUES ('".$time."', '".$trackback['blog_name']." - ".$trackback['title']."', '".$trackback['mail']."', '".$trackback['url']."', '".$trackback['host']."', '".$trackback['excerpt']."', '', '".$id."', 0, 0, 0, NULL, '')");
	            $db->sql_query("UPDATE ".$config['dbprefix']."news SET comments=comments+1 WHERE id=".$id." ");
	        }

	        $xfields->deletevalue($id, 'trackback', $time);
	        $xfields->save();
	   }
?>

<script type="text/javascript">self.location.href="<?php echo $_SERVER['REQUEST_URI']; ?>";</script>

<?php
	}

	echoheader('options', t('TrackBacks'));
?>

<form name="trackbacks" action="" method="post">

<?php
	include root_directory.'/data/xfields-data.php';
    foreach ((array)$array as $k => $v){
        if (!empty($v['trackback'])){
            foreach ($v['trackback'] as $time => $info){
?>

<h3><a href="<?php echo $info['url']; ?>" title="<?php echo $info['host']; ?>"><?php echo $info['blog_name']; ?></a></h3>
<div align="justify">
<small><?php echo langdate('d M Y H:i', $time); ?> /</small> <b><?php echo $info['title']; ?></b>
<br />
<?php echo $info['excerpt']; ?>
<input name="select_trackbacks[<?php echo $time; ?>]" type="checkbox" value="<?php echo $k; ?>">
</div>

<?php            }
        }
    }

    if (!empty($info)){
?>

<p>
<input type="submit" value="  <?php echo t('Опубликовать выбранные'); ?>  " name="add">
<input type="submit" value="  <?php echo t('Удалить выбранные'); ?>  " name="delete">
</p>
<input type="hidden" value="trackback" name="plugin">
</form>

<?php
	} else {
?>

<p><?php echo t('Посланных трэкбэков не обнаружено.'); ?></p>

<?php
	}

	echofooter();
}

#-------------------------------------------------------------------------------

function trackback_request($site="", $location, $send, $user_agent = '') {
global $config;
$arr_site = explode(':', $site);
$site = (!empty($arr_site[0]) ? $arr_site[0] : '');
$port = ((!empty($arr_site[1]) and is_numeric($arr_site[1])) ? $arr_site[1] : 80);
	$fp = fsockopen($site, $port);
	$fo = "POST ".$location." HTTP/1.0\r\n".
	      "Host: ".$site."\r\n".
	      (!empty($user_agent) ? "User-Agent: ".$user_agent."\r\n" : '').
	      "Content-Type: application/x-www-form-urlencoded; charset=".$config['charset']."\r\n".
	      "Content-Length: ".strlen($send)."\r\n\r\n".
	      $send;
	fputs($fp, $fo);
}
?>