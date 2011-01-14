<item>
<title><?php echo htmlspecialchars($tpl['comment']['author']); ?></title>
<dc:creator><?php echo htmlspecialchars($tpl['comment']['author']); ?></dc:creator>
<link><?php echo htmlspecialchars($tpl['post']['link']['post']); ?>#comment<?php echo $tpl['comment']['number']; ?></link>
<guid><?php echo htmlspecialchars($tpl['post']['link']['post']); ?>#comment<?php echo $tpl['comment']['number']; ?></guid>
<description><?php echo htmlspecialchars($tpl['comment']['story']); ?></description>
<pubDate><?php echo date('r', $tpl['comment']['_']['date']); ?></pubDate>
</item>