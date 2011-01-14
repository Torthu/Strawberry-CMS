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
Plugin Name: Файлы к посту. Менеджер файлов.
Plugin URI: 	  http://cutenews.ru/
Description:	  Плагин позволяет прикреплять файлы к посту.
Version: 	  1.2
Application:  Strawberry
Author: 	  Лёха zloy & Mr.Miksar
Author URI:   http://lexa.cutenews.ru
*/







add_filter('new-advanced-options', 'adepto_list');
add_filter('edit-advanced-options', 'adepto_list');

function adepto_list($location){
global $id, $config;

    if (empty($config['delete_files'])){
       $config['delete_files'] = '0';
    }

    if (empty($config['path_upload'])){
       $config['path_upload'] = $config['http_home'].'/attach';
    }

    if (empty($config['deny_files'])){
       $config['deny_files'] = '.cgi .pl .shtml .shtm .php .php3 .php4 .php5 .phtml .phtm .phps .htm .html';
    }

    $attach_directory = straw_parse_url($config['path_upload']);
    $attach_directory = $attach_directory['abs'];

	if (!empty($id) and is_dir($attach_directory.'/'.$id)){
	echo '<div class="adepto_fastload_field">';
        echo '<div id="adepto_fastload_p_'.$location.'"><a href="javascript:ShowOrHide(\'adepto_fastload_'.$location.'\', \'adepto_fastload_p_'.$location.'\')">'.t('Прикреплённые файлы').'</a></div>';
		echo '<div id="adepto_fastload_'.$location.'" class="adepto_fastload" style="display: none;">';

        $handle = opendir($attach_directory.'/'.$id);
        while ($file = readdir($handle)){
            if ($file != '.' and $file != '..'){
            $fs = formatsize(filesize($attach_directory.'/'.$id.'/'.$file));
?>

<a href="javascript:insertext('<a href=&quot;<?php echo $config['path_upload']."/".$id."/".$file; ?>&quot;><?php echo $file; ?></a> (<?php echo $fs; ?>)', '', '<?php echo $location; ?>')"><img width="12" src="admin/images/icons/attach.png" title="<?php echo t('Вставить'); ?>" /></a> <a href="index.php?plugin=adepto_fastload&id=<?php echo $id."&amp;act=ndelete&amp;file=".$file; ?>"><img width="12" src="admin/images/icons/delete.png" title="<?php echo t('Удалить'); ?>" /></a> <?php echo str_stop($file, 50); ?> (<?php echo $fs; ?>)<br />

<?php            }
        }

        echo '</div></div>';
	}

return $location;
}

















add_action('new-advanced-options', 'adepto_uploader');
add_action('edit-advanced-options', 'adepto_uploader');

function adepto_uploader(){
global $ap, $config;
	ob_start();
?>

<fieldset id="adepto_fastload">
<legend><?php echo t('Прикрепить файлы'); ?></legend>

<?php
if (!empty($ap)) {
  echo "<script type=\"text/javascript\" language=\"JavaScript\">";
} else {
  echo "<screept type=\"text/javascript\" language=\"JavaScript\">";
}
?>
f = 0
document.writeln('<input type="file" name="file[0]" value="" onchange="file_uploader(0);" /><br>')
document.writeln('<span id="file_1"></span>')
<?php
if (!empty($ap)) {
  echo "</script>";
} else {
  echo "</screept>";
}
?>

<label for="pack">
<input id="pack" name="pack" type="checkbox" value="on">&nbsp;<?php echo t('Упаковывать простые файлы?'); ?></label>
<br />
<!--<label for="unpack"><input id="unpack" name="unpack" type="checkbox" value="on">&nbsp;<?php echo t('Распаковывать архивы?'); ?></label>-->
</fieldset>

<?php
return ob_get_clean();
}

















add_action('new-save-entry', 'adepto_save');
add_action('edit-save-entry', 'adepto_save');

function adepto_save(){
global $id, $config;

include_once includes_directory.'/zipbackup.inc.php';

    $attach_directory = straw_parse_url($config['path_upload']);
    $attach_directory = $attach_directory['abs'];

    if (reset($_FILES['file']['name'])){
    	if (!@mkdir($attach_directory.'/'.$id, "0".$config['chm_dir'])){
    		return;
    	}

	    for ($i = 0; $i < count($_FILES['file']['name']); $i++){
	    	$filename = $attach_directory.'/'.$id.'/'.$_FILES['file']['name'][$i];
	    	$ext = preg_quote(end($ext = explode('.', $filename)), '/');

	    	if (empty($_FILES['file']['error'][$i])){
	    		move_uploaded_file($_FILES['file']['tmp_name'][$i], $filename);

	            if (!empty($_POST['pack']) and !empty($_FILES['file']['type'][$i]) and $_FILES['file']['type'][$i] != 'application/x-zip-compressed'){
	            	$zipfile = new zipfile();
	            	$zipfile->add_file(file_read($filename), $_FILES['file']['name'][$i]);
	                file_write(preg_replace('/.'.$ext.'$/i', '.zip', $filename), $zipfile->file());
                    unset($zipfile);
	            }

	            if (!empty($_POST['unpack']) and !empty($_FILES['file']['type'][$i]) and $_FILES['file']['type'][$i] == 'application/x-zip-compressed'){
	            }
	    	}
	    }
	}
}













add_filter('news-show-generic', 'adepto_parse');

function adepto_parse($tpl){
global $config, $adepto_xfields, $attach_directory;

    if (!is_object($adepto_xfields)){
    	$attach_directory = straw_parse_url($config['path_upload']);
    	$attach_directory = $attach_directory['abs'];
    }

	if (!empty($tpl['id']) and is_dir($attach_directory.'/'.$tpl['id'])){
		$tpl['attachment'] = '<ul class="adepto_fastload">';

        $handle = opendir($attach_directory.'/'.$tpl['id']);
        while ($file = readdir($handle)){
            if ($file != '.' and $file != '..'){
            	$tpl['attachment'] .= '<li><a href="'.$config['path_upload'].'/'.$tpl['id'].'/'.$file.'">'.$file.'</a> ('.formatsize(filesize($attach_directory.'/'.$tpl['id'].'/'.$file)).')</li>';
            }
        }

        $tpl['attachment'] .= '</ul>';
	}

return $tpl;
}












add_filter('template-short', 'adepto_vars');
add_filter('template-full', 'adepto_vars');
function adepto_vars($variables){
$variables['attachment'] = '';
return $variables;
}











add_filter('options', 'adepto_AddToOptions');
function adepto_AddToOptions($options){
	$options[] = array(
			     'name'     => t('Adepto Fastload'),
			     'url'      => 'plugin=adepto_fastload',
			     'category' => 'plugin',
			     );
return $options;
}











add_action('plugins', 'adepto_CheckAdminOptions');
function adepto_CheckAdminOptions(){
	if (!empty($_GET['plugin']) and $_GET['plugin'] == 'adepto_fastload'){
		adepto_FileManager();
	}
}

function adepto_FileManager(){
global $config, $PHP_SELF, $nid, $act, $allowed_extensions, $gplugin;

if (straw_get_rights($gplugin, 'read')) { 

if (straw_get_rights($gplugin, 'write')) { $yaw = 1; } else { $yaw = 0; }

///////////////////////////////////////////////////////////////////////////////////////////
    $content = "Order Deny,Allow\r\nAllow from all\r\n\r\nAddType text/plain ";
    if (!empty($_POST['save_con'])){
    	$htaccess = straw_parse_url($config['path_upload']);
    	if (!empty($htaccess['abs'])){
	    	file_write($htaccess['abs'].'/.htaccess', $content.$config['deny_files']);
	}
    	header('Location: '.$PHP_SELF.'?plugin=adepto_fastload');
    }
///////////////////////////////////////////////////////////////////////////////////////////

    if (empty($config['delete_files'])){
       $config['delete_files'] = '0';
    }
    if (empty($config['path_upload'])){
       $config['path_upload'] = $config['http_script_dir'].'../attach';
    }
    if (empty($config['deny_files'])){
       $config['deny_files'] = '.cgi .pl .shtml .shtm .php .php3 .php4 .php5 .phtml .phtm .phps .htm .html';
    }
        $att_dir_arr = straw_parse_url($config['path_upload']);
    	$attach_directory = $att_dir_arr['abs'];
    	$attdir = "/".$att_dir_arr['path'];
        //print_r($att_dir_arr);
        
        $et = array();
        $a_et = explode(".", $config['deny_files']);
        foreach ($a_et as $etv){ if(!empty($etv)) $et[] = trim($etv); }
        
///////////////////////////////////////////////////////////////////////////////////////////
$file = !empty($_GET['file']) ? cheker($_GET['file']) : "";
$pf="";
$pd="";

$fid = !empty($_GET['fid']) ? cheker($_GET['fid']) : (!empty($_POST['fid']) ? cheker($_POST['fid']) : "");
$afid = !empty($fid) ? "/".$fid : "";
$bfid = !empty($fid) ? $fid."/" : "";
$adir = !empty($nid) ? "/".$nid : "";

if (empty($act)) {


if (is_dir($attach_directory.$afid)) {


if (!empty($afid)) {
  $arr_fid = explode("/",$fid);
  if (count($arr_fid) > 1) { array_pop($arr_fid); 
    $omfid = "&amp;fid=".implode("/", $arr_fid);
  } else {
    $omfid = "";
  }
  $pd .= "<tr style=\"background: #F9F0FB;\">
  <td colspan=\"3\">&nbsp;&nbsp;&nbsp;<b><a href=\"index.php?plugin=adepto_fastload".$omfid."\" title=\"".t('На уровень выше')."\">../</a></b></td>
  <td><a href=\"index.php?plugin=adepto_fastload".$omfid."\"><img src=\"admin/images/icons/folderup.png\" alt=\"".t('На уровень выше')."\"></a></td></tr>";
}

echoheader('files', t('Управление файлами'));

echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">";
echo "<tr><td colspan=\"4\" height=\"33\" class=\"panel\"><b>".t('Управление файлами в дирректории:%dir', array('dir'=>'<br />'.$attach_directory.$afid))."</b></td></tr>";
echo "<tr><td colspan=\"4\" height=\"10\"></td></tr>";
echo "<tr class=\"panel\"><td width=\"5%\"><b> # </b></td><td width=\"60%\"><b>".t("Имя")."</b></td><td width=\"20%\"><b>".t("Размер")."</b></td><td align=\"center\" width=\"15%\"><b>".t("Опции")."</b></td></tr>";
$jj=0; $ii=0; $tr=0;
$f_arr = array();
    $handle = opendir($attach_directory.$afid);
    while ($file = readdir($handle)){
        if ($file != '.' and $file != '..' and !in_array($file, array('index.php','index.html','index.htm','.htaccess'))){
        $fs = 0;
        $fr = 0;
        $fname = "";
        $ftype = "";
        $fsize = "";
        
            if (is_dir($attach_directory.$afid."/".$file)) { 
            $jj++;
              $fname = "<a href=\"index.php?plugin=adepto_fastload&amp;fid=".$bfid.$file."\">".$attdir.$afid."/".$file."/</a>";
              $fsize = "<i>".t("папка")."</i>";
              $prev = " <a href=\"index.php?plugin=adepto_fastload&amp;fid=".$bfid.$file."\" title=\"\"><img src=\"admin/images/icons/folder.png\" title=\"".t("Открыть папку")."\"></a>";
              $pd .= "<tr style=\"background: #FCFDD7;\">
            <td>".$jj."</td>
            <td>".$fname."</td>
            <td>".$fsize."</td>
            <td align=\"left\">".(!empty($yaw) ? "<a href=\"index.php?plugin=adepto_fastload".(!empty($fid) ? "&amp;fid=".$fid : "")."&amp;act=delete&amp;file=".$file."\"><img width=\"16\" src=\"admin/images/icons/delete.png\" title=\"".t('Удалить')."\" /></a>" : "")." ".$prev."</td></tr>";
            }
            if (is_file($attach_directory.$afid."/".$file)) { 
            $ii++;
              $fname = "<a href=\"".$attdir.$afid."/".$file."\" title=\"".t('Скачать файл %fil', array('fil'=>$file))."\">".$attdir.$afid."/<b>".$file."</b></a>";
              $ftype = strtolower(end(explode('.', $file)));
              $fr = @filesize($attach_directory.$afid."/".$file);
              $tr = $tr + $fr;
              $fsize = razmer($fr);
              $prev = (!empty($ftype) and in_array($ftype, array('jpg','jpeg','gif','png','bmp'))) ? " <a target=\"_blank\" href=\"".$attdir.$afid."/".$file."\" title=\"\"><img src=\"admin/images/icons/image.png\" title=\"<img width='150' src='".$attdir.$afid."/".$file."'>\"></a>" : " <a href=\"".$attdir.$afid."/".$file."\" title=\"\"><img src=\"admin/images/icons/files.png\" title=\"*.".$ftype."\"></a>";
              $pf .= "<tr style=\"background: #ECFFDD;\">
            <td>".$ii."</td>
            <td>".$fname."</td>
            <td>".$fsize."</td>
            <td align=\"left\">".(!empty($yaw) ? "<a href=\"index.php?plugin=adepto_fastload".(!empty($fid) ? "&amp;fid=".$fid : "")."&amp;act=delete&amp;file=".$file."\"><img width=\"16\" src=\"admin/images/icons/delete.png\" title=\"".t('Удалить')."\" /></a>" : "")." ".$prev."</td></tr>";
            }
$prev = "";
        }
    }


echo $pd.$pf;
echo "<tr><td colspan=\"4\" height=\"5\"></td></tr>";
echo "<tr style=\"background: #F0FDFF;\"><td><b>".t("Всего:")."</b></td><td colspan=\"3\">".t('Папок:')." <b>".$jj."</b>; ".t('Файлов:')." <b>".$ii."</b>; ".t('Объем всех файлов в папке:')." <b>".razmer($tr)."</b>;</td></tr>";
echo "</table>";


if (!empty($yaw)) {
echo "<br />";
echo "<div style=\"clear:right;\"></div>";
echo "<form method=\"post\" name=\"addnews\" action=\"\" enctype=\"multipart/form-data\">";
echo "<div class=\"panel\" style=\"float:right;text-align:left;width:250px;\">";
echo adepto_uploader();
echo "<input type=\"hidden\" value=\"fid\" name=\"".$fid."\"/>";
echo "<input type=\"hidden\" value=\"upload\" name=\"act\"/>";
echo "<input type=\"submit\" value=\"".t('Загрузить')."\" style=\"width:100%;\"/>";
echo "</div>";
echo "</form>";
echo "";
}


} else {
  @header("Location: index.php?plugin=adepto_fastload");
}

} elseif(!empty($act) and $act=="ndelete" and !empty($nid) and !empty($yaw)) {

  if (is_file($attach_directory."/".$nid."/".$file)) @unlink($attach_directory."/".$nid."/".$file);
  @header("Location: index.php?mod=editnews&id=".$nid);

} elseif(!empty($act) and $act=="delete" and (!empty($nid) or !empty($file)) and !empty($yaw)) {

$lnid = !empty($afid) ? "&fid=".$fid : "";
$dfile = !empty($file) ? "/".$file : "";


  if (is_file($attach_directory.$afid.$dfile)) {
  @unlink($attach_directory.$afid.$dfile);
  } 
  if (is_dir($attach_directory.$afid.$dfile)) {
  deldir($attach_directory.$afid.$dfile, 1);
  }
  
@header("Location: index.php?plugin=adepto_fastload".$lnid);

} elseif(!empty($act) and $act=="ndelete" and !empty($nid) and !empty($yaw)) {

  if (is_file($attach_directory."/".$nid."/".$file)) @unlink($attach_directory."/".$nid."/".$file);
  @header("Location: index.php?mod=editnews&id=".$nid);

} elseif(!empty($act) and $act=="upload" and !empty($yaw)) {

$lnid = !empty($afid) ? "&fid=".$fid : "";
$dfile = !empty($file) ? "/".$file : "";
########################################################
########################################################

include_once includes_directory.'/zipbackup.inc.php';


    if (reset($_FILES['file']['name'])){
	    for ($i = 0; $i < count($_FILES['file']['name']); $i++){
	    	$filename = $attach_directory.$afid.'/'.$_FILES['file']['name'][$i];
	    	$ext = end($ext = explode('.', $_FILES['file']['name'][$i]));

	    	if (empty($_FILES['file']['error'][$i]) and !in_array($ext, $et)){
	    		move_uploaded_file($_FILES['file']['tmp_name'][$i], $filename);
	    		
	            if (!empty($_POST['pack']) and !empty($_FILES['file']['type'][$i]) and $_FILES['file']['type'][$i] != 'application/x-zip-compressed'){
	            	$zipfile = new zipfile();
	            	$zipfile->add_file(file_read($filename), $_FILES['file']['name'][$i]);
	                file_write(preg_replace('/.'.$ext.'$/i', '.zip', $filename), $zipfile->file());
	                unset($zipfile);
	            }

	            if (!empty($_POST['unpack']) and !empty($_FILES['file']['type'][$i]) and $_FILES['file']['type'][$i] == 'application/x-zip-compressed') { }
	    	}
	    }
	}

########################################################
########################################################
@header("Location: index.php?plugin=adepto_fastload".$lnid);

} else {

echoheader('error', t('Управление файлами'));
echo t("Недопустимое действие");

}






///////////////////////////////////////////////////////////////////////////////////////////

} else { 
  echoheader('error', t('Управление файлами'));
  echo t("У вас нет прав на просмотр данного плагина");
}
echofooter();
}











add_action('mass-deleted', 'adepto_delete');

function adepto_delete(){
global $selected_news, $config;

	$xfields = new PluginSettings('Adepto_Fastload');
    $attach_directory = straw_parse_url($xfields->settings['path_upload']);
    $attach_directory = $attach_directory['abs'];

	if ($xfields->settings['delete_files']){
	$selected_news = !empty($_POST['selected_news']) ? $_POST['selected_news'] : '';
		foreach ($selected_news as $select){
			if (is_dir($attach_directory.'/'.$select)){
	            $handle = opendir($attach_directory.'/'.$select);
	            while ($file = readdir($handle)){
	                if ($file != '.' and $file != '..'){
	                    @unlink($attach_directory.'/'.$select.'/'.$file);
	                }
	            }
	        }

	        @rmdir($attach_directory.'/'.$select);
		}
	}
}
?>