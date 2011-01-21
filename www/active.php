<?php
### STRAWBERRY 1.2.x
### Mr.Miksar (c) 2011

error_reporting(E_ALL);
$ap = 1;
define("str_ajax", true); // что бы не запустили функции. // blocked systems files with out this define
define("str_conf", true); // что бы не запустили системный модуль.
define("str_nocache", true); // что бы де-кеш отдельно не запускали
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
### определим текущее положение и прочие штуки-дрюки... // where we are?
define('root_directory', str_replace("\\", "/", dirname(__FILE__)."/system"));
include_once (root_directory.'/inc/ajax.inc.php');

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

if ($go == 404) {
// all close
exit(0);
} elseif ($go == 1) {
// captcha
///////////////////////////////////////////////


echo pin_one();
unset($tip, $rand_a, $rand_b, $rand_out, $straw_code, $image, $color);
exit(0);


///////////////////////////////////////////////
}  elseif ($go == 2) {
// look pic for capcha
///////////////////////////////////////////////


echo pin_two();
unset($var, $tip);
exit(0);


///////////////////////////////////////////////
} elseif ($go == 3) {
// spesta graf counter by SPESTA
///////////////////////////////////////////////


echo spesta_one();
unset($var);
exit(0);


///////////////////////////////////////////////
} elseif ($go == 4) {
// spesta graf counter 2 by SPESTA
///////////////////////////////////////////////


echo spesta_two();
unset($var);
exit(0);


///////////////////////////////////////////////
} elseif ($go == 5) {
// seo statistic
///////////////////////////////////////////////


include (db_directory."/se.txt");
unset($var);
exit(0);


///////////////////////////////////////////////
} else {
exit(0);
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