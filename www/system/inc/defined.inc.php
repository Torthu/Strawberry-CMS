<?php
#_strawberry
if (!defined("str_define")) {
header("Location: ../../index.php");
exit;
}

/**
 * ��� ������ ��������� ����������� �������
 */

### ���������� register_globals (register_globals off effect)
function unregister_globals() {
    if (!ini_get('register_globals')) { return false; }
    foreach (func_get_args() as $name) {
        foreach ($GLOBALS[$name] as $key=>$value) {
            if (isset($GLOBALS[$key])) { unset($GLOBALS[$key]); }
        }
    }
}

### ��������� �� ���������� ������� // check friendly symbols
function cheker($input) {
   if (isset($input) and !is_array($input)) {
	$output = (preg_match("#[^a-zA-Z�-��-�0-9_\-\,\.\&\;\?\/\:\=\#\@ ]#", $input)) ? "" : $input;
	return $output;   
   } else if (isset($input) and is_array($input)) {
        return $input; 
   }
}

### ��������� ����������������� ������
function getagent() {
	if (getenv("HTTP_USER_AGENT") && strcasecmp(getenv("HTTP_USER_AGENT"), "unknown")) {
		$agent = htmlspecialchars(getenv("HTTP_USER_AGENT"));
	} elseif (!empty($_SERVER['HTTP_USER_AGENT']) && strcasecmp($_SERVER['HTTP_USER_AGENT'], "unknown")) {
		$agent = htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
	} else {
		$agent = "unknown";
	}
	return $agent;
}

/**
 * @package Defined
 * @access public
 */

################## ������ �� ������� ����������
$vars = array('skip', 
'page', 'cpage', 'action', 'id', 'ucat', 'category', 'number', 'template', 'static', 
'year', 'month', 'day', 'title', 'sort', 'user', 'author', 'time', 'link', 'tpl', 'mod', 
'act', 'numb', 'name', 'in', 'search', 'error', 'nid', 'pin', 'rand', 'random', 'error');

$default = array('siteurl' => 'http://'.$_SERVER['SERVER_NAME'], 
'hst' => $_SERVER['SERVER_NAME'], 'put' => $_SERVER['REQUEST_URI'], 'cutepath' => root_directory, 'phpversion' => @phpversion(), 
'HTTP_REFERER' => (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : getenv('HTTP_REFERER')), 
'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'], 'PHP_SELF' => $_SERVER['PHP_SELF'], 'QUERY_STRING' => $_SERVER['QUERY_STRING'], 
'ppiinn' => false, 'ch_lgn' => false, 'is_logged_in' => false, 'yaw' => false, 'cache_uniq' => 0, 'config' => array(), 
'save_con' => array(), 'categories' => array(), 'sfields' => array(), 'users' => array(), 'usergroups' => array(), 'linkcats' => array(), 
'member' => array(), 'tpl' => array(), 'post' => array(), 'gettext' => array(), 'links' => array(), 'rand' => 0, 'pin' => 0, 'error' => 0, 
'robots' => 0, 'it_is_feed' => 0, 'add_way' => '', 'static' => array(), 'tr_lng' => array(), 'otpl' => array(), 'robots' => array(), 
'version_name' => 'Strawberry', 'version_id' => '1.2b4', 'beta' => '4', 'alfa' => '1', 'rc' => '0');

unregister_globals('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
foreach ($vars as $k => $v)    { $$k = (!empty($v) ? @htmlspecialchars($v) : ""); }
//foreach ($_GET as $k => $v)   { $$k = (!empty($v) ? @htmlspecialchars(cheker($v)) : ""); }
//foreach ($_POST as $k => $v) { $$k = (!empty($v) ? @htmlspecialchars(cheker($v)) : ""); }
foreach ($default as $k => $v) { $$k = !is_array($v) ? (!empty($v) ? str_replace("\\", "/", $v) : "") : array(); }
################## // ������ �� ������� ����������

### �������� ������ ������ ������ � ����� ����
if (substr($HTTP_REFERER, -1) == '/'){
$HTTP_REFERER = "http://".$_SERVER['SERVER_NAME'].$PHP_SELF;
$HTTP_REFERER = str_replace ("//", "/", $HTTP_REFERER); //������� ������������ ���...
}



/*
 * �� ������ �������� ����� ����� � ������ �������, � ����� ����������� ��� ��������� ���, 
 * ����� ����� ��������� ������ (�������� �� �����). ��� ������� ���� �� ������� �������, 
 * � �������. �� �������� �����, ����� ������ �����/������������ ������ ����� �����.
 */

define('chmod', 0777);
define('MAX_IDLE_TIME', 3); // ���� ������� ��� �������� ������ (� �������)
define('cookie', true);
define('session', false);
define('check_referer', false); // ������ � �������
define("str_modul", true); // ��� �� ������ �������� �� ���������
define("str_block", true); // ��� �� ����� �������� �� ���������
define("str_adm", true); // ��� �� ��������� ����� �������� �� ���������
define("str_conf", true); // ��� �� ������� �������� �� ���������
define("str_plug", true); // ��� �� ������� �������� �� ���������
define("str_lang", true); // ��� �� ������� �������� �� ���������
define("str_nocache", true); // ��� �� ��-��� �������� �� ���������

// ����� �����������
define('rootpath', root_directory);
define('data_directory', root_directory.'/data');
define('cache_directory', root_directory.'/cache');
define('backup_directory', data_directory.'/backup');

// �����������, ������������ ��� �������� ����������
define('includes_directory', root_directory.'/inc');
define('classes_directory', includes_directory.'/class');
//define('updates_directory', includes_directory.'/upd');
define('databases_directory', includes_directory.'/db');
define('db_directory', data_directory.'/db');
define('smile_directory', data_directory.'/emoticons');
define('skins_directory', root_directory.'/admin');
define('admin_directory', root_directory.'/admin');
define('admin_skins_directory', admin_directory.'/themes');
define('font_directory', data_directory.'/font');
define('watermark_directory', data_directory.'/watermark');
define('banner_directory', data_directory.'/banners');
define('modules_directory', skins_directory.'/mod');
define('plugins_directory', root_directory.'/plugins');
define('languages_directory', data_directory.'/lang');
define('templates_directory', data_directory.'/tpl');
define('tpl', data_directory.'/tpl');
define('stpl_directory', templates_directory.'/system');
define('stpl', templates_directory.'/system');
define('java_directory', data_directory.'/java');
define('mails_directory', stpl_directory.'/mail');
define('links_directory', stpl_directory.'/links');
define('gb_directory', stpl_directory.'/gb');
define('themes_directory', data_directory.'/themes');
define('theme', data_directory.'/themes');
define('modul_directory', root_directory.'/modules');
define('block_directory', root_directory.'/blocks');

// � ������ ���� � ������, ��� ������� �����...
define('active_plugins_file', data_directory.'/plugins.php');
define('settings_file', data_directory.'/settings.php');
define('xfields_file', data_directory.'/xfields-data.php');
define('config_file', data_directory.'/config.php');
define('header_file', db_directory.'/meta_header.txt');
define('js_file', db_directory.'/scripts.txt');
define('rss_file', db_directory.'/rss.txt');
define('go_file', db_directory.'/go.txt');
define('robots_file', db_directory.'/robots.php');
define('no_word_file', db_directory.'/no-words.txt');
define('htac', root_directory.'/../.htaccess');
define('rufus_file_off', data_directory.'/urls.ini');

// ����� �������, ������, ���� � ������� ����, �� �� �������� �� �����
define('plugin_framework_version', '2.0');
// ��������� �������� ��� ���������� ��������
define('plugin_default_priority', 20);
### ����������� ������� ������� �����...


### � ������ ������� ������.
### ��� ��� �� ������� � ������������� - ������ ��� ��� ���.
include config_file; // ���������� ���������

### ������� ����� time �� ������� �����
define('time', (time() + ($config['gmtoffset'] - (date('Z') / 60)) * 60));

### ��������� ���������
@header("Content-Type: text/javascript; charset=".$config['charset']);
@header("Content-Type: text/html; charset=".$config['charset']);



### �������������� ����������
$act_querty = !empty($_POST['act']) ? $_POST['act'] : (!empty($_GET['act']) ? $_GET['act'] : "");
$action_querty = (!empty($_REQUEST['action']) ? $_REQUEST['action'] : "");
$mod_querty = empty($_GET['mod']) ? "" : $_GET['mod'];
$gplugin_querty = empty($_GET['plugin']) ? "" : $_GET['plugin'];
$sql_error_out = (!empty($_REQUEST['error']) ? $_REQUEST['error'] : "");

$modul = (empty($mod_querty)) ? $config['modul'] : cheker($mod_querty);
$act = (empty($act_querty)) ? "" : cheker($act_querty);
$action = (empty($action_querty)) ? "" : cheker($action_querty);
$gplugin = (empty($gplugin_querty)) ? "" : cheker($gplugin_querty);
$sql_error_out = (empty($sql_error_out)) ? "" : cheker($sql_error_out);
$nid = (!empty($_GET['id']) ? ((is_numeric($_GET['id'])) ? cheker($_GET['id']) : cheker($_GET['id'])) : 0);
$year  = ((!empty($_POST['year'])     and is_numeric($_POST['year']))   ? $_POST['year']    : ((!empty($_GET['year'])   and is_numeric($_GET['year']))    ? $_GET['year'] : ''));
$month = ((!empty($_POST['month']) and is_numeric($_POST['month'])) ? $_POST['month'] : ((!empty($_GET['month']) and is_numeric($_GET['month'])) ? $_GET['month'] : ''));
$day   = ((!empty($_POST['day'])      and is_numeric($_POST['day']))    ? $_POST['day']     : ((!empty($_GET['day'])    and is_numeric($_GET['day']))     ? $_GET['day'] : ''));
$spider_here = 0;
$sesuser = "";

if (empty($ap)) {  // ���������� ������� ���� ������ // get type of error
  $sql_error_out = !empty($_GET['error']) ? cheker($_GET['error']) : (!empty($sql_error_out) ? $sql_error_out : "");
}


### ��� ��� ���� ������� ����� �������� ���...
if (!empty($config['mod_rewrite'])) {
  define('rufus_file', data_directory.'/urls_on.ini');
} else {
  define('rufus_file', data_directory.'/urls.ini');
}


################################################
############# robots finder #########################
### �������������� ����������
  $ua = getagent(); // ����� ������������
  $ua_ln = strlen($ua);
### ������ ���������
if(file_exists(robots_file) and filesize(robots_file) != 0 and $ua_ln > 30) {
  include_once robots_file;
    foreach ($allrobots as $rk => $rv) {
	  if (stristr($ua, $rk))
	    {
	      $robots['now']['name'] = $rk;
	      $robots['now']['agent'] = $ua;
	      $spider_here = 1;
	      break;
	   }
    }
} elseif($ua_ln < 30) {
   $robots['now']['name'] = str_replace("/", " - ", $ua);
   $robots['now']['agent'] = $ua;
   $spider_here = 1;
}
    
if (!empty($spider_here)) {
   $sesuser = "robot";
   $id_session = 0;
} else {
   @session_start();  // strawberry session start!
   $sesuser = "user";
   $id_session = @session_id();
}

############# robots finder #########################
################################################





### ����� � �������
$config['lang'] = !empty($config['lang']) ? $config['lang'] : 'ru'; // ���� ����� ����� ������ ������ ������������
$lang_def = $config['lang'];

if (!empty($config['multilang']) and empty($ap) and empty($spider_here)) {
  if ((!empty($_GET['lang']) or !empty($_POST['lang'])) and !empty($config['lang'])) {
    setcookie($config['cookie_prefix']."lang", cheker($_REQUEST['lang']), (time + 1012324305), '/', '', '');
    $_SESSION[$config['cookie_prefix'].'lang'] = cheker($_REQUEST['lang']);
    $config['lang'] = cheker($_REQUEST['lang']);
  } elseif (!empty($_COOKIE[$config['cookie_prefix'].'lang']) or !empty($_SESSION[$config['cookie_prefix'].'lang'])) {
    $config['lang'] = !empty($_COOKIE[$config['cookie_prefix'].'lang']) ? cheker($_COOKIE[$config['cookie_prefix'].'lang']) : cheker($_SESSION[$config['cookie_prefix'].'lang']);
  }
}
  define('rule_file', db_directory.'/rule_'.$config['lang'].'.txt');
if ($config['lang'] != 'ru') {
  define('language', languages_directory.'/'.$config['lang'].'/index.lng');
  @eval(file_get_contents(language));
} else {
  define('language', languages_directory.'/ru/index.lng');
}

### ���������� �������� ������� �������
include_once languages_directory.'/'.$config['lang'].'/functions.php'; // �������� //languager
include_once includes_directory.'/functions.inc.php'; // ������� ������ 1.1.1 // functions v 1.1.1
include_once includes_directory.'/functions_1.2.inc.php'; // ������� ������ 1.2 // functions v 1.2
include_once databases_directory.'/mysql.inc.php'; // ������� ������: MySQL

### ���� ������������ �����
$away = straw_parse_url($config['http_home']);
$addway = (!empty($away['path']) ? '/'.$away['path'].'/' : '/');
### ��������� ���� ������������ �����
$sway = straw_parse_url($config['http_script_dir']);
$addsway = (!empty($sway['path']) ? '/'.$sway['path'].'/' : '/');

### �������
include_once includes_directory.'/plugins.inc.php'; // ������� ��� ������ ��������
include_once includes_directory.'/plugins.default.php'; // ������� �� ��������� (���������� �������)




    if ($sql_error_out != "mysql") {
   # ���� ������ ����������� � ����, �� ��� ��������� ��� ��� �� �������!
   # ����������� ������ - ������������� � ����� ����.

### ������� ����������� ����������� �������� ��� �������.
if (!empty($modul) and is_file(modul_directory."/".$modul."/conf.php")) {
include modul_directory."/".$modul."/conf.php"; // ���������� ��������� �������� ������
}


### �������������
if (!function_exists('iconv')){
include_once includes_directory.'/iconv.inc.php'; // utf-8 � �������
}

include_once includes_directory.'/cache.inc.php'; // �� ������ ��� �� �������
include_once includes_directory.'/counter.inc.php'; // �������

### ������� �������, ��� ����, �� ����� ����� �������� � ���������� ������ � ���� ������?
/*
if (@chmod(cache_directory, 0777)){
  @chmoddir(data_directory, 0777);
  @chmod(data_directory, 0755); // � � ��� ������� � ���������� ���������?
  @chmod(backup_directory, 0777);
}
*/


$cache = new cache($config['cache'], cache_directory, 3600);


if (!empty($_GET)) {
@extract($_GET, EXTR_SKIP);
$_GET  = straw_htmlspecialchars(straw_stripslashes($_GET));
}
if (!empty($_POST)) {
@extract($_POST, EXTR_SKIP);
$_POST = straw_stripslashes($_POST);
}
if (!empty($_COOKIE)) {
@extract($_COOKIE, EXTR_SKIP);
}
if (!empty($_SESSION)) {
@extract($_SESSION, EXTR_SKIP);
}
if (!empty($_ENV)) {
@extract($_ENV, EXTR_SKIP);
}


### �������������� ����������
$ip = $sesip = getip(); // ip ������������

### ���������� �������
$tm = time; // ����� ������������ � ������ GMT
$rtm = time(); // �������� ����� �������
$TheHour = date('H');
$TheMinute = date('i');
$TheSecond = date('s');
$TheYear = date('Y');
$TheMonth = date('m');
$TheDay = date('d');
$thatdat = $TheDay.".".$TheMonth.".".$TheYear;


### CACHER ###
if (empty($ap) and !empty($config['cacher'])) {
header("Cache-Control: public");
header("Expires: " . date("r", time() + 60));
} else {
@header("Cache-Control: private");
}


### DeCACHER ###
if (empty($ap) and empty($config['cacher']) and empty($config['cache']) and empty($config['cache_full']) and !empty($config['decacher'])) {
include_once includes_directory.'/no-cache.inc.php'; // ��������� �����������
}
















### ����������� ��������
if (!empty($nid)) {

if (!$post = $cache->unserialize('post', $nid)) {
$pquery = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news AS a LEFT JOIN ".$config['dbprefix']."story AS b ON (b.post_id=a.id) WHERE ".(!empty($ap)  ? "" : "a.hidden='0' AND")." a.id='".$nid."' OR a.url='".$nid."' LIMIT 0,1");
$post = array($db->sql_fetchrow($pquery));
$post = $cache->serialize(reset($post));
}

}


### ����������� ���������
if (!$categories = $cache->unserialize('categories')) {
$cat_arr_m = array();
$nncc = array();
$row_cat_db = $db->sql_query("SELECT * FROM ".$config['dbprefix']."categories ORDER BY id ASC");
while ($crow=$db->sql_fetchrow($row_cat_db)) {
  $categories[$crow['id']] = $crow;
  $nncc[] = $crow['id'];
}
  $nwcat = implode(",", $nncc);
  $categories = $cache->serialize($categories);
}



### ����������� �������������
if (!$users = $cache->unserialize('users')){
$row_use_db = $db->sql_query("SELECT * FROM ".$config['dbprefix']."users WHERE deleted!='1' ORDER BY id ASC");
while ($urow=$db->sql_fetchrow($row_use_db)) {
	    $urow['name'] = (!empty($urow['name']) ? $urow['name'] : $urow['username']);
	    $users[$urow['username']] = $urow;
	}
	$users = $cache->serialize($users);
}



### ����������� ����� �������������
if (!$usergroups = $cache->unserialize('usergroups')){
$row_ugr_db = $db->sql_query("SELECT * FROM ".$config['dbprefix']."usergroups ORDER BY id ASC");
while ($ugrow=$db->sql_fetchrow($row_ugr_db)) {
	    $ugrow['access'] = ($ugrow['access'] == 'full' ? $ugrow['access'] : unserialize($ugrow['access']));
	    $ugrow['permissions'] = unserialize($ugrow['permissions']);
	    $usergroups[$ugrow['id']] = $ugrow;
	}
	$usergroups = $cache->serialize($usergroups);
}


################






















### �����������
if (empty($spider_here)) {
///////////////////////////////////////////////////////////////////////////////
if (!empty($_COOKIE[$config['cookie_prefix']."username"])){ 
  $user_name = cheker($_COOKIE[$config['cookie_prefix'].'username']); 
} else {
  $user_name = !empty($_POST['username']) ? cheker($_POST['username']) : "";
}

if (!empty($_COOKIE[$config['cookie_prefix'].'md5_password'])){
  $cmd5_password = cheker($_COOKIE[$config['cookie_prefix'].'md5_password']);
} else {
  $cmd5_password = !empty($_POST['password']) ? md5x($_POST['password']) : "";
}

$ppiinn = (!empty($config['pin_auth']) and empty($_COOKIE[$config['cookie_prefix'].'username']) and empty($_COOKIE[$config['cookie_prefix'].'md5_password'])) ? pin_check("auth") : false; 
$ch_lgn = (!empty($user_name) and !empty($cmd5_password)) ? check_login($user_name, $cmd5_password, $ap) : false;

if (!empty($user_name) and !empty($cmd5_password) and !empty($ch_lgn) and empty($ppiinn)){

  $is_logged_in = true;
  straw_setcookie("lastusername", $user_name, (time + 1012324305), '/');
  straw_setcookie('username', $user_name, (time + 3600 * 24 * 365), '/');
  straw_setcookie('md5_password', $cmd5_password, (time + 3600 * 24 * 365), '/');
  if (!empty($_POST['username']) and !empty($_POST['password'])) { header('Location: '.$_SERVER['REQUEST_URI']); }

} else {
  
  $result_in = "";
  $is_logged_in = false;
  straw_setcookie('username', '', (time - 3600 * 24 * 365), '/');
  straw_setcookie('md5_password', '', (time - 3600 * 24 * 365), '/');  
  if (empty($ch_lgn) and (!empty($_POST['action']) and $_POST['action'] == "dologin")) $result_in .= '<font class="admin">'.t('������������ ��� ������������ ��� ������!').'</font>';
  if (empty($ch_lgn) and !empty($ppiinn) and (!empty($_POST['action']) and $_POST['action'] == "dologin")) $result_in .= '<br>';
  if (!empty($ppiinn) and (!empty($_POST['action']) and $_POST['action'] == "dologin")) $result_in .= '<font class="admin">'.t('������ ������������ ����������� ���!').'</font>';

} 
///////////////////////////////////////////////////////////////////////////////
### ������, ��� ������ �� ����� (user, guest, robot)
  $sesuser = chk_user(0, 1);
} else {
  $result_in = "";
  $is_logged_in = false;
}
### // �����������


##############################################################################


### online ## ������� � MySQL ����� (c) Mr.Miksar
strw_online();
############################## // online ###
if (empty($ap) and !empty($config['stat']) and empty($sql_error_out)) strw_spesta();

### ������ ���� ����� �� ��������� �������������
if (!empty($_POST['sel_theme']) and !empty($config['use_sel_theme']) and empty($spider_here)) {
  straw_setcookie("sel_theme", cheker($_POST['sel_theme']), (time + 1012324305), '/');
  $_SESSION[$config['cookie_prefix'].'sel_theme'] = cheker($_POST['sel_theme']);
  $config['mytheme'] = cheker($_POST['sel_theme']);
} elseif ((!empty($_COOKIE[$config['cookie_prefix'].'sel_theme']) or !empty($_SESSION[$config['cookie_prefix'].'sel_theme'])) and !empty($config['use_sel_theme']) and empty($spider_here)) {
  $config['mytheme'] = !empty($_COOKIE[$config['cookie_prefix'].'sel_theme']) ? cheker($_COOKIE[$config['cookie_prefix'].'sel_theme']) : cheker($_SESSION[$config['cookie_prefix'].'sel_theme']);
} elseif (!empty($is_logged_in) and !empty($member['sel_theme']) and !empty($config['use_sel_theme']) and empty($spider_here)) {
  $config['mytheme'] = cheker($member['sel_theme']);
}


########### (c) softtime@softtime.ru ##################
### �������
if (empty($ap) and !empty($config['stat']) and empty($sql_error_out) and empty($spider_here)) {
// ��������� ������� � ������ ip � �������������� ������ �� ������������
$nm = 0;
$reff = (strstr(getref(), $_SERVER['SERVER_NAME'])) ? "" : getref();
// �������, ��������� ���� (id_page) ������� ��������
   $pgs = mysql_query("SELECT * FROM ".$config['dbprefix']."count_pages WHERE name='$put'");
   if (!empty($pgs)) {
if(mysql_num_rows($pgs)>0) {
$pag = mysql_fetch_array($pgs);
$nm = $pag['id_page'];
// ���� ������ �������� ����������� � ������� pages � �� ���� �� ����������� - ��������� ������ �������� � �������.
} else {
$db->sql_query("INSERT INTO ".$config['dbprefix']."count_pages VALUES (0, '$put', 0)");
// �������� ��������� ���� ������ ��� ����������� ��������
$nm = mysql_insert_id();
}
   }
// ������� ��������� � ������� ip
$db->sql_query("INSERT INTO ".$config['dbprefix']."count_ip VALUES (0, '$ip', NOW(), '$nm', '$reff')");

list($host1, $total1) = show_ip_host_site(1,0);
list($hostall, $totalall) = show_ip_host_site(0,0);
### // ������� ������
###########
}


#########################################################################

### ��������� ������ ��� ���������� ��� ������ MySQL
}

?>