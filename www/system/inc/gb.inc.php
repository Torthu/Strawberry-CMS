<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }
// GuestBook Form Universal v.2.3 for Strawberry 1.2.x


// получаем адрес куда отправлять письма. Тут вы можете указать вместо $config['admin_mail'] другой адрес.
$mailadmin = !empty($config['admin_mail']) ? $config['admin_mail'] : "no-reply@".$_SERVER['SERVER_NAME'];

// time
$timesender = langdate('j M Y - H:i', time);

// ip
$ip = getip();

// agent
$usagent = getagent();

$commin_story = "";
$comments = "";
$namesender = !empty($_POST["namesender"]) ? trim($_POST["namesender"]) : "guest";
$emailsender = !empty($_POST["emailsender"]) ? trim($_POST["emailsender"]) : "";
$isqsender = !empty($_POST["isqsender"]) ? trim($_POST["isqsender"]) : "";
$sitesender = !empty($_POST["sitesender"]) ? rurl(trim($_POST["sitesender"])) : "";
$comments = !empty($_POST["commin_story"]) ? trim($_POST["commin_story"]) : (!empty($_GET["commin_story"]) ? trim($_GET["commin_story"]) : "");


$gb_form = "<hr>
<form action=\"\" method=\"post\" name=\"addnews\">
<table border=\"0\" width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"2\" id=\"gb_form\" class=\"nbtext\">
<tr>
<td class=\"admin\" width=\"50%\"><b>Ваше имя (ник)*:</b></td>
<td width=\"50%\" class=\"admin\"><b>Ваш e-mail*:</b></td></tr>
<tr>
<td>";

if (!empty($is_logged_in))
{
$gb_form .= "<u>".$member['name']."</u></td>";
$namesender = $member['name'];
$nmsndr = $member['name'];

if(!empty($member['mail'])) {
$emailsender = $member['mail'];
if (empty($member['hide_mail'])) { $gb_form .= "<td>".$member['mail']; }
} else {
$gb_form .= "<td><input maxlength=\"35\" name=\"emailsender\" class=\"regtext\" value=\"".$emailsender."\">";
}


$gb_form .= "</td></tr><tr><td><b>Ваш номер ICQ:</b></td><td><b>Ваш сайт:</b></td></tr><tr><td>";

if(!empty($member['icq'])) {
$isqsender = $member['icq'];
$gb_form .= $member['icq']."</td>";
} else {
$gb_form .= "<input maxlength=\"11\" name=\"isqsender\" class=\"regtext\" value=\"".$isqsender."\"></td>";
}

if(!empty($member['homepage'])) {
$sitesender = rurl($member['homepage']);
$gb_form .= "<td>".$member['homepage'];
} else {
$gb_form .= "<td><input maxlength=\"30\" name=\"sitesender\" class=\"regtext\" value=\"".$sitesender."\">";
}

} else {
$gb_form .= "<input maxlength=\"80\" name=\"namesender\" class=\"regtext\" value=\"".$namesender."\"></td>
<td><input maxlength=\"35\" name=\"emailsender\" class=\"regtext\" value=\"".$emailsender."\">
</td>
</tr>
<tr>
<td><b>Ваш номер ICQ:</b></td>
<td><b>Ваш сайт:</b></td>
</tr>
<tr>
<td><input maxlength=\"11\" name=\"isqsender\" class=\"regtext\" value=\"".$isqsender."\"></td>
<td><input maxlength=\"30\" name=\"sitesender\" class=\"regtext\" value=\"".$sitesender."\">";
}

$gb_form .= "</td>
</tr>
<tr>
<td colspan=\"2\" class=\"admin\"><b>Ваша запись*:</b></td>
</tr>
<tr>
<td align=\"left\" style=\"padding-left: 10px\">".smiles(14, 'commin')."</td>
<td align=\"right\" style=\"padding-right: 10px\">".cnops(15, 'commin')."</td>
</tr>
<tr>
<td colspan=\"2\" align=\"center\" id=\"commin\"><textarea name=\"commin_story\" class=\"gb\"  style=\"overflow-x: hidden;overflow-y: visible;\"></textarea></td>
</tr>";

if (empty($is_logged_in) and $config['pin']) {
$gb_form .= "<tr><td colspan=\"2\" align=\"center\">".pin_cod("default", "gb")."</td></tr>";
}

$gb_form .= "<tr align=\"center\">
<td><input name=\"submit\" type=\"submit\" class=\"regok\" value=\" ОТПРАВИТЬ \"></td>
<td><input class=\"regok\" name=\"reset\" type=\"reset\" value=\" ОЧИСТИТЬ \"></td>
</tr>
<tr>
<td colspan=\"2\" class=\"sep\"></td>
</tr>
<tr>
<td valign=\"bottom\" colspan=\"2\"><p>* Поля, отмеченные <font class=\"admin\">этим цветом</font>, обязательны для заполнения.</p></td>
</tr>
</table>
<input name=\"send\" type=\"hidden\" value=\"1\">
</form>";

// проверка на заполненные формы

$gb_order = $gb_form;

$no_send = "<p align=\"center\" class=\"admin\"><b>".t('СООБЩЕНИЕ НЕ ОТПРАВЛЕНО!')."</b></p>";
$no_send_el = "<br>&nbsp;&nbsp;» ";
if (!empty($_POST['send']) and is_numeric($_POST['send'])) {




// Проверяем текст сообщения на минимальное и максимальное число символов
    $length = !empty($comments) ? strlen($comments) : 0;
    if (!empty($length) and $length > 2000) {
$e1 = $no_send_el."Слишком длинное сообщение. Количество симвоов: <font class=\"admin\">$length</font>. Объем текста не должен превышать <font class=\"moder\">2000 символов</font>.";
$error[1] = 1;
$error[2] = 0;
    } elseif (!empty($length) and $length < 10 && $length > 0) {
$e2 = $no_send_el."Слишком короткое сообщение. Количество символов: <font class=\"admin\">$length</font>. Объем  не должен быть меньше <font class=\"moder\">10 символов</font>.";
$error[1] = 0;
$error[2] = 1;
    } else {
$error[1] = 0;
$error[2] = 0;
    }
    
    

    // Проверяем, заполнены ли все поля
    if (empty($namesender) or 
        empty($emailsender) or 
        empty($comments) or 
        (!empty($emailsender) and !preg_match('/^[a-z0-9_\.-]+@[a-z0-9_\.-]+\.[a-z]{2,3}$/i', $emailsender)) or 
        (!empty($emailsender) and !empty($mailadmin) and $emailsender == $mailadmin and empty($is_logged_in)) or 
        (pin_check("gb") and empty($is_logged_in)) or !empty($error[1]) or !empty($error[2])
       ) {
$gb_order .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"text\"><tr><td>".$no_send;
$gb_order .= "Были обнаружены ошибки:";

if (empty($namesender)): $gb_order .= $no_send_el."Незаполнено поле \"Ваше имя\""; endif;
if (empty($emailsender)): $gb_order .= $no_send_el."Незаполнено поле \"Ваш E-Mail\""; endif;
if (empty($comments)): $gb_order .= $no_send_el."Незаполнено поле \"Текст сообщения\""; endif;
if (!empty($emailsender) and !preg_match('/^[a-z0-9_\.-]+@[a-z0-9_\.-]+\.[a-z]{2,3}$/i', $emailsender)): $gb_order .= $no_send_el."Введенный вами адрес E-Mail (".$emailsender.") содержит недопустимые символы или имеет неправильный формат."; endif;
if (!empty($emailsender) and !empty($mailadmin) and $emailsender == $mailadmin and empty($is_logged_in)): $gb_order .= $no_send_el."Введенный вами адрес E-Mail (".$emailsender.") совпадает с адресом администратора!"; endif;
if (pin_check("gb") and empty($is_logged_in)): $gb_order .= $no_send_el."Неверный код безопасности!"; endif;
$gb_order .= !empty($error[1]) ? $e1 : "";
$gb_order .= !empty($error[2]) ? $e2 : "";

$gb_order .= "<br></td></tr></table>";
$error[0] = 1;
    } else {
$error[0] = 0;
    }










    // Убираем HTML
$namesender = htmlspecialchars(stripslashes($namesender));
$isqsender = $isqsender ? htmlspecialchars(stripslashes($isqsender)) : 0;
$comments = comm_in($comments);
$sitesender = $sitesender ? rurl(htmlspecialchars(stripslashes($sitesender))) : 0;
$namesender = htmlspecialchars(trim(substr($namesender,0,50)));
$emailsender = trim(substr($emailsender,0,50));


// Если нет ошибок, то отправляем письмо!
if (empty($error[0]) and empty($error[1]) and empty($error[2])) {


// Шлем в базу
$tmsender = time;
$nmsndr = (!empty($is_logged_in) and $member['username']) ? $member['username'] : $namesender; 
$db->sql_query("INSERT INTO ".$config['dbprefix']."gb VALUES (NULL, '$nmsndr', '$emailsender', '$isqsender', '$sitesender', '$comments', '$ip', '$tmsender', '1', '', '')");

// Сообщения в зависимости от того, удачно письмо отправлено или нет.
if ($db) {
$gb_order = "<hr><table width=\"90%\" border=\"0\" align=\"center\" class=\"text\"><tr><td>";
$gb_order .= "<b align=\"center\" class=\"moder\">СООБЩЕНИЕ ОТПРАВЛЕНО!</b><br>";
$gb_order .= "&nbsp;&nbsp;» Спасибо, ".$namesender.", ваше сообщение успешно отправлено.";
$gb_order .= "</td></tr></table>";
$gb_order .= $gb_form;
   }
   else
   {
$gb_order = "<hr><table width=\"90%\" border=\"0\" align=\"center\" class=\"text\"><tr><td>".$no_send;
$gb_order .= "&nbsp;&nbsp;» Произошла непредвиденная ошибка при попытке отправить сообщение.<br>";
$gb_order .= "&nbsp;&nbsp;» Пожалуйста, обратитесь к <b><a href=\"mailto:".$mailadmin."\">Администратору</a></b>.</p>";
$gb_order .= "</td></tr></table>";
$gb_order .= $gb_form;
   }
   
   
   $comments = "";
   $commin_story = "";
   $_POST['commin_story'] = "";
   $_GET['commin_story'] = "";
   
   
}



}











$gb_order .= "<hr>";
$timenow = time;

$tpl['gb']['if-right-have'] = !empty($member['usergroup']) ? $member['usergroup'] : 0;

  $pnum = (isset($_GET['pnum']) and is_numeric($_GET['pnum'])) ? intval($_GET['pnum']) : "1";
  $oskip = ($pnum-1) * $number;
  $oskip = !empty($skip) ? intval($skip) : intval($oskip);
  
$dbgbout = $db->sql_query("SELECT id, namesender, emailsender, isqsender, sitesender, textsender, ipsender, date, activ, admin, answer 
FROM ".$config['dbprefix']."gb WHERE date <= '".$timenow."' ORDER BY id DESC 
".(!empty($number) ? " LIMIT ".((!empty($oskip) ? $oskip : 0) .", ".$number) : "")." ");

if ($db->sql_numrows($dbgbout) > 0) 
{
$emailsender = 0;
while (list($rid, $namesender, $emailsender, $icqsender, $sitesender, $textsender, $ipsender, $datesender, $activ, $adm, $ans) = $db->sql_fetchrow($dbgbout)) { 
$tpl['gb']['id'] = $rid;
$tpl['gb']['number'] = $rid;

if (!empty($users[$namesender]['name'])) {
$tpl['gb']['author'] = $users[$namesender]['name'];
if (!$users[$namesender]['hide_mail'] && $emailsender) { $tpl['gb']['email'] = $emailsender; } else { $tpl['gb']['email'] = 0; }
$tpl['gb']['avatar'] = (file_exists("images/avatar/".$users[$namesender]['username'].".".$users[$namesender]['avatar']."")) ? "<img align=\"left\" width=\"50\" border=\"0\" title=\"".$users[$namesender]['name']." - пользователь сайта ".$config['home_title']."\" src=\"images/avatar/".$users[$namesender]['username'].".".$users[$namesender]['avatar']."\">" : "<img align=\"left\" title=\"".$users[$namesender]['name']." - пользователь сайта ".$config['home_title']."\" width=\"50\" border=\"0\" src=\"images/avatar/user-default.gif\">";
} else {
$tpl['gb']['author'] = $namesender;
$tpl['gb']['email'] = $emailsender;
$tpl['gb']['avatar'] = "<img align=\"left\" width=\"50\" border=\"0\" title=\"".$namesender." - гость сайта ".$config['home_title']."\" src=\"".$config['path_userpic_upload']."/default.gif\">";
}

$tpl['gb']['icq'] = $icqsender;
$tpl['gb']['homepage'] = $sitesender;
$tpl['gb']['date'] = langdate('j M Y H:i', $datesender);
$tpl['gb']['mesagge'] = comm_out($textsender);
$tpl['gb']['admin']['name'] = $adm;
$tpl['gb']['admin']['answer'] = comm_out($ans);


if (!empty($config['mytheme']) and is_dir(gb_directory."_".$config['mytheme'])) {
$gb_th = "_".$config['mytheme'];
} else {
$gb_th = "";
}


ob_start();
include gb_directory.$gb_th.'/comments.tpl';  // выводим форму из шаблонов
    $output = ob_get_clean();
    $output = run_filters('news-comment', $output);
    $output = replace_comment('show', $output);
$gb_order .= $output;
$emailsender = 0;
$tpl['gb']['email'] = 0;
}

#########################################################################
$gb_order .= pnp("gb", (!empty($pnum) ? intval($pnum) : 1), (!empty($number) ? intval($number) : 0),  'system/gb'.$gb_th, (!empty($wheren) ? $wheren : ''), '','', '');


//pnp("gb", ((!empty($skip) and is_numeric($skip)) ? $skip : 0), ((!empty($number) and is_numeric($number)) ? $number : 0), "system/gb".$gb_th, (!empty($where) ? $where : ""));  
#########################################################################

} else {
$gb_order .= "<center class=\"text\">[ - записей нет - ]</center>";
}



echo $gb_order;
?>