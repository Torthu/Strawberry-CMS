
<entry>
<title type="html"><?php echo htmlspecialchars($tpl['comment']['author']); ?></title>
<link rel="alternate" href="<?php echo htmlspecialchars($tpl['post']['link']['post']); ?>#comment<?php echo $tpl['comment']['number']; ?>"/>
<summary type="html"><?php echo htmlspecialchars($tpl['comment']['story']); ?></summary>
<author>
<name><?php echo htmlspecialchars($tpl['comment']['author']); ?></name>
<uri><?php echo $config['http_home_url']; ?></uri>
</author>
<updated><?php echo date('r', $tpl['comment']['_']['date']); ?></updated>
<id><?php echo htmlspecialchars($tpl['post']['link']['post']); ?>#comment<?php echo $tpl['comment']['number']; ?></id>
</entry>
