<?php
echo "<hr>
<form action=\"\" method=\"post\">
<table border=\"0\" width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"2\" id=\"mail_form\" class=\"nbtext\">
<tr class=\"text\">
<td class=\"admin\" width=\"50%\">".t("Ваше имя (ник)*").":</td>
<td width=\"50%\" class=\"admin\">".t("Ваш e-mail*").":</td></tr>
<tr>
<td>";

if (!empty($is_logged_in)) {

echo "<u>".$member['name']."</u></td>";
$namesender = $member['name'];
##$nmsndr = $member['name']." <small>(login: ".$member['username'].", number: ".$member['id'].")</small>";
if(!empty($member['mail'])) {
$emailsender = $member['mail'];
if (!empty($member['hide_mail'])) echo "<td>".$member['mail'];
} else {
echo "<td><input maxlength=\"35\" name=\"emailsender\" class=\"regtext\" value=\"".$emailsender."\">";
}
echo "</td></tr><tr class=\"text\"><td>".t("Ваш номер ICQ").":</td><td>".t("Ваш сайт").":</td></tr><tr><td>";

if(!empty($member['icq'])) {
$isqsender = $member['icq'];
echo "".$member['icq']."</td>";
} else {
echo "<input maxlength=\"11\" name=\"isqsender\" class=\"regtext\" value=\"".$isqsender."\"></td>";
}

if(!empty($member['homepage'])) {
$sitesender = rurl($member['homepage']);
echo "<td>".$member['homepage'];
} else {
echo "<td><input maxlength=\"30\" name=\"sitesender\" class=\"regtext\" value=\"".$sitesender."\">";
}

} else {

echo "<input maxlength=\"80\" name=\"namesender\" class=\"regtext\" value=\"".$namesender."\"></td>
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

echo "</td>
</tr>
<tr class=\"text\">
<td>".t("Тема письма*").":</td>
<td>".t("Своя тема письма").":</td>
</tr>
<tr>
<td><select name=\"subjectsender\" size=\"1\" id=\"subjectsender\"><option value=\"".t("Общий вопрос")."\" selected>".t("Общий вопрос")."</option><option value=\"".t("Сотрудничество")."\">".t("Сотрудничество")."</option><option value=\"".t("Реклама")."\">".t("Реклама")."</option></select></td>
<td><input maxlength=\"300\" name=\"themesender\" class=\"regtext\" value=\"".$themesender."\"></td>
</tr>
<tr>
<td colspan=\"2\"><input type=\"checkbox\" ".$cpp." name=\"sendcopy\" value=\"1\"> ".t("Прислать вам копию письма?")."</td>
</tr>
<tr class=\"text\">
<td colspan=\"2\" class=\"admin\">".t("Текст вашего сообщения*").":</td>
</tr>
<tr>
<td colspan=\"2\" align=\"center\"><textarea name=\"textsender\" class=\"gb\">".$textsender."</textarea></td>
</tr>";

if (empty($is_logged_in) and $config['pin']) {
echo "<tr><td colspan=\"2\" align=\"center\">".pin_cod("default", "mail")."</td></tr>";
}

echo "<tr align=\"center\">
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
?>