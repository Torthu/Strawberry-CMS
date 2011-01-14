<div class="post">
<?php if ($tpl['post']['prev-next']['prev']['title']){ ?>
<small><a href="<?php echo $tpl['post']['prev-next']['prev']['link']; ?>">&laquo; <?php echo $tpl['post']['prev-next']['prev']['title']; ?></a></small>
<?php } ?>
<?php if ($tpl['post']['prev-next']['prev']['title'] and $tpl['post']['prev-next']['next']['title']){ ?>
|
<?php } ?>
<?php if ($tpl['post']['prev-next']['next']['title']){ ?>
<small><a href="<?php echo $tpl['post']['prev-next']['next']['link']; ?>"><?php echo $tpl['post']['prev-next']['next']['title']; ?> &raquo;</a></small>
<?php } ?>
<br /><br />

<div id="news<?php echo $tpl['post']['id']; ?>" class="<?php echo $tpl['post']['alternating']; ?>">
<div class="date"><?php echo $tpl['post']['date']; ?> <small>(<?php echo $tpl['post']['ago']; ?>)</small></div>
<div class="title">
<a href="<?php echo $tpl['post']['link']['post']; ?>"><?php echo $tpl['post']['title']; ?></a>
<?php if ($tpl['post']['pages']){ ?>
<small>(<?php echo $tpl['post']['pages']; ?>)</small>
<?php } ?>
</div>
<hr align="left">
<div class="story">
<?php echo ($tpl['post']['full-story'] ? $tpl['post']['full-story'] : $tpl['post']['short-story']); ?>

<?php if ($tpl['post']['attachment']){ ?>
<p>
<b>Прикреплённые файлы:</b>
<?php echo $tpl['post']['attachment']; ?>
</p>
<?php } ?>
</div>
<hr align="right">
<div class="attr">
<?php if ($tpl['post']['if-right-have']){ ?>
действие:
<a href="<?php echo $config['http_script_dir']; ?>/index.php?mod=editnews&id=<?php echo $tpl['post']['id']; ?>" title="Редактировать новость">edit</a>
/ <a href="<?php echo $config['http_script_dir']; ?>/index.php?mod=editnews&action=delete&selected_news[]=<?php echo $tpl['post']['id']; ?>" title="Удалить новость">del</a>
<br />
<?php } ?>
<?php if ($tpl['post']['category']['name']){ ?>
категория: <?php echo $tpl['post']['category']['name']; ?> /
<?php } ?>
<?php if ($tpl['post']['keywords']['name']){ ?>
ключслова: <?php echo $tpl['post']['keywords']['name']; ?> /
<?php } ?>
<a href="<?php echo $tpl['post']['link']['trackback.php/post']; ?>">trackback</a>
/ <a href="<?php echo $tpl['post']['link']['print.php/post']; ?>">печать</a>
/ <a href="<?php echo $tpl['post']['link']['rss.php/post']; ?>">rss комментариев</a>
</div>
</div>
</div>