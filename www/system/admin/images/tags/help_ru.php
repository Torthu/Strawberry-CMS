<?php
$bb_param_but = "border=\"0\" vspace=\"1\" hspace=\"1\"";
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Charmap</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<script language="javascript" type="text/javascript" src="charmap.js"></script>
	<link href="../../themes/default/style.css" rel="stylesheet" type="text/css" media="screen">
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td class="charmapOver" style="font-size: 30px; height:40px;">&nbsp;BB-Code</td></tr>
<tr><td style="font-size: 12px; font-family: Arial; text-align:left;">&nbsp;Краткое руководство к использованию BB-Code.</td></tr>
</table>


    <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0">
<tr><td><br><b>Оформление текста</b></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Жирный" src="b.gif" <?php echo $bb_param_but; ?>><br>Пример: [b]текст[/b]<br>Используется для придания эффекта <b>жирности тексту</b>.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Курсив" src="i.gif" <?php echo $bb_param_but; ?>><br>Пример: [i]текст[/i]<br>Используется для придания эффекта <i>наклонного текста</i>.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Подчеркнутый" src="u.gif" <?php echo $bb_param_but; ?>><br>Пример: [u]текст[/u]<br>Используется для придания эффекта <u>подчеркнутого текста</u>.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Зачеркнутый" src="s.gif" <?php echo $bb_param_but; ?>><br>Пример: [s]текст[/s]<br>Используется для придания эффекта <s>зачеркнутого текста</s>.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Цвет текста" src="color.gif" <?php echo $bb_param_but; ?>><br>Пример: [color=#008000]текст[/color]<br>Используется для придания цвета <font color="#008000">выделенному тексту</font>.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Размер шрифта" src="size.gif" <?php echo $bb_param_but; ?>><br>Пример: [size=3]текст[/size]<br>Используется для изменения <font size="3">размера</font> текста.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Выравнивание слева" src="align_l.gif" <?php echo $bb_param_but; ?>><img title="Выравнивание по центру" src="align_c.gif" <?php echo $bb_param_but; ?>><img title="Выравнивание справа" src="align_r.gif" <?php echo $bb_param_but; ?>><img title="Выравнивание по ширине" src="align_j.gif" <?php echo $bb_param_but; ?>><br>Пример: [left]текст[/left], [center]текст[/center], [right]текст[/right] и [justify]text[/justify]<br>Используется для выранивания текста слева, по центру, справа и по ширине соответственно.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Перенос строки" src="br.gif" <?php echo $bb_param_but; ?>><br>Пример: [br]<br>Используется для вставки символа после которого текст бдет продолжаться с новой строки.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Линия" src="hr.gif" <?php echo $bb_param_but; ?>><br>Пример: [hr]<br>Используется для вставки горизонтальной разделительной линии.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Подстрочный" src="sub.gif" <?php echo $bb_param_but; ?>><br>Пример: [sub]текст[/sub]<br>Используется для <sub>подстрочного текста</sub>.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Надстрочный" src="sup.gif" <?php echo $bb_param_but; ?>><br>Пример: [sup]текст[/sup]<br>Используется для <sup>надстрочного текста</sup>.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Параграф" src="p.gif" <?php echo $bb_param_but; ?>><br>Пример: [p]текст[/p]<br>Используется для создания параграфа в тексте.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Cписок" src="ul.gif" <?php echo $bb_param_but; ?>><br>Пример: [ul]текст[/ul]<br>Используется для определения области списка.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Элемент списка" src="li.gif" <?php echo $bb_param_but; ?>><br>Пример: [li]текст[/li]<br>Используется для создания элемента списка.<li>пример элемента списка.</li></td></tr>

<tr><td><br><b>Ссылки</b></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Ссылка" src="url.gif" border="0" <?php echo $bb_param_but; ?>><br>Пример: [url]ссылка[/url], [url=ссылка]ссылка[/url] или [url=ссылка]текст[/url]<br>Используется для создания <a target="_blank" href="http://goodgirl.ru">внешней ссылки</a>. Примечание: такие ссылки открываются в новом окне и ссылка должна быть внешней (т.е. начинаться с http или с www).</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Ярлык" src="lnk.gif" border="0" <?php echo $bb_param_but; ?>><br>Пример: [lnk]путь[/lnk], [lnk=путь][/lnk] или [lnk=путь]текст[/lnk]<br>Используется для создания <a href="../../../../">внутренней ссылки</a>. Примечание: такие ссылки открываются в том же окне и в качестве пути должен быть указан путь к файлу относительно того файла, в котором выводится этот ярлык (т.е. вида /images/avatar/demo.gif, attach/archive.rar или ../../). Стоит отметить, что для этого тега внешние ссылки тоже применимы.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="E-mail" src="mailto.gif" <?php echo $bb_param_but; ?>><br>Пример: [mail]адрес[/mail] или [mail=адрес]текст[/mail]<br>Используется для создания <a href="mailto:mr_miksar@mail.ru">ссылки на почтовый ящик e-mail</a>.</td></tr>

<tr><td><br><b>Изображения</b></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Вставка изображения" src="img.gif" <?php echo $bb_param_but; ?>> - Вставка изображения<br>Пример: [img]адрес на изображение[/img] или [img=(left, center или right (указывать без скобок)) alt=Замещающий текст]адрес на изображение[/img\]<br>Используется для вставки изображения в текст. Указанием первого параметра можно выравнивать изображение относительно текста слева, по центру или справа - т.е. выполняется "обтекание текстом". Первый параметр можно и не указывать. В качестве адреса к картинке подойдет как внешняя ссылка, так и относительный путь.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Собственные изображения" src="img_my.gif" <?php echo $bb_param_but; ?>> - Собственные изображения<br>Пример: -<br>Данный тег виден только авторизованным пользователям. Он вызывает окно, где вы можете закачать собственные изображения и вставить в текст уже готовый код.</td></tr>

<tr><td><br><b>Обрамление текста</b></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Цитата" src="q.gif" <?php echo $bb_param_but; ?>><br>Пример: [quote]текст[/quote] или [quote=текст(например кого цитируем)]текст[/quote]; дополнительно [q]текст[/q]<br>Используется для создания выделенной цитаты.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Код" src="c.gif" <?php echo $bb_param_but; ?>><br>Пример: [code]код[/code]<br>Используется для выделения кода.</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Скрытый текст" src="h.gif" <?php echo $bb_param_but; ?>><br>Пример: [hide]код[/hide]<br>Используется для скрытия текста от незарегистрированных пользователей (например, можно прятать ссылки).</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Код PHP" src="php.gif" <?php echo $bb_param_but; ?>><br>Пример: [php]код php[/php]<br>Используется для выделения кода php с подсветкой.</td></tr>

<tr><td><br><b>ActiveX объекты</b></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Аудио" src="a.gif" <?php echo $bb_param_but; ?>><br>Пример: [a=STRAWBERRY_1.2.0 width=300]адрес к аудио файлу[/a]<br>Используется для проигрывания аудио файйлов в флеш плеере StrawPod (см документацию по системе).</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Видео" src="v.gif" <?php echo $bb_param_but; ?>><br>Пример: [v=STRAWBERRY_1.2.0 width=300 height=200]адрес к видео файлу[/v]<br>Используется для проигрывания видео файла в флеш плеере SrawPod (см документацию по системе).</td></tr>
<tr><td></td></tr>
<tr><td style="border: 1px solid #666699;"><img title="Флешка" src="f.gif" <?php echo $bb_param_but; ?>><br>Пример: [fla=STRAWBERRY_1.2.0 width=300 height=200]адрес к флеш файлу (*.swf)[/fla]<br>Используется для вставки флеш-анимации в текст.</td></tr>

<tr><td><br><b>Специальные Символы</b></td></tr>
<tr><td style="border: 1px solid #666699;"><img src="charmap.gif" <?php echo $bb_param_but; ?>><br>Пример: -<br>Этот тег вызывает окно со списком дополнительных символов.</td></tr>

<tr><td><br><b>Помощь</b></td></tr>
<tr><td style="border: 1px solid #666699;"><img src="help.gif" <?php echo $bb_param_but; ?>><br>Пример: -<br>Вызывает помощь по bb-кодам. Вы сейчас здесь.</td></tr>

    </table>

</body>
</html>