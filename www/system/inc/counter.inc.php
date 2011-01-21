<?php
#_strawberry
if (!defined("str_adm")) { 
header("Location: ../../index.php"); 
exit; 
}

/**
 * Функции счетчика Spesta.
 **/

########################################
// host mask # coming inside
$host="strawberry";  
// RSS-feeds (онлайновые) // non use in refferers
$rss= array ("reader/view","lenta.yandex","netvibes.com");
// generation report
$overload = 100000; # in bites
// адрес сайта, на котором установлена статистика
$hosturl="http://www.strawberry.su"; #host url
// максимум строк в real-time'овской статистике по поисковым системам
$numse=500; #from search engines in realtime stat
// количество строк в репорте, который посылается по почте
$maxreport=100; #maximum urls in report
// посылать еженедельный репорт?
$mailreport = "yes";  # yes/no
// посылать еженедельный репорт по этому адресу
$mailaddress = "miksar@mail.ru"; #mail report to this address
// сохранять еженедельные репорты в директорию /report
$savereports = "yes"; # yes/no
// посылать отчеты в зазипованном виде. 
$zipreport="yes"; # yes/no
// показывать счетчик
$showcounter= "no"; # yes/no
// не считать "посетителей" с этих user-agent'ов (т.е. роботы поисковиков)
$robots="Robots: Googlebot, Yandex, Aport, StackRambler";
// flock true/false
$flock =FALSE;
$version="1.4R build 05.10.2007";
########################################

$isreport = !empty($_GET['isreport']) ? 1 : (!empty($_POST['isreport']) ? 1 : 0);
//$isreport = 1;
### функция подсчета.
function strw_spesta() {
global $out, $filterfile, $rtran, $typo, $spider_here, $host, $rss, $overload, $hosturl, $numse, $maxreport, $mailreport, $mailaddress, $savereports, $zipreport, $showcounter, $robots, $flock, $version;

$pg=$_SERVER['REQUEST_URI'];
$ref=str_replace ("<","",trim(getref_all(),"/"));

// REQUEST_URI bugfix
$url=parse_url($pg);
$pg=$url['path'].(!empty($url['query']) ? "?".$url['query'] : "");

// Online RSS readers
foreach ($rss as $key=>$value) { 
if (stristr($ref, $value)) { $ref=""; }
}

// LJ refferers "fix"
if (stristr ($ref, "livejournal.com")) {
 if (stristr ($ref, "friends")) {
   $ref="livejournal.com (friends)";
 } else {
   $ref=preg_replace ("/\?(.*)/","",$ref);
 }
}

// WEB_SPIDERS CHECK
if (!empty($spider_here)) {
 loggg (db_directory."/agents.txt", getagent());
} else {

	$url = urldecode($ref);
	$req=1;
	if (!empty($url) and (!stristr($url, $host))) {

		if (stristr($url, "=")) {
			if(stristr($url, "yand")) { $sw = "text="; $engine = "Y"; } else
			if(stristr($url, "google.")) { $sw="q="; $engine = "G"; } else
			if ((stristr($url, "go.mail.ru")) and (stristr($url, "q="))) { $sw="q="; $engine = "M"; } else
			if(stristr($url, "rambler")) { $sw = "words="; $engine = "R"; } else
			if(stristr($url, "sm.aport")) { $sw="r="; $engine = "A"; } else
			if(stristr($url, "search.yahoo")) { $sw="p="; $engine = "H"; } else
			if(stristr($url, "q=")) { $sw="q="; $engine = "?"; }
		}

		

		        if (isset($engine))	{
			  $req=0;
			  $url2=utf8_convert(strip_tags(stripslashes(urldecode($url))));
			  eregi ($sw."([^&]*)", $url2."&", $url2);
			  $url2=strip_tags ($url2[1]);
			     if (strlen ($url2)>2) {
				$fullfile="[<small><b><a href=\"".$ref."\">".$engine."</a></b></small>] : <a href=\"".$pg."\" title=\"".date("d.m.Y H:i")."\">".(wordwrap ($url2,20))."</a><br>";
				$ya= SP_fr(db_directory."/se.txt");
				$ya= explode("\n", $ya);
				@array_unshift ($ya, $fullfile);
				if (count($ya)>$numse) unset ($ya[$numse]);
				$ya = @array_slice ($ya, 0, 100);
				$output = @array_slice ($ya, 0, 10); 
				$fullfile=implode ("\n", $ya);
				SP_fw(db_directory."/se.txt", $fullfile);
				$fullfile=implode ("\n", $output);
				SP_fw(db_directory."/se10.txt", $fullfile);
				loggg(db_directory."/words.txt", $url2);
			     }
			} else {
			        loggg(db_directory."/referers.txt", $url);
			}
	}

	// REQUEST URL
	loggg(db_directory."/request.txt", (urldecode($pg)));
	// в этом файле содержатся уникальные ip-шники за день и показания счетчика 
	$COUNT_FILE = db_directory."/count.txt"; 
	$message="";
	// $ip - это тот самый айпишник, прокси учитываются
	$ip=getip();
	// вычисляем дату.
	$datum=date("d.m.Y", time); 
	// открываем файл 
	$conts= SP_fr($COUNT_FILE); 
	// date|unique-1|today-2|total-3|search engines-4|other sites-5|homepage-6| text-7
	// считываем кол-во хитов, уникумов и пр. 
	$counts= explode("|",$conts); 
	// полночь. ;) // счетчик обнуляется. 
	if ($counts[0]!=$datum) {
	  $m = $counts[0]."|".$counts[1]."|".$counts[2]."|".$counts[3]."|".$counts[4]."|".$counts[5]."|".$counts[6]; 
	  $counts[0]=$datum; $counts[1]=$counts[2]=$counts[4]=$counts[5]=$counts[6]=$counts[7]=0;
	}
	// уникумы
	$ips=loggg(db_directory."/ip.txt", $ip);
	if ($ips>1000) exit();
	if ($ips == false) {
		$counts[1]++;
	// UNIQUE USER-AGENT
		loggg (db_directory."/agents.txt", $_SERVER['HTTP_USER_AGENT']);
	// FROM SEARCH ENGINES
		if(isset($engine)) { $counts[4]++; }
	// from other sites
		$counts[5]=$counts[5]+$req; 
	}

	// увеличение хитов 
	$counts[2]++; $counts[3]++;
	if (($pg=="/") or ($pg=="")) $counts[6]++; #home

	// search engines
	// ФОРМАТ СЧЕТЧИКА
	// date|unique-1|today-2|total-3|search engines-4|other sites-5|homepage-6| text-7
	// 1 - уникальных, 2 - хитов сегодня, 3 - хитов всего, 4 - с поисковиков сегодня, 5 - с других сайтов сегодня, 6 - на домашней странице сегодня

	$_GET['spesta']=$out['counter']="<b>".$counts[3]."</b> / ".$counts[2]." / <b>".$counts[1]."</b> / ".$counts[5]." / ".$counts[4]; 

	if (!empty($showcounter) and $showcounter=="yes") echo $out['counter'];
	$content=$counts[0]."|".$counts[1]."|".$counts[2]."|".$counts[3]."|".$counts[4]."|".$counts[5]."|".$counts[6];
	if ($content=="||||||") exit ();
	SP_fw($COUNT_FILE, $content);

	///  MIDNIGHT

	   if (!empty($m)) {
		if ($m!="||||||") {
			$fp = fopen(db_directory."/days.txt", "a");
			fwrite($fp, $m."\n");
			fclose($fp);
		}
		SP_fw(db_directory."/ip.txt", "");
		// по понедельникам - отчет
		// ну или если файлы раздулись
		if ((date("w")==1) and !empty($mailreport) and $mailreport=="yes") {
		  include includes_directory."/spesta/report.php";
		} else {
		  if (filesize(db_directory."/words.txt")>$overload || filesize (db_directory."/referers.txt")>$overload || filesize (db_directory."/agents.txt")>$overload || filesize (db_directory."/request.txt")>$overload) {
		   include includes_directory."/spesta/report.php"; 
		  }
		}
	   }
	}
}


////////////////////////////////////////////////////////////////

function SP_file ($name, $w = "\n")	{
  $content = str_replace("\r", '', SP_fr($name));
  return explode($w, $content);
}

////////////////////////////////////////////////////////////////

function SP_fr($inc_file) {
clearstatcache();
$fp = @fopen($inc_file, "rb"); 
@flock($fp, LOCK_SH); 
$conts = @fread($fp, filesize($inc_file)); 
@fclose($fp); 
@flock($fp, LOCK_UN); 
return $conts;
}

////////////////////////////////////////////////////////////////

function SP_fw($inc_file, $text, $add=0) {
$z=rand(0,10000);
  if (!empty($add)) {
$fp = @fopen($inc_file, "a"); 
@fwrite($fp,$text);
@fclose($fp); 
  } else {
$fp = @fopen($inc_file.".".$z, "wb+");
@fwrite($fp,$text);
@fclose($fp);
if (rename($inc_file.".".$z, $inc_file)==false) {  }
  }
}











////////////////////////////////////////////////////////////////

function filt($a, $filter) {
global $what, $z, $z2, $total, $filtered, $item;
if ($item!="") { $filter=array ($item); }
$z="";
$z2="";
$arr="";
$total=0;
$filtered=0;
if (chop ($a[0])=="") {$fr=1; } else { $fr=0; }
for ($i=$fr; $i<count ($a); $i=$i+2) {
$zz = str_replace ("www.", "", $a[$i]);
if ((stristr ($a[$i],"www.")) and (array_search ($zz, $a))) { $a[$i]=$zz; } else {$zz=$a[$i];}
$arr[$zz]=(!empty($arr[$zz]) ? $arr[$zz]+$a[$i+1] : $a[$i+1]); 
}

@arsort ($arr);

if (!empty($arr)) {
foreach ($arr as $key => $value) {

$total=$total+$value;
$del=false;
$fil="";

if (!empty($filter[0])) {
 for ($ii=count ($filter)-1; $ii>=0; $ii--) {
  if (@stristr (chop($key), chop ($filter[$ii]))) { 
	if ($del==false) { $filtered=$filtered+$value; }
	$del=true;  $fil=$filter[$ii]; 
  }
 }
}

if  ($what!="onlyfiltered") {
if (empty($del)) { $z[$key]=$value; }
if (!empty($del)) { $z2[$fil]=$value+$z2[$fil]; }
//NO FILTER - HOSTS
 if (empty($filter[0])  and (stristr ($key, "http"))) {
  $tmp=@parse_url(chop($key)."/");
  $z2[$tmp['host']]= (!empty($tmp['host']) and !empty($z2[$tmp['host']])) ? $value+$z2[$tmp['host']] : $value; 
 }
} else {
  if (!empty($del)) { 
    $z[$key]=$value; 
    echo $a[$i]; 
  }
}
	}
}
@arsort ($z, SORT_NUMERIC );
@arsort ($z2, SORT_NUMERIC );
}

////////////////////////////////////////////////////////////////

function fout ($z, $l=true, $max=1000000, $isip=false) {
global $total, $hosturl, $totalip, $isreport, $report, $host;
$tt=1;
$out="<table border=\"0\" width=\"100%\" cellspacing=\"2\" cellpadding=\"2\">
<tr>
<td width=\"10\"><b>№</b></td>
<td width=100%><b>Значение</b></td>
<td width=10><b>Хитов</b></td>
<td width=5><b>%</b></td>
</tr>";

if (is_array ($z)) foreach ($z as $key => $value) {
if ($value==="") break;
$out.="<tr><td><b>".$tt."</b></td><td>";

$key=strip_tags ($key);
$k=$key;

if (!empty($l)) {
 if (stristr ($key, "http://")) { 
  $out.="<a HREF='".$key."'>".$key."</a>"; 
 } else { 
  $out.="<a HREF='".$hosturl.$key."'>".$key."</a>"; 
 }
} else 

// $out.=$key;
$out.="<a href=\"./?what=onlyfiltered&report=".$report."&item=".$key."\">$key</a>";
$out.="</td><td>".$value."</td><td>".@(round ((($value/$total)*100),2))."</td></tr>";
$tt++;
if ($tt>$max) break;
}
$out.="</table>";
return $out;
}

////////////////////////////////////////////////////////////////

function outt($name, $maxr, $l=true, $isip=false, $cfile="", $filterfile="") {
global $isreport, $what, $filter, $a, $z, $z2, $total, $filtered, $report;

$filter=SP_file($filterfile);
$a=SP_file($cfile);
filt($a, $filter);

$out="<h1>".$name."</h1><p><table width=\"100%\" cellspacing=\"7\" cellpadding=\"10\" class=\"frm4\" bgcolor=\"white\"><tr><td valign=\"top\" width=\"200\" class=\"frm5\">";
$out.= "<p><b>Фильтр:</b>";

if (empty($isreport)) {
$out.=" [ <a href=\"edit.php?cfile=".$cfile."\" onclick=\"window.open(this.href,this.target,'width=500,height=350,'+'location=no,toolbar=no,menubar=no,status=yes');return false;\">изменить</a> ]<br>";
  if ($what=="") { 
    $out.= " [ <a href=\"./?what=onlyfiltered&report=".$report."\">показать&nbsp;отфильтрованное</a> ] "; 
  } else { 
    $out.= " [ <a href=\"./?report=".$report."\">спрятать&nbsp;отфильтрованное</a> ]"; 
  }
}

$out.= "<br><br>".(fout ($z2, false, $maxr, $isip)).
"<p><b>Total: ".$total."</b><br><b>Отфильтровано: ".$filtered."</b> ( ".(@intval($filtered/$total*100)).
"% ) </td><td valign=\"top\" class=\"frm5\"><b>Хитов:</b> <br><br><br>".(fout ($z, $l, $maxr, $isip))."</table>";
return $out;
}

////////////////////////////////////////////////////////////////

function loggg($inc_file, $value) {
$value=trim($value);
$fil = SP_file($inc_file);
$n=array_search($value, $fil);

 if ($n!==false) {
   $fil[$n+1]++;
 } else {
   $fil[]=$value;
   $fil[]=1;
 }

$fullfile=implode("\n", $fil);
SP_fw($inc_file, $fullfile);

  if (!empty($n)) {
    return $fil[$n+1];
  } else {
    return false;
  }

}

////////////////////////////////////////////////////////////////

function logg($inc_file, $value) {
$fp = @fopen($inc_file, "a");
fwrite($fp, $vaule."\n");
fclose($fp);
}

////////////////////////////////////////////////////////////////

?>