<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $config['charset']; ?>">
<title><?php echo $config['home_title']." ".$config['delitel']; ?> ¬Õ»Ã¿Õ»≈: <?php echo $tpl['err']['title']; ?></title>
<link href="<?php echo way("system/admin/themes/default/style.css"); ?>" rel="stylesheet" type="text/css" media="screen">
<screept type="text/javascript" src="<?php echo way("system/data/java/tooltip.js"); ?>"></screept>
</head>

<body>


<table border="0" align="center" cellpadding="0" cellspacing="0" width="96%">
<tr>
<td align="center" height="24" class="main"><?php echo $tpl['err']['menu']; ?></td>
</tr>
<tr>
<td height="19">
<div class="header"><img border="0" src="<?php echo way("system/admin/images/error.gif"); ?>" align="absmiddle" vspace="2" title="<?php echo $config['home_title']." ".$config['delitel']." ".$tpl['err']['title']; ?>">†<?php echo $tpl['err']['title']; ?></div>

<table border="0" cellpading="0" cellspacing="10" width="100%">
<tr><td valign="top"><?php echo $tpl['err']['message'];?></td></tr>
</table>

</td>
</tr>
<tr>
<td height="24" align="center" class="copyrights">{copyrights}</td>
</tr>
</table>


</body>
</html>