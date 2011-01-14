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
Plugin Name:	Комментарии
Plugin URI:		http://www.strawberry.su/
Description:	        Возможность включения/отключения/остановки комментариев для каждой отдельной новости. MySQL версия.
Version:		1.1
Application: 	Strawberry
Author: 		Mr.Miksar
Author URI:        mailto:miksar@mail.ru
*/



function edc_getsavedvalue($id) {
global $row, $post;
$endiscomments = ((!empty($row['add_comm']) and $row['add_comm'] != 'no') ? 1 : ((!empty($post['add_comm']) and $post['add_comm'] != 'no') ? 1 : 0));
$stopcomments  = ((!empty($row['stop_comm']) and $row['stop_comm'] == 'on') ? 0 : ((!empty($post['stop_comm']) and $post['stop_comm'] == 'on') ? 0 : 1));
$return  = array('allow' => $endiscomments, 'stop' => $stopcomments, 'edit' => $row['id']);
return $return;
}





add_action('edit-advanced-options', 'edc_checkbox', 10);
add_action('new-advanced-options', 'edc_checkbox', 10);
function edc_checkbox($hook) {
global $post;
if (!empty($post['id'])) {
$value = edc_getsavedvalue($post['id']);
if (!empty($value['allow'])) {$checked = 'checked="checked"';} else {$checked = '';}
if (empty($value['stop'])) {$checked2 = 'checked="checked"';} else {$checked2 = '';}
} else {
$checked = 'checked="checked"';
$checked2 = '';
}

return '<fieldset id="edcomments"><legend>'.t('Комментарии').'</legend>
<label for="endiscomments"><input type="checkbox" id="endiscomments" name="endiscomments" value="on" '.$checked.' />&nbsp;'.t('Показать комментарии?').'</label>
<br>
<label for="stopcomments">
<input type="checkbox" id="stopcomments" name="stopcomments" value="on" '.$checked2.' />&nbsp;'.t('Остановить комментарии?').'</label>
</fieldset>';
}






add_filter('news-show-generic', 'edc_display');
function edc_display($tpl) {
 $cfg = edc_getsavedvalue($tpl['id']);
  if (empty($cfg['allow'])) {
    $tpl['comments']="";
  }
 return $tpl;
}





add_filter('allow-comments', 'edc_comments');
add_filter('allow-comment-form', 'edc_comments');
function edc_comments($comments) {
global $row;
  if (!empty($comments)){
    $comments = ($row['add_comm'] != 'no' ? 1 : 0);
  }
return $comments;
}





add_filter('allow-comment-form', 'edc_stopcomments');
function edc_stopcomments($comments) {
global $row;
  if (!empty($comments)){
    $comments = ($row['stop_comm'] == 'on' ? 0 : 1);
  }
return $comments;
}


?>