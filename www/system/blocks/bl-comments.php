<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}
$com_bl_out = "";
ob_start();
#if (function_exists('latest_comments')) latest_comments(10, 0);
if (function_exists('all_comments')) { all_comments(4, 500); }
$com_bl_out = ob_get_clean();
$bl_out = $com_bl_out;

?>