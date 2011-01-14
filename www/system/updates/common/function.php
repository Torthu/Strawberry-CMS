<?php

 if (!defined("str_update")) die("Access dinaed");


function toper() {
	global $tit, $config;
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n"
	."<html>\n"
	."<head>\n"
	."<meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\">\n"
	."<title>::: "._SETUP." ::: ".$tit."</title>\n"
	."<meta name=\"resource-type\" content=\"document\">\n"
	."<meta name=\"document-state\" content=\"dynamic\">\n"
	."<meta name=\"distribution\" content=\"global\">\n"
	."<meta name=\"author\" content=\"MrMiksar\">\n"
	."<link rel=\"stylesheet\" href=\"system/updates/skins/style.css\" type=\"text/css\">\n"
	."</head>\n"
	."<body id=\"page_bg\">\n"
	."<div id=\"wrapper\">"
	."<div id=\"header\">"
	."<div id=\"header-left\">"
	."<div id=\"header-right\">"
	."<div id=\"logo\">"
	."<a href=\"updates.php\" title=\""._SETUP."\"><img src=\"system/updates/skins/logotype.png\" alt=\""._SETUP."\"></a>"
	."</div>"
	."</div>"
	."</div>"
	."</div>"
	."<div id=\"shadow-l\">"
	."<div id=\"shadow-r\">"
	."<div id=\"container\">"
	."<h3 class=\"btitle\">".$tit."</h3>";
}

function boter() {
	global $config;
	echo "</div>"
	."</div>"
	."</div>"
	."<div id=\"footer\">"
	."<div id=\"footer-r\">"
	."<div id=\"footer-l\">"
	."<div id=\"copyright\">
<a title=\"Пишите письма мелким почерком!\" href=\"mailto:&#109;&#105;&#107;&#115;&#97;&#114;&#64;&#109;&#97;&#105;&#108;&#46;&#114;&#117;\">Mr.Miksar</a> <a href=\"http://www.mgcorp.ru\" title=\"MGCORP\" >Web Programming</a> © 2004 - 2009<br>
<a target=\"_blank\" href=\"http://strawberry.goodgirl.ru\" title=\"Официальный сайт Strawberry 1.1.x\">Strawberry system</a> © 2005 - 2009
</div>"
	."</div>"
	."</div>"
	."</div>"
	."</div>"
	."</body></html>";
}



?>