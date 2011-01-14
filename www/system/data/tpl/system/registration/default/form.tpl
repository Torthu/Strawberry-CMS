
<form method="post" action="">
<table class="over" width="98%" border="0" align="center" cellspacing="2" cellpadding="5" class="nbtext">
<tr><td class="nbtext" colspan="2"><b>Что даёт регистрация?</b><br>- Вы сможете добавлять свои новости.<br>- Если вы захотите оставить комментарий или сообщение в гостевой книге, то не надо будет заполнять поля аунтификации.<hr></td></tr>
<tr><td width="10%" class="text">&nbsp;&nbsp;Логин:&nbsp;</td><td width="90%"><input type="text" name="register[login]" class="regtext"></td></tr>
<tr><td colspan="2" class="nbtext moder">Логин используется для авторизации в системе. Используйте только латиницу!</td></tr>
<tr><td class="text">&nbsp;&nbsp;Пароль:&nbsp;</td><td><input type="password" name="register[passw1]" class="regtext"></td></tr>
<tr><td colspan="2" class="nbtext moder">Пароль будет использоваться для заполнения формы входа в систему.</td></tr>
<tr><td class="text">&nbsp;&nbsp;Еще раз:&nbsp;</td><td><input type="password" name="register[passw2]" class="regtext"></td></tr>
<tr><td colspan="2" class="nbtext moder">Необходимо проверить правильность набранного пароля. Для этого его нужно ввести еще раз.</td></tr>
<tr><td class="text">&nbsp;&nbsp;Ник/Имя:</td><td><input type="text" name="register[nick]" class="regtext"></td></tr>
<tr><td colspan="2" class="nbtext moder">Указанное вами Имя или Ник будет подставляться в качестве имени автора оставившего сообщение.</td></tr>
<tr><td class="text">&nbsp;&nbsp;E-Mail:&nbsp;</td><td><input type="text" class="regtext" name="register[email]"></td></tr>
<tr><td colspan="2" class="nbtext moder">E-Mail необходим для восстановления пароля и для указания того, как связаться с автором сообщения.</td></tr>
<tr><td class="text" colspan="2">
{lang.terms}
</td></tr>
<tr><td colspan="2" class="text"><label><input class="chek" type="checkbox" name="register[autologin]" value="true"> Вас запомнить?</label></td></tr>
<tr><td colspan="2" class="nbtext moder">Данная опция позволит вашему браузеру запомнить параметры входа в систему. Таким образом вход будет выполняться автоматически. Данная опция работает если у вас поддерживаются COOKIES!</td></tr>
<tr><td colspan="2" align="center">{reg.pin}</td></tr>
<tr><td colspan="2" align="center"><input type="submit" class="regok" style="width:50%" value="РЕГИСТРИРОВАТЬ"></td></tr>
</table>
<input type="hidden" name="step" value="2">
</form>