<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../../../index.php");
exit;
}

require_once("classes/JavaHighlighter.php");

$DH = &new JavaHighlighter();
echo "<!--no"."typo-->";
echo "<div class=\"code\"><pre>";
echo $DH->analysecode($text);
echo "</pre></div>";
echo "<!--/no"."typo-->";
unset($DH);

?>