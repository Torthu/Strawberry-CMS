<br>

<div class="block" style="height:14px;"></div>

<div id="news<?php echo $tpl['post']['id']; ?>" <?php if (!empty($tpl['post']['public'])) { echo "class=\"unpublished\""; } else { echo "class=\"three\""; } ?>>


<div class="ndte"><b><?php echo $tpl['post']['date']; ?></b></div>
<div class="theme"><b><a href="index.php?mod=news&amp;id=<?php echo $tpl['post']['id']; ?>" title="<?php echo $tit." - ".$tpl['post']['title']; ?>" class="theme"><?php echo $config['delitel']." ".$tpl['post']['title']; ?></a></b></div>
<?php if(!empty($tpl['post']['ago'])) { ?><div align="right" class="ndte"><?php echo t('опубликовано'); ?>: <?php echo $tpl['post']['ago']; ?></div><?php } ?>
<div class="four"><div class="nbtext"><?php echo $tpl['post']['short-story']; ?></div></div>

<div class="authnews">
<hr align="right" width="82%"><?php if (!empty($tpl['post']['if-right-have'])){ ?><?php echo t('Действие'); ?>: <a href="system/index.php?mod=editnews&amp;id=<?php echo $tpl['post']['id']; ?>" title="<?php echo t('Редактировать новость'); ?>">edit</a> / <a href="system/index.php?mod=editnews&amp;action=delete&amp;selected_news[]=<?php echo $tpl['post']['id']; ?>" title="<?php echo t('Удалить новость'); ?>">del</a><?php } ?> <?php echo t('Новость добавил'); ?>: <?php echo $tpl['post']['author']; ?>. <font  title="<?php echo t('Просмотров'); ?>: <?php echo $tpl['post']['views']; ?>"><?php echo t('Просмотров'); ?>: <?php echo $tpl['post']['views']; ?>.</font>  <?php if (function_exists('rating')) { ?><span title="<?php echo t('Рейтинг/Голос'); ?>: <?php echo tpl('rating')."/".$tpl['post']['votes']; ?>"><?php echo t('Рейтинг'); ?>: <?php echo tpl('rating'); ?>.</span><?php } ?>
<?php if (!empty($tpl['post']['comments']) and !empty($config['use_comm'])){ ?> <a href="index.php?mod=news&amp;id=<?php echo $tpl['post']['id']; ?>#comments" title="<?php echo t('Комментарии к новости'); ?>"><?php echo t('Комментариев'); ?>: <?php echo $tpl['post']['comments']; ?></a>.<?php 
} 
?> 
<a target="_blank" href="<?php echo $tpl['post']['link']['home/print.php/post']; ?>" title="<?php echo $tit." - ".$tpl['post']['title']; ?> - <?php echo t('Версия для печати'); ?>"><?php echo t('Печать'); ?></a>.  
<a target="_blank" href="<?php echo $tpl['post']['link']['home/rss.php/post']; ?>" title="<?php echo $tit." - ".$tpl['post']['title']; ?> - <?php echo t('RSS версия'); ?>">RSS</a>.
</div>


</div>
<br>