<a name="<?php echo $tpl['comment']['number']; ?>"></a>
<div class="comment" style="margin-left: <?php echo ($tpl['comment']['level'] * 20); ?>px;">
<div id="comment<?php echo $tpl['comment']['id']; ?>" class="<?php echo $tpl['comment']['alternating']; ?>">
<div class="date"><?php echo $tpl['comment']['date']; ?></div>
<div class="title"><?php echo $tpl['comment']['author']; ?> (<a href="javascript:insertext('[b]<?php echo $tpl['comment']['author']; ?>[/b], ', '', 'short')">вст</a>)
<?php if ($tpl['comment']['homepage']){ ?>
<small>(<a href="<?php echo $tpl['comment']['homepage']; ?>"><?php echo $tpl['comment']['homepage']; ?></a>)</small>
<?php } ?>
</div>
<hr align="left">
<div class="story"><?php echo $tpl['comment']['avatar']; ?><?php echo $tpl['comment']['story']; ?><?php if ($tpl['comment']['answer']) { ?>
<table cellpadding="2" cellspacing="2" width="98%" align="right" class="cntnt">
<tr><td>
<table width="70%" align="right" class="nbtext"><tr><td><b class="admin"><?php echo $tpl['comment']['admin']; ?></b> отвечает:<br><?php echo $tpl['comment']['answer']; ?></td></tr></table>
</td></tr></table>
<?php } ?></div>
<hr align="right">
<div class="attr">
действие:
<?php if ($tpl['comment']['if-right-have']){ ?>
<a href="system/index.php?mod=comments&amp;act=view&amp;id=<?php echo $tpl['comment']['id']; ?>" target="_blank" title="Редактировать комментарий">правка</a>
<a href="system/index.php?mod=comments&amp;act=delete&amp;id=<?php echo $tpl['comment']['id']; ?>&amp;nid=<?php echo $tpl['comment']['post_id']; ?>" target="_blank" title="Удалить комментарий">удалить</a>
<?php } ?>
<a href="#" id="reply<?php echo $tpl['comment']['id']; ?>" onclick="quickreply(<?php echo $tpl['comment']['id']; ?>); return false;">ответить</a>
</div>
</div>
</div>