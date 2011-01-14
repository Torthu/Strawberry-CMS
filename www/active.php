<?php
### STRAWBERRY 1.2.x
### Mr.Miksar (c) 2010

error_reporting(E_ALL);
$ap = 1;
define("str_conf", true); // что бы не запустили системный модуль. // blocked systems files with out this define
define("str_nocache", true); // что бы де-кеш отдельно не запускали // blocked systems files with out this define

### начинаем клубничную сессию // begin strawberry`s session
@session_start();

### защищаем наши адреса от всякой фигни, типа XSS атак. // hack attempt
if (strpos($_SERVER['REQUEST_URI'], 'http') or strpos($_SERVER['REQUEST_URI'], '..') or strpos($_SERVER['REQUEST_URI'], '//')) {
@header('HTTP/1.1 301 Moved Permanently');
@header("Location: http://".$_SERVER['SERVER_NAME']."");
exit();
}
if (!empty($_FILES['userfile']['size']) and !intval($_FILES['userfile']['size']) and !stristr(getenv("HTTP_REFERER"), "http://".$_SERVER['SERVER_NAME'])) {
@header('HTTP/1.1 301 Moved Permanently');
@header("Location: http://".$_SERVER['SERVER_NAME']."");
exit();
}

### проверяем на допустимые символы
function cheker($input) {
   if (isset($input) and !is_array($input)) {
	$output = (preg_match("#[^a-zA-Z0-9_]#", $input)) ? "" : $input;
	return $output;   
   } else if (isset($input) and is_array($input)) {
        return $input; 
   }
}

### определим текущее положение и прочие штуки-дрюки... // where we are?
define('root_directory', str_replace("\\", "/", dirname(__FILE__)."/system"));
define('font_directory', root_directory.'/data/font');
define('admin_directory', root_directory.'/admin');
include_once root_directory.'/data/config.php';
define('time', (time() + ($config['gmtoffset'] - (date('Z') / 60)) * 60));

### определяем наши переменные // get our variables
$go = (isset($_POST['go'])) ? intval($_POST['go']) : ((isset($_GET['go'])) ? intval($_GET['go']) : "");
$act = (isset($_POST['act'])) ? cheker($_POST['act']) : ((isset($_GET['act'])) ? texter($_GET['act']) : "");
$id = (isset($_POST['id'])) ? cheker($_POST['id']) : ((isset($_GET['id'])) ? cheker($_GET['id']) : "");
$cid = (isset($_POST['cid'])) ? cheker($_POST['cid']) : ((isset($_GET['cid'])) ? cheker($_GET['cid']) : "");
$tip = (isset($_POST['tip'])) ? cheker($_POST['tip']) : ((isset($_GET['tip'])) ? cheker($_GET['tip']) : "");
$mod = (isset($_POST['mod'])) ? cheker(strtolower($_POST['mod'])) : ((isset($_GET['mod'])) ? cheker(strtolower($_GET['mod'])) : "");
$text = (isset($_POST['text'])) ? cheker(strtolower($_POST['text'])) : ((isset($_GET['text'])) ? cheker(strtolower($_GET['text'])) : "");
$way = (isset($_POST['way'])) ? cheker(strtolower($_POST['way'])) : ((isset($_GET['way'])) ? cheker(strtolower($_GET['way'])) : "");


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

if ($go == 1) {

// captcha
///////////////////////////////////////////////
include_once root_directory.'/inc/no-cache.inc.php';
unset($_SESSION[$tip.'_pin']);
     $rand_a = (time + (mt_rand(15, time)));
     $rand_b = mt_rand(2, 15);
     $rand_out = substr($rand_a, 1, $rand_b);
$_SESSION[$tip.'_pin'] = $rand_out;
$straw_code = (!empty($tip) and !empty($_SESSION[$tip.'_pin'])) ? substr(hexdec(md5(date("F j").$rand_out.$config['sitekey'])), 2, 6) : "ERROR";
$pin_font_size = !empty($config['pin_font_size']) ? $config['pin_font_size'] : "11";
$pin_font = !empty($config['pin_font']) ? $config['pin_font'] : "default.ttf";
	header("Content-type: image/jpeg");
	$image = imagecreatefromjpeg(admin_directory."/images/code_bg.jpg");
	$color = imagecolorallocate($image, 128, 77, 82); // +10
	imagettftext($image, $pin_font_size, rand(-3, 3), rand(5, 15), 18, $color, font_directory."/".$pin_font, $straw_code);
	imagejpeg($image, "", $config['quality']);
	imagedestroy($image);
	
unset($tip, $rand_a, $rand_b, $rand_out, $straw_code, $image, $color);
exit(0);
///////////////////////////////////////////////


}  elseif ($go == 2) {

// look pic for capcha
///////////////////////////////////////////////
include_once root_directory.'/inc/no-cache.inc.php';
echo "<img src=\"/active.php?go=1&amp;tip=".$tip."&amp;time=".(date("HdimYs") + time + round(microtime(), 5)*100000)."\" alt=\"\" /><img src=\"/system/admin/images/icons/arrow_refresh.png\" class=\"pinrefresh\"/>";
unset($var, $tip);
exit();
///////////////////////////////////////////////

	
} elseif ($go == 3) {

// ---
///////////////////////////////////////////////
include_once root_directory.'/inc/no-cache.inc.php';

unset($var);
exit();
///////////////////////////////////////////////


} elseif ($go == 4) {

// ---
///////////////////////////////////////////////
include_once root_directory.'/inc/no-cache.inc.php';

unset($var);
exit();
///////////////////////////////////////////////


} elseif ($go == 5) {

// ---
///////////////////////////////////////////////
include_once root_directory.'/inc/no-cache.inc.php';

unset($var);
exit();
///////////////////////////////////////////////


} 

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

/*
	// In this demo we trigger the uploadError event in SWFUpload by returning a status code other than 200 
	// (which is the default returned by PHP)
	if (!isset($_FILES["userfile"]) || !is_uploaded_file($_FILES["userfile"]["tmp_name"]) || $_FILES["userfile"]["error"] != 0) {
		// Usually we'll only get an invalid upload if our PHP.INI upload sizes are smaller than the size of the file we allowed
		// to be uploaded.
		header("HTTP/1.1 500 File Upload Error");
		if (isset($_FILES["userfile"])) {
			echo $_FILES["userfile"]["error"];
		}
		exit(0);
	}
	
*/


?>