<div id="news<?php echo $tpl['post']['id']; ?>" class="three">


<?php 
if (!empty($tpl['post']['prev-next']['prev']['title'])){ ?><small><a href="<?php echo $tpl['post']['prev-next']['prev']['link']; ?>">&laquo; <?php echo $tpl['post']['prev-next']['prev']['title']; ?></a></small><?php }
 if (!empty($tpl['post']['prev-next']['prev']['title']) and !empty($tpl['post']['prev-next']['next']['title'])){ ?>|<?php } 
 if (!empty($tpl['post']['prev-next']['next']['title'])){ ?><small><a href="<?php echo $tpl['post']['prev-next']['next']['link']; ?>"><?php echo $tpl['post']['prev-next']['next']['title']; ?> &raquo;</a></small><?php } 
 ?>



<div class="theme" style="padding-left: 20px;"><a href="<?php echo $tpl['post']['link']['post']; ?>" class="arttit" title="<?php echo $tpl['post']['author']." ".$config['delitel']." ".$tpl['post']['title']; ?>"><?php echo $tpl['post']['title']; ?></a> <small>(категория: <?php echo $tpl['post']['category']['name']; ?>)</small><br><font class="smtext" style="padding-left: 20px;"><?php if(!empty($tpl['post']['ago'])) { ?>Опубликовано: <?php echo $tpl['post']['ago']; ?> - <?php } ?> <?php echo $tpl['post']['date']; ?> <?php if ($tpl['post']['pages']){ ?>(<?php echo $tpl['post']['pages']; ?>)<?php } ?></font></div>
<div class="four"><div class="nbtext"><?php echo (!empty($tpl['post']['full-story']) ? $tpl['post']['full-story'] : $tpl['post']['short-story']); ?><?php if (!empty($tpl['post']['attachment'])){ ?><br><br><b>» Прикреплённые файлы:</b><?php echo $tpl['post']['attachment']; ?><br><?php } ?></div></div>

<div class="smtext" style="display:inline; width: 100%; text-align: right;"><form name="cnpostrating" method="post" style="margin: 0; padding: 0;">
» просмотров: <?php echo $tpl['post']['views']; ?>
<?php if ($tpl['post']['comments'] and $config['use_comm']){ ?> » <a href="<?php echo $tpl['post']['link']['post']; ?>#comments" title="Комментарии к новости">комментарии (<?php echo $tpl['post']['comments']; ?>)</a><?php } ?> » <a target="_blank" href="<?php echo $tpl['post']['link']['home/print.php/post']; ?>" title="<?php echo $tit." - ".$tpl['post']['title']; ?> - Версия для печати">печать</a> » <a target="_blank" href="<?php echo $tpl['post']['link']['home/rss.php/post']; ?>" title="<?php echo $tit." - ".$tpl['post']['title']; ?> - RSS версия">rss</a> » <a href="<?php echo $tpl['post']['link']['trackback.php/post']; ?>">TrackBack</a>
<?php if (function_exists('rating')) { ?>
 » <span title="Рейтинг/Голос: <?php echo tpl('rating')."/".$tpl['post']['votes']; ?>">рейтинг: <?php echo tpl('rating'); ?></span> 
<?php  if ( !rating('check') ) { ?>
 » оценить новость: <select size="1" name="rating_<?php echo $tpl['post']['id']; ?>">
 <option value="0">0</option>
 <option value="1">1</option>
 <option value="2">2</option>
 <option value="3">3</option>
 <option value="4">4</option>
 <option value="5">5</option>
</select><input class="ratingok" type="submit" value="ok">
<?php } } ?></form></div>


<?php echo (!empty($tpl['news']['1']['main']) ? $tpl['news']['1']['main'] : ""); ?>
<?php echo (!empty($tpl['news']['2']['main']) ? $tpl['news']['2']['main'] : ""); ?>
<?php echo (!empty($tpl['news']['3']['main']) ? $tpl['news']['3']['main'] : ""); ?>
<?php echo (!empty($tpl['news']['4']['main']) ? $tpl['news']['4']['main'] : ""); ?>
<?php echo (!empty($tpl['news']['5']['main']) ? $tpl['news']['5']['main'] : ""); ?>
</div>
<br>
<br>


