<?php
#_strawberry
if (!defined("str_adm")) { 
header("Location: ../../index.php"); 
exit; 
}

/**
 * ����������� ������� Strawberry 1.2, ������� ������ ��������.
 * ����� ��������� ���, ��� � ���� ������� ��� ������ �������, �� - ��� - ��� ������������� �
 * ���, ��������, �������� �������� �� ������...
 * ���� �� ������ ��� �� � ���� ������� ����������� � ��������� ������� �������, �� ������ �� miksar@mail.ru
 * @package Functions
 **/

/**

����� ����� ����� � �����,
�������������.
�������� ����� � ������,
����� �������.

�����-����� ����� � ����,
�� ���������,
������ �� �� ���� �� ����,
������� ������. (�)

**/

/*# strawberry 1.2 original functions #*/

 /**
 * ������ ������� ������������ ���������������. by Mr.Miksar && Lexa Zloy
 * @param string $text
 * @param string $array
 * @return string
 */
function t($text, $array = array()){
global $plugin, $config, $gettext, $tr_lng;
    if (empty($text)){
      return;
    } else {
ignore_user_abort(true); // ��� �� ���� ������ �� �������. ����� ������ �����, �������� �������...
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
 � �����, �������� ����� �������, 
 ����� � ������ �������. 
 � ���� ��� ������ ���������. 
 �������������, �� �����: 
 
 "� ���� ��������� � ������, 
 � ���� ������ ������, 
 � ���� ����� ���� ��������. 
 � ���� ���� ���. � ��� ����. 
 
 ���� ������� ��� ���� ��������, 
 ����� ����� �� ������?!" 
 � ������� ������: "� � �������", 
 � ��� �������� ������ � ���.
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







### ������ ������� ������
function otable() {
global $tit;
echo "<div width=\"100%\"><div class=\"arttit\">".$tit."</div><div class=\"ctable nbtext\" width=\"100%\">";
}

### ����� ������� ������
function ctable() {
echo "</div></div>";
}




########### (c) Mr.Milsar ##################
### ������� ��� �����
### ��������� �� 01.11.2009


### ������� ��� ���� ��� �������� $ct � ������� $it_id
### ������������ ��� ������������ ����� ���� ��� ����� ��� ������� ��������
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
### ����������� ���.�����.
### ������� ���� ������ ��� �����.
### ��� �������� ����� $fo_where
### ��������! ��� ������� ������ ����������� ��� ����� �������� ���������� �������.
function strawberry_array_fields($fo_where = "") {
global $db, $config, $sfields, $cache;

  if (!$sfields = $cache->unserialize('sfields') and !empty($config['use_dop_fields'])){
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


### // ������� ��� �����
################# (c) Mr.Miksar ###############











###########
### ������� ������ �� ������ � ����� �� ����� �� ��������� ������
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
  // ������ �� ���������� �� ��������� �������� ������������ ����������� $begin,$end
if($begin == 0) { 
$tmp2 = "";
 } else {
$tmp2 = "putdate>=date_sub(date_format('".langdate('Y-m-d H:i:s', time)."','%Y-%m-%d 23:59:59'),interval '".$begin."' day)";
 }
$tmp1 = "putdate<date_sub(date_format('".langdate('Y-m-d H:i:s', time)."','%Y-%m-%d 23:59:59'),interval '$end' day)";
  // ����������� and
  if($tmp2!= "" or $tmp != "") { $and1 = " and "; }
  if($tmp2!= "" and $tmp != "") { $and2 = " and ";}

  // ����� ����� �����
  $query_total = "select count(*) from ".$config['dbprefix']."count_ip where 
  ".$tmp1.
  $and1.
  $tmp2.
  $and2.
  $tmp."
  ";
  // ������������ ����� IP-������� (������)
  $query = "select count(distinct ip) from ".$config['dbprefix']."count_ip where ".$tmp1.$and1.$tmp2.$and2.$tmp."";
  // ������������ ������� � ���� ������
  $tot = mysql_query($query_total);
  $ipsad = mysql_query($query);
  if($tot && $ipsad)
  {
    $totl = mysql_fetch_array($tot);
    $ip = mysql_fetch_array($ipsad);
    return array($ip['count(distinct ip)'],$totl['count(*)']);
  } else {
  $sql_error_out = "mysql";
#reporterror("������ ��� ��������� � ������� �������� IP-�������...");
  }
  } else {
  $sql_error_out = "mysql";
  }
}



###########
### ������� ������ �� ������ � ����� � ����������� �� ��������� ������
function show_ip_host($begin="",$end="",$id_page="",$day="",$is_hour="") {
	global $config, $db, $adfile;
  // ��� ���������� ���������� �������������� �� ������ � ����������
  // �������� ��� �� ����� �����.
  if(empty($id_page)) { 
  $tmp = ""; 
  } else {
  $tmp = "id_page=".$id_page;
  }
  // ������ �� ���������� �� ��������� �������� ������������
  // ����������� $begin,$end

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

  // ����������� and
  if($tmp2!="" || $tmp !="") $and1 = " and ";
    else $and1 = ""; 
  if($tmp2!="" && $tmp !="") $and2 = " and ";
    else $and2 = ""; 
  // ����� ����� �����
  $query_total = "select count(*) from ".$config['dbprefix']."count_ip where ".$tmp1.$and1.$tmp2.$and2.$tmp.";";
  // ������������ ����� IP-������� (������)
  $query_host = "select count(distinct ip) from ".$config['dbprefix']."count_ip where ".$tmp1.$and1.$tmp2.$and2.$tmp.";";
  // ������������ ������� � ���� ������
  $tot = $db->sql_query($query_total);
  $ipsad = $db->sql_query($query_host);
  if($tot && $ipsad)
  {
    $totl = mysql_fetch_array($tot);
    $host = mysql_fetch_array($ipsad);
    return array($host['count(distinct ip)'],$totl['count(*)']);
  } else {
    return t("������ ��� ��������� � ������� IP-�������...");
  }
}



###########
### ������� ������ �� ������ � ����� � �������� ���� ����������
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
$schet = "������� ������: ".$total1.". ������� ������: ".$host1.". ����� ������: ".$totalall.". ����� ������: ".$hostall.".";
}

} else {
$schet = "";
}

return $schet;
}
### // �������
########### (c) softtime@softtime.ru ##################



 ### �������� �����
function cook_ch() {
global $config;
  // ��� ��������� ����������� ������, ����������,
  // �������� �� cookies � ������������  
if (empty($_COOKIE['str_test_cookie'])) {
@setcookie('str_test_cookie', '1');
return t('��� ���������� ������ ����� ���������� ��������� cookies!');
} else {
return;
}
}

#### ����������� ����� �����������
function lform() {
global $result_in, $config;
$lf = "<form name=\"login\" action=\"\" method=\"post\">"
."<table border=\"0\" cellpadding=\"2\" class=\"defloginform\">"
."<tr>"
."<td width=\"50\">&nbsp;&nbsp;".t("�����").":&nbsp;</td>"
."<td width=\"170\"><input type=\"text\" name=\"username\" class=\"regtext\"></td>"
."</tr>"
."<tr>"
."<td>&nbsp;&nbsp;".t("������").":&nbsp;</td>"
."<td><input type=\"password\" name=\"password\" class=\"regtext\"></td>"
."</tr>";
$lf .= pin_cod_auth("login", "auth"); 
$lf .= "<tr>"
."<td colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"regok\" value=\"".t("����")."\"></td>"
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
."� <a href=\"index.php?mod=account&amp;act=registration\">".t("�����������")."</a><br>"
."� <a href=\"index.php?mod=account&amp;act=forgot\">".t("������ ������?")."</a></td>"
."</tr></table>"
."<input type=\"hidden\" name=\"action\" value=\"dologin\">"
."</form>";
return $lf;
}


### �������� ������������
# ���� ������� ���������� ������ - robot
# ���� ������������� - �����
# ���� �������� 0 � ����������� - �����
# ���� �������� 1 � ����������� - ���

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
### ���������� ���������� ������
function formatsize($file_size){
    if ($file_size >= 1073741824){
      $file_size = (round($file_size / 1073741824 * 100) / 100).' '.t('�����');
    } elseif ($file_size >= 1048576){
      $file_size = (round($file_size / 1048576 * 100) / 100).' '.t('�����');
    } elseif ($file_size >= 1024){
      $file_size = (round($file_size / 1024 * 100) / 100).' '.t('�����');
    } else {
      $file_size = $file_size.' '.t('����');
    }
return '<nobr>'.$file_size.'</nobr>';
}
*/


// (c) Chaser <coderunnerz@gmail.com>
### ���������� ���������� ������
function formatsize($file_size=0) {

if (is_numeric($file_size)) {

	  	$sizes = array(t('����'), t('�����'), t('�����'), t('�����'), t('�����'));
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


### �������������� ����������� �������
# Size filter
function razmer($size=0) {
if (is_numeric($size)) {
  $name = array(t('����'), t('�����'), t('�����'), t('�����'), t('�����'), t('�����'), t('�����'), t('�����'), t('�����'));
  $thissize = $size ? "".round($size / pow(1024, ($i = floor(log($size, 1024)))), 2)." ".$name[$i]."" : "".$size." Bytes";
  return $thissize;
    	} else {
  	return;
  	}
}




### ���������� - ���� �� http://.
function rurl($adressok, $opt="http"){
// (�) chaser
// modify 09.02.2010 by mr.miksar
if (!preg_match("/(http|https|ftp|rtmp|rtm)\:\/\//", $adressok)) {
$adressok = $opt."://".$adressok;
} 
return $adressok;
}




### ����� �����
function sitelife($vid){
global $config, $TheYear;
if ($config['sitelife'] == 1) {
 ### ���� ����� �� �� ������� ���� ������� �����, �� ����� ������� ��� �� �������� ����� ������������ ;)
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
 $site_live = (!empty($tpl['sitelife']['years']) ? t('��� �����').": ".$tpl['sitelife']['years'] : "").". ".t('���� �����').": ".$tpl['sitelife']['days'];
 }
} else {
$site_live = "";
}
return $site_live;
}


### ���������� �� ������?
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


### ��������� IP
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


### ��������� REFERER
function getref() {
$ref_env = cheker(getenv("HTTP_REFERER"));
	if (!empty($ref_env) && $ref_env != "" && !preg_match("/^unknown/i", $ref_env) && !preg_match("/^bookmark/i", $ref_env) && !strpos($ref_env, $_SERVER["HTTP_HOST"])) {
		$ref = $ref_env;
	} else {
		$ref = "";
	}
	return $ref;
}


### ��������� REFERER c ����
function getref_self() {
$ref_env = cheker(getenv("HTTP_REFERER"));
	if (!empty($ref_env) && $ref_env != "" && !preg_match("/^unknown/i", $ref_env) && !preg_match("/^bookmark/i", $ref_env) && strpos($ref_env, $_SERVER["HTTP_HOST"])) {
		$ref = $ref_env;
	} else {
		$ref = "";
	}
	return $ref;
}


### ��������� REFERER c� ����
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

### ����� ���������� pin ��� �����������

### �������� pin
function pin_check($tip="1") {
	global $config, $is_logged_in, $rand_out;
	if (empty($is_logged_in) and !empty($config['pin'])) {
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


### ����� ���������� pin
function pin_cod($styler="", $tip="1") {
global $config, $is_logged_in;
if (!empty($config['pin'])) {
if (extension_loaded("gd") and empty($is_logged_in)) {
//$tpl['capcha']['img'] = "<img src=\"/index.php?pin=1&amp;tip=".$tip."\" border=\"1\" alt=\"".t("��� ������������")."\">";
//$tpl['capcha']['img'] = "<div OnClick=\"gload('1', '".$tip."', '1', 'img', '', '', '".$tip."', '', '', ''); return false;\" class=\"strawptcha\"><div id=\"ajx".$tip."\"><img src=\"/active.php?go=1&amp;tip=".$tip."\" alt=\"".t("��� ������������")."\"></div></div>";
//$tpl['capcha']['img'] = "<div OnClick=\"wcompleted('".$tip."', '".$tip."', ''); return false;\"><div id=\"ajx".$tip."\"><img src=\"/active.php?go=1&amp;tip=".$tip."\" alt=\"".t("��� ������������")."\"></div></div>";
$tpl['capcha']['img'] = "<div OnClick=\"pinload('1', '".$tip."', '2', '".$tip."', '".way()."'); return false;\" class=\"strawptcha\"><div id=\"ajx".$tip."\"><img src=\"".way("active.php?go=1&amp;tip=".$tip)."\" alt=\"\"><img src=\"".sway("admin/images/icons/arrow_refresh.png")."\" border=\"0\" class=\"pinrefresh\" alt=\"\"/></div></div>";
$tpl['capcha']['enter'] = "<input type=\"text\" name=\"pin_check\" size=\"10\" maxlength=\"10\" class=\"pin_enter\">";
if (is_file(stpl_directory.'/captcha/'.$styler.'.tpl')) {
ob_start();
include stpl_directory.'/captcha/'.$styler.'.tpl';
$codder_cap = ob_get_clean();
} else {
$codder_cap = t('��� ���').": ".$tpl['capcha']['img']."<br>".t("������").": ".$tpl['capcha']['enter'];
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

$metas = "\n<title>".$config['delitel']." ".$config['home_title']." ".$config['delitel']; 
if (!empty($tit)) $metas .= " ".$tit." ".$config['delitel']; 
if (function_exists('metainfo') and !empty($nid)) $metas .= " ".metainfo('title')." ".$config['delitel'];
$metas .= "</title>\r";
$metas .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$config['charset']."\">\r";
$metas .= "<meta http-equiv=\"Content-Language\" content=\"".$config['lang']."\">\r"
."<meta http-equiv=\"Author\" content=\"".$config['home_author']." � ".$TheYear."\">\r";

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
  $metas .= " ".$config['delitel']." ������ ".cheker($_GET['mod']);
}

$metas .= "\">\r";

if (!empty($config['home_author'])) { 
  $metas .= "<meta name=\"author\" content=\"".$config['home_author']."\">\r"; 
}

$metas .= "<meta name=\"copyright\" content=\"� Strawberry 1.2 2009 - ".$TheYear."\">\r";

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

$metas .= "<link rel=\"search\" type=\"application/opensearchdescription+xml\" href=\"".way($config['home_page']."?mod=search")."\" title=\"".$config['home_title']." ".$config['delitel']." Search\">\r";
$metas .= "<link href=\"".way("favicon.ico")."\" type=\"image/x-icon\" rel=\"shortcut icon\">\r"
."<link href=\"".way("favicon.ico")."\" type=\"image/x-icon\" rel=\"icon\">";

if ($sesuser != "robot"){ 
 $metas .= "<link href=\"".sway("data/themes/".$config['mytheme']."/style.css")."\" type=\"text/css\" media=\"screen\" rel=\"stylesheet\">\r";
}

if (!empty($config['js_potok']) and $sesuser != "robot") {
$in_filer = file_read(js_file);
$where_mas = explode(",", $in_filer);
  foreach ($where_mas as $val) {
    if (!empty($val)) $metas .= "<screept type=\"text/javascript\" language=\"JavaScript\" src=\"".sway("data/java/".$val)."\"></screept>\r";
  }
}

if (!empty($config['js_er_bl']) and $sesuser != "robot") {
  $metas .= "<screept type=\"text/javascript\" language=\"JavaScript\" src=\"".sway("data/java/berror.js")."\"></screept>\r";
}

return $metas;
}
######### meta



### ��������� �������
function generate_password($ider=8)  {
// ��� $ider - ����� �������� � ������.
    $arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6','7','8','9','0','.',',','(',')','[',']','!','?','&','^','%','@','*','$','<','>','/','|','+','-','{','}','`','~');
    // ���������� ������
    $pass = "";
    for($i = 0; $i < $ider; $i++)
    {
      // ��������� ��������� ������ �������
      $index = mt_rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
  }

// �� punBB
function random_pass($len = 8){
    $chars    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $password = '';
    for ($i = 0; $i < $len; ++$i){
        $password .= substr($chars, (mt_rand() % strlen($chars)), 1);
    }
return $password;
}



/**
 * ����� ������� - ������� � ������.
 * @access private
 */
class microTimer {
  /**
   * ��������� ����. � �������� - ��� ������ ������� �������, �� � ��������� �����...
   * @access private
   */
    function start(){
    global $starttime;
    $starttime = array_sum(explode(" ", microtime()));
    }

    /**
     * � ������ ���������, ������� �� ������� ������ �� ������� �����...
     * @access private
     * @return unknown
     */
     function stop(){
     global $starttime;
     $totaltime =  round(((array_sum(explode(" ", microtime()))) - $starttime), 5); // ��������� �� 5 ������ ����� �������
     return $totaltime;
    }
}


### ��������� ���������� ���� ��� ��� �� �����
function chuser($ns="", $a="0") {
global $config, $db, $ap, $users;

$us = (!empty($ap) and $ns != 'guest') ? (!empty($users[$ns]['username']) ? $users[$ns]['username'] : '') : ((!empty($users[$ns]['name']) and $ns != 'guest') ? $users[$ns]['name'] : '');

if (!empty($us)) {
$nms = !empty($ap) ? 
"<a href=\"index.php?mod=editusers&amp;action=edituser&amp;id=".$users[$ns]['id']."\" title=\"".t("������������������ ������������ %u", array('u'=>$users[$ns]['name']))."\">".$users[$ns]['name']."</a>" : 
"<a href=\"index.php?mod=users&amp;user=".$users[$ns]['username']."\" title=\"".t("������������������ ������������ %u", array('u'=>$users[$ns]['name']))."\">".$users[$ns]['name']."</a>";
} else {
$nms = $ns;
}
return $nms;
}

### ��� �� ip?
function whois_ip($ids="") {
global $config;
$arr_ids = array();
$ids = (!empty($ids)) ? $ids : getip();

if ($ids == "127.0.0.1") {
  return t('��������� ����');
} elseif (!empty($ids) and $ids != "127.0.0.1") {

  if(strstr($ids, "/")) {
    $ridsdata = array();
    $arr_ids = explode("/", $ids);
    foreach ($arr_ids as $ids_data) {
      $ridsdata[] = "<a target=\"_blank\" href=\"".$config['whois'].$ids_data."\" title=\"".t("���������� ������ IP")."\">".$ids_data."</a>";
    }
    return implode("/",$ridsdata);
  } else {
    return "<a target=\"_blank\" href=\"".$config['whois'].$ids."\" title=\"".t("���������� ������ IP")."\">".$ids."</a>";
  }

} elseif (empty($ids)) {
return;
}
}

### ���� ��� �������
function on_page($inmod="", $up="0") {
global $config, $modul;
$dop = "";
$outmod = !empty($inmod) ? $inmod : (!empty($modul) ? $modul : $config['modul']);
if (!empty($outmod)) {
$dop = "?mod=".$outmod;
}
if (empty($up)) {
$upp = "| <a href=\"#\" title=\"".t("������������� ����� ��������")."\">".t("������")."</a> ";
} else {
$upp = "";
}
return "<center><div class=\"dmenu\">[ <a href=\"javascript:history.go(-1)\" title=\"".t("��������� �� ���������� ��������")."\">".t("�����")."</a> | <a href=\"".$_SERVER['PHP_SELF'].$dop."\" title=\"".t("������� �������� ������")."\">".t("�������")."</a> ".$upp."]</div></center>";
}

### ���������� ������ ���������� GD
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

### ���������� ������ MySQL
function db_version() {
	global $db, $config;
	list($dbversion) = $db->sql_fetchrow($db->sql_query("SELECT VERSION()"));
	return $dbversion;
}



### ������ �����
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
if ($per != $chm) return $loc." ".t("������ ���������")." CHMOD: ".$chm;

} else {
return;
}
		
}



### �������� ����������� GZip ������
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

### ��������� ����
function highlight_code($code) { 
  // ���� �� ����� $code ������������ �������� htmlspecaialchars, ����� ����� �������� ���, ������� �������� �� �������� 
  $code = stripslashes($code); 
  if(!strpos($code,"<?") && substr($code,0,2)!="<?") {
    $code="<?php\n".trim($code)."\n?>"; 
  }  
  $code = trim($code); 
  return highlight_string($code,true);
}



### �������� HIDE
function encode_hide($text) {
global $is_logged_in;
  $start_html = "<fieldset class=\"fld_set\"><legend style=\"color: red;\">".t('������� �����')."</legend><div class=\"fdcd\">";
  $mid_html = "<div class=\"fdcd\">";
  $low_html = "</div>";
  $end_html = "</fieldset>";
  $text = (!empty($is_logged_in)) ? $start_html.$mid_html.$text.$low_html.$end_html : $start_html.$mid_html.t("���� ����� ����� ������������� ������ ����������������� ������������").$low_html.$end_html;
  return $text;
}

### �������� QUOTE
function encode_quote($text, $dp="") {
  $start_html = "<fieldset class=\"fld_set\"><legend style=\"color: green;\">".t('������').(!empty($dp) ? str_replace("=", ": ", $dp) : "")."</legend>";
  $mid_html = "<div class=\"fdcd\">";
  $low_html = "</div>";
  $end_html = "</fieldset>";
  $text = str_replace(array("&lt;","&gt;","&quot;","&#039;","&amp;","&#092;","{nl}","&nbsp;","&lt;?php","?&gt;"), array("<",">","\"","'","&","\\","\n"," ","",""), trim($text));
  $text = $start_html.$mid_html.$text.$low_html.$end_html;
  return $text;
}


### �������� CODE
function encode_code($text="", $dp="") {
if (!empty($text)) {
  $start_html = "<fieldset class=\"fld_set\"><legend style=\"color: blue;\">".t('���').(!empty($dp) ? str_replace("=", " ", $dp) : "")."</legend>";
  $mid_html = "<div class=\"fdcd\">";
  $low_html = "</div>";
  $end_html = "</fieldset>";
  $text = str_replace(array("&lt;","&gt;","&quot;","&#039;","&amp;","&#092;","{nl}","&nbsp;","&lt;?php","?&gt;"), array("<",">","\"","'","&","\\","\n"," ","",""), trim($text));
  $text = $start_html.$mid_html.(highlight_string($text,true)).$low_html.$end_html;
}
  return $text;
}


### �������� PHP
function encode_php($text) {
if (!empty($text)) {
    $start_html = "<fieldset class=\"fld_set\"><legend style=\"color: purple;\">".t('���')." PHP</legend><div class=\"fdcd\">";
    $end_html = "</div></fieldset>";
    $after_replace = trim($text);
    $after_replace = (substr($after_replace, 0, 2) != "<?") ? "<?php ".$after_replace : $after_replace;
    $after_replace = (substr($after_replace, -2) != "?>") ? $after_replace." ?>" : $after_replace;
    $after_replace = str_replace(array("&lt;","&gt;","&quot;","&#039;","&amp;","&#092;","{nl}","&nbsp;","&lt;?php","?&gt;"), array("<",">","\"","'","&","\\","\n"," ","",""), $after_replace);
    $after_replace = str_replace(array("<code>","</code>"), array("<div class=\"fcode\">","</div>"), highlight_string($after_replace,true));
    //$after_replace = "oioiorioiorioeioorieoioeiorioteiorioieotioreiotireoitoeriotioeritoieroitoreioioeriotieroioeiotioeriotreiotireoiotoeitrieotioeriotieroitoetoeitoetoreooerioiert";
    $text = str_replace(array("&lt;?php","&lt;?","?&gt;"), "", $text);
    $text = $start_html.$after_replace.$end_html;
}
  return $text;
}


# ��������� ��������� e-mail // Check inputed e-mail // slaed
function check_email($mail) {
$stop = "";
if (!empty($mail)) {
  $mail = strtolower(cheker($mail));
  if ((!$mail) || ($mail=="") || (!preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/", $mail))) $stop = t('����� ����� �������� ������! (���������: <b>mail@domain.ru</b>)');
  if ((strlen($mail) >= 4) && (substr($mail, 0, 4) == "www.")) $stop = t('������� ������ ������� (<b>www.</b>)');
  if (strrpos($mail, " ") > 0) $stop = t('������� � ������ �����������.');
}
  return $stop;
}

# ��������� ��������� e-mail // Check inputed e-mail // slaed
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

# ��������� ��� ������������ ����� // Check type upload file
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

# ������� ������ �������� // Check size img 
function check_img_size($file, $width, $height) {
	list($imgwidth, $imgheight) = getimagesize($file);
	if ($imgwidth > $width || $imgheight > $height) { return 1; } else { return 0; }
}



### ������������ bb-����
function aply_bbcodes($text) {
global $config, $ap;

$bb = array();
$html = array();

  // �����������
$bb[] = "#\[img\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#i";
  $html[] = "<img src=\"\\1\" border=\"0\" alt=\"\">";
$bb[] = "#\[img=([a-zA-Z]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
  $html[] = "<img src=\"\\2\" align=\"\\1\" border=\"0\" alt=\"\">";
$bb[] = "#\[img alt=([a-zA-Z�-��-�0-9\�\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
  $html[] = "<img src=\"\\2\" border=\"0\" alt=\"\\1\">";
$bb[] = "#\[img=([a-zA-Z]+) alt=([a-zA-Z�-��-�0-9\�\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
  $html[] = "<img src=\"\\3\" align=\"\\1\" border=\"0\" alt=\"\\2\">";

  // ������
$bb[] = "#\[url\]([\w]+?://([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
  $html[] = "<a href=\"\\1\" target=\"_blank\" title=\"\\1\">\\1</a>";
$bb[] = "#\[url\]((www|ftp)\.([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
  $html[] = "<a href=\"http://\\1\" target=\"_blank\" title=\"\\1\">\\1</a>";
$bb[] = "#\[url=([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
  $html[] = "<a href=\"\\1\" target=\"_blank\" title=\"\\1\">\\2</a>";
$bb[] = "#\[url=((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
  $html[] = "<a href=\"http://\\1\" target=\"_blank\" title=\"\\1\">\\3</a>";

  // ������
$bb[] = "#\[lnk\](.+?)\[/lnk\]#is";
  $html[] = "<a href=\"\\1\" title=\"\\1\">\\1</a>";
$bb[] = "#\[lnk=(.+?)\]\[/lnk\]#is";
  $html[] = "<a href=\"\\1\" title=\"\\1\">\\1</a>";
$bb[] = "#\[lnk=(.+?)\](.+?)\[/lnk\]#is";
  $html[] = "<a href=\"\\1\" title=\"\\2\">\\2</a>";

  // �����
$bb[] = "#\[mail\](\S+?)\[/mail\]#i";
$html[] = "<a href=\"mailto:\\1\">\\1</a>";
$bb[] = "#\[mail\s*=\s*([\.\w\-]+\@[\.\w\-]+\.[\w\-]+)\s*\](.*?)\[\/mail\]#i";
$html[] = "<a href=\"mailto:\\1\">\\2</a>";

  // �����
$bb[] = "#\[color=(\#[0-9A-F]{6}|[a-z]+)\](.*?)\[/color\]#si";
  $html[] = "<span style=\"color: \\1\">\\2</span>";
$bb[] = "#\[family=([A-Za-z ]+)\](.*?)\[/family\]#si";
  $html[] = "<span style=\"font-family: \\1\">\\2</span>";
$bb[] = "#\[size=([0-9]{1,2}+)\](.*?)\[/size\]#si";
  $html[] = "<span style=\"font-size: \\1\">\\2</span>";
$bb[] = "#\[font=([A-Za-z ]+)\](.*?)\[/font\]#si";
  $html[] = "<font style=\"font-family:\\1\">\\2</font>";

   // �������
$bb[] = "#\[(left|right|center|justify)\](.*?)\[/\\1\]#is";
  $html[] = "<div align=\"\\1\">\\2</div>";

  // Activ-X ���������
$bb[] = "#\[fla\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] ="<embed src=\"\\1\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla=([a-zA-Z�-��-�0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\2\" bgcolor=\"#\\1\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla\ height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\2\" height=\"\\1\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla\ width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\2\" width=\"\\1\" height=\"200\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla=([a-zA-Z�-��-�0-9\_\-\. ]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\3\" bgcolor=\"#\\1\" height=\"\\2\" width=\"100%\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla=([a-zA-Z�-��-�0-9\_\-\. ]+) width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\3\" bgcolor=\"#\\1\" width=\"\\2\" height=\"200\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla=([a-zA-Z�-��-�0-9\_\-\. ]+) height=([0-9]+) width=([0-9\%]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\4\" bgcolor=\"#\\1\" width=\"\\3\" height=\"\\2\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla=([a-zA-Z�-��-�0-9\_\-\. ]+) width=([0-9\%]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\4\" bgcolor=\"#\\1\" width=\"\\2\" height=\"\\3\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla\ height=([0-9]+) width=([0-9\%]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\3\" width=\"\\2\" height=\"\\1\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";
$bb[] = "#\[fla\ width=([0-9\%]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/fla\]#si";
  $html[] = "<embed src=\"\\3\" width=\"\\1\" height=\"\\2\" wmode=\"opaque\" quality=\"high\" loop=\"true\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/go/getflashplayer\"></embed>";



  // StrawPod ���������

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
$bb[] = "#\[v=([a-zA-Z�-��-�0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"100%\" height=\"200\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\2\" />".$spc;
$bb[] = "#\[v\ height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"100%\" height=\"\\1\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\2\" />".$spc;
$bb[] = "#\[v\ width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\1\" height=\"200\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\2\" />".$spc;
$bb[] = "#\[v=([a-zA-Z�-��-�0-9\_\-\. ]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"100%\" height=\"\\2\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\3\" />".$spc;
$bb[] = "#\[v=([a-zA-Z�-��-�0-9\_\-\. ]+) width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\2\" height=\"200\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\3\" />".$spc;
$bb[] = "#\[v=([a-zA-Z�-��-�0-9\_\-\. ]+) height=([0-9]+) width=([0-9\%]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\3\" height=\"\\2\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\4&amp;w=\\3&amp;h=\\2\" />".$spc;
$bb[] = "#\[v\ height=([0-9]+) width=([0-9\%]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\2\" height=\"\\1\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\3&amp;w=\\2&amp;h=\\1\" />".$spc;
$bb[] = "#\[v=([a-zA-Z�-��-�0-9\_\-\. ]+) width=([0-9\%]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\2\" height=\"\\3\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\4&amp;w=\\2&amp;h=\\3\" />".$spc;
$bb[] = "#\[v\ width=([0-9\%]+) height=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"\\1\" height=\"\\2\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/video.txt")."&amp;file=\\3&amp;w=\\1&amp;h=\\2\" />".$spc;

### ����� �����
$bb[] = "#\[a\]([^?](?:[^\[]+|\[(?!url))*?)\[/v\]#si";
  $html[] = $spa." width=\"100%\" height=\"70\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/audio.txt")."&amp;file=\\1\" />".$spc;
$bb[] = "#\[a=([a-zA-Z�-��-�0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/a\]#si";
  $html[] = $spa." width=\"100%\" height=\"70\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/audio.txt")."&amp;file=\\2\" />".$spc;
$bb[] = "#\[a\ width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/a\]#si";
  $html[] = $spa." width=\"\\1\" height=\"70\"".$spb."<param name=\"flashvars\" value=\"comment=".@htmlspecialchars($config['home_title'])."&amp;st=".sway("data/swf/styles/audio.txt")."&amp;file=\\2\" />".$spc;
$bb[] = "#\[a=([a-zA-Z�-��-�0-9\_\-\. ]+) width=([0-9]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/a\]#si";
  $html[] = $spa." width=\"\\2\" height=\"70\"".$spb."<param name=\"flashvars\" value=\"comment=\\1&amp;st=".sway("data/swf/styles/audio.txt")."&amp;file=\\3\" />".$spc;

  // ��������� � ������ � �����
  $bb[] = "#\[b\](.*?)\[/b\]#si";
  $html[] = "<strong>\\1</strong>";
  $bb[] = "#\[i\](.*?)\[/i\]#si";
  $html[] = "<em>\\1</em>";
  $bb[] = "#\[(strong|small|em|u|s|sub|sup|ul|ol|li|p|h1|h2|h3|h4|h5|h6|h7)\](.*?)\[/\\1\]#is";
  $html[] = "<\\1\>\\2</\\1>";
  
  // ��������
  $bb[] = "#\[sm\](.*?)\[/sm\]#si";
  $html[] = "<img src=\"".sway("data/emoticons/")."\\1.gif\" border=\"0\" alt=\"\\1\" class=\"smile\">";
  $bb[] = "#\[sm=(.*?)\]#si";
  $html[] = "<img src=\"".sway("data/emoticons/")."\\1.gif\" border=\"0\" alt=\"\\1\" class=\"smile\">";

  // ����� � �������
  $bb[] = "#\[(hr|br)\]#si";
  $html[] = "<\\1>";

  // ������ �� ���������
  $bb[] = "#javascript:#si";
  $html[] = "Java Script";
  $bb[] = "#about:#si";
  $html[] = "About";
  $bb[] = "#vbscript:#si";
  $html[] = "VB Script";

  // ��������� ����
  $text = preg_replace($bb, $html, $text);

  // �������� ���� ����, ������, �������� ������ � PHP ��������
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


// �� ������� �������� ������� ������� �������, � �� ������, ���� ������ �������� !=0
function spaser($txt, $opt="") {
 if (!empty($opt)) {
  return str_replace("_", " ", $txt);
 } else {
  return str_replace(" ", "_", $txt);
 }
}


// ����
function cnops($col="", $pref="") {
global $config, $ap, $is_logged_in;

if (empty($config['bb'])) { return;}
$butcol = (!empty($col) ? $col : $config['bb_line']);
$adfld = !empty($pref) ? ",'".$pref."'" : "";

$bb_param_but = "border=\"0\" hspace=\"1\" vspace=\"1\" alt=\"bb-code\"";
$bbb = array();
$bbb[] = "<a href=\"javascript:insertext('[b]','[/b]'".$adfld.")\"><img title=\"".t("������")."\" src=\"".sway("admin/images/tags/")."b.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[i]','[/i]'".$adfld.")\"><img title=\"".t("������")."\" src=\"".sway("admin/images/tags/")."i.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[u]','[/u]'".$adfld.")\"><img title=\"".t("������������")."\" src=\"".sway("admin/images/tags/")."u.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[s]','[/s]'".$adfld.")\"><img title=\"".t("�����������")."\" src=\"".sway("admin/images/tags/")."s.gif\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"javascript:insertext('[left]','[/left]'".$adfld.")\"><img title=\"".t("������������ �����")."\" src=\"".sway("admin/images/tags/")."align_l.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[center]','[/center]'".$adfld.")\"><img title=\"".t("������������ �� ������")."\" src=\"".sway("admin/images/tags/")."align_c.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[right]','[/right]'".$adfld.")\"><img title=\"".t("������������ ������")."\" src=\"".sway("admin/images/tags/")."align_r.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[justify]','[/justify]'".$adfld.")\"><img title=\"".t("������������ �� ������")."\" src=\"".sway("admin/images/tags/")."align_j.gif\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"javascript:insertext('[br]',''".$adfld.")\"><img title=\"".t("������� ������")."\" src=\"".sway("admin/images/tags/")."br.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[hr]',''".$adfld.")\"><img title=\"".t("�����")."\" src=\"".sway("admin/images/tags/")."hr.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[sub]','[/sub]'".$adfld.")\"><img title=\"".t("�����������")."\" src=\"".sway("admin/images/tags/")."sub.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[sup]','[/sup]'".$adfld.")\"><img title=\"".t("�����������")."\" src=\"".sway("admin/images/tags/")."sup.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[p]','[/p]'".$adfld.")\"><img title=\"".t("��������")."\" src=\"".sway("admin/images/tags/")."p.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[ul]','[/ul]'".$adfld.")\"><img title=\"".t("C�����")."\" src=\"".sway("admin/images/tags/")."ul.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[li]','[/li]'".$adfld.")\"><img title=\"".t("������� ������")."\" src=\"".sway("admin/images/tags/")."li.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[color=#008000]','[/color]'".$adfld.")\"><img title=\"".t("���� ������")."\" src=\"".sway("admin/images/tags/")."color.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[size=]','[/size]'".$adfld.")\"><img title=\"".t("������ ������")."\" src=\"".sway("admin/images/tags/")."size.gif\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"javascript:insertext('[url=]','[/url]'".$adfld.")\"><img title=\"".t("������")."\" src=\"".sway("admin/images/tags/")."url.gif\" border=\"0\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[lnk=]','[/lnk]'".$adfld.")\"><img title=\"".t("�����")."\" src=\"".sway("admin/images/tags/")."lnk.gif\" border=\"0\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"javascript:insertext('[mail]','[/mail]'".$adfld.")\"><img title=\"E-mail\" src=\"".sway("admin/images/tags/")."mailto.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[img=left alt=".spaser(@htmlspecialchars(str_replace($config['delitel'], "", $config['home_title'])))."]','[/img]'".$adfld.")\"><img title=\"".t("������� �����������")."\" src=\"".sway("admin/images/tags/")."img.gif\" ".$bb_param_but."></a>";
if ($is_logged_in) {
$bbb[] = "<a href=\"#\" onclick=\"window.open('".sway("index.php?mod=img")."&amp;area=".$pref."', '_Addimage', 'height=400,resizable=yes,scrollbars=yes,width=600');return false;\" target=\"_Addimage\"><img title=\"".t("����������� �����������")."\" src=\"".sway("admin/images/tags/")."img_my.gif\" ".$bb_param_but."></a>";
}
$bbb[] = "<a href=\"javascript:insertext('[quote]','[/quote]'".$adfld.")\"><img title=\"".t("������")."\" src=\"".sway("admin/images/tags/")."q.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[code]','[/code]'".$adfld.")\"><img title=\"".t("���")."\" src=\"".sway("admin/images/tags/")."c.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[hide]','[/hide]'".$adfld.")\"><img title=\"".t("������� �����")."\" src=\"".sway("admin/images/tags/")."h.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[php]','[/php]'".$adfld.")\"><img title=\"".t("���")." PHP\" src=\"".sway("admin/images/tags/")."php.gif\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"javascript:insertext('[a=".spaser(@htmlspecialchars(str_replace($config['delitel'], "", $config['home_title'])))." width=300]','[/a]'".$adfld.")\"><img title=\"".t("�����")."\" src=\"".sway("admin/images/tags/")."a.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[v=".spaser(@htmlspecialchars(str_replace($config['delitel'], "", $config['home_title'])))." width=300 height=200]','[/v]'".$adfld.")\"><img title=\"".t("�����")."\" src=\"".sway("admin/images/tags/")."v.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"javascript:insertext('[fla=".spaser(@htmlspecialchars(str_replace($config['delitel'], "", $config['home_title'])))." width=300 height=200]','[/fla]'".$adfld.")\"><img title=\"".t("������")."\" src=\"".sway("admin/images/tags/")."f.gif\" ".$bb_param_but."></a>";

$bbb[] = "<a href=\"#\" onclick=\"window.open('".sway("admin/images/tags/")."charmap.php?area=".$pref."', '_Charmap', 'height=250,resizable=yes,scrollbars=yes,width=600');return false;\" target=\"_Charmap\"><img title=\"".t("�����������")."\" src=\"".sway("admin/images/tags/")."charmap.gif\" ".$bb_param_but."></a>";
$bbb[] = "<a href=\"#\" onclick=\"window.open('".sway("admin/images/tags/")."help_".$config['lang'].".php', '_Charmap', 'height=300,resizable=yes,scrollbars=yes,width=400');return false;\" target=\"_Help\"><img title=\"".t("������ �� BB-Code")."\" src=\"".sway("admin/images/tags/")."help.gif\" ".$bb_param_but."></a>";

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


### ������
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






### �������� ��� ���� ����� ������.
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


### ��������� ������ �� ���� swf_uploader`�
function straw_uploader ($swf_id = 0, $swf_modul = "other") {
global $config, $is_logged_in;
############################################################ load swf uploader
$out = "";
$swf_id = !empty($swf_id) ? $swf_id : 1;
$out .= "<div style=\"swf_form\">";
if ($swf_id == 1) {
    // ��� ����������� �������� ��� ���������� � ������ ��������� � ������
}

$out .= "<input type=\"submit\" style=\"swf_refresh\" value=\"".t('��������')."\" onclick=\"load_get('','','','','','','','')\"></div>";

$out .= "<div id=\"file_list_".$swf_id."\" class=\"file_list\"></div>";

############################################################ output result
return "<div id=\"straw_uploader_".$swf_id."\" class=\"straw_uploader\">".$out."</div>";
}





##############################################################################################
##############################################################################################
##############################################################################################
##############################################################################################



### ������������ ������������ �� ������
function comm_out($text, $ubb="1", $repcom="1") {
global $config;
$out = (!empty($ubb) and $ubb == 1) ? aply_bbcodes($text) : $text;
//$out = run_filters('news-comment', run_filters('news-comment-content', $out));
$out = (!empty($repcom) and $repcom == 1) ? replace_comment('show', $out) : $out;
return $out;
}

### ������������ ������������ ��� ������
function comm_in($text) {
global $config;
return replace_comment('add', $text);
}

### �������� ���������� �������� �� ��������
/*
* $numb - ���������� �������� �� ��������. ������� � ���� ���������� $number. ���� �� ������� ��������, �� ������� �������� �� ������������ - $config['arr_news']. ���� �������������, �� $number = 1;
* $usst - 1/0 - ������������ ��� �������������� ���������� � ������� ��� ���������� ������� � �������.
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

### �������������� ������ ��� ������ (��� ��������)
function straw_sort($number=1){
global $order, $lsort, $oskip, $lnk, $pnum;
if (!empty($_REQUEST)) {
$add_lnk = "";
$a_choise = array();
$s_arr = array('id', 'category', 'year', 'month', 'day', 'ip', 'author', 'name', 'ref', 'date', 'time', 'link', 'counter', 'post_id', 'comment', 'comments', 'user', 'dop', 'count', 'usergroup', 'publications', 'last_visit', 'namesender', 'emailsender', 'subjectsender', 'textsender', 'hidden', 'title', 'views');
$l_arr = array('category', 'search', 'year', 'month', 'day', 'order', 'author');
// 'mod' ����� �� ������� $l_arr ��� �� � ������� � ��������� �� ���������� ��������� �������� mod=*������*
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

### �������� �� ��������
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


$pnpout = "<div class=\"numbers\" id=\"numbers\"><div class=\"num_text\" id=\"num_text\">".t('�����')." <span>".$count."</span> ".t('��')." <span>".$number."</span> ".t('�� ��������')."</div>";
$pnpout .= "<p class=\"sideoversikt\">";
if (!empty($tpl['prev-next']['prev-link'])){ 
$pnpout .= "<a title=\"".t('������� �� ���������� ��������')."\" href=\"".$tpl['prev-next']['prev-link']."\">&laquo;</a>";
}
$pnpout .= $tpl['prev-next']['pages'];
if (!empty($tpl['prev-next']['next-link'])){
$pnpout .= "<a title=\"".t('������� �� �������� ��������')."\" href=\"".$tpl['prev-next']['next-link']."\">&raquo;</a>";
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
 * ������������ $whatfind � $text
 *
 * @link http://forum.dklab.ru/php/heap/AllocationOfResultInNaydenomAPieceOfTheText.html
 *
 * @param string $whatfind ������� �����
 * @param string $text �����, � ������� ���������� �����
 * @return string
 */
function formattext($whatfind, $text){
  $pos    = @strpos(strtoupper($text), strtoupper($whatfind));
  $otstup = 500; // ���-�� �������� ��� ������ ����������
  $result = '';
  if ($pos !== false){ //���� ������� ���������
      if ($pos < $otstup){ //���� ����������� ������ ��� ������ N ��������
          $result = substr($text, 0, $otstup * 2); //�� ��������� ��������� �� ������ � �� N-�� �������
      } else {
          $start = $pos-$otstup;
          //�� ��������� N ��������  �� ���������� � N �������� �����
          $result = '...'.substr($text, $pos-$otstup, $otstup * 2).'...';
          // ��������
      }
          $result = preg_replace('/ '.$whatfind.' /i', ' <span class="hilite" title="'.t('�� ������').' '.$whatfind.'">'.$whatfind.'</span> ', $result);

  } else {
      $result = substr($text, 0, $otstup * 2);
  }
return $result;
}


# ����� ��������� ������ �� ���� highlight
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
		$result=preg_replace("#".$i."#i", "<font class=\"hilite\" title=\"".t('�� ������').": $0\">$0</font>", $result); 
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






# ������� ������ ����� �� ������.
function texter($text, $array_walk = true){
    $text = strip_tags($text);
    $text = str_replace("\r\n", '', $text);
// $text = preg_replace('/\W/', ' ', $text);
$text = preg_replace('/[^�����Ũ��������������������������ABCDEFGHIKLMNOPQRSTUVWXYZ��������������������������������abcdefghiklmnopqrstuvwxyz]/s', ' ', $text);
// $text = strtolower($text);
$text = strtr($text, "�����Ũ��������������������������ABCDEFGHIKLMNOPQRSTUVWXYZ", "��������������������������������abcdefghiklmnopqrstuvwxyz");
  if (!empty($array_walk)){
    $text = explode(' ', $text);
    array_walk($text, 'need_mooore');
  }
return $text;
}




# ������� ������ ����� �� �����.
function urler($text){
$text = htmlspecialchars(strip_tags($text));
$text = str_replace("\r\n", '', $text);
#$text = preg_replace('/\W/', ' ', $text);
$text = strtolower($text);
return $text;
}




# ������� ���������
function search_this_cat($id){
global $category;
return ($id == $category ? ' selected' : '');
}





# �������� �������� ������
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









# ������� ���� � ��������
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







# ��������� �� ������
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










# ��������� �� ��������
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






### ������� ������� ������ �������� ���� �� ������, � ����� ������� ��������.
### �������� ���������:
###   - $text - ����������� ����� ��� �������� ����� �������� �������� �����
###   - $keywords - �������������� �������� �����. ����� ����������� � ������ ����������.
###   - $description - �������������� ��������. ����� ����������� � ������ �����������.
###                    (������, �������� �� ����� 200 ��������, ������� ���������� �����
###                     ���������� � ������ ������ ���������������)
###
### �������� ���������:
###   - $keyworker['keywords'] - �������������� �������� �����
###   - $keyworker['description'] - � ��������
###
### �������������:
###   - $meta=create_meta($text);
###   - $meta=create_meta($text, $keywords);
###   - $meta=create_meta($text, '��������������,��������,�����');
###   - $meta=create_meta($text, $keywords, $description);
###   - $meta=create_meta($text, '��������������,��������,�����', '��������������,��������');
###
### ������� �������� 7 ������� 2010 ����
### By Mr.Miksar
###
function create_meta($text, $keywords='', $description='') {
global $config;
$keyworker = array();
$dtext = array();
$kwtext = array();
$wstop = array();

### ������������ ������
$text=trim(stripslashes(preg_replace("#[\\r\\n\\t\.\!\?\�\:\;\<\>\,\+\=\"\'\&\#\$\^\%\*\(\)\�\�\{\}\@\\\/ ]#i", ' ', preg_replace("#http://(.*) #si", '', strip_tags($text)))));

### ������� ������� ���� ��� ����.
if(!empty($description)) {
  $description=trim($description).'. ';
}
if(!empty($keywords)) {
  $keywords=trim($keywords);
}


    ### ��������� ������� ����� ���� � ������� ��� ����� �� ������
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

### ���������� ���������� ���������
return $keyworker;
}




### ������ �������� � ��������� �������� �� ������� �� ��������� // Ru -> Ru by Eng translit
function transname($string) {
               $cyrillic = array("�", "�", "�","�", "�","�", "�", "�","�","�","�","�","�","�", "�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�");
               $translit = array("zh","yo","i","yu","'","ch","sh","c","u","k","e","n","g","sh","z","h","'",  "f",  "y",  "v",  "a",  "p",  "r",  "o",  "l",  "d",  "y�", "j�", "s",  "m",  "i",  "t",  "b",  "yo", "I",  "YU", "CH", "'",  "SH", "C",  "U",  "K",  "E",  "N",  "G",  "SH", "Z",  "H",  "'",  "F",  "Y",  "V",  "A",  "P",  "R",  "O",  "L",  "D",  "Zh", "Ye", "Ja", "S",  "M",  "I",  "T",  "B");
               $string = str_replace($cyrillic, $translit, $string);
               $string = preg_replace(array('@\s@','@[^A-z0-9\-_\.]+@',"@_+\-+@","@\-+_+@","@\-\-+@","@__+@"), array('_', '', "-","-","-","_"), $string);
               $string = strtolower($string);
               return($string);
}    






########################################################
 # Mr Miksar (c) 2009 - 2011
 # img resizer (thumb maker)
 # version 1.2b5
 # ������� ������ �������� �������� �� ��������� �������. ������ ������� �� ������ ��������. 
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




















### ������� ���� ��������
### $all_active = 1 - ������ ��������
### $all_active = 0 - ��� ��� ���� � ����
function count_all_entry($all_active = 0){
global $db, $config;
$active = !empty($all_active) ? "WHERE hidden='1'" : "";
$query_total = "select count(*) from ".$config['dbprefix']."news ".$active."";
$result = $db->sql_query($query_total);
return ($result ? $result : '0');
}




/* ������� ������ ������� Excel (�� .xls-�����) �� html-��������
 *����������:
$filename - ��� ����� .xls
$start_sheet - ��������� ������� ��� ����������� (��� ��������������� ������, ��������� ���������� � ����)
$tpl_row - �������� ��������� �����
$tpl_col - �������� ��������� �������
$tpl_list - �������� ��������� ������ (��� ��������������� ������)
$th - �������� ����������
 * Enhanced and maintained by Matt Kruse < http://mattkruse.com >
 * Maintained at http://code.google.com/p/php-excel-reader/
 *
 * Format parsing and MUCH more contributed by:
 * Matt Roxburgh < http://www.roxburgh.me.uk >
 *
 * Re-Make by Ant-Soft (c)
*/

function excel_view($filename, $start_sheet = 0, $tpl_row = true, $tpl_col = true, $tpl_list = true, $tpl = true) {

if (!file_exists($filename)) {echo '���� <b>'.$filename.'</b> �� ������!'; exit;}

//���������� ����������
require_once classes_directory.'/excel_reader.class.php';

//��������� .xls ���� //$config['charset'] - ���� �������� ��� ������� � ����������...
$data = new Spreadsheet_Excel_Reader($filename,$tpl,'CP1251');

//���������� �������� ��� ������
if (!isset($_GET['sheet'])) {$my_sheet = $start_sheet; } else {$my_sheet = $_GET['sheet'];}

//������� ���������� ������ � �������
$lists = $data->boundsheets;

//���� ������ ������ ������ - ��������� "��������"
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


//������� ��������� ����
return $data->dump($tpl_col,$tpl_row,$my_sheet).(!empty($number_lists) ? $number_lists : "");
}








###### ����� ���������� � ������������� Online ###



## online ## ������� � MySQL ����� (c) Mr.Miksar
## ��������� �� 18 ce������ 2010
## ��������� ����� ����������� �� ����� ����� ����������:
## $otpl['online']['all'] - �����, ���������� ������ - $otpl['online']['guest'], ������������� - $otpl['online']['users'] � ������� - $otpl['online']['robot']
function strw_online() {
global $db, $config, $ap, $sql_error_out, $modul, $otpl, $id_session, $sesuser, $sesip, $robots, $ua;
if (!empty($config['online']) and empty($ap) and $sql_error_out != "mysql") {
$namuser = "";
  if (!empty($modul)) {
  //$mtit = t('������')." ".$modul;
  $mod_id = $config['home_page']."?mod=".$modul;
  } else {
  //$mtit = t('������� ��������');
  $mod_id = $config['home_page'];
  }
  $mtit = $ua;
  

// ����� ���� ������ ������� ��� ������������ ��� ���� � ���������� ���������� �������.
$str_onl_del = 0; // ���� �� c ������������ ��������
$str_onl_gst = 0; // ���� �� �����
$str_onl_rbt = 0; // ���� �� �����
$str_onl_usr = 0; // ���� �� ������������
$str_onl_ses = 0; // ���� �� ������ �������� �����
$str_onl_id = 0;   // ������� ����� ������
$str_onl = $db->sql_query("SELECT id, cod, minute(date), user, ip FROM ".$config['dbprefix']."session");
if ($db->sql_numrows($str_onl) > 0) {
   while (list($onl_id, $onl_cod, $putdat, $onl_usr, $onl_ip) = $db->sql_fetchrow($str_onl)) {
     if ($onl_usr == "guest") $str_onl_gst++;
     if ($onl_usr == "robot") $str_onl_rbt++;
     if ($onl_usr == "user") $str_onl_usr++;
     if ($onl_ip == $sesip or $onl_cod == $id_session) { $str_onl_ses = 1; } // ������� ������� ��� ip ��� ���������� ������
     if ((date('i') - $putdat) >= $config['ses_time'] or (date('i') - $putdat) < 0) { $str_onl_del = 1; }
   }
}


if ($sesuser == "robot") {
$namuser = "".$robots['now']['name']."";
} elseif ($sesuser == "guest") {
$namuser = "Guest";
} else {
$namuser = $sesuser;
$sesuser = "user";
}


    if (empty($str_onl_ses)) {
// ���� � �������� ������������ ���� ����� ����� � ip � ����, �� ������� ���.
 $db->sql_query("INSERT INTO ".$config['dbprefix']."session VALUES (NULL, '".$id_session."', NOW(), '".$sesuser."', '".$namuser."', '".$sesip."', '".$mod_id."', '".$mtit."')");
// ���� ���������� �������� ������, ��� ��������� ��-���� ��� ������ ���� ������
// � ���� ������� �� ��������� ��� ����, ������� ��� ��� ����������� �����������, 
// �.�. ��� ������ ��� ������ ����������� � �� ����������� � ����� ��������.
 if ($sesuser == "guest" and empty($str_onl_gst)) $str_onl_gst = $str_onl_gst+1; // ���� �� ������ ��� �����
 if ($sesuser == "robot" and empty($str_onl_rbt))  $str_onl_rbt = $str_onl_rbt+1; // ���� �� ������ ��� ����� 
 if ($sesuser == "user" and empty($str_onl_usr))   $str_onl_usr = $str_onl_usr+1; // ���� �� ������ ��� ����
    } elseif (!empty($str_onl_ses)) {
// ���� � �������� ���������� ���� ���� �����, �� ������� ��� ������.
 $db->sql_query("UPDATE ".$config['dbprefix']."session SET date=NOW(), user='".$sesuser."', name='".$namuser."', iwh='".$mod_id."', dop='".$mtit."' WHERE ip='".$sesip."' OR cod='".$id_session."' ");
    }
 
// ����� �������, ��� ������������, ������� ������������� � ������� $config['ses_time'] ����� - �������� ������ - ������� �� ������ �� ���� ������ 
if (!empty($str_onl_del)) {
 $db->sql_query("DELETE FROM ".$config['dbprefix']."session WHERE date < date_sub(NOW(), interval ".$config['ses_time']." minute)");
}

// ������� ����� �����������
  $otpl['online']['guest'] = $str_onl_gst;
  $otpl['online']['robot'] = $str_onl_rbt;
  $otpl['online']['users'] = $str_onl_usr;
  $otpl['online']['all'] = $str_onl_gst + $str_onl_usr + $str_onl_rbt;

// �����
return $otpl;  
} else {
return;
}
}




### ������������
function online($oth="") {
global $config, $str_onl_gst, $str_onl_usr, $otpl;
if (!empty($config['online'])) {

  if (is_file(stpl_directory.'/online/'.$oth.'.tpl')) {
    ob_start();
    include stpl_directory.'/online/'.$oth.'.tpl';
    $onl = ob_get_contents();
    ob_end_clean();
  } else {
    $onl = "������������� ��-����: ".$otpl['online']['users'].". ������ ��-����: ".$otpl['online']['guest'].". ������� ��-����: ".$otpl['online']['robot'].". ����� �����������: ".$otpl['online']['all'];
  }
  
  return $onl;
} else {
  return;
}
} 


### ������� ����� ������ �� ������� �� $config['ses_time'] �����
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
###### // ����� ���������� � ������������� Online ###


### str_stop - ����������� ����������� substr. ��� ��������� � ����� ��������� "...".
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


### 2 ������� ��� �������� ��������...
function replace_cyr($path) {
  $search = array ("'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'�'", "'0'e");
  $replace= array ('&#1025;', '&#1040;', '&#1041;', '&#1042;', '&#1043;', '&#1044;', '&#1045;', '&#1046;', '&#1047;', '&#1048;', '&#1049;', '&#1050;', '&#1051;', '&#1052;', '&#1053;', '&#1054;', '&#1055;', '&#1056;', '&#1057;', '&#1058;', '&#1059;', '&#1060;', '&#1061;', '&#1062;', '&#1063;', '&#1064;', '&#1065;', '&#1066;', '&#1067;', '&#1068;', '&#1069;', '&#1070;', '&#1071;', '&#1072;', '&#1073;', '&#1074;', '&#1075;', '&#1076;', '&#1077;', '&#1078;', '&#1079;', '&#1080;', '&#1081;', '&#1082;', '&#1083;', '&#1084;', '&#1085;', '&#1086;', '&#1087;', '&#1088;', '&#1089;', '&#1090;', '&#1091;', '&#1092;', '&#1093;', '&#1094;', '&#1095;', '&#1096;', '&#1097;', '&#1098;', '&#1099;', '&#1100;', '&#1101;', '&#1102;', '&#1103;', '&#1105;', '0');
  return preg_replace ($search,$replace,$path);
} 
function cyr_to_chpu($str) {
  $arCyr = Array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�");
  $arLat = Array("e", "i", "ts", "u", "k", "e", "n", "g", "sh", "sch", "z", "h", "", "f", "y", "v", "a", "p", "r", "o", "l", "d", "zh", "e", "ya", "ch", "s", "m", "i", "t", "", "b", "yu", "e", "i", "ts", "u", "k", "e", "n", "g", "sh", "sch", "z", "h", "", "f", "y", "v", "a", "p", "r", "o", "l", "d", "zh", "e", "ya", "ch", "s", "m", "i", "t", "", "b", "yu");
  $str = preg_replace("/[^a-z�-��-�0-9 ]/i", "",$str);
  $str = preg_replace("/ +/", " ",$str);
  $str = str_replace($arCyr,$arLat,$str);
  if(strlen($str)<=0) return "";
  $str = preg_replace("/\w+/ei", "ucfirst('\\0')",$str);
  $str = str_replace(" ", "",$str);
  return $str;
}


### ���� ����������� mysql_escape_string, �� ����� ����...
if(!function_exists('mysql_escape_string')) {
  function mysql_escape_string($string) {
    return htmlspecialchars($string, ENT_QUOTES);
  }
}


### ���� �� ����� ����� �� ����� ��������� �������
function way($in=""){
global $addway;
 if (!empty($in)) {
   $in = (substr($in, 0, 1) == "/") ? substr($in, 1) : $in;
 }
return $addway.$in;
}
### ���� �� ����� ����� �� ����� ��������� �������
function sway($in=""){
global $addsway;
 if (!empty($in)) {
   $in = (substr($in, 0, 1) == "/") ? substr($in, 1) : $in;
 }
return $addsway.$in;
}

### ������ ������
function error_tpl($etitle="", $etext="") {
return $etitle."<br />".$etext;
}


### ������� ����� � ����������
#   $w=0 - ������� ������ ����������
#   $w=1 - ������� ������ � ������
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