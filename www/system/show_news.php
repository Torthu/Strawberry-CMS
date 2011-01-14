<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../index.php"); exit; }


/*
 * @package Show
 * @access private
 */


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



$category = !empty($category) ? cheker($category) : (!empty($_REQUEST['category']) ? cheker($_REQUEST['category']) : 0);
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
if (empty($static) and (!empty($nid) or !empty($id))) {

  $allow_comment_form = true;
  $allow_full_story   = true;
  $allow_active_news  = false;
  $allow_comments     = true;

} else {

  $allow_comment_form = false;
  $allow_full_story   = false;
  $allow_active_news  = true;
  $allow_comments     = false;
  
}



## если ошибка подключения к базе отсутствует, то выполняем процедуру
if ($sql_error_out != "mysql") {

   ## определение корневого параметра ссылки
   $link = (empty($link)) ? 'home' : $link;

   ## определение шаблона новости
   if (empty($template) or strtolower($template) == 'default' or is_file(templates_directory.'/'.$template) or !is_dir(templates_directory.'/'.$template)) { $template = 'default'; }
   
   ## определение уникального кеша
   $cache_uniq = md5($cache->touch_this().$_SERVER['REQUEST_URI'].(!empty($member['usergroup']) ? $member['usergroup'] : '').(!empty($post['id']) ? $post['id'] : ''));

   ## Подключаем вывод информации
   if (!$output = $cache->get('show', $cache_uniq)) {
     ob_start();
     include includes_directory.'/show.inc.php';
     $output = $cache->put(ob_get_contents());
     ob_end_clean();
   }

   ## Основной вывод информации
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