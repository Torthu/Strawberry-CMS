<?php
$skin_prefix = 'serenity';
ob_start();
?>
<div id="nav">
<a href="<?php echo $PHP_SELF; ?>?mod=main" title="������� �������� �������"><?php echo t('�������'); ?></a>
<a href="<?php echo $PHP_SELF; ?>?mod=addnews" title="���������� �������"><?php echo t('��������'); ?></a>
<a href="<?php echo $PHP_SELF; ?>?mod=editnews" title="�������������� ��������"><?php echo t('�������������'); ?></a>
<a href="<?php echo $PHP_SELF; ?>?mod=options" title="��������� ���������� �������"><?php echo t('���������'); ?></a>
<a href="<?php echo $PHP_SELF; ?>?mod=help" title="������� � ������ �� ������������� �������"><?php echo t('������'); ?></a>
<?php /*if ($config['cache']){*/ ?>
<a href="<?php echo $PHP_SELF; ?>?mod=main&action=clearcache" title="������� ���� �������"><?php echo t('�������� ���'); ?></a>
<?php /*}*/ ?>
<a href="<?php echo $PHP_SELF; ?>?mod=logout" title="����� �� �������"><?php echo t('�����'); ?></a></td>
<a target="_blank" href="<?php echo $config['http_home_url']; ?>" title="������� �� ������� �������� �����"><?php echo t('�� ����'); ?></a>
</div>

<?php
$skin_menu = ob_get_clean();
ob_start();
?>

<!-- ����� �� ������ ����� -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $config['charset']; ?>">
<title><?php echo $config['home_title']." ".$config['delitel']; ?> ����������</title>
<link href="admin/themes/<?php echo $skin_prefix; ?>/style.css" rel="stylesheet" type="text/css" media="screen">
<!--[if IE]><style> #category, #trackbacks { margin-right:5px; } </style><![endif]-->
<script type="text/javascript" src="data/java/prototype.js"></script>
<script type="text/javascript" src="data/java/ajax.js"></script>
<script type="text/javascript" src="data/java/cute.js"></script>
<script type="text/javascript" src="data/java/tooltip.js"></script>
</head>

<body>

<div id="wrapper">
<div id="logo"></div>

{menu}

<br />
<table border="0" cellpadding="0" cellspacing="10" id="maintable">
<tr>
<td id="header" colspan="2"><img border="0" src="{image-name}" align="absmiddle" vspace="5" hspace="5">�{header-text}</td>
</tr>

<tr>
<?php if (!empty($is_logged_in)){ ?>
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