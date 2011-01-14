<html>
<head>
<title>::: KARMA :::</title>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<style type="text/css">
body {color:#000; background-color:#fff;}
.text {height:26px; font-family:verdana; font-size:10; cursor:pointer; padding: 0px 0px 0px 15px;}
a:link,a:active,a:visited{font-size: 8pt; font-weight: bold; font-family: Verdana; cursor:hand; text-decoration:none; color:#555}
a:hover{font-size: 8pt; font-weight: bold; font-family: Verdana; cursor:hand; text-decoration:underline; color:#000}
</style>
</head>
<body>

<table border="0" class="text">
<tr>
<td><?php echo $tpl['money']['date']; ?> пользователь <b><?php echo $tpl['money']['from']; ?></b> <u><?php echo ($tpl['money']['action'] == '+' ? 'добавил' : 'убавил'); ?> популярности</u> под предлогом:
<blockquote>» <?php echo $tpl['money']['motivation']; ?></blockquote>
</td>
</tr>
</table>

</body>
</html>