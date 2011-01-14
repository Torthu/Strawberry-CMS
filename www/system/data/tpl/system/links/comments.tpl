<table cellpadding="2" cellspacing="2" width="98%" align="center" class="cntnt">
<tr><td class="text"><a name="<?php echo $tpl['gb']['id']; ?>"></a>№<?php echo $tpl['gb']['number']; ?> <a href="javascript:insertext('[b]<?php echo $tpl['gb']['author']; ?>[/b], ', '', 'short')"><font class="newstitle">» <?php echo $tpl['gb']['author']; ?></font></a> <small><?php if ($tpl['gb']['homepage'] or $tpl['gb']['email'] or $tpl['gb']['icq']) { ?>(<?php if ($tpl['gb']['email']) {?><a href="mailto:<?php echo $tpl['gb']['email']; ?>">MAIL</a><?php } if ($tpl['gb']['homepage'] and $tpl['gb']['email']) { ?>, <?php } if ($tpl['gb']['homepage']){ ?><a href="go.php?go=<?php echo $tpl['gb']['homepage']; ?>">URL</a><?php } if ($tpl['gb']['homepage'] and $tpl['gb']['icq']) { ?>, <?php } if ($tpl['gb']['icq']){ ?> <a target="_blank" href="go.php?go=wwp.icq.com/scripts/search.dll?to=<?php echo $tpl['gb']['icq']; ?>"><?php echo $tpl['gb']['icq']; ?></a><?php } ?>) : <?php } ?>(<?php echo $tpl['gb']['date']; ?>)</small></td></tr>
<tr><td class="nbtext"><?php echo $tpl['gb']['avatar']; ?><?php echo $tpl['gb']['mesagge']; ?></td></tr>
<?php if ($tpl['gb']['admin']['answer']) { ?>

<tr><td>
<table width="70%" align="right" class="nbtext"><tr><td><b class="admin"><?php echo $tpl['gb']['admin']['name']; ?></b> отвечает:<br><?php echo $tpl['gb']['admin']['answer']; ?></td></tr></table>
</td></tr>

<?php } ?>
<?php if ($tpl['gb']['if-right-have']){ ?>
<tr><td class="sep"></td></tr>
<tr><td class="nbtext">» действие:
<a href="<?php echo $config['http_script_dir']; ?>/index.php?mod=gb&amp;act=view&amp;id=<?php echo $tpl['gb']['id']; ?>" target="_blank" title="Редактировать запись">edit</a>
<a href="<?php echo $config['http_script_dir']; ?>/index.php?mod=gb&amp;act=delete&amp;id=<?php echo $tpl['gb']['id']; ?>" target="_blank" title="Удалить комментарий">del</a>
</td></tr>
<?php } ?>

</table>
<br>