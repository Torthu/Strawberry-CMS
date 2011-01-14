<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }

/**
 * @package Show
 * @access private
 */

$post['id'] = !empty($post['id']) ? intval($post['id']) : 0;

$tpl['form']['saved']['name']     = !empty($_COOKIE[$config['cookie_prefix'].'commentname']) ? urldecode($_COOKIE[$config['cookie_prefix'].'commentname']) : '';
$tpl['form']['saved']['mail']     = !empty($_COOKIE[$config['cookie_prefix'].'commentmail']) ? $_COOKIE[$config['cookie_prefix'].'commentmail'] : '';
$tpl['form']['saved']['homepage'] = !empty($_COOKIE[$config['cookie_prefix'].'commenthomepage']) ? $_COOKIE[$config['cookie_prefix'].'commenthomepage'] : '';
$tpl['form']['pin'] = $config['pin'];
$tpl['form']['smilies']           = smiles($config['smilies_line'], 'commin');
$tpl['form']['tags']           = cnops($config['bb_line'], 'commin');
$tpl['form']['mail']              = (!empty($member['author']) and !empty($users[$member['author']]['mail'])) ? $users[$member['author']]['mail'] : "";
$tpl['form']['homepage']          = (!empty($member['author']) and !empty($users[$member['author']]['homepage'])) ? $users[$member['author']]['homepage'] : "";
$tpl['form']['avatar']            = (!empty($member['author']) and !empty($users[$member['author']]['avatar'])) ? $config['path_userpic_upload'].'/'.$member['author'].'.'.$users[$member['author']]['avatar'] : $config['path_userpic_upload'].'/default.jpg';
$tpl['form']['icq']               = (!empty($member['author']) and !empty($users[$member['author']]['icq'])) ? $users[$member['author']]['icq'] : "";
$tpl['form']['location']          = (!empty($member['author']) and !empty($users[$member['author']]['location'])) ? $users[$member['author']]['location'] : "";
$tpl['form']['about']             = (!empty($member['author']) and !empty($users[$member['author']]['about'])) ? run_filters('news-comment-content', $users[$member['author']]['about']) : "";

$tpl['form']['lj-username']       = (!empty($member['author']) and !empty($users[$member['author']]['lj_username'])) ? '<a href="http://'.$users[$member['author']]['lj_username'].'.livejournal.com/profile"><img src="system/skins/images/user.gif" alt="[info]" align="absmiddle" border="0"></a><a href="http://'.$users[$member['author']]['lj_username'].'.livejournal.com">'.$users[$member['author']]['lj_username'].'</a>' : "";
$tpl['form']['author']            = (!empty($member['author']) and !empty($users[$member['author']]['name'])) ? $users[$member['author']]['name'] : "";
$tpl['form']['username']          = (!empty($member['author']) and !empty($users[$member['author']]['username'])) ? $users[$member['author']]['username'] : "";
$tpl['form']['user-id']           = (!empty($member['author']) and !empty($users[$member['author']]['id'])) ? $users[$member['author']]['id'] : "";
$tpl['form']                      = run_filters('form-show-generic', $tpl['form']);

ob_start();
include templates_directory.'/'.$tpl['template'].'/form.tpl';
$output = ob_get_contents();
ob_end_clean();

if (!empty($config['cajax'])) {  
?>

<screept type="text/javascript" language="JavaScript" src="/system/data/java/prototype.js"></screept>
<div id="comment0"></div>

<?php  
$form_add_opt = "onsubmit=\"call_ajax(this, '', ".(empty($is_logged_in) ? "'1'" : "''").");return false;\"";
} else {
//$form_add_opt = !empty($config['pin']) ? "onsubmit=\"wcompleted('comm', 'comm', '');return false;\"" : "";
$form_add_opt = "";
}
?>

<form action="" id="comment" method="post" name="addnews" <?php echo $form_add_opt; ?>>
<div id="error_message" class="error_message" style="display: none;"></div>
<div id="commin" class="comment_form"><?php echo $output; ?></div>
<input type="hidden" id="parent" name="parent" value="0">
<input type="hidden" name="isend" value="comm_add_one">
<input type="hidden" name="id" value="<?php echo $post['id']; ?>">
<input type="hidden" name="template" value="<?php echo $tpl['template']; ?>">
<input type="hidden" name="cpage" value="<?php echo ((!empty($_GET['cpage']) and is_numeric($_GET['cpage'])) ? $_GET['cpage'] : 0); ?>">
<?php echo !empty($user_post_query) ? $user_post_query : ""; ?>
</form>

<?php
if (empty($config['cajax']) and !empty($_POST['isend']) and $_POST['isend'] == "comm_add_one") {
include includes_directory."/show.add-comment.php";
}

?>