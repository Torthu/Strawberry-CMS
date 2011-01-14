<?php
/*
Theme Name: Serenity
Theme URI: http://strawberry.goodgirl.ru
Version: 1.2
Author: Chaser <coderunnerz@gmail.com>
*/

$skin_prefix = 'serenity_reload';
ob_start();
?>
<a href="<?php echo $PHP_SELF; ?>?mod=main" title="Главная страница системы"><?php echo t('Главная'); ?></a>
<a href="<?php echo $PHP_SELF; ?>?mod=addnews" title="Добавление новости"><?php echo t('Добавить'); ?></a>
<a href="<?php echo $PHP_SELF; ?>?mod=editnews" title="Редактирование новостей"><?php echo t('Редактировать'); ?></a>
<a href="<?php echo $PHP_SELF; ?>?mod=options" title="Настройка параметров системы"><?php echo t('Настройки'); ?></a>
<a href="<?php echo $PHP_SELF; ?>?mod=help" title="Справка и советы по использованию системы"><?php echo t('Помощь'); ?></a>
<?php /*if ($config['cache']){*/ ?>
<a href="<?php echo $PHP_SELF; ?>?action=clearcache" title="Очистка кэша системы"><?php echo t('Очистить кэш'); ?></a>
<?php /*}*/ ?>
<a href="<?php echo $PHP_SELF; ?>?mod=logout" title="Выход из системы"><?php echo t('Выход'); ?></a></td>
<a target="_blank" href="<?php echo $config['http_home_url']; ?>" title="Переход на главную страницу сайта"><?php echo t('На сайт'); ?></a>

<?php
$skin_menu = ob_get_clean();
ob_start();
?>

<!-- Зайцы ПЦ инсайд рулят -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $config['charset']; ?>">
<title><?php echo $config['home_title']." ".$config['delitel']; ?> АдминЦентр</title>
<link href="admin/themes/<?php echo $skin_prefix; ?>/style.css" rel="stylesheet" type="text/css" media="screen">
<!--[if IE]><style> #category, #trackbacks { margin-right:5px; } </style><![endif]-->
<script type="text/javascript" src="data/java/prototype.js"></script>
<script type="text/javascript" src="data/java/ajax.js"></script>
<script type="text/javascript" src="data/java/cute.js"></script>
<script type="text/javascript" src="data/java/tooltip.js"></script>
</head>

<body>

<div id="wrapper">

<div id="nav">{menu}</div>
<div id="header"><img border="0" src="admin/themes/<?php echo $skin_prefix; ?>/images/{image-name}.gif"" align="absmiddle" vspace="5" hspace="5"> {header-text}</div>

<table border="0" cellpadding="0" cellspacing="10" id="maintable">
<tr>
<?php if ($is_logged_in){ ?>
<td class="admmenu"><?php echo options_menu_ap(); ?></td>
<?php } ?>

<td id="contentrow"><?php
$skin_header = ob_get_clean();
ob_start();
?></td>
</tr>
</table>

<div id="wrapfooter">
	{copyrights}
</div>

</div>

</body>
</html>

<?php
$skin_footer = ob_get_clean();
?>