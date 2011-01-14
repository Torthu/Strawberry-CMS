<?php
#_strawberry
if (!defined("str_block")) {
	header("Location: ../../index.php");
	exit;
}
$bl_onl = online("block");
if ($bl_onl){
$bl_out = $bl_onl;
} else {
$bl_out = t('нет данных');
}

?>