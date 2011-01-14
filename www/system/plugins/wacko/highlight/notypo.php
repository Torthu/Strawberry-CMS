<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../../../index.php");
exit;
}

print("<!--notypo-->");
$typo = $this->config["default_typografica"];
$this->config["default_typografica"] = false;
include("formatters/wakka.php");
$this->config["default_typografica"] = $typo;
print("<!--/notypo-->");


?>
