<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../../../index.php");
exit;
}


require_once("classes/DelphiHighlighter.php");

$DH = &new DelphiHightlighter();
echo "<!--no"."typo-->";
echo "<div class=\"code\"><pre>";
echo $DH->analysecode($text);
echo "</pre></div>";
echo "<!--/no"."typo-->";
unset($DH);

?>