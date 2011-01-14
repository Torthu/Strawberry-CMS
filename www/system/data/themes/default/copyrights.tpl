<div style="clear:both;"></div>
<div class="foot">

<div class="copyright_l">
<a class="c7" title="<?php echo t('Пишите письма мелким почерком!'); ?>" href="mailto:&#109;&#105;&#107;&#115;&#97;&#114;&#64;&#109;&#97;&#105;&#108;&#46;&#114;&#117;">Mr.Miksar</a> <a class="c7" href="/" title="Strawberry 1.2.x" >Web Programming</a> © 2004 - <?php echo date('Y'); ?>
<br><a target="_blank" class="c7" href="http://strawberry.goodgirl.ru" title="<?php echo t('Официальный сайт Strawberry 1.1.x'); ?>">Strawberry system</a> © 2005 - <?php echo date('Y'); ?>
<br><a target="_blank" class="c7" href="http://cutephp.com" title="<?php echo t('Прародитель скрипта'); ?>">CutePHP system</a> © 2001 - <?php echo date('Y'); ?>
<br>Media player: <a href="http://uppod.ru" title="<?php echo t('Плеер для сайта'); ?>" target="_blank">UpPod</a> ©
</div>

<div class="copyright_r">
<?php 

echo "<div class=\"copyright_l\">";
echo schet("def_theme"); 
echo sitelife("default");
echo "</div>";

if (getip() != "127.0.0.1") {
echo "<div class=\"copyright_r\">";
?>
<!--Rating@Mail.ru counter-->
<screept language="javascript" type="text/javascript"><!--//--></screept>
<!--// Rating@Mail.ru counter-->

<!--LiveInternet counter-->
<screept type="text/javascript"><!--//--></screept>
<!--/LiveInternet-->

<?php
echo "</div>";
} 

?>
</div>

</div>