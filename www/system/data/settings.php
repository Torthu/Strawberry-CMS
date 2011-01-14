<?php
#_strawberry
	if (!defined("str_conf")) {
	header("Location: ../../index.php");
	exit;
	}
$array = array (
  'uhtaccess' => NULL,
  'Blocks' => 
  array (
    'blocks' => 'example/bookmarks|example/calendar|example/content/do/archives|example/content/do/category|example/content/do/else|example/content/do/keywords|example/content/do/map|example/content/do/registration|example/calendar|example/content/do/search|example/content/do/users|example/donate|example/headlines|example/links|example/main|example/menu|example/meta|example/output|example/pages|example/submenu|example/top5|example/useful|example/users',
    'example/head' => 'example/meta',
    'example/left' => 'example/useful|example/links|example/donate|example/users',
    'example/menu' => 'example/menu',
    'example/content' => 'example/submenu',
    'example/bottom' => 'example/output',
  ),
  'linkwords' => 
  array (
    0 => 
    array (
      0 => 'klubnika',
      1 => 'http://strawberry.goodgirl.ru/',
    ),
    1 => 
    array (
      0 => 'Клубника',
      1 => '	http://strawberry.goodgirl.ru/',
    ),
  ),
  'CommSpy' => 
  array (
    'subj' => 'На сайте {page-title} новый комментарий от {author}',
    'body' => 'Здравствуйте!{nl}{nl}Вы подписались на получение новых комментариев с сайта {page-title}. Кто-то оставил там новый комментарий.{nl}{nl}{nl}Комментарий:{nl}----------------{nl}{comment}{nl}{nl}{nl}URL: {link}',
  ),
  'registration' => 
  array (
    '127.0.0.1' => 
    array (
      'warns' => 0,
    ),
  ),
  'BarWord' => 
  array (
    1 => 'сука',
    2 => 'пидар',
    3 => 'бля',
    4 => 'fuck',
  ),
);

?>