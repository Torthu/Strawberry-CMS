<?php
$ap="";
#_strawberry
include_once 'head.php';
if (!defined("str_adm")) { header("Location: ../index.php"); exit; }



/**
 * @package Show
 * @access private
 */

//////////////////////  OPTIONS //////////////////////

/*
Как использовать этот модуль.
Вставьте этот код:
<script language="javascript" src="http://example.com/путь/до/remote_headlines.php"></script>
Можно использовать разное кол-во новостей:
http://example.com/путь/до/remote_headlines.php?rh_num=NUMBER_OF_NEWS
Можно использовать и категории:
http://example.com/путь/до/remote_headlines.php?rh_num=NUMBER_OF_NEWS&rh_cat=CAT_ID
Можно использовать и кодировку(utf-8 или windows-1251):
http://example.com/путь/до/remote_headlines.php?rh_num=NUMBER_OF_NEWS&rh_cat=CAT_ID&rh_char=utf-8
Можно использовать вывод заголовков в виде обычного html списка (поумолчанию выводится в обычном файле шаблона). (т.о. можно парсить ссылки):
http://example.com/путь/до/remote_headlines.php?rh_num=NUMBER_OF_NEWS&rh_cat=CAT_ID&rh_char=utf-8&rh_out=html
http://example.com/путь/до/remote_headlines.php?rh_num=NUMBER_OF_NEWS&rh_cat=CAT_ID&rh_char=utf-8&rh_out=java
( Примечание: Вы можете создать много вариантов оформления вывода. Например, если вы укажите &rh_out=php, 
то будет запрошен файл из шаблонов remote_headlines/active-php.tpl. В этом файле вы можете офомить вывод новостей
как php код. Тогда тем, кто читает вашу ленту, можно импортировать содеримое по ссылке и вывести через функцию eval().)
Можно отдельную новость (при наличии параметра &id=ID_OF_NEWS). Выводится в вызваном шаблоне.
Шаблон для мода по умолчанию remote_headlines
Ниже вы можете задать сколько заголовков по умолчанию выводить и имя шаблона поумолчанию...
*/

//////////////////////  OPTIONS //////////////////////

$def_num = 7;// Сколько заголовков выводить по умолчанию
$def_tpl = 'remote_headlines'; // Шаблон по умолчанию

////////////////////////////////////////////////////////
$static['template'] = !empty($def_tpl) ? $def_tpl : 'remote_headlines';
$static['number'] = (!empty($_GET['rh_num']) and is_numeric($_GET['rh_num'])) ? $_GET['rh_num'] : $def_num;
$static['category'] = (!empty($_GET['rh_cat']) ? cheker($_GET['rh_cat']) : (!empty($config['rss_cat']) ? $config['rss_cat'] : 0));
$static['char'] = !empty($_GET['rh_char']) ? cheker($_GET['rh_char']) : $config['charset'];
$static['tout'] = !empty($_GET['rh_out']) ? cheker(strtolower($_GET['rh_out'])) : '';
$static['id'] = !empty($id) ? $id : (!empty($nid) ? $nid : '');

// определяем статические переменные.
if (!empty($static) and is_array($static)) {
    foreach ($vars as $k => $v){
       if ($v != 'static' and $v != 'id' and $v != 'nid') { 
         unset($$v); 
       }
    }
    foreach ($static as $k => $v) { 
    $$k = $v;
    }
} else {
$static = array();
}




//$category = !empty($category) ? cheker($category) : (!empty($_REQUEST['category']) ? cheker($_REQUEST['category']) : 0);
// если категория задана
if (!empty($category)) {
  $category_tmp = "";
// если категория указана типом url
  if (!strstr($category, ',') and !is_numeric($category)) {
  $category = category_get_id($category);
  }

// если заданно несколько категорий 
foreach (explode(',', str_replace(' ', '', $category)) as $cat) {
$category_tmp .= category_get_children($cat).',';
}

// формирование категорий в виде x,x,x,x
  $category_tmp = chicken_dick($category_tmp, ','); // убираем повторы
  $category  = (!empty($category_tmp) ? $category_tmp : $category);
}


## определяем режим просмотра информации (полная или короткая новость)
 $allow_comment_form = false;
 $allow_comments = false;
 $allow_full_story   = false;
 $allow_active_news  = true;



## если ошибка подключения к базе отсутствует, то выполняем процедуру
if ($sql_error_out != "mysql") {

   ## определение корневого параметра ссылки
   $link = (empty($link)) ? 'home' : $link;

   ## Подключаем вывод информации
   ob_start();
 include includes_directory."/show.remote_headlines.php";
 $output = ob_get_contents();
   ob_end_clean();
   } else {
 $output = t('Сервер недоступен!');   
   }

   ## Основной вывод информации
   if (!empty($char) and !empty($config['charset']) and $char != $config['charset']) {
 echo @iconv($config['charset'], $char, $output);
   } else {
 echo $output;
   }



## сброс динамических переменных
if ($vars = run_filters('unset', $vars)) { 
   foreach ($vars as $var) { 
       if ($var != 'nid') {  
         unset($$var); 
       }       
   }
}



## сброс переменных
unset($category_tmp, $parent, $no_prev, $no_next, $prev, $var);
$static = array();

?>