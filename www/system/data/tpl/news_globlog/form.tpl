<a name="commentors"></a>
<noscript><div class="error_message">Вам нужно разрешить использовать JavaScript, иначе комментарий вы не добавите.</div><br></noscript>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="7">&nbsp;</td>
				<td>
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php if (!$tpl['if-logged']){ ?>
<tr>
<td width="200" style="padding-left: 15px;">Имя:</td>
<td style="padding: 5px;"><input class="regtext" type="text" name="name" value="<?php echo $tpl['form']['saved']['name']; ?>"></td>
</tr>
<tr>
<td style="padding-left: 15px;">E-Mail:</td>
<td style="padding: 5px;"><input class="regtext" type="text" name="mail" value="<?php echo $tpl['form']['saved']['mail']; ?>"></label></td>
</tr>
<tr>
<td style="padding-left: 15px;">URL:</td>
<td style="padding: 5px;"><input class="regtext" type="text" name="homepage" value="<?php echo ($tpl['form']['saved']['homepage'] ? $tpl['form']['saved']['homepage'] : 'http://'); ?>"></td>
</tr>
<tr>
<td style="padding-left: 15px;" colspan="2" height="1"><?php if ($tpl['form']['pin'] == 1) { echo pin_cod("default"); } ?></td>
</tr>
<tr align="left">
<td><input name="captcha" class="sstupid"></td>
<td><label for="rememberme" align="center"><input type="checkbox" id="rememberme" name="rememberme" value="on" checked> Запомнить вас?</label></td>
</tr>
<?php } ?>
<tr>
<td width="200" valign="top" style="padding-left: 5px;"><br />
<a href="javascript:ShowOrHide('bbcodes');"><img src="system/skins/themes/globlog/images/arr_bot.gif" border="0" />BB-Codes</a><br />
<div id="bbcodes" style="display : none;"><br /><noindex><?php echo $tpl['form']['tags']; ?></noindex></div></td>

<td valign="top"><br />
<a href="javascript:ShowOrHide('smilies');"><img src="system/skins/themes/globlog/images/arr_bot.gif" border="0" />Смайлики</a><br />
<div id="smilies" style="display : none;"><br /><noindex><?php echo $tpl['form']['smilies']; ?></noindex></div></td>
</tr>
<tr>
<td colspan="2" style="padding: 15px;">
<textarea class="gb" name="commin_story" style="overflow-x: hidden; overflow-y: visible;" onclick="submit.disabled = false;"></textarea>
</td>
</tr>
<tr>
<td style="padding: 15px;" align="left" colspan="2"><nobr><label for="sendcomments"><input type="checkbox" id="sendcomments" name="sendcomments" value="on"> Посылать комментарии на ваш e-mail?</label>&nbsp;&nbsp;
<span style="padding: 0px 15px 0px 0px;"><input type="submit" name="submit" value=" Добавить " accesskey="s" style="cursor: pointer;"></span></nobr></td>
</tr>
</table>
				</td>
				<td width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				</td>
				<td width="100%"></td>
				<td>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

