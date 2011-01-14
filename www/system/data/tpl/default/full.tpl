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
действие: <a href="system/index.php?mod=editnews&amp;id=<?php echo $tpl['post']['id']; ?>" title="Редактировать новость">edit</a> / <a href="system/index.php?mod=editnews&amp;action=delete&amp;selected_news[]=<?php echo $tpl['post']['id']; ?>" title="Удалить новость">del</a><br />
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
/ <span title="Рейтинг/Голос: <?php echo tpl('rating')."/".$tpl['post']['votes']; ?>">рейтинг: <?php echo tpl('rating'); ?></span>
<?php if ( !rating('check') ) { ?>
 / оценить новость:
<form name="cnpostrating" method="post" style="margin: 0; padding: 0;">
<select size="1" name="rating[<?php echo $tpl['post']['id']; ?>]">
 <option value="0">0</option>
 <option value="1">1</option>
 <option value="2">2</option>
 <option value="3">3</option>
 <option value="4">4</option>
 <option value="5">5</option>
</select>
<input type="submit" value="ok"><!-- голосуй! "суй-суй" - где-то отзывалось эхо -->
</form>
<?php } ?>
</div>
</div>
</div>