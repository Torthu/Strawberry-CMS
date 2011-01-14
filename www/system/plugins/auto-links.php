<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../index.php");
exit;
}

/*
Plugin Name:    Слова - АвтоСсылки
Plugin URI:        http://cutenews.ru
Description:    Из слов в тексте создает активные ссылки. Заменяемые слова берутся из списка в настройках плагина.
Version:           0.2
Application:    Strawberry
Author:            FI-DD
Author URI:        http://english.cutenews.ru/forum/profile.php?mode=viewprofile&u=2
*/


add_filter('news-entry-content', 'link_filterer');
add_filter('options', 'link_AddToOptions');
add_action('plugins', 'link_CheckAdminOptions');

function link_filter($output){
    $barword = new PluginSettings('linkwords');
if (!empty($barword)) {
        foreach($barword->settings as $bad) {
            list($link, $replacement) = $bad;
            //$find[] = "#([\ ]+| |\{nl\})".$link."( |\{nl\}+[\ ])#i";
            $find[] = "#([\\r\\n\\t.!?—:;\<\>,+=\"'&\#\$^%*\(\)\«\»\{\}@/\\ ])".$link."([\\r\\n\\t.!?—:;\<\>,+=\"'&\#\$^%*\(\)\«\»\{\}@/\\ ])#i";
            //$find[] = "#([^A-Za-z0-9_])".$link."([^A-Za-z0-9_])#i";
            $replace[] = "\\1<a href=\"".$replacement."\">".$link."</a>\\2";
        }
        $output = preg_replace($find, $replace, $output);
}
return $output;
}





function link_filterer($output) {
if (!is_array($output)) {
    $barword = (new PluginSettings('linkwords'));

if (!empty($barword)) {

		foreach($barword->settings as $bad){
		$link[] = $bad[0];
		$replacement[] = $bad[1];
		}
		
		preg_match_all("#<[^>]*>#i", $output, $tags);
		if(!empty($tags)) array_unique($tags);

		$taglist = array();
		$k = 0;
		
		if(!empty($tags)) {
		foreach($tags[0] as $i) {
			$k++;
			$taglist[$k] = $i;
			$output = str_ireplace($i, "<".$k.">", $output);
		}
		}

		foreach($link as $lk => $li) if (!is_numeric($li)) { $output=preg_replace("#([\\r\\n\\t.!?—:;\<\>,+=\"'&\#\$^%*\(\)\«\»\{\}@/\\ ])".$li."([\\r\\n\\t.!?—:;\<\>,+=\"'&\#\$^%*\(\)\«\»\{\}@/\\ ])#i", "\\1<a href=\"".$replacement[$lk]."\">".$li."</a>\\2", $output); }
		foreach($taglist as $k => $i) { $output = str_ireplace("<" . $k . ">", $i, $output); }

	}
	return $output;
}
}












function link_AddToOptions($options){
global $PHP_SELF;
    $options[] = array(
        'name'        => 'LinkWords',
        'url'        => 'plugin=linkwords',
        'access'    => '1',
        'category' => 'plugin',
    );
return $options;
}



function link_CheckAdminOptions(){
global $gplugin;
    if (!empty($gplugin) and $gplugin == 'linkwords'){link_AdminOptions();}
}



function link_AdminOptions(){
global $PHP_SELF, $action;

    echoheader('options', 'Linkwords');

    $barword = new PluginSettings('linkwords');
    $buffer = '
              <form method="post" action="'.$PHP_SELF.'?plugin=linkwords">
              <b>'.t('Добавить слово').'</b>
              <table border="0" cellpading="2" cellspacing="2" width="500" class="panel">
              <tr>
              <td valign="top" width="50%">'.t('Слово').':<br /><input type="text" name="add_badword"></td>
              <td valign="top" width="50%">'.t('Замена').':<br /><input type="text" name="add_replacement"><br />'.t('например').': http://www.domain.com</td>
              </tr>
              <tr><td colspan="2"><input type="submit" value="'.t('Добавить в список').'"></td></tr>
              </table>
              </form>

<br />
<b>'.t('Список слов').'</b>

    
  <table border="0" cellpading="2" cellspacing="2" width="100%">
    <tr class="panel">
      <td width="45%"><b>'.t('Слово').'</b></td>
      <td width="45%"><b>'.t('Замена').'</b></td>
      <td width="10%"><b>'.t('Действие').'</b></td>
    </tr>';
$i=0;
    if ($words = $barword->settings){
        foreach($words as $key => $bad){
            list($link, $replacement) = $bad;
                $i++;
                if ($i%2 == 0){ $bg = ' class="enabled"'; } else { $bg = ' class="disabled"'; }
            if (!empty($bad)){$buffer .= '<tr'.$bg.'><td>'.$link.'</td><td>'.$replacement.'</td><td align="center"><a href="'.$PHP_SELF.'?plugin=linkwords&amp;action=remove&amp;rbwid='.$key.'"><img width="16" src="admin/images/icons/delete.png" title="'.t('Удалить').'" /></a></td></tr>';}
        }
    }

    $buffer .= '</table>';
    
    
$rpw = !empty($_POST['add_badword']) ? cheker($_POST['add_badword']) : "";

    if (!empty($rpw)){
        $barword -> settings[] = array($rpw, cheker($_POST['add_replacement']));
        $barword -> save();
        $buffer = t('Слово добавлено!').'<br><br><a href="'.$PHP_SELF.'?plugin=linkwords">'.t('Назад к списку').'</a>';
    }

    if (!empty($action) and $action == 'remove'){
        unset($barword -> settings[$_GET['rbwid']]);
        $barword -> save();
        $buffer = t('Это слово было удалено из списка!').'<br><br><a href="'.$PHP_SELF.'?plugin=linkwords">'.t('Назад к списку').'</a>';
    }

    echo $buffer;

    echofooter();
}
?>