<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }

/**
 * @package Show
 * @access private
 */
$query = array();
$str_up = 0;
$it_is_feed = 1;
  if (!empty($allow_active_news) or !empty($allow_full_story)){
######################################


$query = array();
//$category = !empty($category) ? $category : "";

if ((!empty($allow_full_story) or (!empty($nid) or !empty($id))) and (empty($static) or !empty($static['id']))) {


	        // если новость вызвана адресным запросом
         $query = array($post);


} else {


                // это формирует основной список новостей.
                // задаем условие формирования запроса
$wheren = "";
$where = array();

     run_filters('news-where', $where);

      $where[] = ' a.hidden=0 ';

                   if (!empty($user) or !empty($author)){
      $where[] = " a.author = '".(!empty($author) ? $author : $user)."'";
                   }

                   if (!empty($year) and empty($month)){
      $where[] = ' a.date > '.@mktime(0, 0, 0, 1, 1, $year).' AND a.date < '.@mktime(23, 59, 59, 12, 31, $year);
                   } elseif (!empty($year) and !empty($month) and empty($day)){
      $where[] = ' a.date > '.@mktime(0, 0, 0, $month, 1, $year).' AND a.date < '.@mktime(23, 59, 59, $month, 31, $year);
                   } elseif (!empty($year) and !empty($month) and !empty($day)){
      $where[] = ' a.date > '.@mktime(0, 0, 0, $month, $day, $year).' AND a.date < '.@mktime(23, 59, 59, $month, $day, $year);
                   } else {
      $where[] = ' a.date < '.time;
                   }
  

                // Запрос категорий переписан. 06.12.2009
    if (!empty($category)){
                // удаляем из запроса возможные пробелы
     $category = str_replace(' ', '', $category);
                // Начало формирования условия по категоии    
      $catarr = explode(',', $category);
      $cvc = count($catarr);
      $cvs = 0;
      $czo = "";
           foreach ($catarr as $cat_var) {
                if (!empty($cat_var)) {
                     $cvs++;
                     if ($cvs != ($cvc+1) and $cvs != 1) { $czo .= ' OR '; }
                     $czo .= "a.category LIKE '%,".$cat_var.",%'";
                }
           }
     $where[]  = " (".$czo.")";
                // Конец формирования условия по категоии
  } else {
  $category = "";
  }
  
  $wheren = $wheren.implode(" AND ", $where);
//echo $wheren;
  
  
                // определяем формат сортировки
      if (!empty($sort) and $sort=="RAND") {       
      $sorte = "RAND()"; 
      } elseif (!empty($sort) and $sort!="RAND") {  
      $sorte = "a.date ASC";  
      } else {
      $sorte = "a.date DESC";
      }



                // определяем формат нумерации
      if (empty($number) or !is_numeric($number)) { $number = 0; }
      

                // создаем запрос с вышеуказанными параметрами

  $pnum = (isset($_GET['pnum']) and is_numeric($_GET['pnum'])) ? intval($_GET['pnum']) : "1";
  $oskip = ($pnum-1) * $number;
  $oskip = !empty($skip) ? intval($skip) : intval($oskip);
  
$arr_query = $db->sql_query("SELECT a.*, b.* FROM ".$config['dbprefix']."news AS a 
LEFT JOIN ".$config['dbprefix']."story AS b ON (b.post_id=a.id)
WHERE ".$wheren." ORDER BY ".$sorte." 
".(!empty($number) ? " LIMIT ".((!empty($oskip) ? $oskip : 0) .", ".$number) : "")." ");

while($query[] = $db->sql_fetchrow($arr_query));
array_pop($query); // удаляем лишний

}

                // завершение формирования запросов информации








                // Если вдруг новость отсутствует или не опубликована, то новость не найдена.
$out_content = true;
if (is_array($query)) {
   if (!reset($query)) {
      if (!empty($allow_full_story)){
       echo "<div class=\"error_message\">".t('Информация не найдена.')."</div>";
       $allow_comments = false;
       $allow_comment_form = false;
       $out_content = false;
       $query = array();
      }
    return;
   }
} else {
       echo "<div class=\"error_message\">".t('Информации за данный период не обнаружено.')."</div>";
       $allow_comments = false;
       $allow_comment_form = false;
       $out_content = false;
    return;
}







if (!empty($out_content)) {

$out_put_news = ""; // зануляем! // nulled!
$fnid = array();
//print_r($query);
foreach ($query as $row) {
$row['category'] = substr(substr($row['category'], 0, -1), 1);
    $tpl['post']      = $row;
  //$tpl['post']['_'] = $row;
$fnid[] = $row['id'];
/////////////

    if ($cat_arr = explode(',', $row['category'])){
        $cat = array();

        foreach ($cat_arr as $v){
            $cat['id'][]   = $v;
            $cat['name'][] = (!empty($categories[$v]['name']) ? '<a href="'.straw_get_link($categories[$v], 'category').'" title="'.replace_news('admin', $categories[$v]['description']).'">'.$categories[$v]['name'].'</a>' : '');
            $cat['icon'][] = (!empty($categories[$v]['icon']) ? '<a href="'.straw_get_link($categories[$v], 'category').'"><img src="'.$categories[$v]['icon'].'" alt="" border="0" align="absmiddle" alt="'.replace_news('admin', $categories[$v]['description']).'"></a>' : '');
            $cat['desc'][] = $categories[$v]['description'];
        }
    }






    foreach ((!empty($rufus_file) ? $rufus_file : parse_ini_file(rufus_file, true)) as $type_k => $type_v){
        if (is_array($type_v)){
            foreach ($type_v as $k => $v){
            
                if ($type_k == 'home'){
                    $tpl['post']['link'][$k] = straw_get_link($row, $k);
                } else {
#zakr skobka
                    $tpl['post']['link'][$type_k.'/'.$k] = straw_get_link($row, $k, $type_k);
                }
                
            }
        }
    }



    $tpl['post']['homepage']    = $users[$row['author']]['homepage'];
    $tpl['post']['avatar']      = $config['path_userpic_upload'].'/'.$row['author'].'.'.$users[$row['author']]['avatar'];
    $tpl['post']['icq']         = $users[$row['author']]['icq'];
    $tpl['post']['location']    = $users[$row['author']]['location'];
    $tpl['post']['about']       = run_filters('news-entry-content', $users[$row['author']]['about']);
    $tpl['post']['lj-username'] = '<a href="http://'.$users[$row['author']]['lj_username'].'.livejournal.com/profile"><img src="system/skins/images/user.gif" alt="[info]" align="absmiddle" border="0"></a><a href="http://'.$users[$row['author']]['lj_username'].'.livejournal.com">'.$users[$row['author']]['lj_username'].'</a>';
    $tpl['post']['author']      = $users[$row['author']]['name'];
    $tpl['post']['username']    = $users[$row['author']]['username'];
    $tpl['post']['user-id']     = $users[$row['author']]['id'];
    $tpl['post']['views'] = !empty($id) ? ($row['views']+1) : $row['views'];
    $tpl['post']['title']         = run_filters('news-entry-content', $row['title']);
    $tpl['post']['date']          = langdate($config['timestamp_active'], $row['date']);
    $tpl['post']['f_date']          = $row['date'];
    $tpl['post']['category']      = array(
                                    'name'        => join(', ', $cat['name']),
                                    'icon'        => join(', ', $cat['icon']),
                                    'id'          => join(', ', $cat['id']),
                                    'description' => join(', ', $cat['desc'])
                                    );
    $tpl['post']['f_category'] = $row['category'];
    $tpl['post']['short-story']   = run_filters('news-entry-content', aply_bbcodes($row['short']));
    $tpl['post']['full-story']    = run_filters('news-entry-content', aply_bbcodes($row['full']));                               
    $tpl['post']['alternating']   = straw_that('cn_news_odd', 'cn_news_even');
    $tpl['post']                  = run_filters('news-show-generic', $tpl['post']);


   ob_start();
    include stpl_directory.'/'.((!empty($feed_tpl)) ? $feed_tpl : 'feed_rss').'/'.(!empty($allow_full_story) ? 'full' : 'active').'.tpl';
    $tpl['news'] = array();
    $output = ob_get_contents();
   ob_end_clean();
    $out_put_news .= replace_news('show', run_filters('news-entry', $output))."\n\n";

}

echo $out_put_news;


}
$out_content = true;

######################################
    }
    

?>