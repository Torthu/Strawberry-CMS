<?php
$skin_prefix = 'default';

ob_start();
?>

<td><a class="nav" href="<?php echo $PHP_SELF; ?>?mod=main"><?php echo t('Статистика'); ?></a></td>
<td>|</td>
<td><a class="nav" href="<?php echo $PHP_SELF; ?>?mod=addnews"><?php echo t('Добавить'); ?></a></td>
<td>|</td>
<td><a class="nav" href="<?php echo $PHP_SELF; ?>?mod=editnews"><?php echo t('Редактировать'); ?></a></td>
<td>|</td>
<td><a class="nav" href="<?php echo $PHP_SELF; ?>?mod=options"><?php echo t('Настройки'); ?></a> <?php echo makePlusMinus('options-submenu'); ?></td>
<td>|</td>
<td><a class="nav" href="<?php echo $PHP_SELF; ?>?mod=help"><?php echo t('Помощь'); ?></a></td>
<td>|</td>
<td><a class="nav" href="<?php echo $PHP_SELF; ?>?mod=main&action=clearcache"><?php echo t('Очистить кэш'); ?></a></td>
<td>|</td>
<td><a class="nav" href="<?php echo $PHP_SELF; ?>?mod=logout"><?php echo t('Выход'); ?></a></td>
<td>|</td>
<td><a target="_blank" class="nav" href="<?php echo $config['http_home_url']; ?>"><?php echo t('На сайт'); ?></a></td>
<tr id="options-submenu" style="display: none;">
<td colspan="7"></td>
<td colspan="7"><?php echo options_submenu(); ?></td>

<?php
$skin_menu = ob_get_clean();

ob_start();
?>

<!-- Зайцы инсайд -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $config['charset']; ?>">
<title><?php echo $config['home_title']." ".$config['delitel']; ?> АдминЦентр</title>
<link href="admin/themes/<?php echo $skin_prefix; ?>/style.css" rel="stylesheet" type="text/css" media="screen">
<script type="text/javascript" src="data/java/prototype.js"></script>
<script type="text/javascript" src="data/java/ajax.js"></script>
<script type="text/javascript" src="data/java/cute.js"></script>
<script type="text/javascript" src="data/java/tooltip.js"></script>
</head>

<body>

  
<table border="0" align="center" cellpadding="2" cellspacing="0">
<tr>
<td class="bborder">
<table border="0" cellpadding="0" cellspacing="0" width="700">
<tr>
<td align="center" height="24" class="main">
<table border="0" cellspacing="0" cellpadding="5">
<tr>
<td>{menu}</td>
</tr>
</table>
<tr>
<td height="19">
<table border="0" cellpading="0"cellspacing="15" width="100%" height="100%">
<tr>
<td><div class="header"><img border="0" src="admin/themes/<?php echo $skin_prefix; ?>/images/{image-name}.gif" align="absmiddle"> {header-text}</div></td>
<tr>
<td width="100%" height="100%">

<?php
$skin_header = ob_get_clean();

ob_start();
?>

</td></tr>
</table>
</td>
</tr>
<tr>
<td height="24" align="center" class="copyrights">{copyrights}</td>
</tr>
</table>
</td>
</tr>
</table>

</body>
</html>

<?php
$skin_footer = ob_get_clean();
?>