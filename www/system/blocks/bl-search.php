<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}

$bl_out = "<div class=\"one\"><div class=\"two\"><div class=\"in\">
<form method=\"post\" action=\"".way($config['home_page']."?mod=search")."\" enctype=\"multipart/form-data\">
<input name=\"mod\" type=\"hidden\" value=\"search\">
<b>".t('ПОИСК В СИСТЕМЕ STRAWBERRY')."</b><br><input type=\"text\" name=\"search\" class=\"srup\" value=\"".t('Поиск')."\" autocomplete=\"off\" onfocus=\"javascript:this.value=(this.value=='".t('Поиск')."')?'':this.value;\" onblur=\"javascript:this.value=(this.value=='')?'".t('Поиск')."':this.value;\"> <input type=\"submit\" class=\"srupgo\" value=\"".t('Искать!')."\"><br>".t('Вводить в форму не менее трех символов!')."
</form>
</div></div></div>";

?>