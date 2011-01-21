<?php
#_strawberry
if (!defined("str_ajax")) { 
header("Location: ../../index.php"); 
exit; 
}

/////////////// define ////////
define('font_directory', root_directory.'/data/font');
define('db_directory', root_directory.'/data/db');
define('admin_directory', root_directory.'/admin');
include_once root_directory.'/data/config.php';
define('time', (time() + ($config['gmtoffset'] - (date('Z') / 60)) * 60));
include_once root_directory.'/inc/no-cache.inc.php';
///////////////////////////////

### xss-hack attempt
if (strpos($_SERVER['REQUEST_URI'], 'http') or strpos($_SERVER['REQUEST_URI'], '..') or strpos($_SERVER['REQUEST_URI'], '//')) {
  @header('HTTP/1.1 301 Moved Permanently');
  @header("Location: ".$config['http_home']."");
  exit(0);
}
if (!empty($_FILES['userfile']['size']) and !intval($_FILES['userfile']['size']) and !stristr(getenv("HTTP_REFERER"), $config['http_home'])) {
  @header('HTTP/1.1 301 Moved Permanently');
  @header("Location: ".$config['http_home']."");
  exit(0);
}

### begin strawberry`s session
@session_start();

### check on admissible symbols
function cheker($input) {
   if (isset($input) and !is_array($input)) {
	$output = (preg_match("#[^a-zA-Zа-€ј-я0-9_\-\,\.\&\;\?\/\:\=\#\@ ]#", $input)) ? "" : $input;
	return $output;   
   } else if (isset($input) and is_array($input)) {
        return $input; 
   }
}

### get our variables
$go = (isset($_POST['go'])) ? intval($_POST['go']) : ((isset($_GET['go'])) ? intval($_GET['go']) : "");
$act = (isset($_POST['act'])) ? cheker($_POST['act']) : ((isset($_GET['act'])) ? cheker($_GET['act']) : "");
$id = (isset($_POST['id'])) ? cheker($_POST['id']) : ((isset($_GET['id'])) ? cheker($_GET['id']) : "");
$cid = (isset($_POST['cid'])) ? cheker($_POST['cid']) : ((isset($_GET['cid'])) ? cheker($_GET['cid']) : "");
$tip = (isset($_POST['tip'])) ? cheker($_POST['tip']) : ((isset($_GET['tip'])) ? cheker($_GET['tip']) : "");
$mod = (isset($_POST['mod'])) ? cheker(strtolower($_POST['mod'])) : ((isset($_GET['mod'])) ? cheker(strtolower($_GET['mod'])) : "");
$text = (isset($_POST['text'])) ? cheker(strtolower($_POST['text'])) : ((isset($_GET['text'])) ? cheker(strtolower($_GET['text'])) : "");
$way = (isset($_POST['way'])) ? cheker(strtolower($_POST['way'])) : ((isset($_GET['way'])) ? cheker(strtolower($_GET['way'])) : "/");

// global $go, $act, $id, $cid, $tip, $mod, $text, $way, $config;



### путь от корн€ сайта до места установки скрипта
function way($in=""){
global $config;
$away = straw_parse_url($config['http_home']);
$addway = (!empty($away['path']) ? '/'.$away['path'].'/' : '/');
 if (!empty($in)) {
   $in = (substr($in, 0, 1) == "/") ? substr($in, 1) : $in;
 }
return $addway.$in;
}

### путь от корн€ сайта до места установки скрипта
function sway($in=""){
global $config;
$sway = straw_parse_url($config['http_script_dir']);
$addsway = (!empty($sway['path']) ? '/'.$sway['path'].'/' : '/');
 if (!empty($in)) {
   $in = (substr($in, 0, 1) == "/") ? substr($in, 1) : $in;
 }
return $addsway.$in;
}

### 
function pin_one() {
global $tip, $way, $config;
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
	imagettftext($image, $pin_font_size, rand(-3, 6), rand(0, 15), 18, $color, font_directory."/".$pin_font, $straw_code);
	imagejpeg($image, "", $config['quality']);
	imagedestroy($image);
return true;
}

### 
function pin_two() {
global $tip;
return "<img src=\"".way("active.php?go=1&amp;tip=".$tip."&amp;time=".(date("HdimYs") + time + round(microtime(), 5)*100000))."\" alt=\"\" /><img src=\"".sway("admin/images/icons/arrow_refresh.png")."\" class=\"pinrefresh\"/>";
}

### 
function spesta_one() {
header ("Content-type: image/png");
$im = imagecreatefrompng (admin_directory."/images/blank.png");
$white = imagecolorallocate ($im,255,255,255);
$ff=file_get_contents (db_directory."/count.txt"); 
$ff=explode ("|", $ff);
imagestring ($im, 1, 25, 4, $ff[1]."/".$ff[2], $white);
imagepng($im);
imagedestroy($im);
return true;
}

###
function spesta_two() {
global $way, $config;
header ("Content-type: image/png");
$bet=34; #между
$shi=12; #ширина
$off = 1; #оффсет
$im = imagecreate (600, 330);
$white = imagecolorallocate ($im,255,255,255);
$blue = imagecolorallocate ($im,0,0,200);
$green = imagecolorallocate ($im,0,200,0);
$red = imagecolorallocate ($im,255,0,000);
$black = imagecolorallocate ($im,0,0,0);
$gred = imagecolorallocate ($im,200,120,50);
$ggred = imagecolorallocate ($im,214,148,88);
$gray = imagecolorallocate ($im,200,200,200);
$lgray = imagecolorallocate ($im,230,230,180);
$llgray = imagecolorallocate ($im,245,245,245);
imagefilledrectangle ($im,0,252,600,330,$llgray);
$f=@file(db_directory."/days.txt");

$ff=@file(db_directory."/count.txt");
$f=array_merge($f,$ff); 

$ut=0;$ht=0;$set=0;$hpt=0;$ot=0;$aut=0;$max1=0;$max2=0;
$to=count($f);
$from=$to-14;
if ($from<0) $from=0;
for ($i=$from; $i<$to; $i++) {
$tmp=explode ("|", $f[$i]);
if ($tmp[1]>$max1) $max1=$tmp[1];
if ($tmp[2]>$max2) $max2=$tmp[2];
$ut=$ut+$tmp[1];
$ht=$ht+$tmp[2];
$set=$set+$tmp[4];
$hpt=$hpt+$tmp[6];
$ot=$ot+$tmp[5];
$aut=$aut+$tmp[1]-$tmp[4]-$tmp[5];
}
$pp= @round(($ht-$hpt)/$ut,2)+1;
$i=-1;
for ($z=$from; $z<$to; $z++) {
$i++;
$tmp=explode ("|", $f[$z]);
////////////////////////////
if (!empty($tmp[2])) {
$w= @round((230/$max2)*$tmp[2]);
if ($w<4) $w=4;
$off=134;
imagefilledrectangle ($im, $off+$bet*$i+1,250-$w+1,$off+$bet*$i+$shi,249,$lgray); # заливка
imagerectangle ($im, $off+$bet*$i,250-$w,$off+$bet*$i+$shi,249,$black); #рамка
imagerectangle ($im, $off+$bet*$i+$shi+1,250-$w+3,$off+$bet*$i+$shi+2,249,$gray); # тень
$w= @round((230/$max1)*$tmp[1]);
if ($w<5) $w=1;
$off=120;
imagefilledrectangle ($im, $off+$bet*$i+1,250-$w+1,$off+$bet*$i+$shi+3,249,$gred); # заливка
imagerectangle ($im, $off+$bet*$i,250-$w,$off+$bet*$i+$shi+3,249,$black); #рамка
imagerectangle ($im, $off+$bet*$i+$shi+4,250-$w+4,$off+$bet*$i+$shi+5,249,$black); # тень
$zzz=$tmp[1]-$tmp[4]-$tmp[5];
$w= @round ((230/$max1)*$zzz);
if ($w<4) $w=$w+31;
imagefilledrectangle ($im, $off+$bet*$i+1,250-$w+1,$off+$bet*$i+$shi+3,249,$ggred); # заливка
imagerectangle ($im, $off+$bet*$i,250-$w,$off+$bet*$i+$shi+3,249,$black); #рамка
$d=explode (".", $tmp[0]);
$d=$d[0].".".$d[1];
imagestring ($im, 1, $off+$bet*$i+1, 255, $d, $blue);
imagestring ($im, 1, $off+$bet*$i+1, 265, $tmp[1], $red);
imagestring ($im, 1, $off+$bet*$i+1, 275, $tmp[2], $green);
imagestring ($im, 1, $off+$bet*$i+1, 285, chop ($tmp[6]), $gred);
imagestring ($im, 1, $off+$bet*$i+1, 300, $tmp[5], $black);
imagestring ($im, 1, $off+$bet*$i+1, 310, $tmp[4], $gred);
imagestring ($im, 1, $off+$bet*$i+1, 320, $zzz, $red);
imagestring ($im, 1, 3, 255, "DATE:", $blue);
imagestring ($im, 1, 3, 265, "UNIQUE:", $red);
imagestring ($im, 1, 3, 275, "HITS:", $green);
imagestring ($im, 1, 3, 285, "HOMEPAGE HITS:", $gred);
imagestring ($im, 1, 3, 300, "OTHER SITES:", $black);
imagestring ($im, 1, 3, 310, "SEARCH ENGINES:", $gred);
imagestring ($im, 1, 3, 320, "AUDIENCE:", $ggred);
// 11.02.2003|683|3509|3509|2|49|606
}
////////////////////////////
}
imagefilledrectangle ($im, 5,150,20,160,$gred); # заливка
imagerectangle ($im,5,150,20,160,$black);
imagestring ($im, 1, 25, 151, "- Unique visitors", $black);
imagefilledrectangle ($im, 5,165,20,175,$ggred); # заливка
imagerectangle ($im,5,165,20,175,$black);
imagestring ($im, 1, 25, 166, "- Site audience", $black);
imagefilledrectangle ($im, 5,180,20,190,$lgray); # заливка
imagerectangle ($im,5,180,20,190,$black);
imagestring ($im, 1, 25, 182, "- Hits", $black);
imagerectangle ($im,0,296,599,329,$gray);
imagerectangle ($im,0,252,600,252,$black);
imagerectangle ($im,0,0,599,329,$black);
imagestring ($im, 1, 2, 2, "VISITS BY DAYS FOR ".strtoupper(str_replace("http://","",$config['http_home']))." // ".date("D M j G:i:s T Y", time), $gred);
imagestring ($im, 1, 5, 30, "UNIQUES TOTAL: ". $ut, $black);
imagestring ($im, 1, 5, 40, "HITS TOTAL: ". $ht, $black);
imagestring ($im, 1, 5, 50, "HOMEPAGE HITS: ". $hpt, $black);
imagestring ($im, 1, 5, 70, "PAGES", $black);
imagestring ($im, 1, 5, 80, "PER VISITOR: ". $pp, $black);
imagestring ($im, 1, 5, 100, "OTHER SITES: ". $ot, $black);
imagestring ($im, 1, 5, 110, "SEARCH ENGINES: ". $set, $black);
imagestring ($im, 1, 5, 120, "AVR. AUDIENCE: ". round ($aut/($i+1)), $black);
imagepng($im);
unlink(db_directory."/reports/img.png");
imagepng($im, db_directory."/reports/img.png");
imagedestroy($im);
return true;
}



/////////////////////////////////////////////////////////////////
function straw_parse_url($url){
global $DOCUMENT_ROOT;
 if(!empty($url)) {
    $url = parse_url($url);
    $url['path'] = !empty($url['path']) ? chicken_dick($url['path']) : '';
    $url['abs']  = $DOCUMENT_ROOT.'/'.$url['path'];
    if (is_file($url['abs'])){
      $url['file'] = end($url['file'] = explode('/', $url['path']));
      $url['path'] = chicken_dick(preg_replace('/'.$url['file'].'$/i', '', $url['path']));
      $url['abs']  = $DOCUMENT_ROOT.'/'.$url['path'];
    }
  return $url;
 } else {
  return;
 }
}
function chicken_dick($chicken, $dick = '/'){
  $chicken = preg_replace('/^(['.preg_quote($dick, '/').']+)/', '', $chicken);
  $chicken = preg_replace('/(['.preg_quote($dick, '/').']+)/', $dick, $chicken);
  $chicken = preg_replace('/(['.preg_quote($dick, '/').']+)$/', '', $chicken);
return $chicken;
}
/////////////////////////////////////////////////////////////////
?>