<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../../index.php");
exit;
}

add_action('head', 'ddb_ob_start', 1);

function ddb_ob_start(){
    ob_start();
}

add_filter('options', 'ddb_AddToOptions');

function ddb_AddToOptions($options) {

	$options[] = array(
	             'name'     => t('Drag\'n\'Drop Blocks'),
	             'url'      => 'plugin=ddb',
	             'category' => 'templates'
	       		 );

return $options;
}

add_action('plugins', 'ddb_CheckAdminOptions');

function ddb_CheckAdminOptions(){

	if ($_GET['plugin'] == 'ddb'){
		ddb_AdminOptions();
	}
}

function ddb_AdminOptions(){
global $PHP_SELF, $QUERY_STRING;

    $self = $PHP_SELF.'?plugin=ddb'.straw_query_string($QUERY_STRING, array('action', 'block', 'category', 'plugin'));
    $arr  = explode('/', $_GET['block'].$_GET['category']);
    $name = end($arr);
    unset($arr[(count($arr) - 1)]);
    $cat  = join('/', $arr);

    ob_start();
    list_directory(blocks_directory, 0, $self);
	$list_directory = ob_get_clean();

    ob_start();
    list_categories(blocks_directory, 0, $cat);
	$list_categories = ob_get_clean();

	if ($_POST){
		$self .= '&action='.$_POST['action'];

		if ($_POST['action'] != 'category'){
	        if ($_POST['remove']){
	            @unlink(blocks_directory.'/'.$_POST['block'].'.block');
	            header('Location: '.$self);
	        }

	        if ($_POST['save']){
	            if ($_POST['block'] and $_POST['name']){
	                if ($_POST['block'] != ($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name']){
	                    @rename(blocks_directory.'/'.$_POST['block'].'.block', blocks_directory.'/'.($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name'].'.block');
	                }

	                file_write(blocks_directory.'/'.($_POST['name'] ? ($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name'] : $_POST['block']).'.block', $_POST['content']);
	                @chmod(blocks_directory.'/'.($_POST['name'] ? ($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name'] : $_POST['block']).'.block', 0777);
	            }

	            if (!$_POST['block']){
	                $_POST['name'] = ($_POST['name'] ? $_POST['name'] : 'noname'.count($blocks));
	                file_write(blocks_directory.'/'.($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name'].'.block', $_POST['content']);
	                @chmod(blocks_directory.'/'.($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name'].'.block', 0777);
	            }

	            header('Location: '.$self.'&block='.($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name']);
	        }
		} else {
            if ($_POST['remove']){
                remove_directory(blocks_directory.'/'.$_POST['category']);
                @rmdir(blocks_directory.'/'.$_POST['category']);
                header('Location: '.$self);
            }

			if ($_POST['name']){
                if ($_POST['save']){
                	if ($_POST['category'] and $_POST['name'] and $_POST['category'] != ($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name']){
						@rename(blocks_directory.'/'.$_POST['category'], blocks_directory.'/'.($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name']);
					}

                    if ($_POST['cat']){
                        @mkdir(blocks_directory.'/'.($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name'], 0777);
                        @chmod(blocks_directory.'/'.($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name'], 0777);
                    } else {
                        @mkdir(blocks_directory.'/'.($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name'], 0777);
                        @chmod(blocks_directory.'/'.($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name'], 0777);
                    }

					header('Location: '.$self.'&category='.($_POST['cat'] ? $_POST['cat'].'/' : '').$_POST['name']);
				}
			}
		}
	}

	echoheader('options', 'Drag\'n\'Drop Blocks');
?>

<table border="0" style="float: left;">
<tr <?php echo straw_that(); ?>>
<td style="padding: 5px;"><b><a href="<?php echo $self; ?>&action=category"><?php echo t('Новая категория'); ?></a></b></td>
</tr>
<tr <?php echo straw_that(); ?>>
<td style="padding: 5px;"><b><a href="<?php echo $self; ?>"><?php echo t('Новый блок'); ?></a></b></td>
</tr>
<?php echo $list_directory; ?>
</table>


<form action="<?php echo $self; ?>" method="post">
<table border="0" width="80%">
<tr>
<td>
<input name="name" type="text" value="<?php echo $name; ?>">
<?php if ($list_categories){ ?>
<select size="1" name="cat">
  <option value="">...</option>
  <?php echo $list_categories; ?>
</select>
<?php } ?>
<img border="0" src="admin/images/help_small.gif" align="absmiddle">&nbsp;<a onClick="javascript:Help('ddb')" href="#"><?php echo t('Что такое Drag\'n\'Drop Blocks и с чем их едят?'); ?></a>
<br />
<?php if ($_GET['action'] != 'category'){ ?>
<?php echo t('PHP и HTML разрешены. Форматирования, как в новостях нет.'); ?><br />
<textarea name="content" style="height: 300;"><?php echo htmlspecialchars(file_read(blocks_directory.'/'.$_GET['block'].'.block')); ?></textarea>
<input name="block" type="hidden" value="<?php echo $_GET['block'].$_GET['category']; ?>">
<?php } else { ?>
<?php echo t('Категории это обычные папки.'); ?><br />
<input name="category" type="hidden" value="<?php echo $_GET['block'].$_GET['category']; ?>">
<?php } ?>
<input type="submit" name="save" value="<?php echo t('Сохранить'); ?>">
<?php if ($_GET['block'] or $_GET['category']){ ?>
<input type="submit" name="remove" value="<?php echo t('Удалить'); ?>">
<?php } ?>
</td>
</tr>
</table>
<input name="action" type="hidden" value="<?php echo $_GET['action']; ?>">
</form>

<script>
function complete(request){
    if (request.status == 200){
        $('result').innerHTML = request.responseText;
    } else {
        failure(request);
    }
}

function failure(request){
    $('result').innerHTML = '';
}

function call_ajax(that){
	new Ajax.Updater(
	    {success: 'result'},
	    '<?php echo $_SERVER['PHP_SELF']; ?>?plugin=ajax&call=ddb',
	    {
	        insertion: Insertion.Top,
	        onComplete: function(request){complete(request)},
	        onFailure: function(request){failure(request)},
	        parameters: Form.serialize(that),
	        evalScripts: true
	    }
	);
}

</script>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td>

<form onsubmit="call_ajax(this);return false;" style="clear: right;">
<table border="0" cellspacing="0" cellpadding="4" style="float: left;clear: both;">
<?php foreach (run_filters('constructor-variables', array()) as $k => $v){ ?>
<tr <?php echo straw_that(); ?>>
<td>$<?php echo $k; ?>
<td>=
<td><?php echo $v[1]; ?>
<?php } ?>
<tr>
<td colspan="3"><input type="submit" value=" <?php echo t('Сгенерировать'); ?>">
<tr>
<td colspan="3"><div id="result"></div>
</table>
</form>

<table border="0" cellspacing="0" cellpadding="4">
<?php foreach (run_filters('constructor-functions', array()) as $k => $v){ ?>
<tr <?php echo straw_that(); ?>>
<td> &lt;?=<?php echo $k; ?>(); ?&gt;<br /><small><?php echo function_help($k, t('помощь по работе функции')); ?></small>
<?php } ?>
</table>

</table>

<?
	echofooter();
}

add_action('plugins', 'ddb_call_ajax');

function ddb_call_ajax(){
global $config;

	header('Content-type: text/html; charset='.$config['charset']);

	if ($_POST){
		$variables = run_filters('constructor-variables', array());
		$result[] = '<?';

	    foreach ($_POST as $k => $v){
	        if ($v[0] and $k != 'static'){
	            if ($_POST['static']){
	                $var = 'static[\''.$k.'\']';
	            } else {
	            	$var = $k;
	            }

	            if ($variables[$k][0] == 'array'){
	                $result[] = '$'.$var.' = array(\''.$v[0].'\', \''.$v[1].'\');';
	            } elseif ($variables[$k][0] == 'string'){
	                $result[] = '$'.$var.' = \''.(is_array($v) ? join(', ', $v) : $v).'\';';
	            } elseif ($variables[$k][0] == 'bool'){
	                $result[] = '$'.$var.' = '.($v ? 'true' : 'false').';';
	            } elseif ($variables[$k][0] == 'int'){
	                $result[] = '$'.$var.' = '.$v.';';
	            }
	        }
	    }

	    $result[] = 'include rootpath.\'/show_news.php\';';
	    $result[] = '?>';

	    echo '<textarea>'.join("\r\n", $result).'</textarea>';
	}
}

add_action('head', 'ddb_save_positions');

function ddb_save_positions(){

	if (straw_get_rights('ddb', 'write') and $_GET['ddb'] == 'save'){
		$blocks = new PluginSettings('Blocks');
	    $blocks->settings = array_merge($blocks->settings, $_COOKIE['block']);
	    $blocks->save();
	    header('Location: '.str_replace('ddb=save', 'ddb=edit', $_SERVER['REQUEST_URI']));
	}
}

add_filter('help-sections', 'ddb_help');

function ddb_help($help_sections){
global $config;

	$help_sections['ddb'] = t('<h1>Drag\'n\'Drop Blocks</h1><p><a href="%url">см. тутачки</a></p>', array('url' => $config['http_script_dir'].'/docs/additions.html#ddb'));

return $help_sections;
}

add_filter('constructor-functions', 'default_constructor_functions', 1);

function default_constructor_functions($functions){

return $functions;
}

add_filter('constructor-variables', 'default_constructor_variables', 1);

function default_constructor_variables($variables){
global $sql, $users;

	$sort[''] = t('- по умолчанию -');

	foreach ($sql->describe(array('table' => 'news')) as $k => $row){
	    if ($k != 'primary' and $k != 'sticky'){
	        $sort[$k] = /*'('.$row['type'].') '.*/$k;
	    }
	}

	$user[''] = t('- все -');

	foreach ($users as $row){
	    $user[$row['username']] = $row['name'];
	}

	$template[''] = t('- по умолчанию -');

	$handle = opendir(templates_directory);
	while ($file = readdir($handle)){
	    if (is_file(templates_directory.'/'.$file.'/active.tpl')){
	        $template[$file] = $file;
	    }
	}

	$link[''] = t('- по умолчанию -');

	foreach (parse_ini_file(rufus_file, true) as $k => $v){
	    $link[$k] = $k;
	}

	$variables['number'] = array('int', '<input name="number" type="text" value="" style="width: 32px;">');
	$variables['skip'] = array('int', '<input name="skip" type="text" value="" style="width: 32px;">');
	$variables['sort'] = array('array', makeDropDown($sort, 'sort[0]').' '.makeDropDown(array('DESC' => 'DESC', 'ASC' => 'ASC'), 'sort[1]'));
	$variables['template'] = array('string', makeDropDown($template, 'template'));
	$variables['link'] = array('string', makeDropDown($link, 'link'));

	ob_start();
?>


<select name="category[]" size="5" multiple="multiple">
<option value=""><?php echo t('- все -'); ?></option>
<option value="none"><?php echo t('- новости без категории -'); ?></option>
<?php echo category_get_tree('-&nbsp;', '<option value="{id}">{prefix}{name}</option>'); ?>
</select>

<?
	$variables['category'] = array('string', ob_get_clean());
	$variables['user'] = array('string', makeDropDown($user, 'user'));
	$variables['year'] = array('int', '<input name="year" type="text" value="" maxlength="4" style="width: 32px;">');
	$variables['month'] = array('int', '<input name="month" type="text" value="" maxlength="2" style="width: 32px;">');
	$variables['day'] = array('int', '<input name="day" type="text" value="" maxlength="2" style="width: 32px;">');
	$variables['static'] = array('bool', makeDropDown(array(t('Нет'), t('Да')), 'static'));


return $variables;
}

#-------------------------------------------------------------------------------

function ddb_get($output){
global $config, $dragdropblocks, $config;

    /*
	$handle = opendir(blocks_directory);
	while ($file = readdir($handle)){
	    if (substr($file, -5) == 'block'){
	        $block[substr($file, 0, -6)] = data_directory.'/blocks/'.$file;
	    }
	}
	*/

	dragdropblocks(blocks_directory);
	$block = $dragdropblocks['block'];

	preg_match_all('/\<\!--block:(.*?)--\>/i', $output, $matches);
	$matches[1] = array_merge(array('blocks'), $matches[1]);

	ob_start();
?>

<script type="text/javascript" src="<?php echo $config['http_script_dir']; ?>/skins/cute.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo $config['http_script_dir']; ?>/plugins/ddb/core.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo $config['http_script_dir']; ?>/plugins/ddb/events.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo $config['http_script_dir']; ?>/plugins/ddb/tool-man/css.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo $config['http_script_dir']; ?>/plugins/ddb/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo $config['http_script_dir']; ?>/plugins/ddb/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo $config['http_script_dir']; ?>/plugins/ddb/dragdrop.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo $config['http_script_dir']; ?>/plugins/ddb/dragsort.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo $config['http_script_dir']; ?>/plugins/ddb/cookies.js"></script>
<script>
var junkdrawer = ToolMan.junkdrawer();

window.onload = function(){
<?php foreach ($matches[1] as $position){ ?>
	var item = document.getElementById('<?php echo $position; ?>');
	if (item){
		DragDrop.makeListContainer(item);
		//item.onDragOver = function(){this.style['background'] = '#eef';};
		//item.onDragOut = function(){this.style['background'] = 'none';};
	}
<?php } ?>
};


function saveOrder(){
<?php foreach ($matches[1] as $position){ ?>
	var item = document.getElementById('<?php echo $position; ?>');
	if (item){
		ToolMan.cookies().set('block[<?php echo $position; ?>]', junkdrawer.serializeList(item), 1);
	}
<?php } ?>
	window.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?ddb=save';
}
</script>

<style>
<!--
dfn {
	position: relative;
	cursor: move;
	font-style: normal;
}

.block {
	border: solid 2px red;
	padding: 3px;
}

.blockheader {
	color: red;
	font-size: 10px;
	font-weight: bold;
}

.dragblock {
	text-align: left;
	color: blue;
	font-size: 10px;
	font-weight: bold;
}
-->
</style>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="block_select">
<tr>
<td>
<div id="blocks">

<?
	//echodragdropblocks(blocks_directory, 0, $config['http_script_dir']);

	foreach ($block as $k => $v){
	    if ($v){
	        echo '<dfn itemID="'.$k.'"><div class="dragblock">['.$k.']';
	        echo  '['.t('<a href="%url">редактировать</a>', array('url' => $config['http_script_dir'].'?plugin=ddb&action=block&block='.$k)).']';
	        echo '</div></dfn>';
	    }
	}
?>

</div>
<input type="button" value="<?php echo t('Сохранить'); ?>" onclick="saveOrder()">
</td>
</tr>
</table>
<br />

<?
	$blocks_header = ob_get_clean();

    if (straw_get_rights('ddb', 'read') and $_GET['ddb'] == 'edit'){
    	$output = $blocks_header.$output;
    }

return $output;
}

function list_directory($dir = '.', $level = 0, $self = ''){

	$level++;

	$handle = opendir($dir);
	while ($file = readdir($handle)){
		if ($file != '.' and $file != '..' and is_dir($dir.'/'.$file)){
			$files[] = $file;
        }
	}

	$handle = opendir($dir);
	while ($file = readdir($handle)){
		if ($file != '.' and $file != '..' and is_file($dir.'/'.$file)){
			$files[] = $file;
        }
	}

    if ($files){
    	$filename = preg_replace('/^'.preg_quote(blocks_directory, '/').'/', '', $dir);

	    foreach ($files as $k => $v){
	    	echo '<tr><td>';
	    	echo ($level > 1 ? str_repeat('-&nbsp;', ($level - 1)) : '');

	    	if (is_dir($dir.'/'.$v)){
	           $menu = '<b><a href="'.$self.'&action=category&category='.chicken_dick($filename.'/'.$v).'">'.$v.'</a></b><br />';
	           echo ($_GET['category'] == chicken_dick($filename.'/'.$v) ? strip_tags($menu, '<b>, <br>') : $menu);
	            list_directory($dir.'/'.$v, $level, $self);
	        } else {
	        	$menu = '<a href="'.$self.'&action=block&block='.chicken_dick($filename.'/'.substr($v, 0, -6)).'">'.substr($v, 0, -6).'</a><br />';
	        	echo ($_GET['block'] == chicken_dick($filename.'/'.substr($v, 0, -6)) ? strip_tags($menu, '<b>, <br>') : $menu);
	        }

	        echo '</td></tr>';
	    }
	}
}

function list_categories($dir = '.', $level = 0, $category = ''){

	$level++;

	$handle = opendir($dir);
	while ($file = readdir($handle)){
		if ($file != '.' and $file != '..' and is_dir($dir.'/'.$file)){
			$filename = preg_replace('/^'.preg_quote(blocks_directory, '/').'/', '', $dir);
			$filename = chicken_dick($filename.'/'.$file);

			echo '<option value="'.$filename.'"'.($filename == $category ? ' selected' : '').'>'.($level > 1 ? str_repeat('-&nbsp;', ($level - 1)) : '').$file.'</option>';
			list_categories($dir.'/'.$file, $level, $category);
        }
	}
}

function remove_directory($dir = '.'){

	$handle = opendir($dir);
	while ($file = readdir($handle)){
		if ($file != '.' and $file != '..'){
	        if (is_dir($dir.'/'.$file)){
	            @rmdir($dir.'/'.$file);
	            remove_directory($dir.'/'.$file);
	        } else {
	            @unlink($dir.'/'.$file);
	        }
        }
	}
}

function dragdropblocks($dir = '.'){
global $dragdropblocks;

    $handle = opendir($dir);
    while ($file = readdir($handle)){
        if ($file != '.' and $file != '..'){
            if (is_dir($dir.'/'.$file)){
                dragdropblocks($dir.'/'.$file);
            } elseif (substr($file, -5) == 'block'){
                $filename = preg_replace('/^'.preg_quote(blocks_directory, '/').'/', '', $dir);
                $dragdropblocks['block'][chicken_dick($filename.'/'.substr($file, 0, -6))] = $dir.'/'.$file;
            }
        }
    }
}

function echodragdropblocks($dir = '.', $level = 0, $self = ''){

	$level++;

	$handle = opendir($dir);
	while ($file = readdir($handle)){
		if ($file != '.' and $file != '..' and is_dir($dir.'/'.$file)){
			$files[] = $file;
        }
	}

	$handle = opendir($dir);
	while ($file = readdir($handle)){
		if ($file != '.' and $file != '..' and is_file($dir.'/'.$file)){
			$files[] = $file;
        }
	}

    if ($files){
    	$filename = preg_replace('/^'.preg_quote(blocks_directory, '/').'/', '', $dir);

	    foreach ($files as $k => $v){
	    	//echo ($level > 1 ? str_repeat('-&nbsp;', ($level - 1)) : '');

	    	if (is_dir($dir.'/'.$v)){
	    		echo '<div class="dragblock" style="margin-left: '.(($level - 1) * 5).';">';
	    		echo '['.makePlusMinus(chicken_dick($filename.'/'.$v)).']';
	    		echo '['.$v.']['.t('<a href="%url">редактировать</a>', array('url' => $self.'/?plugin=ddb&action=category&category='.chicken_dick($filename.'/'.$v))).']';
	    		echo '</div>';
	    		echo '<dfn id="'.chicken_dick($filename.'/'.$v).'" style="display: none;">';
	            echodragdropblocks($dir.'/'.$v, $level, $self);
	            echo '</dfn>';
	        } else {
	        	echo '<dfn itemID="'.chicken_dick($filename.'/'.substr($v, 0, -6)).'" class="dragblock"><div style="margin-left: '.(($level - 1) * 7).';">';
	        	echo '['.substr($v, 0, -6).']['.t('<a href="%url">редактировать</a>', array('url' => $self.'/?plugin=ddb&action=block&block='.chicken_dick($filename.'/'.substr($v, 0, -6)))).']';
	        	echo '</div></dfn>';
	        }
	    }
	}
}
?>