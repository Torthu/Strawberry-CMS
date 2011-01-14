<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }

// mail form v.2.3 for strawberry 1.1.1

// получаем адрес куда отправлять письма. Тут вы можете указать вместо $config['admin_mail'] другой адрес.
$mailadmin = !empty($config['admin_mail']) ? $config['admin_mail'] : "no-reply@".$_SERVER['SERVER_NAME'];

// time
$timesender = langdate('j M Y - H:i', time);

// ip
$ip = getip();

// agent
$usagent = getagent();

if (!empty($_POST['sendcopy'])) {
$cpp = "checked";
} else {
$cpp = "";
}

$namesender = !empty($_POST['namesender']) ? $_POST["namesender"] : "guest";
$emailsender = !empty($_POST['emailsender']) ? $_POST["emailsender"] : "";
$isqsender = !empty($_POST['isqsender']) ? $_POST["isqsender"] : "";
$sitesender = !empty($_POST['sitesender']) ? rurl($_POST["sitesender"]) : "";
$subjectsender = !empty($_POST['subjectsender']) ? $_POST["subjectsender"] : t('Без темы');
$themesender = !empty($_POST['themesender']) ? $_POST["themesender"] : "";
$textsender = !empty($_POST['textsender']) ? $_POST["textsender"] : "";
$sendcopy = !empty($_POST['sendcopy']) ? 1 : 0;
$message = "";
$error = array();


ob_start();
include mails_directory.'/mail_form.tpl';  // выводим форму из шаблонов
$mail_order = ob_get_contents();
ob_end_clean();



/*
// Исходная форма, которая работает 100%
// Open Sourse by Mr.Miksar - see in mail_form.tpl

$mail_order = "<hr>
<form action=\"\" method=\"post\">
<table border=\"0\" width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"2\" id=\"mail_form\" class=\"nbtext\">
<tr class=\"text\">
<td class=\"admin\" width=\"50%\">".t("Ваше имя (ник)*").":</td>
<td width=\"50%\" class=\"admin\">".t("Ваш e-mail*").":</td></tr>
<tr>
<td>";

if (!empty($is_logged_in)) {

$mail_order .= "<u>".$member['name']."</u></td>";
$namesender = $member['name'];
##$nmsndr = $member['name']." <small>(login: ".$member['username'].", number: ".$member['id'].")</small>";
if(!empty($member['mail'])) {
$emailsender = $member['mail'];
if (!empty($member['hide_mail'])) $mail_order .= "<td>".$member['mail'];
} else {
$mail_order .= "<td><input maxlength=\"35\" name=\"emailsender\" class=\"regtext\" value=\"".$emailsender."\">";
}
$mail_order .= "</td></tr><tr class=\"text\"><td>".t("Ваш номер ICQ").":</td><td>".t("Ваш сайт").":</td></tr><tr><td>";

if(!empty($member['icq'])) {
$isqsender = $member['icq'];
$mail_order .= "".$member['icq']."</td>";
} else {
$mail_order .= "<input maxlength=\"11\" name=\"isqsender\" class=\"regtext\" value=\"".$isqsender."\"></td>";
}

if(!empty($member['homepage'])) {
$sitesender = rurl($member['homepage']);
$mail_order .= "<td>".$member['homepage'];
} else {
$mail_order .= "<td><input maxlength=\"30\" name=\"sitesender\" class=\"regtext\" value=\"".$sitesender."\">";
}

} else {

$mail_order .= "<input maxlength=\"80\" name=\"namesender\" class=\"regtext\" value=\"".$namesender."\"></td>
<td><input maxlength=\"35\" name=\"emailsender\" class=\"regtext\" value=\"".$emailsender."\">
</td>
</tr>
<tr class=\"text\">
<td>".t("Ваш номер ICQ").":</td>
<td>".t("Ваш сайт").":</td>
</tr>
<tr>
<td><input maxlength=\"11\" name=\"isqsender\" class=\"regtext\" value=\"".$isqsender."\"></td>
<td><input maxlength=\"30\" name=\"sitesender\" class=\"regtext\" value=\"".$sitesender."\">";
}

$mail_order .= "</td>
</tr>
<tr class=\"text\">
<td>".t("Тема письма*").":</td>
<td>".t("Своя тема письма").":</td>
</tr>
<tr>
<td><select name=\"subjectsender\" size=\"1\" id=\"subjectsender\"><option value=\"".t("Общий вопрос")."\" selected>".t("Общий вопрос")."</option><option value=\"".t("Заказ")."\">".t("Заказ")."</option><option value=\"".t("Участие в проекте")."\">".t("Участие")."</option><option value=\"".t("Сотрудничество")."\">".t("Сотрудничество")."</option><option value=\"".t("Реклама")."\">".t("Реклама")."</option></select></td>
<td><input maxlength=\"300\" name=\"themesender\" class=\"regtext\" value=\"".$themesender."\"></td>
</tr>
<tr>
<td colspan=\"2\"><input type=\"checkbox\" ".$cpp." name=\"sendcopy\" value=\"1\"> ".t("Прислать вам копию письма?")."</td>
</tr>
<tr class=\"text\">
<td colspan=\"2\" class=\"admin\">".t("Текст вашего сообщения*").":</td>
</tr>
<tr>
<td colspan=\"2\" align=\"center\"><textarea name=\"textsender\" class=\"gb\" rows=\"5\" cols=\"10\">".$textsender."</textarea></td>
</tr>";

if (empty($is_logged_in) and $config['pin']) {
$mail_order .= "<tr><td colspan=\"2\" align=\"center\">".pin_cod("default", "mail")."</td></tr>";
}

$mail_order .= "<tr align=\"center\">
<td><input name=\"submit\" type=\"submit\" class=\"regok\" value=\" ".t("ОТПРАВИТЬ")." \"></td>
<td><input class=\"regok\" name=\"reset\" type=\"reset\" value=\" ".t("ОЧИСТИТЬ")." \"></td>
</tr>
<tr>
<td colspan=\"2\" class=\"sep\"></td>
</tr>
<tr>
<td valign=\"bottom\" colspan=\"2\"><p>".t("* Поля, отмеченные <font class=\"admin\">этим цветом</font>, обязательны для заполнения.")."</p></td>
</tr>
</table>
<input name=\"send\" type=\"hidden\" value=\"1\">
</form>";
*/










// проверка на заполненные формы
if (!empty($_POST['send']) and is_numeric($_POST['send'])) {
##############################################################################################
##############################################################################################







    // Проверяем, заполнены ли все поля
    if (empty($namesender) or 
         empty($emailsender) or 
         empty($_POST['textsender']) or 
         (pin_check("mail") and empty($is_logged_in))
         ) {
$mail_order .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"text\"><tr><td>";
$mail_order .= "<br><p align=\"center\" class=\"admin\"><b>".t("СООБЩЕНИЕ НЕ ОТПРАВЛЕНО")."!</b></p>";
$mail_order .= t("Не были заполнены следующие обязательные поля").":<br>";
if (empty($namesender)): $mail_order .= "&nbsp; » ".t("Ваше имя")."<br>"; endif;
if (empty($emailsender)): $mail_order .= "&nbsp; » ".t("Ваш E-Mail")."<br>"; endif;
if (empty($_POST['textsender'])): $mail_order .= "&nbsp; » ".t("Текст сообщения")."<br>"; endif;
if (pin_check("mail") and empty($is_logged_in)): $mail_order .= "&nbsp; » ".t("Неверный код безопасности!")."<br>"; endif;

$mail_order .= "<br></td></tr></table>";
$error[0] = 1;
    } else {
$error[0] = 0;
    }


    // Проверяем корректность введенного e-mail
    if (!empty($emailsender) and !preg_match('/^[a-z0-9_\.-]+@[a-z0-9-]+\.[a-z]{2,3}$/i', $emailsender)) {
$mail_order .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"text\"><tr><td>";
$mail_order .= "<br><p align=\"center\" class=\"admin\"><b>".t("СООБЩЕНИЕ НЕ ОТПРАВЛЕНО")."!</b></p>";
$mail_order .= t("Введенный вами адрес E-Mail (%eml) содержит недопустимые символы или имеет неправильный формат.", array('eml' => $emailsender))."<br>";

$mail_order .= "</td></tr></table>";
$error[1] = 1;
    } else {
$error[1] = 0;
    }

// Проверяем текст сообщения на минимальное и максимальное число символов
    $length = !empty($textsender) ? strlen(trim($textsender)) : 0;
    if ($length > 2000) {
$mail_order .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"text\"><tr><td>";
$mail_order .= "<br><p align=\"center\" class=\"admin\"><b>".t("СООБЩЕНИЕ НЕ ОТПРАВЛЕНО")."!</b></p>";
$mail_order .= t("Слишком длинное сообщение. Количество симвоов: <font class=\"admin\">%lngth</font>. Объем текста не должен превышать <font class=\"moder\">2000 символов</font>.", array('lngth' => $length))."<br>";

$mail_order .= "</td></tr></table>";
$error[2] = 1;
    }
    elseif ($length < 10 && $length > 0) {
$mail_order .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"text\"><tr><td>";
$mail_order .= "<br><p align=\"center\" class=\"admin\"><b>".t("СООБЩЕНИЕ НЕ ОТПРАВЛЕНО")."!</b></p>";
$mail_order .= t("Слишком короткое сообщение. Количество символов: <font class=\"admin\">%lngth</font>. Объем  не должен быть меньше <font class=\"moder\">10 символов</font>.", array('lngth' => $length))."<br>";

$mail_order .= "</td></tr></table>";
$error[2] = 1;
    }
    else {
    $error[2] = 0;
    }

    // Проверяем корректность введенного e-mail
    if (!empty($mailadmin) and $mailadmin != "no-reply@".$_SERVER['SERVER_NAME'] and $emailsender == $mailadmin and empty($is_logged_in)){
$mail_order .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"text\"><tr><td>";
$mail_order .= "<br><p align=\"center\" class=\"admin\"><b>".t("СООБЩЕНИЕ НЕ ОТПРАВЛЕНО")."!</b></p>";
$mail_order .= t("Введенный вами адрес E-Mail (%eml) совпадает с адресом администрации! Не шутите с админами!", array('eml' => $emailsender))."<br>";

$mail_order .= "</td></tr></table>";
$error[3] = 1;
    } else {
$error[3] = 0;
    }














// формируем тему письма
$header['subjectsender']  = !empty($subjectsender) ? stripslashes(trim($subjectsender)) : t('Без темы');


    // Убираем HTML
    $namesender = !empty($namesender) ? htmlspecialchars(stripslashes($namesender)) : "guest";
$isqsender = !empty($isqsender) ? htmlspecialchars(stripslashes($isqsender)) : 0;
    $textsender = !empty($textsender) ? htmlspecialchars(stripslashes($textsender)) : "";
$sitesender = !empty($sitesender) ? rurl(htmlspecialchars(stripslashes($sitesender))) : 0;
    $namesender = trim(substr($namesender,0,50));
    $emailsender = trim(substr($emailsender,0,50));


// Если нет ошибок, то отправляем письмо! #and empty($error[4])
if (empty($error[0]) and empty($error[1]) and empty($error[2]) and empty($error[3])) {

// проверка копии
if (!empty($sendcopy)) {
$mailto = $mailadmin.",".$emailsender;
} else {
$mailto = $mailadmin;
}

// проверка темы письма
if (!empty($themesender)) {
$subjectsender = htmlspecialchars(stripslashes($themesender));
}

// формируем письмо
$message .= "<br><font face=\"verdana\" size=\"2\">".$subjectsender;
$message .= "<br>";
$message .= t("Вам пишет %namesender с темой %subjectsender", array('namesender' => $namesender, 'subjectsender' => $subjectsender));
$message .= "<br><br> &nbsp;&nbsp;&nbsp;&nbsp;<b><u>» ".t("Контакты").":</u></b>";
$message .= "<br> E-Mail: ".$emailsender;
$message .= "<br> ICQ: ".$isqsender;
$message .= "<br> URL: ".rurl($sitesender);
$message .= "<br> ".t("Дата отправки").": ".$timesender;
$message .= "<br> &nbsp;&nbsp;&nbsp;&nbsp;<b><u>» ".t("Сообщение").":</u></b>";
$message .= "<br>".$textsender;
$message .= "<br><br> ----------------------------------------------";
$message .= "<br><br> <b>User Agent</b>: ".$usagent;
$message .= "<br> <b>IP</b>: ".$ip;
$message .= "</font><br>";

// формируем шапку письма
$headers  = "MIME-Version: 1.0\n";
$headers .= "From: ".(!empty($emailsender) ? $emailsender : $mailadmin)."\n";
$headers .= "Content-Type: text/html; charset=".$config['charset']."\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Отправляем письмо
$send = mail($mailto, $subjectsender, $message, $headers);

// Щлем в базу
$tmsender = time;
$textsender = replace_news('add', $textsender);
$nmsndr = !empty($is_logged_in) ? $member['username'] : $namesender; 

$db->sql_query("INSERT INTO ".$config['dbprefix']."mail VALUES (NULL, '$nmsndr', '$emailsender', '$isqsender', '$sitesender', '$subjectsender', '$textsender', '$ip', '$usagent', '$tmsender', '1')");

// Сообщения в зависимости от того, удачно письмо отправлено или нет.
if (!empty($send)) {
$mail_order = "<hr><table width=\"90%\" border=\"0\" align=\"center\" class=\"text\"><tr><td>";
$mail_order .= "<br><p align=\"center\" class=\"moder\"><b>".t("СООБЩЕНИЕ ОТПРАВЛЕНО")."!</b></p>";
$mail_order .= "&nbsp; » ".t("Спасибо, %namesender, ваше сообщение успешно отправлено.", array('namesender' => $namesender))."<br>".t("Мы постараемся вам оперативно ответить.")."</p>";

if (!empty($sendcopy)) {
$mail_order .= "&nbsp; » ".t("На ваш E-Mail (%emailsender) послана копия письма. Через несколько минут вы можете проверить указанный вами почтовый ящик, чтобы убедиться, что отправка прошла успешно.", array('emailsender' => $emailsender))."</p>";
}

$mail_order .= t("Вы можете <a href=\"%put\" title=\"Вернуться назад\"><u>вернуться назад</u></a> и написать нам еще чего нибудь.", array('put' => $put));
$mail_order .= "</td></tr></table>";
   }
   else
   {
$mail_order = "<hr><table width=\"90%\" border=\"0\" align=\"center\" class=\"text\"><tr><td>";
$mail_order .= "<br><p align=\"center\" class=\"admin\"><b>".t("СООБЩЕНИЕ НЕ ОТПРАВЛЕНО")."!</b></p>";
$mail_order .= "&nbsp; » ".t("Произошла непредвиденная ошибка при попытке отправить сообщение.")."<br>";
$mail_order .= "&nbsp; » ".t("Пожалуйста, обратитесь к <b><a href=\"mailto:%mailadmin\">Администратору</a></b>.", array('mailadmin' => $mailadmin))."</p>";
$mail_order .= "</td></tr></table>";
   }
}







##############################################################################################
##############################################################################################
}







$mail_order .= "<hr>";
echo $mail_order;
?>