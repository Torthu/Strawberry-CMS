
<item>
<title><?php echo htmlspecialchars($tpl['post']['title']); ?></title>
<description><?php echo htmlspecialchars($tpl['post']['short-story']); ?></description>
<pubDate><?php echo date('r', $tpl['post']['f_date']); ?></pubDate>
<link><?php echo $config['http_home'].'/'.$tpl['post']['link']['post']; ?></link>
<guid><?php echo $config['http_home'].'/'.$tpl['post']['link']['post']; ?></guid>
<comments><?php echo $config['http_home'].'/'.$tpl['post']['link']['post'].'#comments'; ?></comments>
<?php if ($tpl['post']['category']['name']) { ?>
<?php foreach (explode(',', $tpl['post']['f_category']) as $cat){ ?>
<category><?php echo htmlspecialchars($categories[$cat]['name']); ?></category>
<?php } ?>
<?php } ?>
<author><?php echo htmlspecialchars($users[$row['author']]['name']); ?></author>
</item>
