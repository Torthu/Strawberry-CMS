<?php if (empty($tpl['user']['header'])) { ?>
<?php
$tpl['user']['header'] = true; 
$ac = $tpl['user']['count'];
?>
<tr class="smtext">
<th width="30%" class="c4">- Имя -</th>
<th width="20%" class="c4">Зерегистрирован</th>
<th width="20%" class="c4">Последний визит</th>
<th width="30%" class="c4">- Группа -</th>
</tr>
<?php 
}

$page = !empty($_GET['pnum']) ? $_GET['pnum'] : 1; 
$aa++;
if (($aa >= (($page-1)*$tpl['user']['on_page']+1)) and ($aa <= (($page-1)*$tpl['user']['on_page']+$tpl['user']['on_page']))) {
?>

<tr class="out" onmouseover="this.className='over'" onmouseout="this.className='out'">
<td class="text">&nbsp; » <a href="index.php?mod=account&act=users&amp;user=<?php echo $tpl['user']['username']; ?>"><?php echo $tpl['user']['name']; ?></a></td>
<td class="smnbtext" align="center"><?php echo $tpl['user']['date']; ?></td>
<td class="smnbtext" align="center"><?php echo $tpl['user']['last_visit']; ?></td>
<td class="smtext" align="center"><?php $ug = $tpl['user']['usergroup'];
 if ($ug == 'Администраторы')
{ echo "<font class=\"admin\">".$tpl['user']['usergroup']."</font>"; }
 elseif ($ug == 'Журналисты')
{ echo "<font class=\"c3\">".$tpl['user']['usergroup']."</font>"; }
 elseif ($ug == 'Модераторы')
{ echo "<font class=\"moder\">".$tpl['user']['usergroup']."</font>"; }
 else
{ echo "<font class=\"c4\">".$tpl['user']['usergroup']."</font>"; }

echo "</td></tr>";
}
?>