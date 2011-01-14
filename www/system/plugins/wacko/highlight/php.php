<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../../../index.php");
exit;
}

echo "<!--no"."typo-->";
echo "<div class=\"code\"><pre>";
highlight_string($text);
echo "</pre></div>";
echo "<!--/no"."typo-->";
?>