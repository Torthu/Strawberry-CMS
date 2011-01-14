<table width="98%" border="0" cellspacing="2" cellpadding="2" class="over" align="center">
<tr class="moder">
<td class="text">ПОЗДРАВЛЯЮ!<br>Регистрация прошла успешно! Теперь вы можете выполнить вход!</td>
</tr>
</table>

<form name="login" action="" method="post" onsubmit="return process_form(this)">
<table border="0" align="center" width="50%" cellpadding="2" class="smtext">
<tr>
<td width="50">&nbsp;&nbsp;Логин:&nbsp;</td>
<td width="170"><input tabindex="1" type="text" name="username" class="regtext" value="введите ваш логин"></td>
</tr>
<tr>
<td>&nbsp;&nbsp;Пароль:&nbsp;</td>
<td><input type="password" name="password" class="regtext"></td>
</tr>
<tr><td colspan="2" align="center">{reg.pin}</td></tr>
<tr>
<td colspan="2" align="center"><input accesskey="s" type="submit" class="regok" value="ВХОД"></td>
</tr>
</table>
<input type="hidden" name="action" value="dologin">
</form>