<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../index.php");
exit;
}

/**
 * @package Plugins
 */

/*
Plugin Name:    Автор публикации
Plugin URI:       http://strawberry.su/
Description:     Позволяет администратору поменять автора публикации для контента.
Version: 	    1.0
Application:     Strawberry
Author: 	    Mr.Miksar
Author URI:     mailto:miksar@mail.ru 
*/






add_action('new-advanced-options', 'straw_author_AddEdit', 3);
add_action('edit-advanced-options', 'straw_author_AddEdit', 3);

/**
 * @access private
 */
function straw_author_AddEdit(){
global $post, $member;

$out = '<fieldset id="author_post">
<legend>'.t('Автор публикации').'</legend>
<input style="width:210px;" name="author" type="text" value="'.(!empty($_POST['author']) ? cheker($_POST['author']) : (!empty($post['author']) ? $post['author'] : (!empty($member['username']) ? $member['username'] : ""))).'"  '.(is_admin() ? "" : "disabled=\"disabled\"").'>
</fieldset>';

return $out;
}






?>