<?php 
if (!defined("str_modul")) {
	header("Location: ../../../index.php");
	exit;
}

function users_menu() {
global $config, $is_logged_in;
$lmenu = "<a href=\"".way(straw_get_link(array(), 'mod', 'home'))."\" title=\"".t('Главная')."\">".t('Главная')."</a>";
$lmenu .= " | <a href=\"".way(straw_get_link(array(), 'mod', 'home')."&amp;act=users")."\" title=\"".t('Пользователи')."\">".t('Пользователи')."</a>";
if (!empty($is_logged_in)) {
$lmenu .= " | <a href=\"".way("system/index.php")."\" title=\"".t('Панель управления')."\">".t('Панель')."</a>";
$lmenu .= " | <a href=\"".way(straw_get_link(array(), 'mod', 'home')."&amp;act=profil")."\" title=\"".t('Профиль')."\">".t('Профиль')."</a>";
$lmenu .= " | <a href=\"".way($config['home_page']."?mod=logout")."\" title=\"".t('Выход из системы')."\">".t('Выход')."</a>";
} else {
$lmenu .= " | <a href=\"".way(straw_get_link(array(), 'mod', 'home')."&amp;act=registration")."\" title=\"".t('Регистрация')."\">".t('Регистрация')."</a>";
}
echo "<center><div class=\"mmenu\">".$lmenu."</div></center>";
}

  $userpic_folder = straw_parse_url($config['path_userpic_upload']);
  $userpic_path = way($userpic_folder['path']);
  $userpic_folder = $userpic_folder['abs'];

################################################

if ((empty($act) or $act == 'profil') and !empty($is_logged_in)){

       foreach ($member as $key => $value){
         $member[$key] = stripslashes(str_replace(array('"', '\''), array('&quot;', '&#039;'), $member[$key]));
       }

       if (!empty($config['user_avatar'])){

          if (!empty($member['avatar'])){
            $member['avatar'] = '<img src="'.$userpic_path.'/'.$member['username'].'.'.$member['avatar'].'" alt="'.$member['name'].'" title="'.t('Пользователь ресурса: %mem', array('mem'=>$member['name'])).'" style="margin: 5px; border: 0px;">';
            $delava = '<input type="checkbox" name="delavatar" id="delavatar"> <label for="delavatar">'.t('Удалить аватар?').'</label>';
          } else {
            $member['avatar']="";
            $delava="";
          }

            $showrow_avatar = '<tr>
            <td valign="top">&nbsp; '.t('Аватар').':</td>
            <td valign="top"><input type="hidden" name="max_file_size" value="110000"><input tabindex="6" name="newavatar" type="file" size="27" style="color: #000000;"><br />'.$delava.'</td>
            <td style="width:150px;text-align:center;">'.$member['avatar'].'</td>
            </tr>';
            
       } else {

            unset($member['avatar'], $showrow_avatar);

       }
       
  $tit = t('Профиль пользователя %un', array('un'=>$member['name']));
  otable();
  users_menu();
  echo "<form action=\"\" method=\"post\" name=\"personal\" enctype=\"multipart/form-data\">";

?>


<table border="0" cellspacing="2" cellpadding="2" class="nbtext">
<tr>
  <td colspan="3" style="padding-bottom:10px;" class="c2"><b><?php echo t('Общие данные'); ?></b></td>
</tr>
<tr>
  <td width="150">&nbsp; <?php echo t('Ник'); ?></td>
  <td width="600" colspan="2" class="c4"><b><input type="hidden" name="editusername" value="<?php echo $member['username']; ?>" /><?php echo $member['username']; ?></b></td>
</tr>
<tr>
  <td>&nbsp; <?php echo t('Дата регистрации'); ?></td>
  <td colspan="2" class="c4"><b><?php echo langdate('l, j M Y - h:i', $member['date']); ?></b></td>
</tr>
<tr>
  <td>&nbsp; <?php echo t('Группа'); ?></td>
  <td colspan="2" class="c4"><b><?php echo $usergroups[$member['usergroup']]['name']; ?></b></td>
</tr>
<tr>
 <td>&nbsp; <?php echo t('Публикаций'); ?></td>
 <td colspan="2" class="c3"><b><?php echo $member['publications']; ?></b></td>
</tr>

<tr>
 <td colspan="3" style="padding-top: 10px; padding-bottom: 10px;" class="c2"><hr><b><?php echo t('Ваш профиль'); ?></b></td>
</tr>

<tr>
 <td valign="top">&nbsp; <?php echo t('Новый пароль'); ?></td>
 <td colspan="2"><input type="text" name="editpassword" class="regtext" /><br /><?php echo t('если хотите изменить текущий'); ?></td>
</tr>
<tr>
 <td>&nbsp; <?php echo t('Имя'); ?></td>
 <td colspan="2"><input type="text" name="editnickname" class="regtext" value="<?php echo $member['name']; ?>" /></td>
</tr>
<tr>
 <td valign="top">&nbsp; <?php echo t('E-mail'); ?></td>
 <td colspan="2"><input type="text" name="editmail" class="regtext" value="<?php echo $member['mail']; ?>" /><br /><input type="checkbox" name="edithidemail" <?php echo (!empty($member['hide_mail']) ? 'checked' : ''); ?> id="edithidemail" /> <label for="edithidemail"><?php echo t('спрятать e-mail'); ?></label></td>
</tr>
<tr>
 <td>&nbsp; <?php echo t('Сайт'); ?></td>
 <td colspan="2"><input type="text" name="editsite" class="regtext" value="<?php echo $member['homepage']; ?>" /></td>
</tr>
<tr>
 <td>&nbsp; <?php echo t('ICQ'); ?></td>
 <td colspan="2"><input type="text" name="editicq" class="regtext" value="<?php echo $member['icq']; ?>" /></td>
</tr>
<tr>
 <td>&nbsp; <?php echo t('Аккаунт в ЖЖ'); ?></td>
 <td colspan="2"><input type="text" name="editlj" class="regtext" value="<?php echo $member['lj_username']; ?>" /></td>
</tr>
<tr>
 <td>&nbsp; <?php echo t('Пароль от ЖЖ'); ?></td>
 <td colspan="2"><input type="text" name="editljpass" class="regtext" value="<?php echo $member['lj_password']; ?>" /></td>
</tr>
<tr>
 <td>&nbsp; <?php echo t('Откуда'); ?></td>
 <td colspan="2"><input type="text" name="editfrom" class="regtext" value="<?php echo $member['location']; ?>" /></td>
</tr>
<tr>
 <td valign="top">&nbsp; <?php echo t('О себе'); ?></td>
 <td colspan="2"><textarea name="editabout" class="gb" style="padding:2px;overflow-x:hidden;overflow-y:visible;"><?php echo replace_comment("admin", $member['about']); ?></textarea></td>
</tr>

<?php 
echo $showrow_avatar; 
?>

<tr>
<td height="1" colspan="3"><br>
<input type="hidden" name="mod" value="account" />
<input type="hidden" name="act" value="doedituser" />
<input type="submit" class="regok" value="<?php echo t('Сохранить'); ?>" accesskey="s" />
</td>
</tr>

</table>

<?php echo "</form>"; ?>









<div class="arttit"><?php echo t('15 последних комментариев'); ?></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="smnbtext">
<?php 
$dbcom = $db->sql_query("SELECT date, comment, post_id, id FROM ".$config['dbprefix']."comments WHERE author='".$member['username']."' ORDER BY date DESC LIMIT 0,15");
$k=0;
while (list($date, $textsender, $postid, $cmid) = $db->sql_fetchrow($dbcom)) {
$k++;
$textsender = str_stop(preg_replace("# {2,}#si", " ", strip_tags(comm_out($textsender))), 50);
?>
<tr>
<td style="padding: 3px;width:20px;"><?php echo $cmid; ?>)</td>
<td style="padding: 3px;"> <a href="<?php echo way($config['home_page']."?mod=news&amp;id=".$postid."#comment".$cmid); ?>"><?php echo (!empty($textsender) and strlen($textsender) > 2) ? $textsender : "<i>".t('нет текста')."</i>"; ?></a></td>
</tr>
<?php } ?>
</table>








<?php

ctable();

} else {

   if ((!$act or $act == 'profil') and empty($is_logged_in)){
     $tit = t('Вход');
     otable();
     users_menu();
     echo lform();
     ctable();
   }

}






#######################

// ********************************************************************************
// Save Personal Options
// ********************************************************************************
if (!empty($act) and $act == 'doedituser' and !empty($is_logged_in)){

$avatar_error = "";
$editnickname = !empty($_POST['editnickname']) ? cheker($_POST['editnickname']) : "";
$editmail = (!empty($_POST['editmail']) and remail($_POST['editmail'])) ? cheker($_POST['editmail']) : "";
$mess = "";

$aquery = $db->sql_query("SELECT * FROM ".$config['dbprefix']."users WHERE id!='".$member['id']."' and (name='".$editnickname."' OR mail='".$editmail."') LIMIT 0,1 ");
$aqrow = $db->sql_fetchrow($aquery);

		if (!empty($editnickname) and strtolower($aqrow['name']) == strtolower($editnickname)) {
			$mess .= error_tpl(t('Ошибка'), t('Такое имя уже кто-то использует'));
			$tit = t('Изменения не сохранены');
		} elseif (!empty($editmail) and strtolower($aqrow['mail']) == strtolower($editmail)) {
			$mess .= error_tpl(t('Ошибка'), t('Такой e-mail уже кто-то использует'));
			$tit = t('Изменения не сохранены');
		} elseif (empty($editnickname) or empty($editmail)) {
		        $tit = t('Изменения не сохранены');
		        if (empty($editnickname)) {$mess .= error_tpl(t('Ошибка'), t('Не заполнено поле Имя'));}
		        if (empty($editmail))        {$mess .= error_tpl(t('Ошибка'), t('Не заполнено поле e-mail'));}
		} else {


    if (!is_dir($userpic_folder)) {
      @mkdir($userpic_folder, "0".$config['chm_dir']);
      @chmod($userpic_folder, "0".$config['chm_dir']);
    }


    if (!empty($_POST['delavatar'])) {
    	unset($change_avatar);
    	@unlink($userpic_folder.'/'.$member['username'].'.'.$member['avatar']);
    } else {
    	$change_avatar = $member['avatar'];
    }


    if (!empty($_POST['editljpass'])) {
    	$editljpass = cheker($_POST['editljpass']);
    } else {
    	$editljpass = $member['lj_password'];
    }


    if (!empty($_FILES) and empty($_FILES['newavatar']['error'])) {
	    // Загружаем файл(ы)
	    $avatarname = $_FILES['newavatar']['name'];
	    $avatartemp = $_FILES['newavatar']['tmp_name'];
	    $type          = end($type = explode('.', $avatarname));

	    // Проверяем картинку или фигню какую-то пытается закачать юзер
	    if (!in_array($type, $allowed_extensions) or !in_array(strtolower($type), $allowed_extensions)){
	        @unlink($userpic_folder.'/'.$avatarname);

	        $change_avatar = $member['avatar'];
	        $avatar_error  = t('Аватар не загружен!<br>Загрузка таких типов файлов запрещена');
	    } else {
	        @unlink($userpic_folder.'/'.$member['username'].'.'.$member['avatar']);
	        @move_uploaded_file($avatartemp, $userpic_folder.'/'.$avatarname);

	        $size = @getimagesize($userpic_folder.'/'.$avatarname);
	        $avatar_error = "";
	        // Проверяем размеры загружаемой картинки
	        if (($size[0] > $config['avatar_w']) or ($size[1] > $config['avatar_h'])){
	            $avatar_error  = t('Аватар не загружен!<br>Размеры картинки должны быть %ww x %hh', array('ww'=>$config['avatar_w'], 'hh'=>$config['avatar_h']));
	            $change_avatar = $member['avatar'];
	            @unlink($userpic_folder.'/'.$avatarname);
	        } else {
	            $change_avatar = @rename($userpic_folder.'/'.$avatarname, $userpic_folder.'/'.$member['username'].'.'.$type);
	            $change_avatar = $type;
	            $avatar_error = "";
	        }
	    }
    }



        if (!empty($_POST['editpassword'])) {
            $row_pass = md5x($_POST['editpassword']);
            $_SESSION['md5_password'] = $row_pass;
            straw_setcookie('md5_password', $row_pass);
        } else {
            $row_pass = $member['password'];
        }

//print_r($_POST);
//echo replace_comment('add', $_POST['editabout']);

$db->sql_query("UPDATE ".$config['dbprefix']."users SET 
password='".$row_pass."', 
name='".$editnickname."', 
mail='".$editmail."', 
hide_mail='".(!empty($_POST['edithidemail']) ? 1 : 0)."', 
avatar='".$change_avatar."', 
homepage='".replace_comment('add', rurl($_POST['editsite'], 'http'))."', 
icq='".replace_comment('add', cheker($_POST['editicq']))."', 
location='".replace_comment('add', cheker($_POST['editfrom']))."', 
about='".replace_comment('add', $_POST['editabout'])."', 
lj_username='".replace_comment('add', cheker($_POST['editlj']))."', 
lj_password='".$editljpass."' 
WHERE id=".$member['id']." ");

$tit = t('Изменения сохранены');

               }
  
  otable();
  if (!empty($avatar_error)) {
    echo error_tpl(t('Проблемы с аватаром'), $avatar_error);
  }
  if (!empty($mess)) {
    echo $mess;
  }
  ctable();

}

#######################

if ($act == 'users'){
$tit = t("Пользователи сайта");
otable();
users_menu();
include rootpath.'/show_users.php';
ctable();
}

#######################

if ($act == 'registration' and empty($is_logged_in)){
$tit = t("Регистрация");
otable();
users_menu();
echo regForm('default');
ctable();
} elseif (!empty($act) and $act == 'registration' and !empty($is_logged_in)){
@header("Location: ".way($config['home_page']));
}

#######################

if (!empty($act) and $act == 'forgot' and empty($is_logged_in)){
$tit = t("Восстановление пароля");
otable();
users_menu();
######################################################
	if (!empty($_POST['for']) or !empty($_GET['for'])){
	$for = !empty($_POST['for']) ? cheker($_POST['for']) : cheker($_GET['for']);
	    if (!empty($_GET['key'])){
	    
	        $new_password = @file_read(cache_directory.'/_'.$for.'_.tmp');
	        unlink(cache_directory.'/_'.$for.'_.tmp');

	        if (!empty($_GET['key']) and cheker($_GET['key']) == $new_password){


$db->sql_query("UPDATE ".$config['dbprefix']."users SET password='".$new_password."' WHERE username='".$for."'");


	            echo t('Восстановление пароля')."<br>".t('Пароль был успешно изменён изменён.');
	        } else {
	            echo t('Ошибка')."<br>".t('Ключ не верный. Попробуйте снова.');
	        }
	        
	    } else {
	    
$itsendeds = "";


$arr_query = $db->sql_query("SELECT * FROM ".$config['dbprefix']."users WHERE username='".$for."' OR mail='".$for."' ");
$row = $db->sql_fetchrow($arr_query);
	            
	                if (!empty($row['mail'])){
	                    $itsendeds = "1";
	                    $new_password   = random_pass();
	                    $activation_url = $config['http_script_dir'].'?mod=account&amp;act=forgot&amp;key='.md5x($new_password).'&amp;for='.$row['username'];

	                    ob_start();
	                    include mails_directory.'/forgot.tpl';
	                    $tpl['body'] = ob_get_clean();

	                    preg_match('/Subject:(.*)/i', $tpl['body'], $tpl['subject']);
	                    preg_match('/Attachment:(.*)/i', $tpl['body'], $tpl['attachment']);

	                    $tpl['body']       = preg_replace('/Subject:(.*)/i', '', $tpl['body']);
	                    $tpl['body']       = preg_replace('/Attachment:(.*)/i', '', $tpl['body']);
	                    $tpl['body']       = trim($tpl['body']);
	                    $tpl['subject']    = trim($tpl['subject'][1]);
	                    $tpl['attachment'] = !empty($tpl['attachment']['1']) ? trim($tpl['attachment']['1']) : '';

	                    if (!file_exists(cache_directory.'/_'.$row['username'].'_.tmp')){
	                        file_write(cache_directory.'/_'.$row['username'].'_.tmp', md5x($new_password));
	                        straw_mail($row['mail'], $tpl['subject'], $tpl['body'], $tpl['attachment']);
	                    }

	                    echo t('Восстановление пароля')."<br>".t('Вам на e-mail было отправлено сообщение. Проверьте почту.');
	                } else {
	                    echo t('Восстановление пароля')."<br>".t('Пользователь <b>%user</b> найден, но он не указал свой e-mail. Если вы являетесь этим забывчивым пользователем, то обратитесь к администрации.', array('user' => $row['name']));
	                }
	        
	        
	        
	     if (empty($itsendeds)) {
	      echo t('Восстановление пароля')."<br>".t('Нет такого пользователя в базе. Вам следует зарегистрироваться.');
	      $itsendeds = "1"; 
	     }  
	        
	        
	    }
	} else {

?>
<center>
<form action="" method="post">
<?php echo t('Укажите ваш логин или e-mail'); ?><br>
<input name="for" type="text" value=""> <input type="submit" value="  <?php echo t('Восстановить'); ?> ">
<input type="hidden" name="mod" value="account">
<input type="hidden" name="act" value="forgot">
</form>
</center>
<?php

	}
######################################################

ctable();
} elseif (!empty($act) and $act == 'forgot' and !empty($is_logged_in)){
@header("Location: ".way($config['home_page']));
}

#######################

echo on_page($modul);
?>