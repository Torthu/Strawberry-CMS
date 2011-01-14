<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../index.php"); exit; }

/*
 * @package Show
 * @access private
 */
//////////////////////////// OPTIONS ////////////////////

$feed_tpl = 'feed_rss'; // select theme for this feed


////////////////////////////////////////////////////////////
$number = (!empty($_GET['number']) and is_numeric($_GET['number'])) ? $_GET['number'] : $config['rss_news'];
$category = (!empty($config['rss_cat']) ? $config['rss_cat'] : '0'); // кака€ категори€ новостей будет показана.

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="'.$config['charset'].'"?>';
?>

<rss version="2.0">
<channel>

<title><?php echo $config['home_title']; 
 if (!empty($nid)) { echo " ".$config['delitel']." ".metainfo('title'); } 
 ?></title>
<link><?php echo $config['http_home_url']; ?></link>
<description><?php echo $config['description'];  
 if (is_numeric($nid)) { 
 echo " ".$config['delitel']." ".metainfo('description'); 
 } else { 
 echo " ¬ ленте запрошено новостей: ".$number; 
 }
?></description>
<generator><?php echo $config['version_name'].' '.$config['version_id']; ?></generator>
<copyright><?php echo $config['home_author']; ?></copyright>
<language><?php echo $config['lang']; ?></language>


<?php


// если категори€ задана
if (!empty($category)) {
  $category_tmp = "";
// если категори€ указана типом url
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




 $allow_comment_form = false;
 $allow_comments = false;
  
if (empty($static) and (!empty($nid) or !empty($id))) {
  $allow_full_story   = true;
  $allow_active_news  = false;
} else {
  $allow_full_story   = false;
  $allow_active_news  = true;
}







## если ошибка подключени€ к базе отсутствует, то выполн€ем процедуру
if ($sql_error_out != "mysql") {

   ## определение корневого параметра ссылки
   $link = (empty($link)) ? 'home' : $link;

   ## определение уникального кеша
   $cache_uniq = md5($cache->touch_this().$_SERVER['REQUEST_URI'].(!empty($member['usergroup']) ? $member['usergroup'] : '').(!empty($post['id']) ? $post['id'] : ''));

   ## ѕодключаем вывод информации
   if (!$output = $cache->get('show', $cache_uniq)) {
     ob_start();
     include includes_directory.'/show.feed.inc.php';
     $output = $cache->put(ob_get_contents());
     ob_end_clean();
   }

   ## ќсновной вывод информации
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

</channel>
</rss>