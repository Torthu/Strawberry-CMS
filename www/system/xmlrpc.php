<?php
/**
 * @package Private
 * @access private
 */

#_strawberry
include_once 'head.php';
if (!defined("str_adm")) { header("Location: ../index.php"); exit; }

header('Content-Type: text/xml; charset='.$config['charset']);

include_once includes_directory.'/xmlrpc.inc.php';

function XMLRPC_symbol_decoder($str){return totranslit($str);}

$xmlrpc_request = XMLRPC_parse($HTTP_RAW_POST_DATA);
$methodName 	= XMLRPC_getMethodName($xmlrpc_request);
$params 		= XMLRPC_getParams($xmlrpc_request);

$methods = array(
	'blogger.getUsersBlogs'     => 'blogger_getUsersBlogs',

	'metaWeblog.getPost'        => 'metaWeblog_getPost',
	'metaWeblog.getRecentPosts' => 'metaWeblog_getRecentPosts',
	'metaWeblog.getCategories'  => 'metaWeblog_getCategories',

	'metaWeblog.newPost'        => 'metaWeblog_newPost',
	'metaWeblog.editPost'       => 'metaWeblog_editPost',
	'blogger.deletePost'        => 'metaWeblog_deletePost',
	'metaWeblog.deletePost'     => 'metaWeblog_deletePost',

	'demo.SayHello'		    => 'demo_SayHello',

	'method_not_found'          => 'method_not_found'
);

function demo_SayHello($params){
	XMLRPC_response('Hello Dolly ;).');
}

function blogger_getUsersBlogs($params){
global $config;

	if (!check_login($params[1], md5x($params[2]))){
		XMLRPC_error(666, t('Неправильное имя пользователя или пароль!'));
		exit;
	}

	XMLRPC_response(array(
		XMLRPC_prepare(array(array(
		'url'      => $config['http_home_url'],
		'blogName' => $config['home_title'],
		'blogid'   => '1'
		))
	)));
}

function metaWeblog_getPost($params){
global $sql;

	if (!check_login($params[1], md5x($params[2]))){
		XMLRPC_error(666, t('Неправильное имя пользователя или пароль!'));
		exit;
	}

    $query = $sql->select(array(
    	     'table'   => 'news',
    	     'orderby' => array('id', 'DESC'),
    	     'join'    => array('table' => 'story', 'where' => 'id = post_id'),
    	     ));

	foreach($query as $row){
        $post['postid'] = $row['id'];
	$post['dateCreated'] = XMLRPC_convert_timestamp_to_iso8601($row['date']);
        $post['title'] = $row['title'];
	$post['description'] = replace_news('admin', $row['short']);
	$posts[] = $post;
	}

	XMLRPC_response(XMLRPC_prepare($posts), WEBLOG_XMLRPC_USERAGENT);
}

function metaWeblog_editPost($params){
global $sql, $config, $users, $member;
global $id, $added_time, $title, $short_story, $category, $mod;

	if (!check_login($params[1], md5x($params[2]))){
		XMLRPC_error(666, t('Неправильное имя пользователя или пароль!'));
		exit;
	}

	if (!$params[3]['title']){
		XMLRPC_error(666, t('Заголовок не может быть пустым!'));
		exit;
	}

    foreach ($sql->select(array('table' => 'categories')) as $row){
        $cat_name[XMLRPC_symbol_decoder($row['name'])] = $row['id'];
    }

    if ($params[3]['categories']){
	    foreach ($params[3]['categories'] as $category){
	        $categories[] = $cat_name[XMLRPC_symbol_decoder($category)];
	    }
    }

    $id = $params[0];
	$title       = $params[3]['title'];
	$short_story = $params[3]['description'];
	$category    = chicken_dick(@join(',', $categories), ',');
	$mod 		 = 'editnews';

	if (!straw_get_rights('edit_all')){
		XMLRPC_error(666, t('Вам запрещён доступ к этому модулю!'));
		exit;
	}

    run_actions('xmlrpc-edit-save-entry');

	$sql->update(array(
	'table'  => 'news',
	'where'  => array("id = $id"),
	'values' => array(
	            'title'    => replace_news('add', $title),
	            'c_short'    => strlen(replace_news('add', $short_story)),
	            'c_full'     => strlen(replace_news('add', $full_story)),
	            'category' => $category,
	            'url'      => ($url ? straw_namespace($url) : straw_namespace(totranslit($title))),
	            'hidden'   => straw_get_rights('approve_news')
	            )
	));

	$sql->update(array(
	'table'  => 'story',
	'where'  => array("post_id = $id"),
	'values' => array(
	            'short' => replace_news('add', $short_story),
	            'full'  => replace_news('add', $full_story)
	            )
	));

	XMLRPC_response(XMLRPC_prepare(true), WEBLOG_XMLRPC_USERAGENT);
}

function metaWeblog_deletePost($params){
global $sql, $users;

	if (!check_login($params[2], md5x($params[3]))){
		XMLRPC_error(666, t('Неправильное имя пользователя или пароль!'));
		exit;
	}

	if (!straw_get_rights('delete_all')){
		XMLRPC_error(666, t('Вам запрещён доступ к этому модулю!'));
		exit;
	}

    $sql->delete(array(
	'table' => 'news',
	'where' => array("id = $params[1]")
	));

    $sql->delete(array(
    'table' => 'story',
    'where' => array("post_id = $params[1]")
    ));

    $sql->delete(array(
    'table' => 'comments',
    'where' => array("post_id = $params[1]")
    ));

	XMLRPC_response(XMLRPC_prepare(true), WEBLOG_XMLRPC_USERAGENT);
}

function metaWeblog_getRecentPosts($params){
global $sql;

	if (!check_login($params[1], md5x($params[2]))){
		XMLRPC_error(666, t('Неправильное имя пользователя или пароль!'));
		exit;
	}

    foreach ($sql->select(array('table' => 'categories')) as $row){
        $cat_name[$row['id']] = XMLRPC_symbol_decoder($row['name']);
    }

    $query = $sql->select(array(
    	     'table'   => 'news',
    	     'orderby' => array('id', 'DESC'),
    	     'join'    => array('table' => 'story', 'where' => 'id = post_id'),
    	     'limit'   => array(0, $params[3])
    	     ));

	foreach($query as $row){
        $post['postid']      = $row['id'];
		$post['dateCreated'] = XMLRPC_convert_timestamp_to_iso8601($row['date']);
        $post['title'] 		 = $row['title'];
        $post['link'] 		 = straw_get_link($row);
        $post['permaLink']   = straw_get_link($row);
		$post['description'] = replace_news('admin', $row['short']);
		$post['categories']  = array();

		foreach (explode(',', $row['category']) as $category){
			$post['categories'][] = $cat_name[$category];
		}

		$posts[] = $post;
	}

	XMLRPC_response(XMLRPC_prepare($posts), WEBLOG_XMLRPC_USERAGENT);
}

function metaWeblog_newPost($params){
global $sql, $config, $users, $member;
global $id, $added_time, $title, $short_story, $category, $mod;

	if (!check_login($params[1], md5x($params[2]))){
		XMLRPC_error(666, t('Неправильное имя пользователя или пароль!'));
		exit;
	}
	if (!$params[3]['title']){
		XMLRPC_error(666, t('Заголовок не может быть пустым!'));
		exit;
	}

    foreach ($sql->select(array('table' => 'categories')) as $row){
        $cat_name[XMLRPC_symbol_decoder($row['name'])] = $row['id'];
    }

    if ($params[3]['categories']){
	    foreach ($params[3]['categories'] as $category){
	        $categories[] = $cat_name[XMLRPC_symbol_decoder($category)];
	    }
    }

	if (!$params[3]['dateCreated']){
		$params[3]['dateCreated'] = (time() + $config['date_adjust'] * 60);
	} else {
		$params[3]['dateCreated'] = ($params[3]['dateCreated'] + $config['date_adjus'] * 60);
	}

    $id          = $sql->last_insert_id('news', '', 'id') + 1;
	$added_time  = $params[3]['dateCreated'];
	$title       = $params[3]['title'];
	$short_story = $params[3]['description'];
	$category    = chicken_dick(@join(',', $categories), ',');
	$mod 		 = 'addnews';

	if (!straw_get_rights('addnews', 'write')){
		XMLRPC_error(666, t('Вам запрещён доступ к этому модулю!'));
		exit;
	}

	run_actions('xmlrpc-new-save-entry');

	$sql->insert(array(
	'table'  => 'news',
	'values' => array(
	            'date'     => $added_time,
	            'author'   => $member['username'],
	            'title'    => replace_news('add', iconv("utf-8",$config['charset'],$title)),
	            'short'    => strlen(replace_news('add', iconv("utf-8",$config['charset'],$short_story))),
	            'full'     => strlen(replace_news('add', iconv("utf-8",$config['charset'],$full_story))),
	            'category' => $category,
	            'url'      => ($url ? straw_namespace($url) : straw_namespace(totranslit($title))),
	            'hidden'   => straw_get_rights('approve_news')
	            )
	));

//     $post_title = iconv("utf-8",$_s['encoding'],$post_title);
// $post_body = iconv("utf-8",$_s['encoding'],$post_body);
   
   $sql->insert(array(
	'table'  => 'story',
	'values' => array(
				'post_id' => $id,
	            'short'   => replace_news('add', iconv("utf-8",$config['charset'],$short_story)),
	            'full'    => replace_news('add', iconv("utf-8",$config['charset'],$full_story))
	            )
	));

	run_actions('xmlrpc-new-save-entry');

	foreach ($sql->select(array('table' => 'users')) as $row){
	    $sql->update(array(
	    'table'  => 'users',
	    'where'  => array("username = $member[username]"),
	    'values' => array('publications' => sizeof($sql->select(array('table' => 'news', 'where' => array("author = $member[username]")))))
	    ));
	}

	XMLRPC_response(XMLRPC_prepare($id), WEBLOG_XMLRPC_USERAGENT);
}

function metaWeblog_getCategories($params){
global $sql;

	if (!check_login($params[1], md5x($params[2]))){
		XMLRPC_error(666, t('Неправильное имя пользователя или пароль!'));
		exit;
	}

    foreach ($sql->select(array('table' => 'categories')) as $row){
        $cat['categoryId']  = $row['id'];
        $cat['title']       = XMLRPC_symbol_decoder($row['name']);
        $cat['description'] = XMLRPC_symbol_decoder($row['name']);
        $cats[]             = $cat;
    }

	XMLRPC_response(XMLRPC_prepare($cats), WEBLOG_XMLRPC_USERAGENT);
}

function method_not_found($methodName){
	XMLRPC_error(666, t('Вызываемая процедура "%method" не существует!', array('method' => $methodName)));
}

if (!$methods[$methodName]){
	$methods['method_not_found']($methodName);
} else {
	$methods[$methodName]($params);
}
?>