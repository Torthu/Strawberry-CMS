
echo ("<li><a href='<?php echo $config['http_home'].'/'.$tpl['post']['link']['post']; ?>'><?php echo htmlspecialchars($tpl['post']['title'], ENT_QUOTES); ?></a><?php echo !empty($id) ? "<br />".$tpl['post']['short-story'] : ""; ?></li>"); 
