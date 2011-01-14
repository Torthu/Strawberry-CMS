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
Plugin Name: 	Менеджер смайлов
Description: 	При помощи данного плагина вы можете добавить или убрать смайлики для сайта.
Version: 		0.2
Application: 	Strawberry
Author: 		Лёха zloy и красивый
Author URI:     http://lexa.cutenews.ru
*/




add_filter('options', 'emoticons_AddToOptions');
function emoticons_AddToOptions($options){
$options[] = array(
'name'     => t('Смайлики'),
'url'      => 'plugin=emoticons',
'category' => 'plugin',
 );
return $options;
}




add_action('plugins', 'emoticons_CheckAdminOptions');
function emoticons_CheckAdminOptions(){
global $gplugin;
 if (!empty($gplugin) and $gplugin == 'emoticons'){
  emoticons_AdminOptions();
 }
}






function emoticons_AdminOptions(){
global $config, $allowed_extensions, $modul, $PHP_SELF;

$folder = 'data/emoticons';
$smiles = explode(',', $config['smilies']);

	foreach ($smiles as $k => $v) {
		$smiles[$k] = trim($v);
	}


#####################################################################
	if (!empty($_FILES['image']['name'])) {
	    for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
	    	if (empty($_FILES['image']['error'][$i])){
	            $ext = strtolower(end($ext = explode('.', $_FILES['image']['name'][$i])));
	            if (!file_exists($folder.'/'.$_FILES['image']['name'][$i]) and in_array($ext, array('gif','jpg','jpeg','png','bmp','tif'))){
	                move_uploaded_file($_FILES['image']['tmp_name'][$i], smile_directory.'/'.$_FILES['image']['name'][$i]);
	            }
	        }
	    }
	    header('Location: index.php?plugin=emoticons');
	}
#####################################################################

#####################################################################
	if (!empty($_POST['save']) and !empty($_POST['smiles'])){
	$smiles = array();
	foreach ($_POST['smiles'] as $k => $v) { $smiles[] = $k; }
	$config['smilies'] = @join(', ', $smiles);
        save_config($config);
	header('Location: index.php?plugin=emoticons');
	}
#####################################################################

#####################################################################
	if (!empty($_POST['delete']) and !empty($_POST['dsmiles'])) {
	foreach ($_POST['dsmiles'] as $k => $v) { unlink(smile_directory."/".$k); }
	header('Location: index.php?plugin=emoticons');
	}
#####################################################################


if (straw_get_rights($modul, 'write')) { $yaw = 1; } else { $yaw = 0; }
echoheader('images', t('Управление смайликами'));
?>

<form action="" name="upload" method="post" enctype="multipart/form-data">
<table border="0" cellpading="2" cellspacing="2" width="100%">
<tr><td height="33" class="panel"><b><?php echo t('Данный плагин позволяет загружать смайлики и активировать их для показа на страницах.'); ?></b></td></tr>
<tr><td></td></tr>
<tr><td class="panel">

<script language="javascript">
p = 0
document.writeln('<input type="file" name="image[0]" size="70" value="" onchange="img_uploader(0);"><br>')
document.writeln('<span id="image_1"></span>')
</script>

<?php echo (!empty($yaw) ? '<input type="submit" value="'.t('Загрузить').'">' : ''); ?>
</td></tr>
</table>
<imput type="hidden" name="plugin" value="emoticons"/>
</form>

<br><br>

<form action="" method="post">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
<tr>

<?php
	$i = 0;
	$handle = opendir(smile_directory);
	while ($file = readdir($handle)) {
		$ext  = strtolower(end($ext = explode('.', $file)));
		$name = preg_replace('/\.'.$ext.'$/i', '', $file);

	    if (in_array($ext, array('gif','jpg','jpeg','png','bmp','tif'))) {
	    	$i++;
?>

<td <?php echo straw_that(); ?> width="10%" align="left" valign="top" title="<?php echo t('Код смайлика'); ?>:<?php echo $name; ?>:">
<input name="smiles[<?php echo $name; ?>]" type="checkbox" value="on"<?php echo (in_array($name, $smiles) ? ' checked' : ''); ?>>save<br>
<input name="dsmiles[<?php echo $name.".".$ext; ?>]" type="checkbox" value="on">delete<br>
<img src="<?php echo sway($folder.'/'.$file); ?>" align="absmiddle" title="<?php echo t('Код смайлика'); ?>:<?php echo $name; ?>:"></td>
<?php echo ($i%10 == 0 ? '</tr><tr>' : ''); ?>

<?php } } ?>

</tr>
<tr>
<td colspan="5" align="left"><?php echo (!empty($yaw) ? '<input type="submit" value="'.t('Удалить').'" name="delete">' : ''); ?></td>
<td colspan="5" align="right"><?php echo (!empty($yaw) ? '<input type="submit" value="'.t('Сохранить').'" name="save">' : ''); ?></td>

</tr>
</table>
</form>

<?php
echo on_page();
echofooter();
}
?>