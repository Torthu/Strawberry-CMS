<?php
#_strawberry

 if (!defined("str_setup")) die("Вам отказано в доступе");
 error_reporting(0);
 @ignore_user_abort(true);
 @session_start();
 
 $slangs = array('rus'=>'Russian','eng'=>'English','ger'=>'Germany','srb'=>'Serbska','chn'=>'China','jap'=>'Japan','frn'=>'France');
 include (root_directory."/system/data/config.php");
 $module_name = str_replace(array('/system','system','/setup','setup'), "",substr(str_replace("\\", "/",dirname(__FILE__)), strlen($_SERVER['DOCUMENT_ROOT'])));
 $config['http_home'] = !empty($config['http_home']) ? $config['http_home'] : 'http://'.$_SERVER['SERVER_NAME'].$module_name;
 $config['http_script_dir'] = $config['http_home'].'/system';
 include (root_directory."/system/inc/functions_1.2.inc.php"); 
 include (root_directory."/system/inc/functions.inc.php");
 define("databases_directory", root_directory."/system/inc/db");
 define("config_file", root_directory."/system/data/config.php");
   if (!empty($_GET['set_lang']) and empty($_COOKIE['set_lang']) and empty($_SESSION['set_lang'])) {
    $set_lang = $_GET['set_lang'];
    setcookie("set_lang", $set_lang, (time + 1012324305), '/');
    $_SESSION['set_lang'] = $set_lang;
  } elseif (!empty($_COOKIE['set_lang']) or !empty($_SESSION['set_lang'])) {
    $set_lang = !empty($_COOKIE['set_lang']) ? $_COOKIE['set_lang'] : $_SESSION['set_lang'];
  } else {
    $set_lang = "";
    define("SETUP","Installation Strawberry 1.2.0");
  }
 include (root_directory."/system/setup/lang/".($set_lang)."/lang.php");

$sm = (ini_get('safe_mode') == "1") ? 1 : 0;
if (!$sm && function_exists('set_time_limit')) set_time_limit(0);
$host = ($config['http_home']) ? str_replace("http://", "",$config['http_home']) : getenv("HTTP_HOST");
### Путь относительно корня
$away = straw_parse_url($config['http_home']);
$addway = (!empty($away['path']) ? '/'.$away['path'].'/' : '/');
### Системный путь относительно корня
$sway = straw_parse_url($config['http_script_dir']);
$addsway = (!empty($sway['path']) ? '/'.$sway['path'].'/' : '/');

header("Expires: Tue, 24 Jun 2009 12:30:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

$mod = $_REQUEST['mod'];

function toper() {
	global $tit, $config;
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n"
	."<html>\n"
	."<head>\n"
	."<meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\">\n"
	."<title>::: ".SETUP." ::: ".$tit."</title>\n"
	."<link rel=\"stylesheet\" href=\"".sway("setup/skins/style.css")."\" type=\"text/css\">\n"
	."</head>\n"
	."<body id=\"page_bg\">\n"
	."<div id=\"wrapper\">"
	."<div id=\"header\">"
	."<div id=\"header-left\">"
	."<div id=\"header-right\">"
	."<div id=\"logo\">"
	."<img src=\"".sway("setup/skins/logotype.png")."\" alt=\"".$title."\">"
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
<a href=\"mailto:&#109;&#105;&#107;&#115;&#97;&#114;&#64;&#109;&#97;&#105;&#108;&#46;&#114;&#117;\">Mr.Miksar</a> <a href=\"http://www.mgcorp.ru\" title=\"MGCORP\" >Web Programming</a> © 2004 - 2010<br>
<a target=\"_blank\" href=\"http://strawberry.goodgirl.ru\" title=\"Официальный сайт Strawberry 1.1.x\">Strawberry system</a> © 2005 - 2010
</div>"
	."</div>"
	."</div>"
	."</div>"
	."</div>"
	."</body></html>";
}

function text_info($table, $id) {
	$text = "<tr><td>"._TABLE.":</td><td>".$table." ".(($id) ? "</td><td><font color=\"green\">"._OK."</font></td>" : "<td><font color=\"red\">"._ERROR."</font></td>")."</tr>";
	return $text;
}




function license() {
	global $tit, $set_lang, $slangs;
	if (empty($set_lang)) {
	$l_out = "";
	$tit = "Choose lang / Выбрать язык";
	$handle = opendir(root_directory."/system/setup/lang");
	while ($file = readdir($handle)){
	   if ($file != '.' and $file != '..' and is_dir(root_directory."/system/setup/lang")){
             $l_out .= "<a href=\"".way("setup.php?set_lang=".$file."")."\" title=\"".(!empty($slangs[$file]) ? $slangs[$file] : 'UnKnown')."\"><img src=\"".way("images/lang/".$file.".png")."\" border=\"0\" style=\"width:80px;height:80px;margin:10px;\" alt=\"".(!empty($slangs[$file]) ? $slangs[$file] : 'UnKnown')."\"/></a>";
           }
        }
	toper();
	//echo substr(sway("setup/lang"), 1)."<br/>";
	echo "<div style=\"text-align: center;\">".$l_out."</div>";
	
	} else {
	$tit = ""._LICENSE."";
	toper();
	$bodytext = file_get_contents("system/setup/lang/".$set_lang."/lic.txt");
	echo "<form action=\"setup.php\" method=\"post\"><table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">"
	."<tr><td><textarea cols=\"80\" rows=\"25\">".$bodytext."</textarea></td></tr>"
	."<tr><td><input type=\"checkbox\" name=\"license_check\" value=\"1\">"._LICENSE_OK."</td></tr>"
	."<tr><td align=\"center\"><input type=\"submit\" value=\""._NEXT."\" class=\"fbutton\"></td></tr>"
	."</table><input type=\"hidden\" name=\"mod\" value=\"config\"></form><br>"._LIC_TEXT_MENU."";
	}
	boter();
}



function gen_pass($m) {
	$m = intval($m);
	$pass = "";
	for ($i = 0; $i < $m; $i++) {
		$te = mt_rand(48, 122);
		if (($te > 57 && $te < 65) || ($te > 90 && $te < 97)) $te = $te - 9;
		$pass .= chr($te);
	}
	return $pass;
}





function no111() {
global $tip, $mod, $tit, $sql, $config;
  $act = !empty($_POST['act']) ? $_POST['act'] : $_GET['act'];
  if ($act == "mysql") {
    $tit = ""._TXT_TO_MYSQL;
    toper();
    include (root_directory."/system/setup/db/mysql.php");
    boter();
  } elseif ($act == "02x") {
    $tit = ""._FROM_CN_TO_STRAW;
    toper();
    include (root_directory."/system/setup/db/cn02x.php");
    boter();
  } else {
    $tit = ""._UPD_OLD_VERS;
    toper();
    echo ""._UPD_OLD_VERS_TXT;
    boter();
  }
}







function config() {
	global $tit, $config, $host;
	$tit = ""._CONFIG."";

	if (file_exists(root_directory."/system/data/config.php")) {
		chmod(root_directory."/system/data/config.php", 0666);
		$permsdir = decoct(fileperms(root_directory."/system/data/config.php"));
		$perms = substr($permsdir,-3);
		if ($perms != "666") {
			$tit = ""._CONF_FILE." ".sway("data/config.php")." "._SERRORPERM." CHMOD - 666";
			toper();
			echo ""._CONF_FILE." ".sway("data/config.php")." "._SERRORPERM." CHMOD - 666";
			boter();
			exit;
		}
	}

	$license_check = isset($_POST['license_check']);
	if ($license_check == "1") {
		include (root_directory."/system/data/config.php");
		$xdbhost = !empty($config['dbserver']) ? $config['dbserver'] : "localhost";
		$xdbuname = !empty($config['dbuser']) ? $config['dbuser'] : "";
		$xdbpass = !empty($config['dbpassword']) ? "" : gen_pass("10");
		$xdbname = !empty($config['dbname']) ? $config['dbname'] : "";
		$xprefix = !empty($config['dbprefix']) ? $config['dbprefix']."_" : gen_pass("5")."_";
		$xadmin_file = "admin";
		$info = sprintf(""._CONF_5_INFO."", $xadmin_file);
		toper();
		echo "<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"500\">"
		."<form action=\"setup.php\" method=\"post\">"
		."<tr><td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">"
		."<tr><td width=\"260\">"._SETUP_NEW.":</td><td align=\"left\">
		<input type=\"radio\" name=\"setup\" value=\"new\" checked></td></tr>"
		."<tr><td>"._SUPDATE." "._SUPDATE_TO.":</td><td align=\"left\"><input type=\"radio\" name=\"setup\" value=\"update1_2\"></td></tr>"
		."<tr><td colspan=\"2\">"._SUPDATE_TO_TEXT."<hr></td></tr>"
		."<tr><td colspan=\"2\"><hr></td></tr>"
		."</table></td></tr>"
		."<tr><td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">"
		."<tr><td>"._CONF_1." ("._USUALY_LH."):</td><td align=\"right\"><input type=\"text\" name=\"xdbhost\" value=\"".$xdbhost."\" size=\"35\"></td></tr>"
		."<tr><td>"._CONF_4.":</td><td align=\"right\"><input type=\"text\" name=\"xdbname\" value=\"".$xdbname."\" size=\"35\"></td></tr>"
		."<tr><td>"._CONF_2.":</td><td align=\"right\"><input type=\"text\" name=\"xdbuname\" value=\"".$xdbuname."\" size=\"35\"></td></tr>"
		."<tr><td>"._CONF_3.":</td><td align=\"right\"><input type=\"text\" name=\"xdbpass\" value=\"".$xdbpass."\" size=\"35\"></td></tr>"
		."<tr><td colspan=\"2\"><hr></td></tr>"
		."</table></td></tr>"
		."<tr><td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">"
		."<tr><td>"._CONF_5.": <br>("._PRIMER_PREFIX.")</td><td align=\"right\"><input type=\"text\" name=\"xprefix\" value=\"".$xprefix."\" size=\"35\"></td></tr>"
		."<tr><td colspan=\"2\"><hr></td></tr>"
		."<tr><td colspan=\"2\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">"
		."<tr><td>"._SITE_AUTHOR." ("._PRIMER_AUTHOR."):</td><td align=\"right\"><input type=\"text\" name=\"xsite_author\" value=\"".$_POST['xsite_author']."\" size=\"35\"></td></tr>"
		."<tr><td>"._SITE_ADDRES." ("._NO_SLASH."):</td><td align=\"right\"><input type=\"text\" name=\"xsite_url\" value=\"".($_POST['xsite_url'] ? $_POST['xsite_url'] : "http://".$host)."\" size=\"35\"></td></tr>"
		."<tr><td>"._CODER_SITE." <br>("._PRIMER_CODER.")</td><td align=\"right\"><input type=\"text\" name=\"x\" value=\"".($_POST['xsite_charset'] ? $_POST['xsite_charset'] : "windows-1251")."\" size=\"35\"></td></tr>"
		."<tr><td colspan=\"2\"><hr></td></tr>"
		."</table></td></tr>"
		."<tr><td align=\"center\" colspan=\"2\">"
		."<input type=\"submit\" value=\""._NEXT."\" class=\"fbutton\"></td></tr></table>"
		."</td></tr></table><input type=\"hidden\" name=\"mod\" value=\"save\"></form>";
		boter();
	} else {
		license();
	}
}





function save() {
global $tit, $config, $set_lang;
$setup = (isset($_POST['setup'])) ? $_POST['setup'] : "";
if ($setup != "admin" and $setup != "setadmin") {
///////////////////////////////////////////////////////////////////////////////////////


	$xdbhost = (isset($_POST['xdbhost'])) ? $_POST['xdbhost'] : "";
	$xdbuname = (isset($_POST['xdbuname'])) ? $_POST['xdbuname'] : "";
	$xdbpass = (isset($_POST['xdbpass'])) ? $_POST['xdbpass'] : "";
	$xdbname = (isset($_POST['xdbname'])) ? $_POST['xdbname'] : "";
	$xadmin_file = (isset($_POST['xadmin_file'])) ? $_POST['xadmin_file'] : "";
	$xprefix = (isset($_POST['xprefix'])) ? $_POST['xprefix'] : "";
	$bodytext = "";
	$dbserver = $xdbhost;
	$dbuser = $xdbuname;
	$dbpassword = $xdbpass;
	$dbname = $xdbname;
	$dbprefix = $xprefix;
	$charset = !empty($_POST['xsite_charset']) ? $_POST['xsite_charset'] : "windows-1251";
	$config['dbserver'] = $xdbhost;
	$config['dbuser'] = $xdbuname;
	$config['dbpassword'] = $xdbpass;
	$config['dbname'] = $xdbname;
	$config['dbprefix'] = $xprefix;

include (root_directory."/system/inc/db/mysql.inc.php");
include (root_directory."/system/inc/db/database.inc.php");

	if ($setup == "new") {

///////////////------------------////////////////
		$tit = ""._SAVE_NEW."";
		$bodytext .= "<tr><td colspan=\"3\">"._CREATE_TABLES."</td></tr>";
		$filename = root_directory."/system/setup/sql/table.sql";
		$readdump = fopen($filename, "r");
		$stringdump = fread($readdump, filesize($filename));
		fclose($readdump);
		$stringdump = explode(";",$stringdump);
		$dump_count = count($stringdump);
		for ($i = 0; $i < $dump_count; $i++) {
			$string = str_replace("{pref}", $xprefix, $stringdump[$i]);
			$id = $db->sql_query($string);
			if (preg_match("/CREATE|ALTER|DELETE|DROP|UPDATE/i", $string)) {
				$table = explode("`", $string);
				$bodytext .= text_info($table[1], $id);
			}
		}
$bodytext .= "<tr><td colspan=\"3\">"._IMPORT_DATA."</td></tr>";
		$filename = root_directory."/system/setup/sql/insert.sql";
		$readdump = fopen($filename, "r");
		$stringdump = fread($readdump, filesize($filename));
		fclose($readdump);
		$stringdump = explode(");",$stringdump);
		$dump_count = count($stringdump);
		for ($ii = 0; $ii < $dump_count; $ii++) {
			$stringer = str_replace("{pref}", $xprefix, $stringdump[$ii]).");";
			$id2 = $db->sql_query($stringer);
			if (preg_match("/CREATE|ALTER|DELETE|DROP|UPDATE|INSERT/i", $stringer)) {
				$table = explode("`", $stringer);
				$bodytext .= text_info($table[1], $id2);
			}
		}
///////////////------------------////////////////

	} elseif ($setup == "update1_2") {

///////////////------------------////////////////
$bodytext .= "<tr><td colspan=\"3\">"._CREATE_AND_UPD_TABLES."</td></tr>";
		$tit = ""._SAVE_UPDATE."";
		$filename = root_directory."/system/setup/sql/table_update1_2.sql";
		$readdump = fopen($filename, "r");
		$stringdump = fread($readdump, filesize($filename));
		fclose($readdump);
		$stringdump = explode(";",$stringdump);
		$dump_count = count($stringdump);
		for ($i = 0; $i < $dump_count; $i++) {
			$string = str_replace("{pref}", $xprefix, $stringdump[$i]);
			$id = $db->sql_query($string);
			if (preg_match("/CREATE|ALTER|DELETE|DROP|UPDATE/i", $string)) {
				$table = explode("`", $string);
				$bodytext .= text_info($table[1], $id);
			}
		}
$bodytext .= "<tr><td colspan=\"3\">"._IMPORT_DATA."</td></tr>";
		$filename = root_directory."/system/setup/sql/insert_update1_2.sql";
		$readdump = fopen($filename, "r");
		$stringdump = fread($readdump, filesize($filename));
		fclose($readdump);
		$stringdump = explode(");",$stringdump);
		$dump_count = count($stringdump);
		for ($ii = 0; $ii < $dump_count; $ii++) {
			$string = str_replace("{pref}", $xprefix, $stringdump[$ii]).");";
			$id2 = $db->sql_query($string);
			if (preg_match("/CREATE|ALTER|DELETE|DROP|UPDATE|INSERT/i", $string)) {
				$table = explode("`", $string);
				$bodytext .= text_info($table[1], $id2);
			}
		}
///////////////------------------////////////////

	}


$config = array (
  'home_title' => 'STRAWBERRY 1.2.0',
  'home_author' => $_POST['xsite_author'],
  'delitel' => ':::',
  'keywords' => 'Mr.Miksar, Strawberry, CutePHP',
  'description' => 'Strawberry 1.2 - клубничка среди CMS!',
  'revisit' => '2',
  'skin' => 'default',
  'mytheme' => 'default',
  'use_sel_theme' => '1',
  'html_comment' => '0',
  'html_shlak' => '0',
  'html_nl' => '0',
  'rss_potok' => '1',
  'js_potok' => '1',
  'go_link' => '0',
  'tload' => '0',
  'stepen' => '0',
  'coun_my_sql' => '1',
  'sitelife' => '0',
  'stat' => '0',
  'online' => '0',
  'whois' => 'http://legacytools.dnsstuff.com/tools/whois.ch?tool_id=66&ip=',
  'http_script_dir' => $_POST['xsite_url'].'/system',
  'http_home' => $_POST['xsite_url'],
  'home_page' => 'index.php',
  'path_image_upload' => $_POST['xsite_url'].'/images/upimages',
  'adm_image_prev_show' => '1',
  'adm_image_prev_w' => '100',
  'adm_image_prev_h' => '100',
  'charset' => $charset,
  'dbcharset' => 'cp1251',
  'mod_rewrite' => '0',
  'mod_rewrite_lite' => '0',
  'cache' => '0',
  'cache_full' => '0',
  'cacher' => '0',
  'decacher' => '0',
  'lang' => (($set_lang == 'rus') ? 'ru' : 'en'),
  'multilang' => '1',
  'admin_mail' => '',
  'gmtoffset' => '0',
  'cookie_prefix' => 'klubnika_',
  'ses_time' => '5',
  'gzip' => '0',
  'gziper' => '0',
  'gzip_info' => '1',
  'y' => '2009',
  'm' => '01',
  'd' => '01',
  'close' => '0',
  'close_text' => '',
  'modul' => 'news',
  'atitle' => '1',
  'timestamp_active' => 'j M Y',
  'date_header' => '1',
  'date_headerformat' => 'l, j M Y',
  'send_mail_upon_new' => '1',
  'pages_break' => '10',
  'pages_section' => '5',
  'cuplace' => '2',
  'use_dop_fields' => '0',
  'use_dop_fields_n' => '1',
  'dop_fields' => '5',
  'use_rss' => '1',
  'rss_news' => '15',
  'arr_news' => '50',
  'out_news' => '6',
  'auto_keywords' => '0',
  'auto_keywords_col' => '30',
  'auto_description' => '0',
  'auto_description_col' => '21',
  'use_comm' => '1',
  'send_mail_upon_posting' => '0',
  'need_mail' => '1',
  'cup' => '1',
  'cajax' => '0',
  'cnumber' => '10',
  'auto_wrap' => '50',
  'flood_time' => '30',
  'smilies' => 'angry, biggrin, evil, grin, laugh, sad, smile, wink',
  'smilies_line' => '2',
  'bb' => '1',
  'bb_line' => '4',
  'only_registered_comment' => '0',
  'comment_max_long' => '5000',
  'comment_max_long_guest' => '2000',
  'timestamp_comment' => 'j M Y - H:i',
  'cpages_break' => '10',
  'cpages_section' => '5',
  'users_on_page' => '10',
  'use_dop_fields_u' => '0',
  'use_dop_fields_u_out' => '0',
  'user_avatar' => '1',
  'path_userpic_upload' => $_POST['xsite_url'].'/images/avatar',
  'use_images_uf' => '1',
  'avatar_w' => '100',
  'avatar_h' => '100',
  'preventRegFlood' => '1',
  'RegDelay' => '120',
  'banOnWarns' => '4',
  'regLevel' => '4',
  'uterms' => '1',
  'chm_file' => '644',
  'chm_dir' => '755',
  'path_upload' => $_POST['xsite_url'].'/attach',
  'delete_files' => '1',
  'deny_files' => '.cgi .pl .shtml .shtm .php .php3 .php4 .php5 .phtml .phtm .phps .htm .html .js .perl .asp',
  'notype' => '0',
  'pin' => '1',
  'pin_font' => 'default.ttf',
  'quality' => '100',
  'pin_font_size' => '13',
  'pin_auth' => '1',
  'dbname' => $xdbname,
  'dbuser' => $xdbuname,
  'dbpassword' => $xdbpass,
  'dbprefix' => $xprefix,
  'dbserver' => $xdbhost,
  'cl_sql' => '1',
  'rss_cat' => '2',
  'dop_fields_def' => '1<>2<>3<>4<>5',
  'database' => 'mysql',
  'version_name' => 'Strawberry',
  'version_id' => '1.2.0',
  'http_home_url' => $_POST['xsite_url'].'/index.php',
  'path_image_by_main' => way('images/upimages'),
  'userpic_upload' => way('images/avatar'),
  'sitekey' => uniqid('10').uniqid('5').uniqid('34'),
);
$allowed_extensions = array('gif', 'jpg', 'png', 'bmp', 'jpe', 'jpeg');
save_config($config);

	$bodytext .= "<tr><td colspan=\"2\">"._CONF_FILE.":</td><td><font color=\"green\">"._SAVED."</font></td></tr>";
	$add_bodytext = "<br>"._MESS_ABOUT_ERR."<br>";

	toper();
	echo "<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">".$bodytext."</table>";
	if ($setup == "update1_2") {
	  echo $add_bodytext."<form action=\"".sway("index.php")."\" method=\"post\"><center><input type=\"submit\" value=\""._GO_IN_ADPAN."\" class=\"fbutton\"></center></form>";
	} else {
	  echo "<form action=\"".way("setup.php")."\" method=\"post\"><center><input type=\"submit\" value=\""._ADMIN."\" class=\"fbutton\"></center><input type=\"hidden\" name=\"mod\" value=\"save\"><input type=\"hidden\" name=\"setup\" value=\"admin\"></form>";
	}
	boter();
///////////////////////////////////////////////////////////////////////////////////////


} elseif ($setup == "admin") {


///////////////////////////////////////////////////////////////////////////////////////
$tit = ""._ADM_SETUP;
	toper();
	echo "<form action=\"".way("setup.php")."\" method=\"post\">"
		."<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">"
		."<tr><td>"._LOGIN."</td><td align=\"right\"><input type=\"text\" name=\"xadmin_login\" value=\"".$_POST['xadmin_login']."\" size=\"35\"></td></tr>"
		."<tr><td>"._PASS."</td><td align=\"right\"><input type=\"text\" name=\"xadmin_pass\" value=\"".$_POST['xadmin_pass']."\" size=\"35\"></td></tr>"
		."<tr><td colspan=\"2\">"._L_A_P_MHOLS."</td></tr>"
		."<tr><td colspan=\"2\"><hr></td></tr>"
		."</table>"
	."<center><input type=\"submit\" value=\""._ADD_ADM."\" class=\"fbutton\"></center>"
	."<input type=\"hidden\" name=\"mod\" value=\"save\">"
	."<input type=\"hidden\" name=\"setup\" value=\"setadmin\">"
	."</form>";
	boter();
///////////////////////////////////////////////////////////////////////////////////////


} elseif ($setup == "setadmin") {


///////////////////////////////////////////////////////////////////////////////////////
if (!empty($_POST['xadmin_login']) and !empty($_POST['xadmin_pass'])) {
$username = $_POST['xadmin_login'];
$password = $_POST['xadmin_pass'];
$cmd5_password = md5x($password);
@setcookie('klubnika_lastusername', $username, (time() + 1012324305));
@setcookie('klubnika_username', $username, (time() + 3600 * 24 * 365));
@setcookie('klubnika_md5_password', $cmd5_password, (time() + 3600 * 24 * 365));
include (root_directory."/system/inc/db/mysql.inc.php");
include (root_directory."/system/inc/db/database.inc.php");
$db->sql_query("INSERT INTO ".$config['dbprefix']."users (date, usergroup, username, password, name, mail, hide_mail, last_visit, id) VALUES ('".time()."', 1, '".$username."', '".$cmd5_password."', 'Administrator', '', 1, '".time()."', NULL)");
$tit = ""._ADM_CREATED;
	toper();
	echo "<form action=\"".sway("index.php")."\" method=\"post\">"
	."<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr><td>"._INSTALL_COMPLITE."</td></tr></table>"
	."<center><input type=\"submit\" value=\""._GO_IN_ADPAN."\" class=\"fbutton\"></center>"
	."</form>";
	boter();
} else {
$tit = ""._ERROR2;
toper();
if (empty($_POST['xadmin_login'])) echo ""._NO_ADM_LOGIN;
if (empty($_POST['xadmin_login']) and empty($_POST['xadmin_pass'])) echo "<br>";
if (empty($_POST['xadmin_pass'])) echo ""._NO_ADM_PASS;
echo "<input type=\"submit\" value=\""._BACK."\" class=\"fbutton\" onclick=\"javascript:history.go(-1)\">";
boter();
}
///////////////////////////////////////////////////////////////////////////////////////


}
}





switch($mod) {
	default:
	license();
	break;

	case "no111":
	no111();
	break;

	case "config":
	config();
	break;
	
	case "save":
	save();
	break;
}
?>