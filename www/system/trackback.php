<?php
/**
 * @package Show
 * @access private
 */

#_strawberry
include_once 'head.php';
if (!defined("str_adm")) { header("Location: ../index.php"); exit; }

foreach($_GET as $k => $v){
	$$k = (empty($v) ? $$k : @htmlspecialchars($v));
}

foreach($_POST as $k => $v){
	$$k = (empty($v) ? $$k : @htmlspecialchars($v));
}

function trackback_response($error = 0, $error_message = ''){
global $config;
	if (!empty($error)){
		echo '<?xml version="1.0" encoding="'.$config['charset'].'"?'.">\n";
		echo "<response>\n";
		echo "<error>1</error>\n";
		echo "<message>$error_message</message>\n";
		echo "</response>";
	} else {
		echo '<?xml version="1.0" encoding="'.$config['charset'].'"?'.">\n";
		echo "<response>\n";
		echo "<error>0</error>\n";
		echo "</response>";
	}
	exit;
}

if (empty($_POST)){
	trackback_response(1, 'XML-RPC server accepts POST requests only.');
}

if (!function_exists('trackback_send')){
	trackback_response(1, 'Sorry, we are not want a new TrackBacks.');
}

$query = $sql->select(array('table' => 'news', 'where' => array("id = $id", 'or', "url = $id")));

if (!is_array($query[0])){
	trackback_response(1, 'Sorry, post not found.');
}

if (function_exists('mb_convert_encoding')){
	$charset   = ($charset ? $charset : 'auto');
	$blog_name = mb_convert_encoding($blog_name, $config['charset'], $charset);
	$title     = mb_convert_encoding($title, $config['charset'], $charset);
	$excerpt   = mb_convert_encoding($excerpt, $config['charset'], $charset);
} elseif (function_exists('iconv')){
	$charset   = ($charset ? $charset : 'utf-8');
        $blog_name = iconv($charset, $config['charset'], $blog_name);
        $title     = iconv($charset, $config['charset'], $title);
        $excerpt   = iconv($charset, $config['charset'], $excerpt);
}

$blog_name  = (!empty($blog_name) ? $blog_name : 'no name');
$url        = (!empty($url) ? $url : 'none');
$excerpt    = (!empty($excerpt) ? $excerpt : 'ugly idiot');
$xfields    = new XFieldsData();
$post       = $xfields->fetch($query['id'], 'trackback');
$post[time] = array(
			  'blog_name' => replace_comment('add', $blog_name),
			  'url'       => str_replace('&', '&amp;', $url),
			  'host'      => $_SERVER['REMOTE_ADDR'],
			  'title'     => replace_comment('add', $title),
			  'excerpt'   => replace_comment('add', preg_replace("/(.*?)\n\n..../i", '\\1', $excerpt)),
			  'charset'   => $charset
			  );
$xfields->set($post, $query[0]['id'], 'trackback');
$xfields->save();

header('Content-Type: text/xml;');
trackback_response();
?>