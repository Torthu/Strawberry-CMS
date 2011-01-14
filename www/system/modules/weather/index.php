<?php
if (!defined("str_modul")) {
	header("Location: ../../../index.php");
	exit;
}
### Параметры модуля
$tit = t("Погода");
$this_town = "878";
$kod = "27553";
$link = "http://www.gismeteo.ru/towns/".$kod.".htm";


//echo strtotime($_POST['day'].' '.$_POST['month'].' '.$_POST['year'].' '.$_POST['hour'].':'.$_POST['minute'].':'.$_POST['second']);
//$t = strtotime("1 September 2001 1:1:1");
//echo "<br/>1 s ".(strtotime("1 September 2001 1:1:2") - strtotime("1 September 2001 1:1:1")), "";

function readservis($link) {
$fd = fopen($link, "r");
$text="";
if (empty($fd)) {
return t("Данные временно недоступны...");
} else {
  while (!feof ($fd)) {
  $text .= fgets($fd, 4096);
  }
}
fclose ($fd);
return $text;
}

 otable();
?>
<div style="width:100%">
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="420">
<param name="movie" value="http://st.kaktam.in.ua/weathervk.swf" />
<param name="FlashVars" value="detectcity=false&id=<?php echo $this_town; ?>"/>
<param name="bgcolor" value="#FFFFFF"/>
<!--[if !IE]>-->
<object type="application/x-shockwave-flash" data="http://st.kaktam.in.ua/weathervk.swf" width="100%" height="420">
<param name="FlashVars" value="detectcity=false&id=<?php echo $this_town; ?>"/>
<param name="bgcolor" value="#FFFFFF"/>
<!--<![endif]-->
<!--[if !IE]>-->
</object>
<!--<![endif]-->
</object>

<div style="clear: both;"></div></div>
<?php
 ctable();
 echo on_page();
?>