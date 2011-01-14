<div class="comment" style="margin-left: <?php echo ($tpl['comment']['level'] * 20); ?>px;">
<div id="comment<?php echo $tpl['comment']['id']; ?>" class="<?php echo $tpl['comment']['alternating']; ?>">

<table cellpadding="2" cellspacing="2" width="98%" align="center" class="cntnt" align="left">
<tr><td class="text" align="left"><a name="<?php echo $tpl['comment']['id']; ?>"></a> <a href="javascript:insertext('[b]<?php echo $tpl['comment']['author']; ?>[/b], ', '', 'commin')"><font class="newstitle">» <?php echo $tpl['comment']['author']; ?></font></a> <small><?php if ($tpl['comment']['homepage'] or $tpl['comment']['mail']) { ?>(<?php if ($tpl['comment']['mail']) {?><a href="mailto:<?php echo $tpl['comment']['mail']; ?>">MAIL</a><?php } if ($tpl['comment']['homepage'] and $tpl['comment']['mail']) { ?>, <?php } if ($tpl['comment']['homepage']){ ?><a href="go.php?go=<?php echo $tpl['comment']['homepage']; ?>">URL</a><?php } ?>) : <?php } ?>(<?php echo $tpl['comment']['date']; ?>)</small></td></tr>
<tr><td class="nbtext" align="left"><div class="story"><?php echo $tpl['comment']['avatar']; ?><?php echo $tpl['comment']['story']; ?></div></td></tr>

<?php if (!empty($tpl['comment']['answer'])) { ?>
<tr><td style="padding: 0px 0px 0px 40px;">
<table width="70%" class="nbtext"><tr><td align="left"><b class="admin"><?php echo $tpl['comment']['admin']; ?></b> отвечает:<br><?php echo $tpl['comment']['answer']; ?></td></tr></table>
</td></tr>
<?php } ?>

<tr>
<td class="nbtext" align="left"><div class="attr">» действие:
<?php if (!empty($tpl['comment']['if-right-have'])){ ?>
<a href="system/index.php?mod=comments&amp;act=view&amp;id=<?php echo $tpl['comment']['id']; ?>" target="_blank" title="Редактировать комментарий">правка</a>
<a href="system/index.php?mod=comments&amp;act=delete&amp;id=<?php echo $tpl['comment']['id']; ?>&amp;nid=<?php echo $tpl['comment']['post_id']; ?>" target="_blank" title="Удалить комментарий">удалить</a>
<?php } ?>
<?php  if (!empty($member['name']) and $member['name'] != $tpl['comment']['author']){ ?>
<a href="#" id="reply<?php echo $tpl['comment']['id']; ?>" onclick="quickreply(<?php echo $tpl['comment']['id']; ?>); return false;" title="Ответить на комментарий от <?php echo $tpl['comment']['author']; ?>">ответить</a></td>
<?php } else { ?>
<a href="#" id="reply<?php echo $tpl['comment']['id']; ?>" onclick="quickreply(<?php echo $tpl['comment']['id']; ?>); return false;" title="Ответить на комментарий от <?php echo $tpl['comment']['author']; ?>">ответить</a></td>
<?php } ?>
</div></tr>
</table>

</div>
</div>
<br>