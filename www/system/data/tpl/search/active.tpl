<hr>
<table cellpadding="1" cellspacing="1" width="100%" class="out" onmouseover="this.className='over'" onmouseout="this.className='out'">
<tr>
<td width="20" height="20" align="center" valign="middle" class="searchsep">»</td>
<td class="text" width="100%"><a href="index.php?id=<?php echo $tpl['post']['id']; ?>"><?php echo $tpl['post']['title']; ?></a></td>
<td width="40" align="center" class="text"><?php if (!empty($tpl['post']['if-right-have'])){ ?><small>(<a href="<?php echo $config['http_script_dir']; ?>/index.php?mod=editnews&amp;id=<?php echo $tpl['post']['id']; ?>" title="Редактировать новость">edit</a>)</small><?php } ?></td>
</tr>
<tr>
<td colspan="3" class="smnbtext"><?php echo $tpl['post']['short-story']; ?></td>
</tr>
</table>
<hr>