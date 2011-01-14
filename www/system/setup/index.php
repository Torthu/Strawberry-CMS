<?php
#_strawberry
# © 2008 - 2010 MrMiksar

define("str_setup", true);
define("str_adm", true);
define("str_conf", true);
define('root_directory', str_replace("\\", "/", dirname(__FILE__)));

if (is_file(root_directory."/setup.php")) {
include root_directory."/setup.php";
} else {
echo "You cant`t to install system!";
}


?>