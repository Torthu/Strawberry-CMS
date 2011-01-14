
<entry>
<title><?php echo htmlspecialchars($tpl['post']['title']); ?></title>
<link rel="alternate" href="<?php echo $config['http_home'].'/'.$tpl['post']['link']['post']; ?>"/>
<summary type="html"><?php echo (htmlspecialchars($tpl['post']['full-story']) ? htmlspecialchars($tpl['post']['full-story']) : htmlspecialchars($tpl['post']['short-story'])); ?></summary>
<author>
<name><?php echo htmlspecialchars($users[$row['author']]['name']); ?></name>
<uri><?php echo $config['http_home_url']; ?></uri>
</author>
<updated><?php echo date('r', $tpl['post']['f_date']); ?></updated>
<id><?php echo $config['http_home'].'/'.$tpl['post']['link']['post']; ?></id>
</entry>
