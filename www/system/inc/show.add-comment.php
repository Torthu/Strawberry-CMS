<?php
#_strawberry
#if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }

/**
 * @package Show
 * @access private
 */
$ap = 1;
include_once substr(dirname(__FILE__), 0, -4).'/head.php';

if (!empty($config['cajax'])) {
   foreach ($_POST as $k => $v){
     $$k = iconv('utf-8', $config['charset'], $v);
   }
  if (!empty($config['charset'])) { 
     header('Content-type: text/html; charset='.$config['charset']); 
  }
}



$error_message = array();
$name = !empty($name) ? htmlspecialchars(trim($name)) : '';
$mail = !empty($mail) ? trim($mail) : '';
$homepage = !empty($homepage) ? rurl($homepage) : '';

// если авторизован, то берем готовые данные.
if (!empty($is_logged_in)){
  $uname = $member['username'];
  $name     = $member['name'];
  $mail     = !empty($member['mail']) ? $member['mail'] : '';
  $homepage = !empty($member['homepage']) ? $member['homepage'] : '';
}


$allow_add_comment = true;
$allow_add_comment = run_filters('allow-add-comment', $allow_add_comment);

//----------------------------------
// Check the lenght of comment, include name + mail
//----------------------------------

if (!empty($name) and strlen($name) > 30){
  $error_message[] = t('Вы ввели слишком длинное имя. Максимум 30 символов!');
}

if (!empty($mail) and strlen($mail) > 50){
  $error_message[] = t('Вы ввели слишком длинный e-mail. Максимум 50 символов!');
}

if (pin_check("comm") and empty($is_logged_in) and !empty($config['pin'])) {
  $error_message[] = t('Вы ввели неверный проверочный код!');
}

if (!empty($_POST['captcha'])){
$error_message[] = t('А вот это поле заполнять не надо, дурашка!'); 
unset($_POST['captcha']); 
}
 
$sumsumb = 0;
if (empty($is_logged_in) and !empty($config['comment_max_long_guest'])){
$sumsumb = $config['comment_max_long_guest'];
} else {
$sumsumb = !empty($config['comment_max_long']) ? $config['comment_max_long'] : 0;
}

if (!empty($commin_story) and strlen($commin_story) > $sumsumb and !empty($config['comment_max_long'])){
  $error_message[] = t('Вы ввели слишком длинный комментарий.<br>  Максимум '.$config['comment_max_long'].' символов!');
}

if (empty($allow_add_comment)){
  $error_message[] = t('Вы не можете добавить этот комментарий! Возможно в нем есть заблокированные слова...');
}


// Check Flood Protection
if (!straw_get_rights('full') and !empty($config['flood_time']) and flooder(getip(), $id)){
  $error_message[] = t('Включена защита от флуда! Подождите <b>%time</b> секунд после вашей последней публикации.', array('time' => $config['flood_time']));
}


//////////////////////////////////////////////////////////////////////////////////
// Check if IP is banned
$act_ipb = 0;
if ($sesuser != "robot") {
 $b_query = $db->sql_query("SELECT * FROM ".$config['dbprefix']."ipban WHERE ip='".getip()."' ");
 while($bq = $db->sql_fetchrow($b_query)){
   $db->sql_query("UPDATE ".$config['dbprefix']."ipban SET count=count+1 WHERE ip='".getip()."' ");
   $act_ipb = 1;
 }
}
if (!empty($act_ipb) or $sesuser == "robot" or (!straw_get_rights('comments') and !empty($config['only_registered_comment']))){
  $error_message[] = t('Извините, но вам запрещено публиковать комментарии.');
}
//////////////////////////////////////////////////////////////////////////////////


if (!empty($config['only_registered_comment']) and empty($is_logged_in)){
  $error_message[] = t('Извините, только зарегистрированные пользователи могут оставлять комментарии.');
}

$commin_story = replace_comment('add', $commin_story);




if (empty($name)){
  $error_message[] = t('Введите ваше имя.');
} else {
  
  $name = replace_comment('add', $name);

  if (!empty($config['need_mail']) and empty($mail) and empty($is_logged_in)){
      $error_message[] = t('Введите e-mail.');
  } elseif (!empty($mail) and !preg_match('/^[\.A-z0-9_\-]+[@][\.A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/', $mail)){
      $error_message[] = t('Извините, этот e-mail неправильный.');
  } else {
      
  }


  if (empty($is_logged_in) and (!empty($name) or !empty($mail))){
    foreach ($users as $row){
      if ((!empty($name) and (strtolower($row['username']) == strtolower($name) or strtolower($row['name']) == strtolower($name))) or (!empty($mail) and strtolower($row['mail']) == strtolower($mail))){
        $error_message[] = t('Вы используете данные зарегистрированного пользователя, но не зашли в систему.');
      }
    }
    if (!empty($homepage) and !preg_match('/^(http|https|ftp)+\:\/\/([\.A-z0-9_\-]+)\.([A-z]{1,4})$/', $homepage)){
      $error_message[] = t('Извините, этот адрес неправильный.');
    }
  }

}


if (empty($commin_story)){
  $error_message[] = t('Заполните поле "Комментарий".');
} else {
### Защита от инъекций
  
  $zbb[] = "#\<script(.*?)script\>#si";
  $zhtml[] = "<div class=\"nbtext\"><font class=\"admin\">".t('ВНИМАНИЕ! ПОПЫТКА ВРЕДОНОСНОГО ВКЛЮЧЕНИЯ НА САЙТ!')."</font><br><textarea border=\"1\" style=\"width: 100%; height: 50px; font-size: 8pt;\">\\1</textarea></div>";
 //  $zhtml[] = "<script\\1script>";

  $zbb[] = "#\<screept#si";
  $zhtml[] = "<script";
  $zbb[] = "#\</screept#si";
  $zhtml[] = "</script"; 
  
  $zbb[] = "#\<iframe(.*?)iframe\>#si";
  $zhtml[] = "<div class=\"nbtext\"><font class=\"admin\">".t('ВНИМАНИЕ! ПОПЫТКА ВРЕДОНОСНОГО ВКЛЮЧЕНИЯ НА САЙТ!')."</font><br><textarea border=\"1\" style=\"width: 100%; height: 50px; font-size: 8pt;\">\\1</textarea></div>";
  $zbb[] = "#\<okno#si";
  $zhtml[] = "<iframe";
  $zbb[] = "#\</okno#si";
  $zhtml[] = "</iframe";
### // Защита от инъекций
$commin_story = preg_replace($zbb, $zhtml, $commin_story);
}

if (reset($error_message)){
    foreach ($error_message as $k => $v){
        $error_message[$k] = $v;
    }

    $allow_add_comment = false;
    header('HTTP/1.0 500 Internal Server Error');
    echo join('<br>', $error_message);
}

if (empty($allow_add_comment)){
  return;
}

$time = (time() + ($config['gmtoffset'] - (date('Z') / 60)) * 60);

$parent = (!empty($_POST['parent']) and is_numeric($_POST['parent'])) ? $_POST['parent'] : '0';

// Add the Comment

// определяет, на какой комментарий мы ответили. Его параметры передаются в почтовую форму.
$reply_row = array();
if (!empty($parent)){
$rep_query = $db->sql_query("SELECT * FROM ".$config['dbprefix']."comments WHERE id = ".$parent." ");
$reply_row = $db->sql_fetchrow($rep_query);
}


// заносим в базу новый комментарий
$db->sql_query("INSERT INTO ".$config['dbprefix']."comments VALUES ('".$time."', '".(!empty($is_logged_in) ? $member['username'] : $name)."', '".$mail."', '".$homepage."', '".getip()."', '".$commin_story."', '', '".$id."', '".(!empty($is_logged_in) ? $member['id'] : 0)."', '".$parent."', '".(!empty($parent) ? ($reply_row['level'] + 1) : 0)."', NULL, '')");

// обновляем в текущей новости число комментариев
$db->sql_query("UPDATE ".$config['dbprefix']."news SET comments=comments+1 WHERE id='".$id."'");

// если есть время флуда - то ограничиваем по времени!
if (!empty($config['flood_time'])){
  $db->sql_query("INSERT INTO ".$config['dbprefix']."flood VALUES ('".$time."', '".getip()."', NULL)");
}

// если нас попросили запомнить данные визитера
if (!empty($_POST['rememberme']) and $_POST['rememberme'] == 'on'){
  $now = (time + 3600 * 24 * 365);
  straw_setcookie('comment_name', urlencode($name), $now, '/');
  straw_setcookie('comment_mail', $mail, $now, '/');
  straw_setcookie('comment_homepage', $homepage, $now, '/');
} else { // если не просили
  $now = (time - 3600 * 24 * 365);
  straw_setcookie('comment_name', '', $now, '/');
  straw_setcookie('comment_mail', '', $now, '/');
  straw_setcookie('comment_homepage', '', $now, '/');
}






      $n_query = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news WHERE id = ".$id." ");
      $row = $db->sql_fetchrow($n_query);
      $commid = $comid = $sql->last_insert_id('comments', '', 'id');
      if (!empty($row)) {

if (!empty($parent)){
  if (!empty($users[$reply_row['author']]) and !empty($reply_row['author'])){
    $reply_row['author'] = $users[$reply_row['author']]['username'];
    $reply_row['mail']   = !empty($reply_row['author']) ? $users[$reply_row['author']]['mail'] : "";
  }
  if (!empty($reply_row['mail']) and $reply_row['mail'] != 'none' and $reply_row['author'] != $name and !empty($reply_row['mail']) and $reply_row['mail'] != $config['admin_mail']){
        ob_start();
        include mails_directory.'/reply.tpl';
        $tpl['body'] = ob_get_clean();
        preg_match('/Subject:(.*)/i', $tpl['body'], $tpl['subject']);
        preg_match('/Attachment:(.*)/i', $tpl['body'], $tpl['attachment']);
        $tpl['body']       = preg_replace('/Subject:(.*)/i', '', $tpl['body']);
        $tpl['body']       = preg_replace('/Attachment:(.*)/i', '', $tpl['body']);
        $tpl['body']       = trim($tpl['body']);
        $tpl['subject']    = trim($tpl['subject'][1]);
        $tpl['attachment'] = !empty($tpl['attachment'][1]) ? trim($tpl['attachment'][1]) : "";
        straw_mail($reply_row['mail'], $tpl['subject'], aply_bbcodes($tpl['body']), $tpl['attachment']);
  }
}

if (!empty($config['admin_mail']) and !empty($reply_row['mail']) and $config['admin_mail'] != $reply_row['mail'] and $config['admin_mail'] != $mail and !empty($config['send_mail_upon_posting'])){
        ob_start();
        include mails_directory.'/new_comment.tpl';
        $tpl['body'] = ob_get_clean();
        preg_match('/Subject:(.*)/i', $tpl['body'], $tpl['subject']);
        preg_match('/Attachment:(.*)/i', $tpl['body'], $tpl['attachment']);
        $tpl['body']       = preg_replace('/Subject:(.*)/i', '', $tpl['body']);
        $tpl['body']       = preg_replace('/Attachment:(.*)/i', '', $tpl['body']);
        $tpl['body']       = str_replace("\r\n", "\n", trim($tpl['body']));
        $tpl['subject']    = trim($tpl['subject'][1]);
        $tpl['attachment'] = !empty($tpl['attachment'][1]) ? trim($tpl['attachment'][1]) : "";
        straw_mail($config['admin_mail'], $tpl['subject'], aply_bbcodes($tpl['body']), $tpl['attachment']);
}

      }


if (!empty($config['cajax'])) {
$tpl['template'] = $template;
$post['id'] = $id;
include dirname(__FILE__).'/show.comments.php';
}

?>