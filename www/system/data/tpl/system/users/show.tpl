<tr><td colspan="3" class="arttit" align="center"><?php echo $config['delitel']; ?> <u><?php echo $tpl['user']['name']; ?></u> <?php echo $config['delitel']; ?></td></tr>

<tr class="nbtext" valign="top">
<td width="90"><b>ICQ</b></td>
<td><?php echo $tpl['user']['icq']; ?></td>
<td rowspan="6" align="center" valign="middle"><?php echo $tpl['user']['avatar']; ?></td>
</tr>

<tr class="nbtext" valign="top">
<td><b>Web-сайт</b></td>
<td><?php echo $tpl['user']['homepage']; ?></td>
</tr>

<tr class="nbtext" valign="top">
<td><nobr><b>Живой Журнал</b></nobr></td>
<td><?php echo $tpl['user']['lj-username']; ?></td>
</tr>

<tr class="nbtext" valign="top">
<td><b>Откуда</b></td>
<td><?php echo $tpl['user']['location']; ?></td>
</tr>

<tr class="nbtext" valign="top">
<td><b>Публикаций</b></td>
<td><b class="c2"><?php echo $tpl['user']['publications']; ?></b></td>
</tr>

<tr class="nbtext" valign="top">
<td><b>Популярность</b></td>
<td><b class="c3"><?php 
if (!empty($member['username']) and $tpl['user']['username'] != $member['username']) { 
?><a href="#" onclick="giveMoney('<?php echo $tpl['user']['username']; ?>')">добавить (+)</a> / 
<a href="#" onclick="takeMoney('<?php echo $tpl['user']['username']; ?>')">отнять (-)</a> /<?php } ?> 
<a href="#" onclick="showMoney('<?php echo $tpl['user']['username']; ?>')">посмотреть операции</a>
</b>
<br>(+) Всего плюсов: <b class="c3"><?php echo $tpl['user']['money']['plus']; ?></b>
<br>(-) Всего минусов: <b class="c3"><?php echo $tpl['user']['money']['minus']; ?></b>
<br>Личный рейтинг популярности: <b class="c3"><?php echo $tpl['user']['money']['rating']; ?>%</b>
<br>Общий рейтинг популярности: <b class="c3"><?php echo $tpl['user']['money']['global_rating']; ?>%</b></td>
</tr>

<tr class="nbtext" valign="top">
<td><b>О себе</b></td>
<td colspan=2><?php echo $tpl['user']['about']; ?></td>
</tr>

<tr class="nbtext" valign="top">
<td><b>Вы можете:</b></td>
<td><?php
$a = $tpl['user']['name'];
$b = !empty($member['username']) ? $member['name'] : '';
if(!empty($member['username']) and $a == $b) echo "<a href=\"index.php?mod=account&act=profil\"><u>Редактировать свой профиль</u></a>;<br>";
?>
<a target="_blank" href="rss.php?user=<?php echo $tpl['user']['username']; ?>">Посмотреть последниe публикации <?php echo $tpl['user']['name']; ?>`a в RSS</a>;<br><a href="index.php?mod=account&act=users">Вернуться к списку пользователей</a>.</td>
</tr>