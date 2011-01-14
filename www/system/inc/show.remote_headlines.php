<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }

/*
 * @package Show
 * @access private
 */
$query = array();

if ((!empty($nid) or !empty($id)) and (empty($static) or !empty($static['id']))) {
  

	        // это формирует полный текст новости


   if (!empty($post) and (!empty($nid) or !empty($_GET['url'])) and empty($static)){

	        // если новость вызвана адресным запросом
         $query = array($post);

                // +1 просмотр
        //$db->sql_query("UPDATE ".$config['dbprefix']."news SET views=views+1 WHERE id='".$post['id']."'");


   } else if ((empty($static) or !empty($static['id']))) {

                // если новость вызвана статическим запросом
        $arr_query = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news AS a LEFT JOIN ".$config['dbprefix']."story AS b ON (a.id=b.post_id) LEFT JOIN ".$config['dbprefix']."fields AS f ON (a.id=f.content_id) WHERE a.hidden='0' AND a.id='".(!empty($id) ? $id : ( !empty($static['id']) ? $static['id'] : 0))."' OR a.url='".(!empty($id) ? $id : ( !empty($static['id']) ? $static['id'] : 0))."' LIMIT 1");
        $query = array($db->sql_fetchrow($arr_query));
        $allow_short_story = true;
        $allow_full_story = false;
   }


} else {

                // это формирует основной список новостей.
                // задаем условие формирования запроса
$wheren = "";
$where = array();
      $where[] = run_filters('news-where', $wheren);

      $wheren = ' a.hidden=0 ';

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


$sort = !empty($sort) ? $sort : "";

                // определяем формат сортировки
      if (!empty($sort) and $sort=="RAND") {       
      $sorte = "RAND()"; 
      } elseif (!empty($sort) and $sort!="RAND") {  
      $sorte = "a.date ASC";  
      } else {
      $sorte = "a.date DESC";
      }


                // определяем формат нумерации
      if (empty($number) or !is_numeric($number) or !empty($id) or !empty($nid)) { $number = 1; }


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
      if ($allow_full_story){
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

foreach ($query as $row) {
$row['category'] = substr(substr($row['category'], 0, -1), 1);
    $tpl['post']      = $row;
  //$tpl['post']['_'] = $row;


    if (!empty($row['template'])) {
        $tpl['template'] = $row['template'];
    } elseif (!in_array(basename($_SERVER['PHP_SELF']), run_filters('unset-template', array())) and empty($static) and (!empty($categories[$category]['template']) or !empty($categories[$row['category']]['template']))){
    
        if (!empty($categories[$category]['template'])){
            $tpl['template'] = $categories[$category]['template'];
        } elseif (!empty($categories[$row['category']]['template'])) {
            $tpl['template'] = $categories[$row['category']]['template'];
        }
        
    } else {
        $tpl['template'] = $template;
    }
    
/////////////

if (!empty($tpl['template']) and !empty($config['mytheme']) and is_dir(templates_directory.'/'.$tpl['template']."_".$config['mytheme'])) {
$tpl['template'] = $tpl['template']."_".$config['mytheme'];
} elseif (!empty($tpl['template']) and is_dir(templates_directory.'/'.$tpl['template']) and $tpl['template'] != "default") {
$tpl['template'] = $tpl['template'];
} else {
$tpl['template'] = "default";
}


/////////////

    if ((!empty($categories[$category]['usergroups']) and !in_array($member['usergroup'], explode(',', $categories[$category]['usergroups']))) or (!empty($categories[$row['category']]['usergroups']) and !in_array($member['usergroup'], explode(',', $categories[$row['category']]['usergroups'])))){
      $count--;
      continue;
    }

/////////////


  $row['full'] = "";

   
/////////////

    if ($cat_arr = explode(',', $row['category'])){
        $cat = array();

        foreach ($cat_arr as $v){
            $cat['id'][]   = $v;
            $cat['name'][] = ($categories[$v]['name'] ? '<a href="'.straw_get_link($categories[$v], 'category').'" title="'.replace_news('admin', $categories[$v]['description']).'">'.$categories[$v]['name'].'</a>' : '');
            $cat['icon'][] = ($categories[$v]['icon'] ? '<a href="'.straw_get_link($categories[$v], 'category').'"><img src="'.$categories[$v]['icon'].'" alt="" border="0" align="absmiddle" alt="'.replace_news('admin', $categories[$v]['description']).'"></a>' : '');
            $cat['desc'][] = $categories[$v]['description'];
        }
    }
    
/////////////


    if (!empty($is_logged_in)){
        if (straw_get_rights('edit_all') or straw_get_rights('delete_all') or ((straw_get_rights('edit') or straw_get_rights('delete')) and $member['username'] == $row['author'])){
            $tpl['post']['if-right-have'] = true;
        } else {
            $tpl['post']['if-right-have'] = false;
        }

        $tpl['if-logged'] = true;
    } else {
        $tpl['if-logged'] = false;
    }

/////////////

    if (!empty($users[$row['author']]['mail']) and empty($users[$row['author']]['hide_mail'])){
        $tpl['comment']['mail'] = $users[$row['author']]['mail'];
    }
    
/////////////
    
    if (!empty($users[$row['author']]['icq'])){
        $tpl['comment']['icq'] = $users[$row['author']]['icq'];
    }

/////////////




    foreach ((!empty($rufus_file) ? $rufus_file : parse_ini_file(rufus_file, true)) as $type_k => $type_v){
        if (is_array($type_v)){
            foreach ($type_v as $k => $v){
            
                if ($type_k == 'home'){
                    $tpl['post']['link'][$k] = "".straw_get_link($row, $k);
                } else {
#zakr skobka
                    $tpl['post']['link'][$type_k.'/'.$k] = "".straw_get_link($row, $k, $type_k);
                }
                
            }
        }
    }



   $tpl['post']['author']      = $users[$row['author']]['name'];
    $tpl['post']['username']    = $users[$row['author']]['username'];
    $tpl['post']['user-id']     = $users[$row['author']]['id'];

    $tpl['post']['title']         = run_filters('news-entry-content', $row['title']);
    $tpl['post']['date']          = langdate($config['timestamp_active'], $row['date']);
    $tpl['post']['category']      = array(
                                    'name'        => join(', ', $cat['name']),
                                    'icon'        => join(', ', $cat['icon']),
                                    'id'          => join(', ', $cat['id']),
                                    'description' => join(', ', $cat['desc'])
                                    );
    $tpl['post']['short-story']   = run_filters('news-entry-content', aply_bbcodes($row['short']));
    $tpl['post']['full-story']    = ""; 


    $tpl_out = !empty($template) ? $template : $tpl['template'];
##############
//if (!empty($config['use_dop_fields_n'])) { flds(); } // новая система доп полей.
##############

   ob_start();
   include templates_directory.'/'.(!empty($tpl['template']) ? $tpl['template'] : 'default').'/'.((!empty($tout) and is_file(templates_directory.'/'.(!empty($tpl['template']) ? $tpl['template'] : 'default').'/active-'.$tout.'.tpl')) ? 'active-'.$tout : 'active').'.tpl';
   $out_put_news .= replace_news('show', run_filters('news-entry', ob_get_contents()));
   ob_end_clean();

}


// output content in titles
echo $out_put_news;


}
$out_content = true;

?>