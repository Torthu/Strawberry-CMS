<?php
#_strawberry
if (!defined("str_lang")) {
	header("Location: ../../../../index.php");
	exit;
}
////////////////////////////////////////////////////////
// Function:    totranslit
// Description: Транслит символов

function totranslit($text, $that = '-'){
global $config;

    //$text = totranslate($text, $config['lang']);
    $text = strtolower(strip_tags(html_entity_decode($text)));
    $text = preg_replace('/\W/', $that, strip_tags($text));
    $text = chicken_dick($text, $that);

return $text;
}

////////////////////////////////////////////////////////
// Function:    totranslate
// Description: Перевод слов через сервис Translate.ru

function totranslate($text, $to = 'ru'){

	if (!$fp = @fsockopen('www.translate.ru', 80)){
		return $text;
	} else {
	    $query_str = 'lang='.$to.'&status=translate&source='.urlencode($text).'&SResalt=&direction=re&template=General&image1=1';
	    $output  = "POST /text.asp HTTP/1.0\n";
	    $output .= "Host: www.translate.ru\n";
	    $output .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.5) Gecko/20031007\n";
	    $output .= "Accept: application/x-shockwave-flash,text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,image/jpeg,image/gif;q=0.2,*/*;q=0.1\n";
	    $output .= "Accept-Language: ru,en;q=0.8,en-gb;q=0.5,en-us;q=0.3\n";

	    $output .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\n";
	    $output .= "Keep-Alive: 300\n";
	    $output .= "Referer: http://www.translate.ru/text.asp\n";
	    $output .= "Content-Type: application/x-www-form-urlencoded\n";
	    $output .= "Content-Length: ".strlen($query_str)."\n";
	    $output .= "Connection: keep-alive\n\n";
	    $output .= $query_str;

	    fputs($fp, $output);

	    while(!feof($fp)){
	        $recieved .= fgets($fp, 500);
	    }

//	    ereg('<span id="r_text" name="r_text">([^<]*)</span>', $recieved, $reg);
preg_match('/<span id="r_text" name="r_text">([^<]*)<\/span>/i', $recieved, $reg);
	    return trim($reg[1]);
	}
}
?>