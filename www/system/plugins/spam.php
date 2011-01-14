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
Plugin Name: 	Фильтры плохих слов
Plugin URI:     http://cutenews.ru
Description: 	Фильтр указанных слов в комментариях.
Version: 		0.5
Application: 	Strawberry
Author: 		Лёха zloy и красивый
Author URI: 	http://lexa.cutenews.ru
*/

add_filter('allow-add-comment', 'spam_filter', 22);
add_filter('options', 'spam_AddToOptions', 22);
add_action('plugins', 'spam_CheckAdminOptions', 22);



function spam_filter($allow=""){
global $name, $mail, $comments, $commin_story;

    $barword = new PluginSettings('BarWord');

    if (!$barword->settings){
    	return (!empty($allow) ? true : false);
    }

	if (!empty($commin_story)) {
	    foreach($barword->settings as $bad) {
	    //if (preg_match('/'.preg_quote($bad, '/').'/i', strtolower($commin_story))) { $allow = false; }
			if (stristr(strtolower(str_replace(array("\"",".",",","?","!","  ","-","+","=","_","(",")","&","?","<",">","/","\\","|","#","@","!","~","`",";",":","^","*","[","]","{","}","~","\n","\r","\t",""), " ", $commin_story)), preg_quote($bad, '/'))) { $allow = false; }
	    }
	}

return (!empty($allow) ? true : false);
}





function spam_AddToOptions($options){
global $PHP_SELF;
	$options[] = array(
	             'name'     => t('Фильтры слов'),
	             'url'      => 'plugin=spam',
	             'category' => 'plugin',
	             );
return $options;
}





function spam_CheckAdminOptions(){
global $gplugin;
	if (!empty($gplugin) and $gplugin == 'spam') { spam_AdminOptions(); }
}





function spam_AdminOptions(){
global $PHP_SELF;

echoheader('options', t('Фильтры слов'));
    $barword = new PluginSettings('BarWord');
	$buffer = '<form method=post action="'.$PHP_SELF.'?plugin=spam">
	          <b>'.t('Ввести слово').'</b>
	          <table border="0" cellpading="0" cellspacing="0" width="379"  class="panel" cellpadding="7" >
	          <tr>
	          <td width="79" height="25">&nbsp;'.t('Слово:').'</td>
	          <td width="300" height="25"><input type="text" name="add_badword">&nbsp;&nbsp;<input type="submit" value="'.t('Запретить').'"></td>
	          </tr>
	          </table>
	          </form>
<br /><br />
    <b>'.t('Заблокированные слова').'</b>

  <table width="100%" cellspacing="2" cellpadding="2">
    <tr class="panel">
      <td><b>'.t('Слово').':</b></td>
      <td width="80" class="panel">&nbsp;<b>'.t('Действие').':</b></td>
    </tr>';
$i=0;
    if ($words = $barword->settings){
	    foreach($words as $key => $bad){
	        $i++;
	        if ($i%2 == 0){$bg = ' class="enabled"';}
	        else {$bg = ' class="disabled"';}

	        if (!empty($bad)){$buffer .= '<tr'.$bg.'><td><b>'.$bad.'</b></td><td align="center"><a href="'.$PHP_SELF.'?plugin=spam&amp;action=remove&amp;id='.$key.'"><img src="admin/images/icons/delete.png" alt="'.t('Разблокировать').'"/></a></td></tr>';}
	    }
	}

	$buffer .= '</table></table>';

	if (!empty($_POST['add_badword'])){
		$barword -> settings[] = strtolower(cheker($_POST['add_badword']));
		$barword -> save();

		$buffer = t('Слово успешно заблокировано!').'<br><br><a href="'.$PHP_SELF.'?plugin=spam">'.t('Вернуться назад').'</a>';
	}

	if (!empty($_GET['action']) and $_GET['action'] == 'remove'){
		unset($barword -> settings[intval($_GET['id'])]);
		$barword -> save();

		$buffer = t('Слово успешно разблокировано!').'<br><br><a href="'.$PHP_SELF.'?plugin=spam">'.t('Вернуться назад').'</a>';
	}

    echo $buffer;

	echofooter();
}
?>