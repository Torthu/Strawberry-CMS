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
Plugin Name:	Ѕаннеры
Plugin URI: 	http://cutenews.ru/
Description:	–еклама между постами.
Version: 		0.2
Application: 	Strawberry
Author: 		ЋЄха zloy и красивый
Author URI:     http://lexa.cutenews.ru
*/


add_filter('options', 'baners_AddToOptions');
function baners_AddToOptions($options){
	$options[] = array(
			     'name'     => t('баннеры'),
			     'url'      => 'plugin=baners',
			     'category' => 'plugin',
			     );
return $options;
}


add_action('plugins', 'baners_CheckAdminOptions');
function baners_CheckAdminOptions(){
global $gplugin;
	if (!empty($gplugin) and $gplugin == 'baners'){
		baners_AdminOptions();
	}
}


add_action('head', 'baners_make_array');
function baners_make_array(){
global $baners_array;
	$baners = new PluginSettings('Baners');
	$baners_array = $baners->settings;
}












function baners_AdminOptions(){
global $categories, $PHP_SELF;
	$baners = new PluginSettings('Baners');
	$handle = opendir(templates_directory);
	
	while ($file = readdir($handle)){
	    if ($file != '.' and $file != '..' and is_dir(templates_directory.'/'.$file)){
	        $templates[$file] = $file;
	    }
	}
	
	if (!empty($_POST)){
	
		$_POST['posts'] = chicken_dick($_POST['posts'], ',');

		if (empty($_POST['template'][0])){
			$_POST['template']="";
		}

		if (empty($_POST['category'][0])){
			$_POST['category']=0;
		}

	    if (!empty($_POST['baner']) and is_numeric($_POST['baner'])) {
	    	$baners->settings[intval($_POST['baner'])] = $_POST;
	    } elseif (!count($baners->settings)) {
	    	$baners->settings[1] = $_POST;
	    } else {
	    	$baners->settings[] = $_POST;
	    }

	    $baners->save();

	}

	if (!empty($_GET['remove']) and is_numeric($_GET['remove'])){
		$baners->settings[intval($_GET['remove'])] = null;
		$baners->save();
		header('Location: '.$PHP_SELF.'?plugin=baners');
	}

	if (!empty($_GET['baner']) and is_numeric($_GET['baner']) and $baners->settings[$_GET['baner']]){
		$name     = $baners->settings[$_GET['baner']]['name'];
		$posts    = $baners->settings[$_GET['baner']]['posts'];
		$category = $baners->settings[$_GET['baner']]['category'];
		$template = $baners->settings[$_GET['baner']]['template'];
		$text     = $baners->settings[$_GET['baner']]['text'];
	} else {
		$name     = "";
		$posts    = "";
		$category = "";
		$template = "";
		$text   = "";
	}

	echoheader('options', t('баннеры'));
?>
<form action="" method="post">
<table align="center" border="0" cellpadding="5" cellspacing="1" width="100%">
<tr>
 <td colspan="3" class="panel" height="33"><b><?php echo t('ƒанный плагин позволит показывать рекламу между постами в выбранных категори€х'); ?></b></td>
</tr>
<tr>


 
 <?php 
if ($baners->settings){ 
echo "<td rowspan=\"5\" align=\"left\" width=\"25%\" valign=\"top\">";
 echo "<b>".t('»меющиес€ баннеры')."</b>"; 
  foreach ($baners->settings as $k => $row){ 
    if (!empty($row)){ 
?>
<li><a href="<?php echo $PHP_SELF; ?>?plugin=baners&amp;baner=<?php echo $k; ?>"><?php echo $row['name']; ?></a>
<small>(<a href="<?php echo $PHP_SELF; ?>?plugin=baners&amp;remove=<?php echo $k; ?>"><?php echo t('удалить'); ?></a>)</small><hr /></li>
<?php 
    } 
  } 
echo "</td>";
}
?>
 


                <td height="14" colspan="2" valign="top">
                
                <?php echo t('Ќазвание'); ?><br />
                <input name="name" type="text" value="<?php echo $name; ?>"><br />
                <?php echo t(' аким постом (или постами - через зап€тую) будет выводитьс€ баннер'); ?><br />
                <input name="posts" type="text" value="<?php echo $posts; ?>"><br />
                
                </td>
	</tr>

	<tr>
		<td height="14" width="50%"><?php echo t(' атегории в которой будет отображатьс€ баннер'); ?></td>
		<td height="14" width="50%"><?php echo t('Ўаблоны дл€ которых будет отображатьс€ баннер'); ?></td>
	</tr>
	
	<tr>
		<td height="14"><select name="category[]" size="7" multiple="multiple" style="width:200px;height:100px;">
		<option value="">...</option>
		<?php echo category_get_tree('&nbsp;', '<option value="{id}"[php]baners_category_selected({id}, '.($category ? join(',', $category) : 0).')[/php]>{prefix}{name}</option>'); ?>
		</select></td>
		<td height="14"><select name="template[]" size="7" multiple="multiple" style="width:200px;height:100px;">
		<option value="">...</option>
		<?php foreach ($templates as $k => $v){ if($v != "system") { ?>
		<option value="<?php echo $k; ?>"<?php echo (@in_array($k, $template) ? ' selected' : ''); ?>><?php echo $v; ?></option>
		<?php }} ?>
		</select></td>
	</tr>
	<tr>
		<td height="14" colspan="2">
		<?php echo t(' од баннера'); ?><br />
		<textarea name="text" style="width:100%;height:200px;"><?php echo $text; ?></textarea>
		</td>
	</tr>
	<tr>
		<td align="right" colspan="2" valign="top">
		<input type="submit" value="<?php echo (!empty($_GET['baner']) ? t('–едактировать') : t('—оздать')); ?>">
		<input name="baner" type="hidden" value="<?php echo $_GET['baner']; ?>">
		</td>
	</tr>
</table>
</form>

<?php
echo on_page();
echofooter();
}












add_filter('news-show-generic', 'baner_after_news');
function baner_after_news($btpl){
global $baners_array, $tpl, $cat, $out_put_news, $str_up, $it_is_feed;
static $i;

    if (!empty($baners_array) and empty($str_up) and empty($it_is_feed)){
$i++;    
	    foreach ($baners_array as $brow){
	      if (!empty($brow['posts'])) {

$row_posts = array();
$row_category = array();
$cat_id = array();

	        if (!empty($brow['posts']) and strstr($brow['posts'],",")) {
	          $row_posts = explode(',', $brow['posts']);
	        } elseif (!empty($brow['posts'])) {
	          $row_posts[] = $brow['posts'];
	        }

	        if (!empty($brow['category']) and count($brow['category'])>1) {
	          $row_category = $brow['category'];
	        } elseif (!empty($brow['category'])) {
	          $row_category[] = $brow['category'][0];
	        }

	        if (!empty($cat['id']) and count($cat['id'])>1) {
	          $cat_id = $cat['id'];
	        } elseif (!empty($cat['id'])) {
	          $cat_id[] = $cat['id'][0];
	        }


/*
$out_put_news .= "
<br>= номер поста (".$i.") 
<br>= номера постов (".@implode(",",$row_posts).") совпал (".in_array($i, $row_posts).") 
<br>= категории новости (".@implode(",",$cat_id).")
<br>= категории баннера  (".@implode(",",$row_category).")
<br>= категории совпали (".in_array($cat_id, $row_category).") // надо оператор поиска совпадений в двух массивах.
<hr />";
*/


	        if (in_array($i, $row_posts) and (empty($brow['template']) or (!empty($brow['template']) and in_array($tpl['template'], $brow['template'])))) {
	            
	            if (!empty($row_category) and !empty($cat_id) and array_intersect($cat_id, $row_category)) {
	                    $out_put_news .= $brow['text'];
	            } else {
	                    $out_put_news .= $brow['text'];
	            }
	            
	        }
	        
	      }
	    }
    }
return $btpl;
}



#-------------------------------------------------------------------------------



function baners_category_selected($id, $select){
	if (@in_array($id, explode(',', $select))){
		return ' selected';
	}
}


?>