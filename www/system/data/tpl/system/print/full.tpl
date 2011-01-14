<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['home_title']." ".$config['delitel']." ".$tpl['post']['title']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style>
body, td {
	font-family: verdana, arial, sans-serif;
	color: #555;
	font-size: 11px;
}
h1, h2, h3, h4 {
	font-family: verdana, arial, sans-serif;
	color: #444;
	font-size: 13px;
	margin: 0px;
}
.link, .link a {
	color: #0000ff;
	text-decoration: underline;
}
</style>
</head>

<body bgcolor="#FFFFFF">

<table border="0" width="100%" cellspacing="1" cellpadding="3">
<tr>
<td align="left"><h3><?php echo $tpl['post']['title']; ?> 

<?php if (!empty($tpl['post']['comments'])){ 
if (empty($_GET['comm'])){ 
?>
<small>(<a href="<?php echo $PHP_SELF."?id=".$nid."&amp;comm=1"; ?>"><?php echo t('показать комментарии'); ?></a>)</small>
<?php
} else {
?>
<small>(<a href="<?php echo $PHP_SELF."?id=".$nid; ?>"><?php echo t('скрыть комментарии'); ?></a>)</small>
<?php
}}
?>


</h3><hr width="30%" align="left"><p><?php echo (!empty($tpl['post']['full-story']) ? $tpl['post']['full-story'] : $tpl['post']['short-story']); ?></p></td>
</tr>
</table>

<br>
<hr>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
  <td width="200"><nobr><?php echo t('Оригинал новости %ntit', array('ntit'=>'&laquo;'.$tpl['post']['title'].'&raquo;')); ?></nobr></td>
  <td width="20"><nobr>&nbsp;&nbsp;-&nbsp;&nbsp;</nobr></td>
  <td class="link"><?php echo $config['http_home'].'/'.$config['home_page']; ?>?id=<?php echo $tpl['post']['id']; ?></td>
</tr>
 <tr>
  <td><nobr>&laquo;<?php echo $config['home_title']; ?>&raquo;</nobr></td>
  <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
  <td class="link"><?php echo $config['http_home_url']; ?></td>
</tr>
</table>

</body>
</html>