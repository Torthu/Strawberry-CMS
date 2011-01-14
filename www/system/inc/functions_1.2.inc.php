<?php
#_strawberry
if (!defined("str_adm")) { 
header("Location: ../../index.php"); 
exit; 
}

/**
 * Стандартные функции Strawberry 1.2, которые всегда доступны.
 * Может случиться так, что в этом разделе нет нужной функции, то - увы - она нестандартная и
 * Вам, вероятно, придется написать ее самому...
 * Если вы хотите что бы и ваши функции участвовали в следующих версиях скрипта, то пишите на miksar@mail.ru
 * @package Functions
 **/

/**

Ходят ходят ежики в туман,
Осторожненько.
Собирают ежики в карман,
Капли дождика.

Долго-долго ежики в пути,
За порожиком,
Только ты за ними не ходи,
Станешь ежиком. (с)

**/

/*# strawberry 1.2 original functions #*/

 /**
 * Третья попытка организовать мультиязычность. by Mr.Miksar && Lexa Zloy
 * @param string $text
 * @param string $array
 * @return string
 */
function t($text, $array = array()){
global $plugin, $config, $gettext, $tr_lng;
    if (empty($text)){
      return;
    } else {
ignore_user_abort(true); // что бы юзер запись не отменил. когда текста много, возможны тормоза...
    if ($config['lang'] != "ru") {
####################
// not RU Only -- language


$ar_in = array('"', '$', '<?', '?>', "\n", "\r");
$ar_out = array('\"', '\$', '&lt;?', '?&gt;', '', '');
//$tr_txt = htmlspecialchars(str_replace($ar_in, $ar_out, $text));
$tr_txt = str_replace($ar_in, $ar_out, $text);
$sha1_key = sha1($tr_txt);

if (!defined($sha1_key) and !in_array($sha1_key, $tr_lng)) {
  $nd = "define(\"".$sha1_key."\",\"".$tr_txt."\");\n";
  $tr_lng[] = $sha1_key;
  @chmod(language,0666);
  file_write(language, $nd, false, '0666', 'a');
  @chmod(language,0644);
} elseif (defined($sha1_key)) {
  $text = constant($sha1_key);
} else {
  $text = $text;
}


/*
 В степи, покрытой пылью бренной, 
 Сидел и плакал человек. 
 А мимо шел Творец Вселенной. 
 Остановившись, он изрек: 
 
 "Я друг униженных и бедных, 
 Я всех убогих берегу, 
 Я знаю много слов заветных. 
 Я есмь твой Бог. Я все могу. 
 
 Меня печалит вид твой грустный, 
 Какой бедою ты тесним?!" 
 И человек сказал: "Я – русский", 
 И Бог заплакал вместе с ним.
 */


// RU Only
####################
} 

  if (!empty($array)) {  
    foreach ($array as $k => $v) {
        $text = str_replace('%'.$k, $v, $text);
    }
  }

  if (!empty($config['charset']) and $config['charset'] != 'windows-1251') {
    $text = iconv('windows-1251', $config['charset'], $text);
  }

  //  print_r($tr_lng);
      return $text;
   }
}







### начало таблицы модуля
function otable() {
global $tit;
echo "<div width=\"100%\"><div class=\"arttit\">".$tit."</div><div class=\"ctable nbtext\" width=\"100%\">";
}

### конец таблицы модуля
function ctable() {
echo "</div></div>";
}




########### (c) Mr.Milsar ##################
### Функция доп полей
### изменения от 01.11.2009


### выводит доп поля для контента $ct с номером $it_id
### используется для одноразового вывда всех доп полей для единицы контента
function strawberry_id_fields($ct="news", $it_id="0") {
global $db, $id, $config, $user_id, $id_of_search_news, $row, $tpl, $post, $sfields;

$arr_content = array();
$sfields = array();

if (!empty($config['use_dop_fields'])) {

if ($ct == "users"){
$cfid = ($it_id) ? $it_id : $user_id;
} elseif ($ct == "search") {
$cfid = ($it_id) ? $it_id : $id_of_search_news;
} else {
$ct = "news";
$cfid = (($it_id) ? $it_id : (($row['id']) ? $row['id'] : $id));
}

$arr_content = !empty($sfields[$ct][$cfid]) ? $sfields[$ct][$cfid] : array();

if (count($arr_content) > 0) {

foreach ($arr_content as $fk => $fv) {

$handle = opendir(stpl_directory.'/fields/');

   while ($file = readdir($handle)){
        if (strtolower(end(explode('.', $file))) == "tpl"){
############
$tpl_field_name  = $fv['fname'];
$tpl_field_value = run_filters('news-entry-content', aply_bbcodes($fv['fvalue']));

 ob_start();
include stpl_directory.'/fields/'.$file;
$fld_out = ob_get_contents();
 ob_end_clean();
$ffile = substr($file, 0, -4);
$tpl[$ct][$fv['fnum']][$ffile] = $fld_out;
############
        }
   }
}

return $tpl;

} else {
return;
}

} else {
return;
}

}



////////////////////////////////////////////////////////////////////////////////////
### Подключение доп.полей.
### Выводит весь массив доп полей.
### или заданный через $fo_where
### Внимание! при большом объеме содержимого доп полей возможна перегрузка сервера.
function strawberry_array_fields($fo_where = "") {
global $db, $config, $sfields, $cache;

  if (!$sfields = $cache->unserialize('sfields') and $config['use_dop_fields']){
            $row_sfil_db = $db->sql_query("SELECT * FROM ".$config['dbprefix']."fields WHERE status='1' ".$fo_where." ORDER BY fid ASC");
        while ($frow=$db->sql_fetchrow($row_sfil_db)) {
	    $sfields[$frow['modul']][$frow['content_id']][$frow['fnum']] = $frow;
	}
	    $sfields = $cache->serialize($sfields);
return $sfields;	    
  } else {
  return;
  }
}
////////////////////////////////////////////////////////////////////////////////////


function strawberry_fields($sf_table = "news", $sf_cont = array(), $in_cont = "") {
global $config, $cache, $sfields;
$sfields = array();
$fo_where = "AND modul='".$sf_table."'";
$fo_where .= !empty($sf_cont) ? " AND content_id IN (".implode(",",$sf_cont).")" : "";

if (!empty($sf_cont) and is_array($sf_cont) and !empty($config['use_dop_fields'])) {

 if (preg_match("/{f-(.*?)-f}/si", $in_cont)) {

if ((!empty($config['use_dop_fields_n']) and $sf_table == "news") or (!empty($config['use_dop_fields_u']) and $sf_table == "users")) {
strawberry_array_fields($fo_where);
}

  foreach ($sf_cont as $fk => $fv) {
    if (!empty($sfields[$sf_table][$fv])) {
      $arr_content = $sfields[$sf_table][$fv];
      foreach ($arr_content as $ffk => $ffv) {
      
        $handle = opendir(stpl_directory.'/fields/');
        while ($file = readdir($handle)){
          if (strtolower(end(explode('.', $file))) == "tpl"){
############
           $tpl_field_name  = $ffv['fname'];
           $tpl_field_value = $ffv['fvalue'];
            ob_start();
            include stpl_directory.'/fields/'.$file;
           $fld_out = ob_get_contents();
            ob_end_clean();
           $ffile = substr($file, 0, -4);
           $fftpl[$sf_table][$ffv['content_id']][$ffv['fnum']][$ffile] = $fld_out;
############
          }
        }
      
      }
    }
  }




 
  $match_count = preg_match_all("/{f-(.*?)-f}/si", $in_cont, $ftpl);
   for ($i = 0; $i < $match_count; $i++) {
    $ffim = $ftpl[1][$i];
    $ffa = explode('-', $ffim);
    $ffm[] = "#{f-".$ffim."-f}#si";
    if ((!empty($config['use_dop_fields_n']) and $sf_table == "news") or (!empty($config['use_dop_fields_u']) and $sf_table == "users")) { 
       $ffhtml[] = !empty($fftpl[$ffa[0]][$ffa[1]][$ffa[2]][$ffa[3]]) ? $fftpl[$ffa[0]][$ffa[1]][$ffa[2]][$ffa[3]] : "";
    } else {
       $ffhtml[] = "";
    }
   }
   
   $ffhtml = (!empty($ffhtml)) ? run_filters('news-entry-content', aply_bbcodes($ffhtml)) : array();
   return preg_replace($ffm, $ffhtml, $in_cont);  

 } else {
   return $in_cont;
 }

} else {
  return $in_cont;
}

}


### // Функция доп полей
################# (c) Mr.Miksar ###############











###########
### выводим данные по хостам и хитам на сайте за указанный период
function show_ip_host_site($begin="",$end="",$id_page="") {
global $config, $online, $tpl, $sql_error_out;
$and1 = "";
$and2 = "";

 if(empty($id_page)) { 
  $tmp = ""; 
  } else {
  $tmp = "id_page=".$id_page;
  }

if ($sql_error_out != "mysql") {
  // Запрос на статистику за временной интервал определяемый параметрами $begin,$end
if($begin == 0) { 
$tmp2 = "";
 } else {
$tmp2 = "putdate>=date_sub(date_format('".langdate('Y-m-d H:i:s', time)."','%Y-%m-%d 23:59:59'),interval '".$begin."' day)";
 }
$tmp1 = "putdate<date_sub(date_format('".langdate('Y-m-d H:i:s', time)."','%Y-%m-%d 23:59:59'),interval '$end' day)";
  // Расставляем and
  if($tmp2!= "" or $tmp != "") { $and1 = " and "; }
  if($tmp2!= "" and $tmp != "") { $and2 = " and ";}

  // Общее число хитов
  $query_total = "select count(*) from ".$config['dbprefix']."count_ip where 
  ".$tmp1.
  $and1.
  $tmp2.
  $and2.
  $tmp."
  ";
  // Подсчитываем число IP-адресов (хостов)
  $query = "select count(distinct ip) from ".$config['dbprefix']."count_ip where ".$tmp1.$and1.$tmp2.$and2.$tmp."";
  // Осуществляем запросы к базе данных
  $tot = mysql_query($query_total);
  $ipsad = mysql_query($query);
  if($tot && $ipsad)
  {
    $totl = mysql_fetch_array($tot);
    $ip = mysql_fetch_array($ipsad);
    return array($ip['count(distinct ip)'],$totl['count(*)']);
  } else {
  $sql_error_out = "mysql";
#reporterror("Ошибка при обращении к таблице счетчика IP-адресов...");
  }
  } else {
  $sql_error_out = "mysql";
  }
}



###########
### выводим данные по хостам и хитам в админпанели за указанный период
function show_ip_host($begin="",$end="",$id_page="",$day="",$is_hour="") {
	global $config, $db, $adfile;
  // Эта переменная определяет осуществляется ли запрос к конкретной
  // странице или ко всему сайту.
  if(empty($id_page)) { 
  $tmp = ""; 
  } else {
  $tmp = "id_page=".$id_page;
  }
  // Запрос на статистику за временной интервал определяемый
  // параметрами $begin,$end

  if($begin == 0) {
    $tmp2 = "";
  } else {

   if(empty($is_hour)) {
    $tmp2 = "putdate>=date_sub(date_format('".langdate('Y-m-d H:i:s', time)."','%Y-%m-%d 23:59:59'),interval '".$begin."' day)";
  } else {
    $tmp2 = "putdate>=date_add(date_format(date_sub('".langdate('Y-m-d H:i:s', time)."',interval '$day' day),'%Y-%m-%d 00:00:00'),interval '$end' hour)";
  }
  
  }

     if(empty($is_hour)) {
    $tmp1 = "putdate<date_sub(date_format('".langdate('Y-m-d H:i:s', time)."','%Y-%m-%d 23:59:59'),interval '$end' day)";
  } else {
    $tmp1 = "putdate<date_add(date_format(date_sub('".langdate('Y-m-d H:i:s', time)."',interval '$day' day),'%Y-%m-%d 00:00:00'),interval '$begin' hour)";
  }

  // Расставляем and
  if($tmp2!="" || $tmp !="") $and1 = " and ";
    else $and1 = ""; 
  if($tmp2!="" && $tmp !="") $and2 = " and ";
    else $and2 = ""; 
  // Общее число хитов
  $query_total = "select count(*) from ".$config['dbprefix']."count_ip where ".$tmp1.$and1.$tmp2.$and2.$tmp.";";
  // Подсчитываем число IP-адресов (хостов)
  $query_host = "select count(distinct ip) from ".$config['dbprefix']."count_ip where ".$tmp1.$and1.$tmp2.$and2.$tmp.";";
  // Осуществляем запросы к базе данных
  $tot = $db->sql_query($query_total);
  $ipsad = $db->sql_query($query_host);
  if($tot && $ipsad)
  {
    $totl = mysql_fetch_array($tot);
    $host = mysql_fetch_array($ipsad);
    return array($host['count(distinct ip)'],$totl['count(*)']);
  } else {
    return t("Ошибка при обращении к таблице IP-адресов...");
  }
}



###########
### выводим данные по хостам и хитам в заданной теме оформления
function schet($counttheme) {
global $config, $host1, $total1, $hostall, $totalall, $tpl;

if ($host1 && $total1 && $hostall && $totalall) {
$tpl['counter']['host']  = $host1;
$tpl['counter']['total'] = $total1;
$tpl['counter']['hostall']  = $hostall;
$tpl['counter']['totalall'] = $totalall;

if (is_file(stpl_directory.'/counter/'.$counttheme.'.tpl')) {
ob_start();
include stpl_directory.'/counter/'.$counttheme.'.tpl';
$schet = ob_get_contents();
 ob_end_clean();
} else {
$schet = "Сегодня кликов: ".$total1.". Сегодня хостов: ".$host1.". Всего кликов: ".$totalall.". Всего хостов: ".$hostall.".";
}

} else {
$schet = "";
}

return $schet;
}
### // счетчик
########### (c) softtime@softtime.ru ##################



 ### проверка куков
function cook_ch() {
global $config;
  // это небольшой проверочный скрипт, выясняющий,
  // включены ли cookies у пользователя  
if (empty($_COOKIE['str_test_cookie'])) {
@setcookie('str_test_cookie', '1');
return t('Для корректной работы сайта необходима поддержка cookies!');
} else {
return;
}
}

#### стандартная форма авторизации
function lform() {
global $result_in, $config;
$lf = "<form name=\"login\" action=\"\" method=\"post\">"
."<table border=\"0\" cellpadding=\"2\" class=\"defloginform\">"
."<tr>"
."<td width=\"50\">&nbsp;&nbsp;".t("Логин").":&nbsp;</td>"
."<td width=\"170\"><input type=\"text\" name=\"username\" class=\"regtext\"></td>"
."</tr>"
."<tr>"
."<td>&nbsp;&nbsp;".t("Пароль").":&nbsp;</td>"
."<td><input type=\"password\" name=\"password\" class=\"regtext\"></td>"
."</tr>";
$lf .= pin_cod_auth("login", "auth"); 
$lf .= "<tr>"
."<td colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"regok\" value=\"".t("ВХОД")."\"></td>"
."</tr>";

if (!empty($result_in) and !cook_ch()) {
$lf .= "<tr>"
."<td colspan=\"2\">".$result_in."</td>"
."</tr>";
}
$ck_out = cook_ch();
if (!empty($ck_out)) {
$lf .= "<tr>"
."<td colspan=\"2\" class=\"moder\">".$ck_out."</td>"
."</tr>";
}

$lf .= "<tr>"
."<td colspan=\"2\">"
."» <a href=\"index.php?mod=account&amp;act=registration\">".t("Регистрация")."</a><br>"
."» <a href=\"index.php?mod=account&amp;act=forgot\">".t("Забыли пароль?")."</a></td>"
."</tr></table>"
."<input type=\"hidden\" name=\"action\" value=\"dologin\">"
."</form>";
return $lf;
}


### Проверка пользователя
# Если система распознала робота - robot
# Если неавторизован - гость
# Если параметр 0 и авторизован - логин
# Если параметр 1 и авторизован - имя

function chk_user($ch_name, $bot=0) {
global $is_logged_in, $member, $spider_here;

if (!empty($bot)) {
      if (!empty($spider_here)) {
       return "robot";
      }
}

  if (!empty($is_logged_in)) {
      if (!empty($ch_name)) {
        return $member['name'];
      } else {
        return $member['username'];
      }
  } else {
        return "guest";
  }

}

/*
### Определяем правильный размер
function formatsize($file_size){
    if ($file_size >= 1073741824){
      $file_size = (round($file_size / 1073741824 * 100) / 100).' '.t('Гбайт');
    } elseif ($file_size >= 1048576){
      $file_size = (round($file_size / 1048576 * 100) / 100).' '.t('Мбайт');
    } elseif ($file_size >= 1024){
      $file_size = (round($file_size / 1024 * 100) / 100).' '.t('Кбайт');
    } else {
      $file_size = $file_size.' '.t('байт');
    }
return '<nobr>'.$file_size.'</nobr>';
}
*/


// (c) Chaser <coderunnerz@gmail.com>
### Определяем правильный размер
function formatsize($file_size=0) {

if (is_numeric($file_size)) {

	  	$sizes = array(t('байт'), t('Кбайт'), t('Мбайт'), t('Гбайт'), t('Тбайт'));
  		$rext = $sizes[0];
  		for ($i=1; (($i < count($sizes)) && ($file_size >= 1024)); $i++) 
		{
	   	$file_size = $file_size / 1024;   		
		$rext  = $sizes[$i];
	        }
  	return round($file_size, 2)." ".$rext;
  	
  	} else {
  	
  	return;
  	
  	}
}


### Альтернативная определялка размера
# Size filter
function razmer($size=0) {
if (is_numeric($size)) {
  $name = array(t('байт'), t('Кбайт'), t('Мбайт'), t('Гбайт'), t('Тбайт'), t('Пбайт'), t('Ебайт'), t('Збайт'), t('Юбайт'));
  $thissize = $size ? "".round($size / pow(1024, ($i = floor(log($size, 1024)))), 2)." ".$name[$i]."" : "".$size." Bytes";
  return $thissize;
    	} else {
  	return;
  	}
}




### Определяем - есть ли http://.
function rurl($adressok, $opt="http"){
// (с) chaser
// modify 09.02.2010 by mr.miksar
if (!preg_match("/(http|https|ftp|rtmp|rtm)\:\/\//", $adressok)) {
$adressok = $opt."://".$adressok;
} 
return $adressok;
}




### Жизнь сайта
function sitelife($vid){
global $config, $TheYear;
if ($config['sitelife'] == 1) {
 ### если вдруг вы не указали дату запуска сайта, то будем считать дни от рождения сайта разработчика ;)
 if(!$config['d']) $config['d'] = 1;
 if(!$config['m']) $config['m'] = 2;
 if(!$config['y']) $config['y'] = 2009;
if ($config['d'] > 31) $config['d'] = 31;
if ($config['m'] > 12) $config['m'] = 12;
if ($config['y'] > $TheYear) $config['y'] = $TheYear;
if((substr($config['d'], 0, 1)) == "0") $config['d'] = substr($config['d'], 1, 2);
if((substr($config['m'], 0, 1)) == "0") $config['m'] = substr($config['m'], 1, 2);
 $dto = mktime(0, 0, 0, $config['m'], $config['d'], $config['y']);
 $dtt = time();
 $dt = ($dtt - $dto) / (60*60*24);
 if(strpos($dt,'.')>0) list($dt) = explode('.',$dt);
 $pryear = (!empty($dt) and $dt > 0) ? $dt/365 : 0;
 $ryear = round($pryear);
 $tpl['sitelife']['years'] = ($ryear > $pryear) ? $ryear-1 : $ryear;
 $tpl['sitelife']['days'] = round($dt-(365*$tpl['sitelife']['years']));
 
 if (is_file(stpl_directory.'/sitelife/'.$vid.'.tpl')) {
 ob_start();
 include stpl_directory.'/sitelife/'.$vid.'.tpl';
 $site_live = ob_get_clean();
 } else {
 $site_live = (!empty($tpl['sitelife']['years']) ? t('Лет сайту').": ".$tpl['sitelife']['years'] : "").". ".t('Дней сайту').": ".$tpl['sitelife']['days'];
 }
} else {
$site_live = "";
}
return $site_live;
}


### Отзывается ли сервер?
function readserver($adr = '') {
if (!empty($adr)) {
$fd = fopen($adr, "r");
fclose ($fd);
if (!empty($fd)) {
return 1;
} else {
return;
}
} else {
return;
}
}


### Получение IP
function getip() {
	if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
		$ip = getenv("REMOTE_ADDR");
	} elseif (!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
		$ip = $_SERVER['REMOTE_ADDR'];
	} else {
		$ip = "0.0.0.0";
	}
if ((getenv("HTTP_X_FORWARDED_FOR") != NULL) and (getenv("HTTP_X_FORWARDED_FOR") != $ip)) { 
$a_gx = array();
$a_gx= explode(", ", getenv('HTTP_X_FORWARDED_FOR'));
$ip .= "/".implode("/", $a_gx);
}
return $ip;
}


### Получение REFERER
function getref() {
$ref_env = cheker(getenv("HTTP_REFERER"));
	if (!empty($ref_env) && $ref_env != "" && !preg_match("/^unknown/i", $ref_env) && !preg_match("/^bookmark/i", $ref_env) && !strpos($ref_env, $_SERVER["HTTP_HOST"])) {
		$ref = $ref_env;
	} else {
		$ref = "";
	}
	return $ref;
}


### Получение REFERER c себя
function getref_self() {
$ref_env = cheker(getenv("HTTP_REFERER"));
	if (!empty($ref_env) && $ref_env != "" && !preg_match("/^unknown/i", $ref_env) && !preg_match("/^bookmark/i", $ref_env) && strpos($ref_env, $_SERVER["HTTP_HOST"])) {
		$ref = $ref_env;
	} else {
		$ref = "";
	}
	return $ref;
}


### Получение REFERER cо всех
function getref_all() {
$ref_env = cheker(getenv("HTTP_REFERER"));
	if (!empty($ref_env) && $ref_env != "" && !preg_match("/^unknown/i", $ref_env) && !preg_match("/^bookmark/i", $ref_env)) {
		$ref = $ref_env;
	} else {
		$ref = "";
	}
	return $ref;
}











################### PIN

### Вывод рэндомного pin для авторизации

### Проверка pin
function pin_check($tip="1") {
	global $config, $is_logged_in, $rand_out;
	if (empty($is_logged_in)) {
	$p_ch = !empty($_POST['pin_check']) ? intval($_POST['pin_check']) : '';
//intval($_REQUEST['rand']);
  $code = substr(hexdec(md5(date("F j").(!empty($_SESSION[$tip.'_pin']) ? $_SESSION[$tip.'_pin'] : $p_ch).$config['sitekey']."")), 2, 6);
  
		if (extension_loaded("gd") && $code != $p_ch) {
			return 1;
		} else {
	                unset($_SESSION[$tip.'_pin'], $code, $p_ch, $_POST['pin_check']);	
			return 0;
		}
		
	} else {
		return 0;
	}
}


### Вывод рэндомного pin
function pin_cod($styler="", $tip="1") {
global $config, $is_logged_in;
if (!empty($config['pin'])) {
if (extension_loaded("gd") and empty($is_logged_in)) {

//$tpl['capcha']['img'] = "<img src=\"/index.php?pin=1&amp;tip=".$tip."\" border=\"1\" alt=\"".t("Код безопасности")."\">";
//$tpl['capcha']['img'] = "<div OnClick=\"gload('1', '".$tip."', '1', 'img', '', '', '".$tip."', '', '', ''); return false;\" class=\"strawptcha\"><div id=\"ajx".$tip."\"><img src=\"/active.php?go=1&amp;tip=".$tip."\" alt=\"".t("Код безопасности")."\"></div></div>";
//$tpl['capcha']['img'] = "<div OnClick=\"wcompleted('".$tip."', '".$tip."', ''); return false;\"><div id=\"ajx".$tip."\"><img src=\"/active.php?go=1&amp;tip=".$tip."\" alt=\"".t("Код безопасности")."\"></div></div>";


$tpl['capcha']['img'] = "<div OnClick=\"pinload('1', '".$tip."', '2', '".$tip."', ''); return false;\" class=\"strawptcha\"><div id=\"ajx".$tip."\"><img src=\"/active.php?go=1&amp;tip=".$tip."\" alt=\"\"><img src=\"/system/admin/images/icons/arrow_refresh.png\" border=\"0\" class=\"pinrefresh\" alt=\"\"/></div></div>";
$tpl['capcha']['enter'] = "<input type=\"text\" name=\"pin_check\" size=\"10\" maxlength=\"10\" class=\"pin_enter\">";


if (is_file(stpl_directory.'/captcha/'.$styler.'.tpl')) {
ob_start();
include stpl_directory.'/captcha/'.$styler.'.tpl';
$codder_cap = ob_get_clean();
} else {
$codder_cap = t('Ваш код').": ".$tpl['capcha']['img']."<br>".t("Повтор").": ".$tpl['capcha']['enter'];
}
return $codder_cap;

}
} else {
return;
}
}


function pin_cod_auth($styler="", $tip="") {
global $config;
if (!empty($config['pin_auth'])) {
return pin_cod($styler, $tip);
} else {
return;
}
}

################## PIN




######### meta
function str_meta(){
global $tit, $config, $TheYear, $nid, $siteurl, $sesuser;

$fha = straw_parse_url($config['http_script_dir']);
$fhb = straw_parse_url($config['http_home']);
$fhap = $fha['path'];
$fhbp = $fhb['path'] ? "/".$fhb['path']."/" : "/";

$metas = "\n<title>".$config['delitel']." ".$config['home_title']." ".$config['delitel']; 
if (!empty($tit)) $metas .= " ".$tit." ".$config['delitel']; 
if (function_exists('metainfo') and !empty($nid)) $metas .= " ".metainfo('title')." ".$config['delitel'];
$metas .= "</title>\r";
$metas .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$config['charset']."\">\r";
$metas .= "<meta http-equiv=\"Content-Language\" content=\"".$config['lang']."\">\r"
."<meta http-equiv=\"Author\" content=\"".$config['home_author']." © ".$TheYear."\">\r";

if (!empty($config['decacher'])) $metas .= "<meta http-equiv=\"Pragma\" content=\"no-cache\">\r";

$metas .= "<meta name=\"keywords\" content=\"";
if (function_exists('metainfo') and !empty($nid)) $metas .= metainfo('keywords').', ';
$metas .= $config['keywords'];
$metas .= "\">\r";

$metas .= "<meta name=\"description\" content=\"";
if (function_exists('metainfo') and !empty($nid)) {
$metas .= metainfo('description');

} elseif (!empty($_GET['category']) and !empty($categories[category_get_id($_GET['category'])]['description'])) {
$metas .= $categories[category_get_id($_GET['category'])]['description'];

} else {
$metas .= $config['description'];
} 

if (!empty($_GET['mod'])) {
$metas .= " ".$config['delitel']." модуль ".cheker($_GET['mod']);
}

$metas .= "\">\r";

if (!empty($config['home_author'])) { 
$metas .= "<meta name=\"author\" content=\"".$config['home_author']."\">\r"; 
}

$metas .= "<meta name=\"copyright\" content=\"© Strawberry 1.2 2009 - ".$TheYear."\">\r";

if (!empty($config['revisit'])) { 
$metas .= "<meta name=\"revisit\" content=\"".$config['revisit']." days\">\r"
."<meta name=\"revisit-after\" content=\"".$config['revisit']." days\">\r";
}

$metas .= file_get_contents(header_file)."\r";

if (!empty($config['rss_potok'])) {
  $in_filer = file(data_directory."/db/rss.txt");
  for($in_rss=0; $in_rss<count($in_filer); $in_rss++) {
    $out_rss = explode("<>", $in_filer[$in_rss]);
    $metas .= "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"".trim($out_rss[1])."\" title=\"".trim($out_rss[0])."\">\r";
  }
}

$metas .= "<link rel=\"search\" type=\"application/opensearchdescription+xml\" href=\"".$fhbp.$config['home_page']."?mod=search\" title=\"".$config['home_title']." ".$config['delitel']." Search\">\r";
$metas .= "<link href=\"".$fhbp."favicon.ico\" type=\"image/x-icon\" rel=\"shortcut icon\">\r"
."<link href=\"".$fhbp."favicon.ico\" type=\"image/x-icon\" rel=\"icon\">";

if ($sesuser != "robot"){ 
 $metas .= "<link href=\"/".$fhap."/data/themes/".$config['mytheme']."/style.css\" type=\"text/css\" media=\"screen\" rel=\"stylesheet\">\r";
}

if (!empty($config['js_potok']) and $sesuser != "robot") {
$in_filer = file_read(js_file);
$where_mas = explode(",", $in_filer);
  foreach ($where_mas as $val) {
    if (!empty($val)) $metas .= "<screept type=\"text/javascript\" language=\"JavaScript\" src=\"/".$fhap."/data/java/".$val."\"></screept>\r";
  }
}

return $metas;
}
######### meta



### генератор паролей
function generate_password($ider=8)  {
// где $ider - число символов в пароле.
    $arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6','7','8','9','0','.',',','(',')','[',']','!','?','&','^','%','@','*','$','<','>','/','|','+','-','{','}','`','~');
    // Генерируем пароль
    $pass = "";
    for($i = 0; $i < $ider; $i++)
    {
      // Вычисляем случайный индекс массива
      $index = mt_rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
  }

// из punBB
function random_pass($len = 8){
    $chars    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $password = '';
    for ($i = 0; $i < $len; ++$i){
        $password .= substr($chars, (mt_rand() % strlen($chars)), 1);
    }
return $password;
}



/**
 * Класс поменян - поэтому с доками.
 * @access private
 */
class microTimer {
  /**
   * Запускаем часы. В принципе - это аналог старого запуска, но в упрощеной форме...
   * @access private
   */
    function start(){
    global $starttime;
    $starttime = array_sum(explode(" ", microtime()));
    }

    /**
     * А теперь посчитаем, сколько же времени прошло от запуска часов...
     * @access private
     * @return unknown
     */
     function stop(){
     global $starttime;
     $totaltime =  round(((array_sum(explode(" ", microtime()))) - $starttime), 5); // округляем до 5 знаков после запятой
     return $totaltime;
    }
}


### проверяем зарегенный юзер или нет на сайте
function chuser($ns="", $a="0") {
global $config, $db, $ap, $users;

$us = (!empty($ap) and $ns != 'guest') ? (!empty($users[$ns]['username']) ? $users[$ns]['username'] : '') : ((!empty($users[$ns]['name']) and $ns != 'guest') ? $users[$ns]['name'] : '');

if (!empty($us)) {
$nms = !empty($ap) ? 
"<a href=\"index.php?mod=editusers&amp;action=edituser&amp;id=".$users[$ns]['id']."\" title=\"".t("Зарегистрированный пользователь %u", array('u'=>$users[$ns]['name']))."\">".$users[$ns]['name']."</a>" : 
"<a href=\"index.php?mod=users&amp;user=".$users[$ns]['username']."\" title=\"".t("Зарегистрированный пользователь %u", array('u'=>$users[$ns]['name']))."\">".$users[$ns]['name']."</a>";
} else {
$nms = $ns;
}
return $nms;
}

### Что за ip?
function whois_ip($ids="") {
global $config;
$arr_ids = array();
$ids = (!empty($ids)) ? $ids : getip();

if ($ids == "127.0.0.1") {
  return t('Локальный хост');
} elseif (!empty($ids) and $ids != "127.0.0.1") {

  if(strstr($ids, "/")) {
    $ridsdata = array();
    $arr_ids = explode("/", $ids);
    foreach ($arr_ids as $ids_data) {
      $ridsdata[] = "<a target=\"_blank\" href=\"".$config['whois'].$ids_data."\" title=\"".t("Посмотреть данные IP")."\">".$ids_data."</a>";
    }
    return implode("/",$ridsdata);
  } else {
    return "<a target=\"_blank\" href=\"".$config['whois'].$ids."\" title=\"".t("Посмотреть данные IP")."\">".$ids."</a>";
  }

} elseif (empty($ids)) {
return;
}
}

### Меню для модулей
function on_page($inmod="", $up="0") {
global $config, $modul;
$dop = "";
$outmod = !empty($inmod) ? $inmod : (!empty($modul) ? $modul : $config['modul']);
if (!empty($outmod)) {
$dop = "?mod=".$outmod;
}
if (empty($up)) {
$upp = "| <a href=\"#\" title=\"".t("Переместиться вверх страницы")."\">".t("Наверх")."</a> ";
} else {
$upp = "";
}
return "<center><div class=\"dmenu\">[ <a href=\"javascript:history.go(-1)\" title=\"".t("Вернуться на предыдущую страницу")."\">".t("Назад")."</a> | <a href=\"".$_SERVER['PHP_SELF'].$dop."\" title=\"".t("Главная страница модуля")."\">".t("Главная")."</a> ".$upp."]</div></center>";
}

### Определяем версию библиотеки GD
function php_gd() {
	ob_start();
	phpinfo(8);
	$module_info = ob_get_contents();
	ob_end_clean();
	if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info, $matches)) {
		$gdversion = $matches[1];
	} else {
		$gdversion = 0;
	}
	return $gdversion;
}

### Определяем версию MySQL
function db_version() {
	global $db, $config;
	list($dbversion) = $db->sql_fetchrow($db->sql_query("SELECT VERSION()"));
	return $dbversion;
}



### Ставим права
function chmoder($loc, $chm) {
global $config;

if (!empty($loc)) {

if (file_exists($loc)) {
$chm = !empty($chm) ? intval($chm) : $config['chm_file'];
} elseif (is_dir($loc)) {
$chm = !empty($chm) ? intval($chm) : $config['chm_dir'];
}

chmod($loc, "0".$chm);
$pdir = decoct(fileperms($loc));
$per = substr($pdir, -3);
if ($per != $chm) return $loc." ".t("Ошибка установки")." CHMOD: ".$chm;

} else {
return;
}
		
}



### Проверим доступность GZip сжатия
function checkgzip() { 

if (headers_sent() || connection_aborted()) { 
return; 
} 

if (!empty($_SERVER['HTTP_ACCEPT_ENCODING']) and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') !== false and extension_loaded('zlib')) { 
return "deflate"; 
} elseif (!empty($_SERVER['HTTP_ACCEPT_ENCODING']) and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false and extension_loaded('zlib')) { 
return "x-gzip"; 
} elseif (!empty($_SERVER['HTTP_ACCEPT_ENCODING']) and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false and extension_loaded('zlib')) { 
return "gzip"; 
} else {
return; 
}

}

### подсветка кода
function highlight_code($code) { 
  // если до этого $code обрабатывали функцией htmlspecaialchars, здесь нужно дописать код, который отменяет ее действие 
  $code = stripslashes($code); 
  if(!strpos($code,"<?") && substr($code,0,2)!="<?") {
    $code="<?php\n".trim($code)."\n?>"; 
  }  
  $code = trim($code); 
  return highlight_string($code,true);
}



### Выделяем HIDE
function encode_hide($text) {
global $is_logged_in;
  $start_html = "<fieldset class=\"fld_set\"><legend style=\"color: red;\">".t('Скрытый текст')."</legend><div class=\"fdcd\">";
  $mid_html = "<div class=\"fdcd\">";
  $low_html = "</div>";
  $end_html = "</fieldset>";
  $text = (!empty($is_logged_in)) ? $start_html.$mid_html.$text.$low_html.$end_html : $start_html.$mid_html.t("Этот текст могут просматривать только зарегистрированые пользователи").$low_html.$end_html;
  return $text;
}

### Выделяем QUOTE
function encode_quote($text, $dp="") {
  $start_html = "<fieldset class=\"fld_set\"><legend style=\"color: green;\">".t('Цитата').(!empty($dp) ? str_replace("=", ": ", $dp) : "")."</legend>";
  $mid_html = "<div class=\"fdcd\">";
  $low_html = "</div>";
  $end_html = "</fieldset>";
  $text = str_replace(array("&lt;","&gt;","&quot;","&#039;","&amp;","&#092;","{nl}","&nbsp;","&lt;?php","?&gt;"), array("<",">","\"","'","&","\\","\n"," ","",""), trim($text));
  $text = $start_html.$mid_html.$text.$low_html.$end_html;
  return $text;
}


### Выделяем CODE
function encode_code($text="", $dp="") {
if (!empty($text)) {
  $start_html = "<fieldset class=\"fld_set\"><legend style=\"color: blue;\">".t('Код').(!empty($dp) ? str_replace("=", " ", $dp) : "")."</legend>";
  $mid_html = "<div class=\"fdcd\">";
  $low_html = "</div>";
  $end_html = "</fieldset>";
  $text = str_replace(array("&lt;","&gt;","&quot;","&#039;","&amp;","&#092;","{nl}","&nbsp;","&lt;?php","?&gt;"), array("<",">","\"","'","&","\\","\n"," ","",""), trim($text));
  $text = $start_html.$mid_html.(highlight_string($text,true)).$low_html.$end_html;
}
  return $text;
}


### Выделяем PHP
function encode_php($text) {
if (!empty($text)) {
    $start_html = "<fieldset class=\"fld_set\"><legend style=\"color: purple;\">".t('Код')." PHP</legend><div class=\"fdcd\">";
    $end_html = "</div></fieldset>";
    $after_replace = trim($text);
    $after_replace = (substr($after_replace, 0, 2) != "<?") ? "<?php ".$after_replace : $after_replace;
    $after_replace = (substr($after_replace, -2) != "?>") ? $after_replace." ?>" : $after_replace;
    $after_replace = str_replace(array("&lt;","&gt;","&quot;","&#039;","&amp;","&#092;","{nl}","&nbsp;","&lt;?php","?&gt;"), array("<",">","\"","'","&","\\","\n"," ","",""), $after_replace);
    $after_replace = highlight_string($after_replace,true);
    $text = str_replace(array("&lt;?php","&lt;?","?&gt;"), "", $text);
    $text = $start_html.$after_replace.$end_html;
}
  return $text;
}


# Проверяем указанный e-mail // Check inputed e-mail // slaed
function check_email($mail) {
$stop = "";
if (!empty($mail)) {
  $mail = strtolower(cheker($mail));
  if ((!$mail) || ($mail=="") || (!preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/", $mail))) $stop = t('Адрес имеет неверный формат! (правильно: <b>mail@domain.ru</b>)');
  if ((strlen($mail) >= 4) && (substr($mail, 0, 4) == "www.")) $stop = t('Указаны лишние символы (<b>www.</b>)');
  if (strrpos($mail, " ") > 0) $stop = t('Пробелы в адресе недопустимы.');
}
  return $stop;
}

# Проверяем указанный e-mail // Check inputed e-mail // slaed
function remail($mail) {
if (!empty($mail)) {
  $mail = strtolower(cheker($mail));
  if ((!$mail) || ($mail=="") || (!preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/", $mail))) $stop = 0;
  if ((strlen($mail) >= 4) && (substr($mail, 0, 4) == "www.")) $stop = 0;
  if (strrpos($mail, " ") > 0) $stop = 0;
  $stop = 1;
} else {
  $stop = 0;
}
  return $stop;
}

# Проверяем тип загруженного файла // Check type upload file
function check_file_type($type, $typefile='') {
global $config;
if (!empty($type) and $type != ".") {
	$strtypefile = !empty($typefile) ? str_replace(",", "|", $typefile) : '';
	$ctf = str_replace(".", "", $config['deny_files']);
	$type = str_replace(".", "", $type);
	$actf = explode(" ", $ctf);
	$forms = implode("|",$actf);
	if (!preg_match("#".$strtypefile."#i", $type) || preg_match("#php.*|".$forms."#i", $type)) { return 1; } else { return 0; }
} else {
        return $config['notype'];
}
}

# Проерим размер картинки // Check size img 
function check_img_size($file, $width, $height) {
	list($imgwidth, $imgheight) = getimagesize($file);
	if ($imgwidth > $width || $imgheight > $height) { return 1; } else { return 0; }
}



### раскодировка bb-кода
function aply_bbcodes($text) {
global $config, $ap;

$bb = array();
$html = array();

  // изображения
$bb[] = "#\[img\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#i";
  $html[] = "<img src=\"\\1\" border=\"0\" alt=\"\">";
$bb[] = "#\[img=([a-zA-Z]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
  $html[] = "<img src=\"\\2\" align=\"\\1\" border=\"0\" alt=\"\">";
$bb[] = "#\[img alt=([a-zA-Zа-яА-Я0-9\ё\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
  $html[] = "<img src=\"\\2\" border=\"0\" alt=\"\\1\">";
$bb[] = "#\[img=([a-zA-Z]+) alt=([a-zA-Zа-яА-Я0-9\ё\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
  $html[] = "<img src=\"\\3\" align=\"\\1\" border=\"0\" alt=\"\\2\">";

  // ссылки
$bb[] = "#\[url\]([\w]+?://([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
  $html[] = "<a href=\"\\1\" target=\"_blank\" title=\"\\1\">\\1</a>";
$bb[] = "#\[url\]((www|ftp)\.([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
  $html[] = "<a href=\"http://\\1\" target=\"_blank\" title=\"\\1\">\\1</a>";
$bb[] = "#\[url=([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
  $html[] = "<a href=\"\\1\" target=\"_blank\" title=\"\\1\">\\2</a>";
$bb[] = "#\[url=((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
  $html[] = "<a href=\"http://\\1\" target=\"_blank\" title=\"\\1\">\\3</a>";

  // ярлыки
$bb[] = "#\[lnk\](.+?)\[/lnk\]#is";
  $html[] = "<a href=\"\\1\" title=\"\\1\">\\1</a>";
$bb[] = "#\[lnk=(.+?)\]\[/lnk\]#is";
  $html[] = "<a href=\"\\1\" title=\"\\1\">\\1</a>";
$bb[] = "#\[lnk=(.+?)\](.+?)\[/lnk\]#is";
  $html[] = "<a href=\"\\1\" title=\"\\2\">\\2</a>";

  // почта
$bb[] = "#\[mail\](\S+?)\[/mail\]#i";
$html[] = "<a href=\"mailto:\\1\">\\1</a>";
$bb[] = "#\[mail\s*=\s*([\.\w\-]+\@[\.\w\-]+\.[\w\-]+)\s*\](.*?)\[\/mail\]#i";
$html[] = "<a href=\"mailto:\\1\">\\2</a>";

  // стиль
$bb[] = "#\[color=(\#[0-9A-F]{6}|[a-z]+)\](.*?)\[/color\]#si";
  $html[] = "<span style=\"color: \\1\">\\2</span>";
$bb[] = "#\[family=([A-Za-z ]+)\](.*?)\[/family\]#si";
  $html[] = "<span style=\"font-family: \\1\">\\2</span>";
$bb[] = "#\[size=([0-9]{1,2}+)\](.*?)\[/size\]#si";
  $html[] = "<span style=\"font-size: \\1\">\\2</span>";
$bb[] = "#\[font=([A-Za-z ]+)\](.*?)\[/font\]#si";
  $html[] = "<font style=\"font-family:\\1\">\\2</font>";

   // позиция
$bb[] = "#\[(left|right|center|justify)\](.*?)\[/\\1\]#is";
  $html[] = "<div align=\"\\1\">\\2</div>";

  // Activ-X включения
$bb[] = "#\[fla\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] ="<embed src=\"\\1\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla=([a-zA-Zа-яА-Я0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\2\" bgcolor=\"#\\1\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla\ height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\2\" height=\"\\1\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla\ width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\2\" width=\"\\1\" height=\"200\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla=([a-zA-Zа-яА-Я0-9\_\-\. ]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\3\" bgcolor=\"#\\1\" height=\"\\2\" width=\"100%\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla=([a-zA-Zа-яА-Я0-9\_\-\. ]+) width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\3\" bgcolor=\"#\\1\" width=\"\\2\" height=\"200\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla=([a-zA-Zа-яА-Я0-9\_\-\. ]+) height=([0-9]+) width=([0-9\%]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\4\" bgcolor=\"#\\1\" width=\"\\3\" height=\"\\2\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla=([a-zA-Zа-яА-Я0-9\_\-\. ]+) width=([0-9\%]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\4\" bgcolor=\"#\\1\" width=\"\\2\" height=\"\\3\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla\ height=([0-9]+) width=([0-9\%]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\3\" width=\"\\2\" height=\"\\1\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla\ width=([0-9\%]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\3\" width=\"\\1\" height=\"\\2\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";



  // StrawPod включения

if (strpos(getagent(), "MSIE") !== false) {
$ie_param_a = "<param name=\"movie\" value=\"".sway("data/swf/strawpod.swf")."\">";
$ie_param_b = "";
} else {
$ie_param_a = "";
$ie_param_b = " data=\"".sway("data/swf/strawpod.swf")."\"";
}

#  object
$spa = "<object id=\"0\" type=\"application/x-shockwave-flash\"".$ie_param_b." pluginspage=\"http://www.macromedia.com/go/getflashplayer\" wmode=\"opaque\" quality=\"high\"";
$spb = " />
<param name=\"allowFullScreen\" value=\"true\" />
<param name=\"allowScriptAccess\" value=\"always\" />
<param name=\"wmode\" value=\"opaque\" />".$ie_param_a;
$spc = "</object>";



$bb[] = "#\[v\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"100%\" height=\"200\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\1&amp;\" />".$spc;
$bb[] = "#\[v=([a-zA-Zа-яА-Я0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"100%\" height=\"200\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\2\" />".$spc;
$bb[] = "#\[v\ height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"100%\" height=\"\\1\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\2\" />".$spc;
$bb[] = "#\[v\ width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\1\" height=\"200\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\2\" />".$spc;
$bb[] = "#\[v=([a-zA-Zа-яА-Я0-9\_\-\. ]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"100%\" height=\"\\2\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\3\" />".$spc;
$bb[] = "#\[v=([a-zA-Zа-яА-Я0-9\_\-\. ]+) width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\2\" height=\"200\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\3\" />".$spc;
$bb[] = "#\[v=([a-zA-Zа-яА-Я0-9\_\-\. ]+) height=([0-9]+) width=([0-9\%]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\3\" height=\"\\2\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\4&amp;w=\\3&amp;h=\\2\" />".$spc;
$bb[] = "#\[v\ height=([0-9]+) width=([0-9\%]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\2\" height=\"\\1\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\3&amp;w=\\2&amp;h=\\1\" />".$spc;
$bb[] = "#\[v=([a-zA-Zа-яА-Я0-9\_\-\. ]+) width=([0-9\%]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\2\" height=\"\\3\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\4&amp;w=\\2&amp;h=\\3\" />".$spc;
$bb[] = "#\[v\ width=([0-9\%]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\1\" height=\"\\2\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\3&amp;w=\\1&amp;h=\\2\" />".$spc;

### аудио плеер
$bb[] = "#\[a\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"100%\" height=\"70\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/audio.txt")."&amp;file=\\1\" />".$spc;
$bb[] = "#\[a=([a-zA-Zа-яА-Я0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/a\]#si";
  $html[] = $spa." width=\"100%\" height=\"70\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/audio.txt")."&amp;file=\\2\" />".$spc;
$bb[] = "#\[a\ width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/a\]#si";
  $html[] = $spa." width=\"\\1\" height=\"70\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/audio.txt")."&amp;file=\\2\" />".$spc;
$bb[] = "#\[a=([a-zA-Zа-яА-Я0-9\_\-\. ]+) width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/a\]#si";
  $html[] = $spa." width=\"\\2\" height=\"70\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/audio.txt")."&amp;file=\\3\" />".$spc;

  // выделение и списки и абзац
  $bb[] = "#\[b\](.*?)\[/b\]#si";
  $html[] = "<strong>\\1</strong>";
  $bb[] = "#\[i\](.*?)\[/i\]#si";
  $html[] = "<em>\\1</em>";
  $bb[] = "#\[(strong|small|em|u|s|sub|sup|ul|ol|li|p|h1|h2|h3|h4|h5|h6|h7)\](.*?)\[/\\1\]#is";
  $html[] = "<\\1\>\\2</\\1>";
  
  // смайлики
  $bb[] = "#\[sm\](.*?)\[/sm\]#si";
  $html[] = "<img src=\"".sway("data/emoticons/")."\\1.gif\" border=\"0\" alt=\"\\1\" class=\"smile\">";
  $bb[] = "#\[sm=(.*?)\]#si";
  $html[] = "<img src=\"".sway("data/emoticons/")."\\1.gif\" border=\"0\" alt=\"\\1\" class=\"smile\">";

  // черта и отступы
  $bb[] = "#\[(hr|br)\]#si";
  $html[] = "<\\1>";

  // защита от включений
  $bb[] = "#javascript:#si";
  $html[] = "Java Script";
  $bb[] = "#about:#si";
  $html[] = "About";
  $bb[] = "#vbscript:#si";
  $html[] = "VB Script";

  // Применяем тэги
  $text = preg_replace($bb, $html, $text);

  // Выделяем поля кода, цитаты, скрытого текста и PHP сценария
  if (!is_array($text)) {
//////////////
 if (preg_match("#\[hide\](.*?)\[/hide\]#si", $text)) {
    $text = encode_hide($text);
    $match_count = preg_match_all("#\[hide(.*?)\](.*?)\[/hide\]#i", $text, $ihid);
    for ($i = 0; $i < $match_count; $i++) {
    $text = str_replace("[hide".$ihid[1][$i]."]".$ihid[2][$i]."[/hide]", encode_hide($ihid[2][$i], $ihid[1][$i]), $text);
    }
 }
//////////////
 if (preg_match("#\[quote(.*?)\](.*?)\[/quote\]#i", $text)){
    $match_count = preg_match_all("#\[quote(.*?)\](.*?)\[/quote\]#i", $text, $iquot);
    for ($i = 0; $i < $match_count; $i++) {
    $text = str_replace("[quote".$iquot[1][$i]."]".$iquot[2][$i]."[/quote]", encode_quote($iquot[2][$i], $iquot[1][$i]), $text);
    }
 }
//////////////
  if (preg_match("#\[code(.*?)\](.*?)\[/code\]#i", $text)) {
    $match_count = preg_match_all("#\[code(.*?)\](.*?)\[/code\]#i", $text, $icode);
    for ($i = 0; $i < $match_count; $i++) {
    $text = str_replace("[code".$icode[1][$i]."]".$icode[2][$i]."[/code]", encode_code($icode[2][$i], $icode[1][$i]), $text);
    }
  }
//////////////
  if (preg_match("#\[php\](.*?)\[/php\]#i", $text)) {
    $match_count = preg_match_all("#\[php\](.*?)\[/php\]#i", $text, $iphp);
    for ($i = 0; $i < $match_count; $i++) {
    $text = str_replace("[php]".$iphp[1][$i]."[/php]", encode_php($iphp[1][$i]), $text);
    }
  }
//////////////
  }
return $text;
}


// Эа функция заменяет пробелы нижними слешами, и на оборот, если второй параметр !=0
function spaser($txt, $opt="") {
 if (!empty($opt)) {
  return str_replace("_", " ", $txt);
 } else {
  return str_replace(" ", "_", $txt);
 }
}


// Теги
function cnops($col="", $pref="") {
global $config, $ap, $is_logged_in;

if (empty($config['bb'])) { return;}
$butcol = (!empty($col) ? $col : $config['bb_line']);
$adfld = !empty($pref) ? ",'".$pref."'" : "";

$bb_param_but = "border=\"0\" hspace=\"1\" vspace=\"1\" alt=\"bb-code\"";
$bbb = array();
$bbb[] = "<a href=\"javascript:insertext('[b]','[/b]'".$adfld.")\"><img title=\"".t("Жирный")."\" src=\"".sway("admin/images/tags/")."b.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[i]','[/i]'".$adfld.")\"><img title=\"".t("Курсив")."\" src=\"".sway("admin/images/tags/")."i.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[u]','[/u]'".$adfld.")\"><img title=\"".t("Подчеркнутый")."\" src=\"".sway("admin/images/tags/")."u.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[s]','[/s]'".$adfld.")\"><img title=\"".t("Зачеркнутый")."\" src=\"".sway("admin/images/tags/")."s.gif\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"javascript:insertext('[left]','[/left]'".$adfld.")\"><img title=\"".t("Выравнивание слева")."\" src=\"".sway("admin/images/tags/")."align_l.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[center]','[/center]'".$adfld.")\"><img title=\"".t("Выравнивание по центру")."\" src=\"".sway("admin/images/tags/")."align_c.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[right]','[/right]'".$adfld.")\"><img title=\"".t("Выравнивание справа")."\" src=\"".sway("admin/images/tags/")."align_r.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[justify]','[/justify]'".$adfld.")\"><img title=\"".t("Выравнивание по ширине")."\" src=\"".sway("admin/images/tags/")."align_j.gif\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"javascript:insertext('[br]',''".$adfld.")\"><img title=\"".t("Перенос строки")."\" src=\"".sway("admin/images/tags/")."br.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[hr]',''".$adfld.")\"><img title=\"".t("Линия")."\" src=\"".sway("admin/images/tags/")."hr.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[sub]','[/sub]'".$adfld.")\"><img title=\"".t("Подстрочный")."\" src=\"".sway("admin/images/tags/")."sub.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[sup]','[/sup]'".$adfld.")\"><img title=\"".t("Надстрочный")."\" src=\"".sway("admin/images/tags/")."sup.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[p]','[/p]'".$adfld.")\"><img title=\"".t("Параграф")."\" src=\"".sway("admin/images/tags/")."p.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[ul]','[/ul]'".$adfld.")\"><img title=\"".t("Cписок")."\" src=\"".sway("admin/images/tags/")."ul.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[li]','[/li]'".$adfld.")\"><img title=\"".t("Элемент списка")."\" src=\"".sway("admin/images/tags/")."li.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[color=#008000]','[/color]'".$adfld.")\"><img title=\"".t("Цвет текста")."\" src=\"".sway("admin/images/tags/")."color.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[size=]','[/size]'".$adfld.")\"><img title=\"".t("Размер шрифта")."\" src=\"".sway("admin/images/tags/")."size.gif\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"javascript:insertext('[url=]','[/url]'".$adfld.")\"><img title=\"".t("Ссылка")."\" src=\"".sway("admin/images/tags/")."url.gif\" border=\"0\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[lnk=]','[/lnk]'".$adfld.")\"><img title=\"".t("Ярлык")."\" src=\"".sway("admin/images/tags/")."lnk.gif\" border=\"0\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"javascript:insertext('[mail]','[/mail]'".$adfld.")\"><img title=\"E-mail\" src=\"".sway("admin/images/tags/")."mailto.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[img=left alt=".spaser(@htmlspecialchars(str_replace($config['delitel'], "", $config['home_title'])))."]','[/img]'".$adfld.")\"><img title=\"".t("Вставка изображения")."\" src=\"".sway("admin/images/tags/")."img.gif\" ".$bb_param_but."></a>";
if ($is_logged_in) {
$bbb[] = "<a href=\"#\" onclick=\"window.open('".sway("index.php?mod=img")."&amp;area=".$pref."', '_Addimage', 'height=400,resizable=yes,scrollbars=yes,width=600');return false;\" target=\"_Addimage\"><img title=\"".t("Собственные изображения")."\" src=\"".sway("admin/images/tags/")."img_my.gif\" ".$bb_param_but."></a>";
}
$bbb[] = "<a href=\"javascript:insertext('[quote]','[/quote]'".$adfld.")\"><img title=\"".t("Цитата")."\" src=\"".sway("admin/images/tags/")."q.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[code]','[/code]'".$adfld.")\"><img title=\"".t("Код")."\" src=\"".sway("admin/images/tags/")."c.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[hide]','[/hide]'".$adfld.")\"><img title=\"".t("Скрытый текст")."\" src=\"".sway("admin/images/tags/")."h.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[php]','[/php]'".$adfld.")\"><img title=\"".t("Код")." PHP\" src=\"".sway("admin/images/tags/")."php.gif\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"javascript:insertext('[a=".spaser(@htmlspecialchars(str_replace($config['delitel'], "", $config['home_title'])))." width=300]','[/a]'".$adfld.")\"><img title=\"".t("Аудио")."\" src=\"".sway("admin/images/tags/")."a.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[v=".spaser(@htmlspecialchars(str_replace($config['delitel'], "", $config['home_title'])))." width=300 height=200]','[/v]'".$adfld.")\"><img title=\"".t("Видео")."\" src=\"".sway("admin/images/tags/")."v.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[fla=".spaser(@htmlspecialchars(str_replace($config['delitel'], "", $config['home_title'])))." width=300 height=200]','[/fla]'".$adfld.")\"><img title=\"".t("Флешка")."\" src=\"".sway("admin/images/tags/")."f.gif\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"#\" onclick=\"window.open('".sway("admin/images/tags/")."charmap.php?area=".$pref."', '_Charmap', 'height=250,resizable=yes,scrollbars=yes,width=600');return false;\" target=\"_Charmap\"><img title=\"".t("СпецСимволы")."\" src=\"".sway("admin/images/tags/")."charmap.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"#\" onclick=\"window.open('".sway("admin/images/tags/")."help_".$config['lang'].".php', '_Charmap', 'height=300,resizable=yes,scrollbars=yes,width=400');return false;\" target=\"_Help\"><img title=\"".t("Помощь по BB-Code")."\" src=\"".sway("admin/images/tags/")."help.gif\" ".$bb_param_but."></a>";

$all = count($bbb);
$i = 1;
$a = 0;
$buttons = "";
for ($c = 0; $c < $all; $c++) {

if ($butcol != 0 ) {
  if (($i/$butcol) == 1){
    $bugfix = "</nobr><br><nobr>";
    $i=1;
  } else {
    $bugfix = (($a == ($all-1)) ? "" : "");
    $i++;
  }
} else {
    $bugfix = "";
}    

$buttons .= $bbb[$a].$bugfix;
$a++;
}

return "<div class=\"tags\"><nobr>".$buttons."</nobr></div>";
}


### Смайлы
function smiles($col="", $pref=""){
global $config, $ap;
$output = "";
if (empty($config['smilies'])) { return;}
$adfld = !empty($pref) ? ",'".$pref."'" : "";
$config['smilies_line'] = (!empty($col)) ? $col : $config['smilies_line'];
$i=1;
$smilies = explode(',', $config['smilies']);

    foreach ($smilies as $smile){

      if (!empty($config['smilies_line']) and ($i/$config['smilies_line']) == 1){
        $bugfix = '</nobr><br><nobr>';
        $i=1;
      } else {
        $i++;
        $bugfix = "";
      }
 
$smile_param_face = "border=\"0\" align=\"middle\" hspace=\"2\" vspace=\"1\"";
$output .= '<a href="javascript:insertext(\' :'.trim($smile).':\', \'\''.$adfld.')"><img alt="'.trim($smile).'" src="'.sway("data/emoticons/").trim($smile).'.gif" '.$smile_param_face.'></a>'.$bugfix;
    }

return "<div class=\"smiles\"><nobr>".$output."</nobr></div>";
}


##############################################################################################
##############################################################################################
##############################################################################################
##############################################################################################






### редактор для поля ввода текста.
function straw_editor ($tid = 1, $tname = "news", $tcontent = "", $ttype = "user", $tclass = "textarea", $trow = 1, $tcol = 1, $deditor = "") {
global $config, $ugroups, $member, $is_logged_in;
#################################################################################### settings

$editor = "no";
$tcontent = !empty($_POST[$tname]) ? $_POST[$tname] : $tcontent;
$ttype = ($ttype != "user" and $ttype != "guest") ? "admin" : (($ttype != "user" and $ttype == "guest") ? "guest" : "user");
$ta = "<textarea id=\"".$tid."\" name=\"".$tname."\" class=\"".$tclass."\" row=\"".$trow."\" col=\"".$tcol."\">".replace_news("admin", $tcontent)."</textarea>";

#################################################################################### type of editor

if ($ttype != "admin") {
  if (empty($is_logged_in) and empty($deditor)) {
    $editor = !empty($config['editor_guest']) ? $config['editor_guest'] : $editor;
  } elseif (!empty($is_logged_in) and in_array($member['group'],$config['editor_groups']) and empty($deditor)) {
    $editor = (!empty($member['editor']) and !empty($config['members_editor'])) ? $member['editor'] : (!empty($user_groups[$member[group]]['editor']) ? $user_groups[$member[group]]['editor'] : (!empty($config['editor_user']) ? $config['editor_user'] : $editor));
  } 
    $editor = !empty($deditor) ? $deditor : $editor;
} else {
    $editor = (!empty($member['editor'])) ? $member['editor'] : (!empty($user_groups[1]['editor']) ? $user_groups[1]['editor'] : (!empty($config['editor_admin']) ? $config['editor_admin'] : $editor));
}
#################################################################################### include interface of editor

if (!empty($editor) and $editor != "no" and is_dir(editors."/".$editor)) {
    include editors."/".$editor."/strawberry_12.php";
} else { 
    $out = $ta; 
}

#################################################################################### output result
return "<div id=\"straw_editor_".$tid."\" class=\"straw_editor\">".$out."</div>";
}


### загрузчик файлов на базе swf_uploader`а
function straw_uploader ($swf_id = 0, $swf_modul = "other") {
global $config, $is_logged_in;
############################################################ load swf uploader
$out = "";
$swf_id = !empty($swf_id) ? $swf_id : 1;
$out .= "<div style=\"swf_form\">";
if ($swf_id == 1) {
    // тут подключение скриптов для загрузчика и кнопка ЗАГРУЗИТЬ и ОТМЕНА
}

$out .= "<input type=\"submit\" style=\"swf_refresh\" value=\"".t('ОБНОВИТЬ')."\" onclick=\"load_get('','','','','','','','')\"></div>";

$out .= "<div id=\"file_list_".$swf_id."\" class=\"file_list\"></div>";

############################################################ output result
return "<div id=\"straw_uploader_".$swf_id."\" class=\"straw_uploader\">".$out."</div>";
}





##############################################################################################
##############################################################################################
##############################################################################################
##############################################################################################



### раскодировка комментариев на выходе
function comm_out($text, $ubb="1", $repcom="1") {
global $config;
$out = (!empty($ubb) and $ubb == 1) ? aply_bbcodes($text) : $text;
//$out = run_filters('news-comment', run_filters('news-comment-content', $out));
$out = (!empty($repcom) and $repcom == 1) ? replace_comment('show', $out) : $out;
return $out;
}

### раскодировка комментариев при записи
function comm_in($text) {
global $config;
return replace_comment('add', $text);
}

### выбираем количество контента на странице
/*
* $numb - количество контента на странице. Выходит в виде переменной $number. Если не указать величтну, то берется значение из конфигураций - $config['arr_news']. Если неустановлено, то $number = 1;
* $usst - 1/0 - использовать или неиспользовать дополнения в ссылках для сотрировки таблицы с данными.
*/
function straw_number($numb=0, $usst=0) {
global $number, $modul, $config;
$number = !empty($numb) ? intval($numb) : (!empty($config['arr_news']) ? intval($config['arr_news']) : '1');
 if (!empty($_POST['number_'.$modul]) and is_numeric($_POST['number_'.$modul]) and $_POST['number_'.$modul] > 0) {
    $number = intval($_POST['number_'.$modul]);
    $_SESSION[$config['cookie_prefix'].'number_'.$modul] = $number;
 } elseif (!empty($_COOKIE[$config['cookie_prefix'].'number_'.$modul]) and is_numeric($_COOKIE[$config['cookie_prefix'].'number_'.$modul])  and $_COOKIE[$config['cookie_prefix'].'number_'.$modul] > 0) {
    $number = intval($_COOKIE[$config['cookie_prefix'].'number_'.$modul]);
 } elseif (!empty($_SESSION[$config['cookie_prefix'].'number_'.$modul]) and is_numeric($_SESSION[$config['cookie_prefix'].'number_'.$modul])  and $_SESSION[$config['cookie_prefix'].'number_'.$modul] > 0) {
    $number = intval($_SESSION[$config['cookie_prefix'].'number_'.$modul]);
 }
if (!empty($usst)) { straw_sort($number); } 
$form = "<form method=\"post\" action=\"\"><input type=\"text\" size=\"10\" name=\"number_".$modul."\" value=\"".$number."\"><input type=\"submit\" value=\" OK \"></form>";
return $form;
}

### дополнительные данные для ссылки (для листалки)
function straw_sort($number=1){
global $order, $lsort, $oskip, $lnk, $pnum;
if (!empty($_REQUEST)) {
$add_lnk = "";
$a_choise = array();
$s_arr = array('id', 'category', 'year', 'month', 'day', 'ip', 'author', 'name', 'ref', 'date', 'time', 'link', 'counter', 'post_id', 'comment', 'comments', 'user', 'dop', 'count', 'usergroup', 'publications', 'last_visit', 'namesender', 'emailsender', 'subjectsender', 'textsender', 'hidden', 'title', 'views');
$l_arr = array('category', 'search', 'year', 'month', 'day', 'order', 'author');
// 'mod' убран из массива $l_arr что бы в админке к нумерации не добавлялся повторный параметр mod=*модуль*
if (!empty($_REQUEST['by']) and in_array($_REQUEST['by'], $s_arr)) {
   $order_by = $_REQUEST['by'];
   $a_lnk[] = "by=".$order_by;
} elseif (!empty($_REQUEST['by'])) {
   $a_lnk[] = "by=date";
   $order_by = "date";
} else {
   $order_by = "date";
}

if (!empty($_REQUEST['sort']) and $_REQUEST['sort'] == "asc") {
$order_sort = "ASC";
$a_lnk[] = "sort=asc";
$lsort = "&amp;sort=desc";
} elseif (!empty($_REQUEST['sort']) and $_REQUEST['sort'] == "desc") {
$order_sort = "DESC";
$a_lnk[] = "sort=desc";
$lsort = "&amp;sort=asc";
} else {
$order_sort = "DESC";
$lsort = "&amp;sort=asc";
}
$order = $order_by." ".$order_sort;
foreach ($_REQUEST as $lk => $lv){
  if (in_array($lk, $l_arr)){
     $a_lnk[] = $lk."=".cheker($lv);
  }
}
$lnk = (!empty($a_lnk) ? (((!empty($config['mod_rewrite']) or !empty($config['mod_rewrite_lite'])) and empty($ap)) ? '?' : '&').implode("&", $a_lnk) : '');
  $pnum = (!empty($_REQUEST['pnum']) and is_numeric($_REQUEST['pnum'])) ? intval($_REQUEST['pnum']) : "1";
  $oskip = ($pnum-1) * $number;
  $oskip = !empty($skip) ? intval($skip) : intval($oskip);
  if (!empty($pnum) and $pnum > 1) {
  $lsort .= "&amp;pnum=".$pnum;
  }
}
}

### разбивка на страницы
function pnp($db_table="", $pnum=0, $number=0, $thistpl="default", $where="", $order="", $limit="", $link="", $count=0) {
global $allow_full_story, $prev_next_msg, $template_prev_next, $config, $db, $skip;

if (is_file(templates_directory."/".$thistpl."/prev_next.tpl")) {

//$link = '';
// << Previous & Next >>
//$prev_next_msg = $template_prev_next;
$skip = (!empty($_GET['skip']) and is_numeric($_GET['skip'])) ? intval($_GET['skip']) : intval($skip);
$pnum = (!empty($_GET['pnum']) and is_numeric($_GET['pnum'])) ? intval($_GET['pnum']) : intval($pnum);
$number=intval($number);

// Previous link
if (!empty($skip)){
    $tpl['prev-next']['prev-link'] = straw_get_link(array('skip' => ($skip - $number)), 'skip', 'home').$link;
} elseif (!empty($pnum) and $pnum != 1) {
    $tpl['prev-next']['prev-link'] = straw_get_link(array('pnum' => ($pnum - 1)), 'pnum', 'home').$link;
} elseif (empty($skip) or empty($pnum) or $pnum == 1) {
    $tpl['prev-next']['prev-link'] = '';
    $no_prev = true;
}

// Pages
if (!empty($number)){
if (empty($count)) {
$st_tab = ($db_table == "news") ? " AS a LEFT JOIN ".$config['dbprefix']."story AS b ON (b.post_id=a.id) " : "";
$st_sel = ($db_table == "news") ? "a.*, b.*" : "*";
$dbnumout = $db->sql_query("SELECT ".$st_sel." 
FROM ".$config['dbprefix'].$db_table.$st_tab."
".(!empty($where) ? " WHERE ".$where : "")." 
".(!empty($order) ? " ORDER BY ".$order : "")." 
".(!empty($limit) ? " LIMIT ".$limit : "")." 
");
$count = $db->sql_numrows($dbnumout);
}

    $pages_count   = @ceil($count / $number);
    $pnum_skip = 0;
    $pages_skip    = 0;
    $pages         = array();
    $pages_section = (int)$config['pages_section'];
    $pages_break   = (int)$config['pages_break'];

    if (!empty($pages_break) and $pages_count > $pages_break){

        for ($j = 1; $j <= $pages_section; $j++){
$pnum_skip = ($j)*$number;
            if ((!empty($skip) and $pages_skip != $skip) or (!empty($pnum) and ceil($pnum_skip/$number) != $pnum and empty($skip))){
              if (!empty($skip))  $pages[] = '<a title="'.$j.'" href="'.straw_get_link(array('skip' => $pages_skip), 'skip', 'home').$link.'">'.$j.'</a>';
              if (!empty($pnum) and empty($skip))  $pages[] = '<a title="'.$j.'" href="'.straw_get_link(array('pnum' => $j), 'pnum', 'home').$link.'">'.$j.'</a>';
            } else {
              $pages[] = '<b>'.$j.'</b>';
            }

           $pages_skip += $number;
        }


        if (
        (!empty($skip) and ((($skip / $number) + 1) > 1) and ((($skip / $number) + 1) < $pages_count))
         or 
        (!empty($pnum) and ($pnum > 1) and ($pnum < $pages_count))
         ) {
         
            $pages[] = (
            (!empty($skip) and (($skip / $number) + 1) > ($pages_section + 2))
             or 
             (!empty($pnum) and ($pnum) > ($pages_section + 2))
             ) ? '...' : '';

if (!empty($skip)) {
$page_min = ((($skip / $number) + 1) > ($pages_section + 1)) ? ($skip / $number) : ($pages_section + 1);
$page_max = ((($skip / $number) + 1) < ($pages_count - ($pages_section + 1))) ? (($skip / $number) + 1) : $pages_count - ($pages_section + 2);
} else {
$page_min = ($pnum > ($pages_section + 1)) ? $pnum - 1 : ($pages_section + 1);
$page_max = ($pnum < ($pages_count - ($pages_section + 1))) ? ($pnum-1) : $pages_count - ($pages_section+2);
}

$pages_skip = ($page_min - 1) * $number;

            for ($j = $page_min; $j < $page_max + ($pages_section - 1); $j++){
            $pnum_skip = ($j)*$number;
                if ((!empty($skip) and $pages_skip != $skip) or (!empty($pnum) and ceil($pnum_skip/$number) != $pnum and empty($skip))){
            if (!empty($skip))    $pages[] = '<a title="'.$j.'" href="'.straw_get_link(array('skip' => $pages_skip), 'skip', 'home').$link.'">'.$j.'</a>';
            if (!empty($pnum) and empty($skip))  $pages[] = '<a title="'.$j.'" href="'.straw_get_link(array('pnum' => $j), 'pnum', 'home').$link.'">'.$j.'</a>';
                } else {
                  $pages[] = '<b>'.$j.'</b>';
                }

                $pages_skip += $number;
            }

if (!empty($skip)) {
$pages[] = ((($skip / $number) + 1) < $pages_count - ($pages_section + 2)) ? '...' : '';
} else {
$pages[] = ($pnum < $pages_count - ($pages_section + 1)) ? '...' : '';
}

        } else {
          $pages[] = '...';
        }

        $pages_skip = ($pages_count - $pages_section) * $number;

        for ($j = ($pages_count - ($pages_section - 1)); $j <= $pages_count; $j++){

$pnum_skip = ($j)*$number;
            if ((!empty($skip) and $pages_skip != $skip) or (!empty($pnum) and ceil($pnum_skip/$number) != $pnum and empty($skip))) {
              if (!empty($skip))  $pages[] = '<a title="'.$j.'" href="'.straw_get_link(array('skip' => $pages_skip), 'skip', 'home').$link.'">'.$j.'</a>';
              if (!empty($pnum) and empty($skip))  $pages[] = '<a title="'.$j.'" href="'.straw_get_link(array('pnum' => $j), 'pnum', 'home').$link.'">'.$j.'</a>';
            } else {
              $pages[] = '<b>'.$j.'</b>';
            }
            $pages_skip += $number;
        }

    } else {

         for ($j = 1; $j <= $pages_count; $j++){

$pnum_skip = ($j)*$number;
            if ((!empty($skip) and $pages_skip != $skip) or (!empty($pnum) and ceil($pnum_skip/$number) != $pnum)) {
              if (!empty($skip))  $pages[] = '<a title="'.$j.'" href="'.straw_get_link(array('skip' => $pages_skip), 'skip', 'home').$link.'">'.$j.'</a>';
              if (!empty($pnum) and empty($skip))  $pages[] = '<a title="'.$j.'" href="'.straw_get_link(array('pnum' => $j), 'pnum', 'home').$link.'">'.$j.'</a>';
            } else {
              $pages[] = '<b>'.$j.'</b>';
            }

            $pages_skip += $number;
        }
    }

    $tpl['prev-next']['pages']        = join(' ', $pages);
    $tpl['prev-next']['current-page'] = (($skip + $number) / $number);
    $tpl['prev-next']['total-pages']  = $pages_count;
}

// Next link
if (!empty($skip) and ($skip + $number) < $count){
    $tpl['prev-next']['next-link'] = straw_get_link(array('skip' => ($skip + $number)), 'skip', 'home').$link;
} elseif (!empty($pnum) and !empty($number) and ($pnum) < ceil($count/$number) and empty($skip)) {
    $tpl['prev-next']['next-link'] = straw_get_link(array('pnum' => ($pnum + 1)), 'pnum', 'home').$link;
} else {
    $tpl['prev-next']['next-link'] = '';
    $no_next = true;
}

if (empty($no_prev) or empty($no_next)){

if (is_file(templates_directory."/".$thistpl."/prev_next.tpl")) {
ob_start();
include templates_directory."/".$thistpl."/prev_next.tpl";
$pnpout = ob_get_contents();
ob_end_clean();
} else {


$pnpout = "<div class=\"numbers\" id=\"numbers\"><div class=\"num_text\" id=\"num_text\">".t('Всего')." <span>".$count."</span> ".t('по')." <span>".$number."</span> ".t('на странице')."</div>";
$pnpout .= "<p class=\"sideoversikt\">";
if (!empty($tpl['prev-next']['prev-link'])){ 
$pnpout .= "<a title=\"".t('Перейти на предыдущую страницу')."\" href=\"".$tpl['prev-next']['prev-link']."\">&laquo;</a>";
}
$pnpout .= $tpl['prev-next']['pages'];
if (!empty($tpl['prev-next']['next-link'])){
$pnpout .= "<a title=\"".t('Перейти на следущую страницу')."\" href=\"".$tpl['prev-next']['next-link']."\">&raquo;</a>";
}
$pnpout .= "</p>";
$pnpout .= "</div>";

}

return $pnpout;
} else {
return;
}
} else {
return;
}

}





function need_mooore(&$item1, $key){
  if (strlen($item1) < 3){
    $item1 = '';
  }
}







/**
 * Подсвечивает $whatfind в $text
 *
 * @link http://forum.dklab.ru/php/heap/AllocationOfResultInNaydenomAPieceOfTheText.html
 *
 * @param string $whatfind Искомое слово
 * @param string $text Текст, в котором проводится поиск
 * @return string
 */
function formattext($whatfind, $text){
  $pos    = @strpos(strtoupper($text), strtoupper($whatfind));
  $otstup = 500; // кол-во символов при выводе результата
  $result = '';
  if ($pos !== false){ //если найдена подстрока
      if ($pos < $otstup){ //если встречается раньше чем первые N символов
          $result = substr($text, 0, $otstup * 2); //то результат подстрока от начала и до N-го символа
      } else {
          $start = $pos-$otstup;
          //то результат N символов  от совпадения и N символов вперёд
          $result = '...'.substr($text, $pos-$otstup, $otstup * 2).'...';
          // выделяем
      }
          $result = preg_replace('/ '.$whatfind.' /i', ' <span class="hilite" title="'.t('Вы искали').' '.$whatfind.'">'.$whatfind.'</span> ', $result);

  } else {
      $result = substr($text, 0, $otstup * 2);
  }
return $result;
}


# Новая подсветка текста по типу highlight
function format_text($result, $whatfind) {
	$whatfind = urldecode($whatfind);
	if ($whatfind) {
		if (strstr($whatfind, " ")) {
			$farray = explode(" ", str_replace("  ", " ", $whatfind));
		} else {
			$farray[] = $whatfind;
		}
		preg_match_all("#<[^>]*>#", $result, $tags);
		array_unique($tags);
		$taglist = array();
		$k = 0;
		foreach($tags[0] as $i) {
			$k++;
			$taglist[$k] = $i;
			$result = str_replace($i, "<".$k.">", $result);
		}
		foreach($farray as $i) if (!is_numeric($i)) { 
		$result=preg_replace("#".$i."#i", "<font class=\"hilite\" title=\"".t('Вы искали').": $0\">$0</font>", $result); 
		}
		foreach($taglist as $k => $i) {
		$result = str_replace("<" . $k . ">", $i, $result);
		}
	}
	return $result;
}







/* new function */
function utf8_convert($str, $type="w")
{
   static $conv = '';
   if (!is_array($conv))
   {
      $conv = array();
      for ($x=128; $x <= 143; $x++)
      {
         $conv['utf'][] = chr(209) . chr($x);
         $conv['win'][] = chr($x + 112);
      }
      for ($x=144; $x<= 191; $x++)
      {
         $conv['utf'][] = chr(208) . chr($x);
         $conv['win'][] = chr($x + 48);
      }
      $conv['utf'][] = chr(208) . chr(129);
      $conv['win'][] = chr(168);
      $conv['utf'][] = chr(209) . chr(145);
      $conv['win'][] = chr(184);
   }
   if ($type == 'w')
   {
      return str_replace($conv['utf'], $conv['win'], $str);
   }
   elseif ($type == 'u')
   {
      return str_replace($conv['win'], $conv['utf'], $str);
   }
   else
   {
      return $str;
   }
}






# убирает всякую шнягу из текста.
function texter($text, $array_walk = true){
    $text = strip_tags($text);
    $text = str_replace("\r\n", '', $text);
// $text = preg_replace('/\W/', ' ', $text);
$text = preg_replace('/[^АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯABCDEFGHIKLMNOPQRSTUVWXYZабвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghiklmnopqrstuvwxyz]/s', ' ', $text);
// $text = strtolower($text);
$text = strtr($text, "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯABCDEFGHIKLMNOPQRSTUVWXYZ", "абвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghiklmnopqrstuvwxyz");
  if (!empty($array_walk)){
    $text = explode(' ', $text);
    array_walk($text, 'need_mooore');
  }
return $text;
}




# убирает всякую шнягу из урлов.
function urler($text){
$text = htmlspecialchars(strip_tags($text));
$text = str_replace("\r\n", '', $text);
#$text = preg_replace('/\W/', ' ', $text);
$text = strtolower($text);
return $text;
}




# текущая категория
function search_this_cat($id){
global $category;
return ($id == $category ? ' selected' : '');
}





# создание картинки превью
function img_resize($src, $dest, $new_size, $rgb = 0xFFFFFF, $quality = 100){
global $type, $thumb_p, $make_thumb, $make_thumb_px, $make_thumb_pr;
  if (!file_exists($src)){
    return false;
  } elseif (($size = getimagesize($src)) === false){
    return false;
  }

  if (!function_exists($icfunc = 'imagecreatefrom'.strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1)))){
    return false;
  }


$make_thumb = $make_thumb_pr;
if ($thumb_p == "1") {

  $new_size    = (($new_size > 100) ? 40 : $new_size);
  $width       = $size[0] * $new_size / 100;
  $height      = $size[1] * $new_size / 100;
  $x_ratio     = $width / $size[0];
  $y_ratio     = $height / $size[1];
  $ratio       = min($x_ratio, $y_ratio);
  $use_x_ratio = ($x_ratio == $ratio);
  $new_width   = ($use_x_ratio  ? $width  : floor($size[0] * $ratio));
  $new_height  = (!$use_x_ratio ? $height : floor($size[1] * $ratio));
  $new_left    = ($use_x_ratio  ? 0 : floor(($width - $new_width) / 2));
  $new_top     = (!$use_x_ratio ? 0 : floor(($height - $new_height) / 2));
  $isrc        = $icfunc($src);
  $idest       = imagecreatetruecolor($width, $height);

} else {

$make_thumb = $make_thumb_px;
  $new_size = (($new_size > $size[0]) ? $size[0] : $new_size);
  $width       = $new_size ;
  $height  = $size[1] * ($width / $size[0]);
  $x_ratio     = $width / $size[0];
  $y_ratio     = $height / $size[1];
  $ratio       = min($x_ratio, $y_ratio);
  $use_x_ratio = ($x_ratio == $ratio);
  $new_width   = ($use_x_ratio  ? $width  : floor($size[0] * $ratio));
  $new_height  = (!$use_x_ratio ? $height : floor($size[1] * $ratio));
  $new_left    = ($use_x_ratio  ? 0 : floor(($width - $new_width) / 2));
  $new_top     = (!$use_x_ratio ? 0 : floor(($height - $new_height) / 2));
  $isrc        = $icfunc($src);
  $idest       = imagecreatetruecolor($width, intval($height));
$new_height  = intval($new_height);
$new_top  = intval($new_top);

}

  imagefill($idest, 0, 0, $rgb);
  imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
  imagejpeg($idest, $dest, $quality);
  imagedestroy($isrc);
  imagedestroy($idest);

return true;
}









# создает тень к картинке
///////////////////////////
//Function dropshadow
//Adds a dropshadow to the thumb
//Code taken from http://codewalkers.com/tutorials/83/1.html
//////////////////////////////////

function make_shadow($thumb_in) {
global $type;

define("DS_OFFSET", 5);
define("DS_STEPS", 10);
define("DS_SPREAD", 1);

$background = array("r" => 256, "g" => 256, "b" => 256);
list($o_width, $o_height) = getimagesize($thumb_in);

$width        = $o_width + DS_OFFSET;
$height = $o_height + DS_OFFSET;
$image_sh = @imagecreatetruecolor($width, $height);

$step_offset = array("r" => ($background["r"] / DS_STEPS), "g" => ($background["g"] / DS_STEPS), "b" => ($background["b"] / DS_STEPS));

$current_color = $background;
for ($i = 0; $i <= DS_STEPS; $i++) {
        $colors[$i] = @imagecolorallocate($image_sh, round($current_color["r"]), round($current_color["g"]), round($current_color["b"]));

        $current_color["r"] -= $step_offset["r"];
        $current_color["g"] -= $step_offset["g"];
        $current_color["b"] -= $step_offset["b"];
}
@imagefilledrectangle($image_sh, 0,0, $width, $height, $colors[0]);

for ($i = 0; $i < count($colors); $i++) {
        @imagefilledrectangle($image_sh, DS_OFFSET, DS_OFFSET, $width, $height, $colors[$i]);
        $width -= DS_SPREAD;
        $height -= DS_SPREAD;
}

if (strtolower($type) == "gif") {
$original_image = @imagecreatefromgif($thumb_in);
}
else {
 $original_image = @imagecreatefromjpeg($thumb_in);
 }
  @imagecopymerge($image_sh, $original_image, 0,0, 0,0, $o_width, $o_height, 100);

  if (strtolower($type) == "gif") {
@imagegif($image_sh, $thumb_in);
}
else {
  @imagejpeg($image_sh, $thumb_in);
  }
}







# вотермарк из текста
/////////////////
//Function Watermark
//Code taken from http://edge.dev.box.sk/smsread.php?newsid=310
///////////////////

function add_watermark($thumb_in,$text="[date]",$hotspot=8,$rgbtext="FFCC00",$font_size=12,$font="Verdana.TTF",$datfmt="d-m-Y",$rgbtsdw="000000",$txp=15,$typ=5,$sxp=1,$syp=1) {

$suffx=substr($thumb_in,strlen($thumb_in)-4,4);
$suffx = strtolower($suffx);
if ($suffx==".jpg" || $suffx=="jpeg" || $suffx==".png" || $suffx==".gif") {
#$text=str_replace("[date]",date($datfmt),$text);

if ($suffx==".jpg" || $suffx=="jpeg") {
$image=imagecreatefromjpeg($thumb_in);
}
if ($suffx==".png") {
$image=imagecreatefrompng($thumb_in);
}
if ($suffx == ".gif") {
$image=imagecreatefromgif($thumb_in);
}

$rgbtext=HexDec($rgbtext);
$txtr=floor($rgbtext/pow(256,2));
$txtg=floor(($rgbtext%pow(256,2))/pow(256,1));
$txtb=floor((($rgbtext%pow(256,2))%pow(256,1))/pow(256,0));

$rgbtsdw=HexDec($rgbtsdw);
$tsdr=floor($rgbtsdw/pow(256,2));
$tsdg=floor(($rgbtsdw%pow(256,2))/pow(256,1));
$tsdb=floor((($rgbtsdw%pow(256,2))%pow(256,1))/pow(256,0));

$coltext = imagecolorallocate($image,$txtr,$txtg,$txtb);
$coltsdw = imagecolorallocate($image,$tsdr,$tsdg,$tsdb);

if ($hotspot!=0) {
$ix=imagesx($image); $iy=imagesy($image); $tsw=strlen($text)*$font_size/imagefontwidth($font)*3; $tsh=$font_size/imagefontheight($font);
switch ($hotspot) {
case 1:
$txp=$txp; $typ=$tsh*$tsh+imagefontheight($font)*2+$typ;
break;
case 2:
$txp=floor(($ix-$tsw)/2); $typ=$tsh*$tsh+imagefontheight($font)*2+$typ;
break;
case 3:
$txp=$ix-$tsw-$txp; $typ=$tsh*$tsh+imagefontheight($font)*2+$typ;
break;
case 4:
$txp=$txp; $typ=floor(($iy-$tsh)/2);
break;
case 5:
$txp=floor(($ix-$tsw)/2); $typ=floor(($iy-$tsh)/2);
break;
case 6:
$txp=$ix-$tsw-$txp; $typ=floor(($iy-$tsh)/2);
break;
case 7:
$txp=$txp; $typ=$iy-$tsh-$typ;
break;
case 8:
$txp=floor(($ix-$tsw)/2); $typ=$iy-$tsh-$typ;
break;
case 9:
$txp=$ix-$tsw-$txp; $typ=$iy-$tsh-$typ;
break;
}
}

ImageTTFText($image,$font_size,0,$txp+$sxp,$typ+$syp,$coltsdw,$font,$text);
ImageTTFText($image,$font_size,0,$txp,$typ,$coltext,$font,$text);

if ($suffx==".jpg" || $suffx=="jpeg") {
imagejpeg($image, $thumb_in);
}
if ($suffx==".png") {
imagepng($image, $thumb_in);
}
if ($suffx == ".gif") {
imagegif($image, $thumb_in);
}
}
}










# вотермарк из картинки
////////////////////
// Made By MrMiksar
// (c) 2009
///////////////////////

function mergePix($sourcefile, $insertfile, $targetfile, $pos=0, $transition=100)
{

###
$suffx=substr($sourcefile,strlen($sourcefile)-4,4);
$suffx = strtolower($suffx);
###
if ($suffx==".jpg" || $suffx=="jpeg") {
$sourcefile_id = imagecreatefromjpeg($sourcefile);
}
if ($suffx==".png") {
$sourcefile_id = imagecreatefrompng($sourcefile);
}
if ($suffx == ".gif") {
$sourcefile_id = imagecreatefromgif($sourcefile);
}

###
$insuffx=substr($insertfile,strlen($insertfile)-4,4);
$insuffx = strtolower($insuffx);
###
if ($insuffx==".jpg" || $insuffx=="jpeg") {
$insertfile_id = imagecreatefromjpeg($insertfile);
}
if ($insuffx==".png") {
$insertfile_id = imagecreatefrompng($insertfile);
}
if ($insuffx == ".gif") {
$insertfile_id = imagecreatefromgif($insertfile);
}


//Get the sizes of both pix
        $sourcefile_width=imageSX($sourcefile_id);
        $sourcefile_height=imageSY($sourcefile_id);
        $insertfile_width=imageSX($insertfile_id);
        $insertfile_height=imageSY($insertfile_id);

//middle
        if( $pos == 0 )
        {
                $dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 );
                $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
        }

//top left
        if( $pos == 1 )
        {
                $dest_x = 10;
                $dest_y = 10;
        }

//top right
        if( $pos == 2 )
        {
                $dest_x = $sourcefile_width - $insertfile_width - 10;
                $dest_y = 10;
        }

//bottom right
        if( $pos == 3 )
        {
                $dest_x = $sourcefile_width - $insertfile_width - 10;
                $dest_y = $sourcefile_height - $insertfile_height - 10;
        }

//bottom left
        if( $pos == 4 )
        {
                $dest_x = 10;
                $dest_y = $sourcefile_height - $insertfile_height - 10;
        }

//top middle
        if( $pos == 5 )
        {
                $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
                $dest_y = 10;
        }

//middle right
        if( $pos == 6 )
        {
                $dest_x = $sourcefile_width - $insertfile_width - 10;
                $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
        }

//bottom middle
        if( $pos == 7 )
        {
                $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
                $dest_y = $sourcefile_height - $insertfile_height - 10;
        }

//middle left
        if( $pos == 8 )
        {
                $dest_x = 10;
                $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
        }


imagealphablending($sourcefile_id, TRUE);
imagealphablending($insertfile_id, TRUE);
imagealphablending($targetfile, TRUE);

//The main thing : merge the two pix
#imageCopyMerge($sourcefile_id, $insertfile_id, $dest_x, $dest_y, 0, 0, $insertfile_width, $insertfile_height, $transition);
imagecopy($sourcefile_id, $insertfile_id, $dest_x, $dest_y, 0, 0,$insertfile_width, $insertfile_height);

if ($suffx==".jpg" || $suffx=="jpeg") {
imagejpeg($sourcefile_id, $targetfile);
}
if ($suffx==".png") {
imagepng($sourcefile_id, $targetfile);
}
if ($suffx == ".gif") {
imagegif($sourcefile_id, $targetfile);
}

imagedestroy($targetfile);
imagedestroy($sourcefile_id);
imagedestroy($insertfile_id);

}






### Функция создает список ключевых слов по тексту, а также краткое описание.
### Входящие параметры:
###   - $text - Сообственно текст для которого хотим получить ключевые слова
###   - $keywords - Дополнительные ключевые слова. Будут добавляться в начало полученных.
###   - $description - Дополнительное описание. Будет добавляться в начало полученного.
###                    (Учтите, описание не более 200 символов, поэтому полученное будет
###                     обрезаться с учетом длинны дополнительного)
###
### Выходные параметры:
###   - $keyworker['keywords'] - Соответственно ключевые слова
###   - $keyworker['description'] - и описание
###
### Использование:
###   - $meta=create_meta($text);
###   - $meta=create_meta($text, $keywords);
###   - $meta=create_meta($text, 'дополнительные,ключевые,слова');
###   - $meta=create_meta($text, $keywords, $description);
###   - $meta=create_meta($text, 'дополнительные,ключевые,слова', 'дополнительное,описание');
###
### Функция изменена 7 декабря 2010 года
### By Mr.Miksar
###
function create_meta($text, $keywords='', $description='') {
global $config;
$keyworker = array();
$dtext = array();
$kwtext = array();
$wstop = array();

### Нормализация текста
$text=trim(stripslashes(preg_replace("#[\\r\\n\\t\.\!\?\—\:\;\<\>\,\+\=\"\'\&\#\$\^\%\*\(\)\«\»\{\}\@\\\/ ]#i", ' ', preg_replace("#http://(.*) #si", '', strip_tags($text)))));

### Считаем сколько слов уже есть.
if(!empty($description)) {
  $description=trim($description).'. ';
}
if(!empty($keywords)) {
  $keywords=trim($keywords);
}


    ### Загружаем таблицу общих слов и удаляем эти слова из текста
    if(file_exists(no_word_file)) {
        if($file=fopen(no_word_file, 'r')) {
            while(!feof($file)){
                $word=trim(fgets($file));
                if(!empty($word[0]) and $word[0]=='#')continue;
                $wstop[]=''.$word.''; // our words
            }
            fclose($file);
        }
    }

$atext = explode(" ", $text);
$a=0; $b=0;
foreach ($atext as $tk => $tv) {
$a++;
$b++;
  if (!empty($tv) and $a<=$config['auto_description_col']) {
    $dtext[] = $tv;
  } else {
    $a--;
  }
  if (!empty($tv) and strlen($tv) > 3 and !in_array($tv, $kwtext) and !in_array($tv, $wstop) and $b<=$config['auto_keywords_col']) {
    $kwtext[] = $tv;
  } else {
    $b--;
  }
}

$dtext = implode(" ", $dtext);
$textt = implode(",", $kwtext);
$keyworker['description']=$description.$dtext;

if(!empty($keywords)) { 
  $keywords=preg_replace('/,$/i', '', $keywords).','; 
}

$keyworker['keywords']=$keywords.$textt;

### Возвращаем полученный результат
return $keyworker;
}




### делаем транслит с понятного русского на русский по буржуйски // Ru -> Ru by Eng translit
function transname($string) {
               $cyrillic = array("ж", "ё", "й","ю", "ь","ч", "щ", "ц","у","к","е","н","г","ш", "з","х","ъ","ф","ы","в","а","п","р","о","л","д","э","я","с","м","и","т","б","Ё","Й","Ю","Ч","Ь","Щ","Ц","У","К","Е","Н","Г","Ш","З","Х","Ъ","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","С","М","И","Т","Б");
               $translit = array("zh","yo","i","yu","'","ch","sh","c","u","k","e","n","g","sh","z","h","'",  "f",  "y",  "v",  "a",  "p",  "r",  "o",  "l",  "d",  "yе", "jа", "s",  "m",  "i",  "t",  "b",  "yo", "I",  "YU", "CH", "'",  "SH", "C",  "U",  "K",  "E",  "N",  "G",  "SH", "Z",  "H",  "'",  "F",  "Y",  "V",  "A",  "P",  "R",  "O",  "L",  "D",  "Zh", "Ye", "Ja", "S",  "M",  "I",  "T",  "B");
               $string = str_replace($cyrillic, $translit, $string);
               $string = preg_replace(array('@\s@','@[^A-z0-9\-_\.]+@',"@_+\-+@","@\-+_+@","@\-\-+@","@__+@"), array('_', '', "-","-","-","_"), $string);
               $string = strtolower($string);
               return($string);
}    






########################################################
 # Mr Miksar (c) 2009 - 2011
 # img resizer (thumb maker)
 # version 1.2b5
 # Создает превью заданной картинки по заданному размеру. Превью берется из центра картинки. 
########################################################
 
function img_resizer($src, $dest, $dw = 0, $dh = 0, $rgb = "0xFFFFFF", $quality = 100, $rew = 0){

  if (!file_exists($src)){
    return false;
  } elseif (($size = getimagesize($src)) === false){
    return false;
  }
$iw = $size[0];
$ih = $size[1];

  if (!function_exists($icfunc = 'imagecreatefrom'.strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1)))){
    return false;
  }

  if (!empty($dest) and file_exists($dest) and !empty($rew)){
    unlink($dest);
  } elseif (!empty($dest) and file_exists($dest) and empty($rew)){
    return false;
  }

  if (!is_numeric($dw) and !is_numeric($dh)) {
    return false;
  } elseif (is_numeric($dw) and !is_numeric($dh)) {
    $dh = 0;
  } elseif (!is_numeric($dw) and is_numeric($dh)) {
    $dw = 0;
  }
  
  if(!is_numeric($quality)) {
    $quality = 75;
  }
  
$a = 0;
$b = 0;

$ks = $iw/$ih;
$rks = $ih/$iw;
$imin = ($iw>$ih) ? $ih : $iw;
$imax = ($iw>$ih) ? $iw : $ih;

if (empty($dh) and !empty($dw)) {
$dh = round($dw*$rks);
} elseif (empty($dw) and !empty($dh)) {
$dw = round($dh*$ks);
}

if ($dw == $dh) { 
    if ($dw >= $iw and $dh >= $ih) {
      $dw = $iw; 
      $dh = $ih; 
   } elseif (($dw > $imin and $dh < $imin) or ($dw < $imin and $dh > $imin)) {
      $dw = $imin; 
      $dh = $imin; 
   }
} elseif ($dw != $dh and $dw > $iw and $dh <= $ih) {
   $dw = $iw;
} elseif ($dw != $dh and $dh > $ih and $dw <= $iw) {
   $dh = $ih;
} elseif ($dw != $dh and $dh >= $ih and $dw >= $iw) {
   $dw = $iw;
   $dh = $ih;
}

$kd = $dw/$dh;
$rkd = $dh/$dw;

$dstx = 0; 
$dsty = 0;
$srcx = 0; 
$srcy = 0;
$dstw = $dw;
$dsth = $dh;
$srcw = $iw; 
$srch = $ih;

if ($iw <= $dw and $ih <= $dh) {
$isrc = $icfunc($src);
$idest = imagecreatetruecolor($iw, $ih);
imagefill($idest, 0, 0, $rgb);
imagecopyresampled($idest, $isrc, $dstx, $dsty, $srcx, $srcy, $dstw, $dsth, $srcw, $srch);
imagejpeg($idest, $dest, $quality);
imagedestroy($isrc);
imagedestroy($idest);
return true;

} else if ($iw <= $dw and $ih > $dh) {
$srcy = (($srch-$dh)/2); 
$srch = $dh;
  $isrc = $icfunc($src);
  $idest  = imagecreatetruecolor($dstw, $dsth);
 imagefill($idest, 0, 0, $rgb);
imagecopyresampled($idest, $isrc, $dstx, $dsty, $srcx, $srcy, $dstw, $dsth, $srcw, $srch);
imagejpeg($idest, $dest, $quality);
imagedestroy($isrc);
imagedestroy($idest);
return true;

} else if ($iw > $dw and $ih <= $dh) {
$srcx = (($srcw-$dw)/2);
$srcw = $dw;   
  $isrc = $icfunc($src);
  $idest  = imagecreatetruecolor($dstw, $dsth);
  imagefill($idest, 0, 0, $rgb);
imagecopyresampled($idest, $isrc, $dstx, $dsty, $srcx, $srcy, $dstw, $dsth, $srcw, $srch);
imagejpeg($idest, $dest, $quality);
  imagedestroy($isrc);
  imagedestroy($idest);
return true;

} else if ($dw < $iw and  $dh < $ih)  {

if ($iw < $ih) {

if ($dw >= $dh) {

$rh = $dw*$rks;

if ($dh <= $rh) {
   $rh = $dh;
   $rsrch = round(($srcw/$dw)*$dh);
} elseif ($dh > $rh and $dh <= $srch) {
   $rw = $dw; 
   $rsrcw = $srcw;
   $rsrch = round(($srcw/$dw)*$dh);
   $rsrch = ($rsrch <= $srch) ? $rsrch : $srch;
   $a = round(($srch - $rsrch)/2);
   $srcy = $a;
   $srch = $rsrch; 
   } elseif ($dh > $rh and $dh > $srch) {
   $rh = $srch;
   $rsrch = $srch;
}
$rsrch = ($rsrch <= $srch) ? $rsrch : $srch;
   $a = round(($srch - $rsrch)/2);

$srcy = $a;
$dsth = $rh;
$srch = $rsrch;

} else if ($dw < $dh) {

$rw = $dh*$ks;


if ($dw <= $rw) {
   $rw = $dw;
   $rsrcw = round(($srch/$dh)*$rw);
} elseif ($dw > $rw and $dw <= $srcw) {
   $rsrcw = round(($srch/$dh)*$rw);
} elseif ($dw > $rw and $dw > $srcw) {
   $rw = $srcw;
   $rsrcw = $srcw;
}
$rsrcw = ($rsrcw <= $srcw) ? $rsrcw : $srcw;
   $b = round(($srcw - $rsrcw)/2);

$srcx = $b;
$dstw = $rw;
$srcw = $rsrcw;

}

} else if ($iw >= $ih) {

if ($dw > $dh) {

$rh = $dw*$rks;

if ($dh <= $rh) {
   $rh = $dh;
   $rsrch = round(($srcw/$dw)*$rh);
} elseif ($dh > $rh and $dh <= $srch) {
   $rh = $dh;
   $rsrch = $srch;
   $rsrcw = round(($srch/$dh)*$dw);
   $rsrcw = ($rsrcw <= $srcw) ? $rsrcw : $srcw;
   $b = round(($srcw - $rsrcw)/2);
   $srcx = $b;
   $srcw = $rsrcw;
} elseif ($dh > $rh and $dh > $srch) {
   $rh = $srch;
   $rsrch = $srch;
}
$rsrch = ($rsrch <= $srch) ? $rsrch : $srch;
   $a = round(($srch - $rsrch)/2);

$srcy = $a;
$dsth = $rh;
$srch = $rsrch;

} else if ($dw <= $dh) {

$rw = $dh*$ks;

if ($dw <= $rw) {
   $rw = $dw;
   $rsrcw = round(($srch/$dh)*$rw);
} elseif ($dw > $rw and $dw <= $srcw) {
   $rsrcw = round(($srch/$dh)*$rw);
} elseif ($dw > $rw and $dw > $srcw) {
   $rw = $srcw;
   $rsrcw = $srcw;
}
$rsrcw = ($rsrcw <= $srcw) ? $rsrcw : $srcw;
   $b = round(($srcw - $rsrcw)/2);

$srcx = $b;
$dstw = $rw;
$srcw = $rsrcw;

}


}

/////////////////// save //////////////////////
 $isrc    = $icfunc($src);
 $idest  = imagecreatetruecolor($dstw, $dsth);
// $idest  = imagecreatetruecolor(500, 300);
 imagefill($idest, 0, 0, $rgb);
 imagecopyresampled($idest, $isrc, $dstx, $dsty, $srcx, $srcy, $dstw, $dsth, $srcw, $srch);
// imagecopyresampled($idest, $isrc, 0, 0, 0, 125, 299, 250, 299, 250);
imagejpeg($idest, $dest, $quality);
 imagedestroy($isrc);
 imagedestroy($idest);
/////////////////// save //////////////////////

return true;
#######################################################
}

}




















### Подсчет всех новостей
### $all_active = 1 - только активные
### $all_active = 0 - все что есть в базе
function count_all_entry($all_active = 0){
global $db, $config;
$active = !empty($all_active) ? "WHERE hidden='1'" : "";
$query_total = "select count(*) from ".$config['dbprefix']."news ".$active."";
$result = $db->sql_query($query_total);
return ($result ? $result : '0');
}




/* Функция вывода таблицы Excel (из .xls-файла) на html-страницу
 *Переменные:
$filename - имя файла .xls
$start_sheet - стартовая таблица для отображения (для многостраничных таблиц, нумерация начинается с нуля)
$tpl_row - показать нумерацию строк
$tpl_col - показать нумерацию колонок
$tpl_list - показать нумерацию листов (для многостраничных таблиц)
$th - показать оформление
 * Enhanced and maintained by Matt Kruse < http://mattkruse.com >
 * Maintained at http://code.google.com/p/php-excel-reader/
 *
 * Format parsing and MUCH more contributed by:
 * Matt Roxburgh < http://www.roxburgh.me.uk >
 *
 * Re-Make by Ant-Soft (c)
*/

function excel_view($filename, $start_sheet = 0, $tpl_row = true, $tpl_col = true, $tpl_list = true, $tpl = true) {

if (!file_exists($filename)) {echo 'Файл <b>'.$filename.'</b> не найден!'; exit;}

//Подключаем библиотеку
require_once classes_directory.'/excel_reader.class.php';

//Открывает .xls файл //$config['charset'] - надо придумат что сделать с кодировкой...
$data = new Spreadsheet_Excel_Reader($filename,$tpl,'CP1251');

//Определяем страницу для вывода
if (!isset($_GET['sheet'])) {$my_sheet = $start_sheet; } else {$my_sheet = $_GET['sheet'];}

//Считаем количество листов в таблице
$lists = $data->boundsheets;

//Если листов больше одного - формируем "листалку"
if ((count($lists) > 1) and ($tpl_list)) {
	$number_lists = '<div class="lister">';
	for ($i=0; $i < count($lists); $i++) {
		$ln = substr($lists[$i]['name'],0,10);
		if ($i != $my_sheet) {
			$number_lists .= '<span class="list-passive" title="'.$lists[$i]['name'].'">&nbsp;<a href="?sheet='.$i.'">'.$ln.'</a>&nbsp;</span>';
		} else {
			$number_lists .= '<span class="list-active" title="'.$lists[$i]['name'].'">&nbsp;'.$ln.'&nbsp;</span>';
		}
	}
	$number_lists .= '</div>';
}


//Выводим выбранный лист
return $data->dump($tpl_col,$tpl_row,$my_sheet).(!empty($number_lists) ? $number_lists : "");
}








###### Вывод информации о пользователях Online ###



## online ## счетчик с MySQL базой (c) Mr.Miksar
## Изменения от 18 ceнтября 2010
## Выводится число посетителей на сайте через переменную:
## $otpl['online']['all'] - всего, количество гостей - $otpl['online']['guest'], пользователей - $otpl['online']['users'] и роботов - $otpl['online']['robot']
function strw_online() {
global $db, $config, $ap, $sql_error_out, $modul, $otpl, $id_session, $sesuser, $sesip, $robots, $ua;
if (!empty($config['online']) and empty($ap) and $sql_error_out != "mysql") {
$namuser = "";
  if (!empty($modul)) {
  //$mtit = t('Модуль')." ".$modul;
  $mod_id = $config['home_page']."?mod=".$modul;
  } else {
  //$mtit = t('Главная страница');
  $mod_id = $config['home_page'];
  }
  $mtit = $ua;
  

// Через один запрос выведем все интересующие нас вещи о заполнении сессионной таблицы.
$str_onl_del = 0; // есть ли c просроченным временем
$str_onl_gst = 0; // есть ли гости
$str_onl_rbt = 0; // есть ли гости
$str_onl_usr = 0; // есть ли пользователи
$str_onl_ses = 0; // есть ли сессия текущего юзера
$str_onl_id = 0;   // текущий номер сессии
$str_onl = $db->sql_query("SELECT id, cod, minute(date), user, ip FROM ".$config['dbprefix']."session");
if ($db->sql_numrows($str_onl) > 0) {
   while (list($onl_id, $onl_cod, $putdat, $onl_usr, $onl_ip) = $db->sql_fetchrow($str_onl)) {
     if ($onl_usr == "guest") $str_onl_gst++;
     if ($onl_usr == "robot") $str_onl_rbt++;
     if ($onl_usr == "user") $str_onl_usr++;
     if ($onl_ip == $sesip or $onl_cod == $id_session) { $str_onl_ses = 1; } // Смотрим наличие его ip или уникальной сессии
     if ((date('i') - $putdat) >= $config['ses_time'] or (date('i') - $putdat) < 0) { $str_onl_del = 1; }
   }
}


if ($sesuser == "robot") {
$namuser = $robots['now']['name'];
} elseif ($sesuser == "guest") {
$namuser = "Guest";
} else {
$namuser = $sesuser;
$sesuser = "user";
}


    if (empty($str_onl_ses)) {
// если у текущего пользователя нету своей сесии и ip в базе, то запишем его.
 $db->sql_query("INSERT INTO ".$config['dbprefix']."session VALUES (NULL, '".$id_session."', NOW(), '".$sesuser."', '".$namuser."', '".$sesip."', '".$mod_id."', '".$mtit."')");
// если посетитель оказался первым, кто находится он-лайн при пустой базе сессий
// и дабы счетчик не показывал ему ноль, покажем ему его присутствие принужденно, 
// т.к. его запись еще только произведена и не учитывается в общем подсчете.
 if ($sesuser == "guest" and empty($str_onl_gst)) $str_onl_gst = $str_onl_gst+1; // если он пришел как гость
 if ($sesuser == "robot" and empty($str_onl_rbt))  $str_onl_rbt = $str_onl_rbt+1; // если он пришел как робот 
 if ($sesuser == "user" and empty($str_onl_usr))   $str_onl_usr = $str_onl_usr+1; // если он пришел как юзер
    } elseif (!empty($str_onl_ses)) {
// если у текущего посетителя есть своя сесия, то обновим его данные.
 $db->sql_query("UPDATE ".$config['dbprefix']."session SET date=NOW(), user='".$sesuser."', name='".$namuser."', iwh='".$mod_id."', dop='".$mtit."' WHERE ip='".$sesip."' OR cod='".$id_session."' ");
    }
 
// Будем считать, что пользователи, которые отсутствовали в течении $config['ses_time'] минут - покинули ресурс - удаляем их сессию из базы данных 
if (!empty($str_onl_del)) {
 $db->sql_query("DELETE FROM ".$config['dbprefix']."session WHERE date < date_sub(NOW(), interval ".$config['ses_time']." minute)");
}

// Выводим число посетителей
  $otpl['online']['guest'] = $str_onl_gst;
  $otpl['online']['robot'] = $str_onl_rbt;
  $otpl['online']['users'] = $str_onl_usr;
  $otpl['online']['all'] = $str_onl_gst + $str_onl_usr + $str_onl_rbt;

// Вывод
return $otpl;  
} else {
return;
}
}




### Шаблонизатор
function online($oth="") {
global $config, $str_onl_gst, $str_onl_usr, $otpl;
if (!empty($config['online'])) {

  if (is_file(stpl_directory.'/online/'.$oth.'.tpl')) {
    ob_start();
    include stpl_directory.'/online/'.$oth.'.tpl';
    $onl = ob_get_contents();
    ob_end_clean();
  } else {
    $onl = "Пользователей он-лайн: ".$otpl['online']['users'].". Гостей он-лайн: ".$otpl['online']['guest'].". Роботов он-лайн: ".$otpl['online']['robot'].". Всего посетителей: ".$otpl['online']['all'];
  }
  
  return $onl;
} else {
  return;
}
} 


### Считает число сессий на сервере за $config['ses_time'] минут
function getOnlineUsers(){
global $config;
  if ($directory_handle = opendir(session_save_path())) {
    $count = 0;
    while (false !== ($file = readdir($directory_handle))) {
      if($file != "." && $file != ".."){
        if(time()- fileatime(session_save_path()."/".$file) < $config['ses_time'] * 60) {
          $count++;
        }
      } 
    }
    closedir($directory_handle);
    return $count;
  } else {
    return false;
  }
}
###### // Вывод информации о пользователях Online ###


### str_stop - расширенная модификация substr. При обрезании в конце добавляет "...".
function str_stop($string, $max_length){

     if ($max_length == '0' or empty($max_length) or !is_numeric($max_length)) { return $string;}
     
     if (!empty($string) and !empty($max_length) and is_numeric($max_length) and strlen($string) > $max_length){
         $string = substr($string, 0, $max_length);
         $pos = strrpos($string, " ");
           if ($pos === false) {
             return substr($string, 0, $max_length)."...";
           }
          return substr($string, 0, $pos)."...";
        } else {
          return $string;
        }
} 


### 2 Функции для керевода кирилицы...
function replace_cyr($path) {
  $search = array ("'Ё'", "'А'", "'Б'", "'В'", "'Г'", "'Д'", "'Е'", "'Ж'", "'З'", "'И'", "'Й'", "'К'", "'Л'", "'М'", "'Н'", "'О'", "'П'", "'Р'", "'С'", "'Т'", "'У'", "'Ф'", "'Х'", "'Ц'", "'Ч'", "'Ш'", "'Щ'", "'Ъ'", "'Ы'", "'Ь'", "'Э'", "'Ю'", "'Я'", "'а'", "'б'", "'в'", "'г'", "'д'", "'е'", "'ж'", "'з'", "'и'", "'й'", "'к'", "'л'", "'м'", "'н'", "'о'", "'п'", "'р'", "'с'", "'т'", "'у'", "'ф'", "'х'", "'ц'", "'ч'", "'ш'", "'щ'", "'ъ'", "'ы'", "'ь'", "'э'", "'ю'", "'я'", "'ё'", "'0'e");
  $replace= array ('&#1025;', '&#1040;', '&#1041;', '&#1042;', '&#1043;', '&#1044;', '&#1045;', '&#1046;', '&#1047;', '&#1048;', '&#1049;', '&#1050;', '&#1051;', '&#1052;', '&#1053;', '&#1054;', '&#1055;', '&#1056;', '&#1057;', '&#1058;', '&#1059;', '&#1060;', '&#1061;', '&#1062;', '&#1063;', '&#1064;', '&#1065;', '&#1066;', '&#1067;', '&#1068;', '&#1069;', '&#1070;', '&#1071;', '&#1072;', '&#1073;', '&#1074;', '&#1075;', '&#1076;', '&#1077;', '&#1078;', '&#1079;', '&#1080;', '&#1081;', '&#1082;', '&#1083;', '&#1084;', '&#1085;', '&#1086;', '&#1087;', '&#1088;', '&#1089;', '&#1090;', '&#1091;', '&#1092;', '&#1093;', '&#1094;', '&#1095;', '&#1096;', '&#1097;', '&#1098;', '&#1099;', '&#1100;', '&#1101;', '&#1102;', '&#1103;', '&#1105;', '0');
  return preg_replace ($search,$replace,$path);
} 
function cyr_to_chpu($str) {
  $arCyr = Array("ё", "й", "ц", "у", "к", "е", "н", "г", "ш", "щ", "з", "х", "ъ", "ф", "ы", "в", "а", "п", "р", "о", "л", "д", "ж", "э", "я", "ч", "с", "м", "и", "т", "ь", "б", "ю", "Ё", "Й", "Ц", "У", "К", "Е", "Н", "Г", "Ш", "Щ", "З", "Х", "Ъ", "Ф", "Ы", "В", "А", "П", "Р", "О", "Л", "Д", "Ж", "Э", "Я", "Ч", "С", "М", "И", "Т", "Ь", "Б", "Ю");
  $arLat = Array("e", "i", "ts", "u", "k", "e", "n", "g", "sh", "sch", "z", "h", "", "f", "y", "v", "a", "p", "r", "o", "l", "d", "zh", "e", "ya", "ch", "s", "m", "i", "t", "", "b", "yu", "e", "i", "ts", "u", "k", "e", "n", "g", "sh", "sch", "z", "h", "", "f", "y", "v", "a", "p", "r", "o", "l", "d", "zh", "e", "ya", "ch", "s", "m", "i", "t", "", "b", "yu");
  $str = preg_replace("/[^a-zа-яА-Я0-9 ]/i", "",$str);
  $str = preg_replace("/ +/", " ",$str);
  $str = str_replace($arCyr,$arLat,$str);
  if(strlen($str)<=0) return "";
  $str = preg_replace("/\w+/ei", "ucfirst('\\0')",$str);
  $str = str_replace(" ", "",$str);
  return $str;
}


### Если отсутствует mysql_escape_string, но очень надо...
if(!function_exists('mysql_escape_string')) {
  function mysql_escape_string($string) {
    return htmlspecialchars($string, ENT_QUOTES);
  }
}


### путь от корня сайта до места установки скрипта
function way($in=""){
global $addway;
 if (!empty($in)) {
   $inl = strlen($in);
   $in = (substr($in, 0, 1) == "/") ? substr($in, 1, ($inl-1)) : $in;
 }
return $addway.$in;
}
### путь от корня сайта до места установки скрипта
function sway($in=""){
global $addsway;
 if (!empty($in)) {
   $inl = strlen($in);
   $in = (substr($in, 0, 1) == "/") ? substr($in, 1, ($inl-1)) : $in;
 }
return $addsway.$in;
}

### шаблон ошибки
function error_tpl($etitle="", $etext="") {
return $etitle."<br />".$etext;
}


### удалить папку с содержимым
#   $w=0 - удалить только содержимое
#   $w=1 - удалить вместе с папкой
function deldir($dir, $w=0){
if (is_dir($dir)) {
  $handle = opendir($dir);
  while (false !== ($file = @readdir($handle))){
      if ($file != '.' and $file !== '..'){
          if (is_file($dir.'/'.$file)){
              @unlink($dir.'/'.$file);
          } elseif(is_dir($dir.'/'.$file)) {
              deldir($dir.'/'.$file);
              @rmdir($dir.'/'.$file);
          }
      }
  }
  closedir($handle);
  if (!empty($w)) @rmdir($dir);
} elseif (is_file($dir)) {
  @unlink($dir);
}
  return true;
}


### A faster way to replace the strings in multidimensional array is to json_encode() it
function str_replace_json($search, $replace, $subject){
     return json_decode(str_replace($search, $replace,  json_encode($subject)));

}


### This method is almost 3x faster (in 10000 runs.) than using recursive calling and looping method, and 10x simpler in coding.
function str_replace_deep($search, $replace, $subject)
{
    if (is_array($subject))
    {
        foreach($subject as &$oneSubject)
        $oneSubject = str_replace_deep($search, $replace, $oneSubject);
        unset($oneSubject);
        return $subject;
    } else {
        return str_replace($search, $replace, $subject);
    }
} 




?>