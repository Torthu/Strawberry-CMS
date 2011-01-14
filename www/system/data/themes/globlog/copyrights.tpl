<br><table border="0" width="100%">
<tr>
<td class="cr" width="100%"><a class="c7" title="Пишите письма мелким почерком!" href="mailto:&#109;&#114;&#109;&#105;&#107;&#115;&#97;&#114;&#64;&#109;&#97;&#105;&#108;&#46;&#114;&#117;">Mr.Miksar</a> <a class="c7" href="/" title="Strawberry 1.2.x" >Web Programming</a> © 2004 - <?php echo date('Y'); ?><br><a target="_blank" class="c7" href="http://strawberry.goodgirl.ru" title="Официальный сайт Strawberry 1.1.x">Strawberry system</a> © 2005 - <?php echo date('Y'); ?><br><a target="_blank" class="c7" href="http://cutephp.com" title="Прародитель скрипта">CutePHP system</a> © 2001 - <?php echo date('Y'); ?><br>Media player: <a href="http://uppod.ru" title="Плеер для сайта" target="_blank">UpPod</a> ©</td>
<td><a href="http://uppod.ru" title="Плеер для сайта" target="_blank"><img border="0" src="system/skins/images/banners/uppod/uppod_banner_gray_164x55.png" alt="Плеер для сайта" width="164" height="55"></a></td>
<td>

<div class="schet"><marquee class="mar" id="fc" scrollAmount="2" direction="up" onmouseover="javascript:fc.stop()" onmouseout="javascript:fc.start()">
<?php 

echo schet("default");
echo "<br><br>"; 
echo online("default");
echo "<br><br>"; 
echo sitelife("default");

if (getip() != "127.0.0.1") {
echo "<br><br>";
?>



<!--Rating@Mail.ru counter-->
<screept language="javascript" type="text/javascript"><!--//--></screept>
<!--// Rating@Mail.ru counter-->
<br><br>

<!--LiveInternet counter-->
<screept type="text/javascript"><!--//--></screept>
<!--/LiveInternet-->

<?php
}
?>
</marquee></div>

</td>
</tr>
</table>