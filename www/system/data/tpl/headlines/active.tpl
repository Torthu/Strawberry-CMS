<li><?php echo ($tpl['post']['type'] == 'page' ? str_repeat('&nbsp;', ($tpl['post']['_']['level'] - 1)) : ''); ?>
<a href="<?php echo $tpl['post']['link']['post']; ?>"><?php echo $tpl['post']['title']; ?></a>
<?php if ($tpl['post']['if-right-have']){ ?>
<small>(<a href="<?php echo $config['http_script_dir']; ?>/index.php?mod=editnews&id=<?php echo $tpl['post']['id']; ?>" title="Редактировать новость">edit</a>
<a href="<?php echo $config['http_script_dir']; ?>/index.php?mod=editnews&action=delete&selected_news[]=<?php echo $tpl['post']['id']; ?>" title="Удалить новость">del</a>)</small>
<?php } ?></li>