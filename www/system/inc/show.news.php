<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }

/*
 * @package Show
 * @access private
 */
$query = array();
$str_up = 0;
if ((!empty($allow_full_story) or (!empty($nid) or !empty($id))) and (empty($static) or !empty($static['id']))) {
  

	        // это формирует полный текст новости


   if (!empty($post) and (!empty($nid) or !empty($_GET['url'])) and empty($static)){

	        // если новость вызвана адресным запросом
        $query = array($post);

                // +1 просмотр
        if ($sesuser != "robot") $db->sql_query("UPDATE ".$config['dbprefix']."news SET views=views+1 WHERE id='".$post['id']."'");
        


   } else if ((empty($static) or !empty($static['id']))) {

                // если новость вызвана статическим запросом
        //$arr_query = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news AS a LEFT JOIN ".$config['dbprefix']."story AS b ON (a.id=b.post_id) LEFT JOIN ".$config['dbprefix']."fields AS f ON (a.id=f.content_id) WHERE a.hidden='0' AND a.id='".(!empty($id) ? $id : ( !empty($static['id']) ? $static['id'] : 0))."' OR a.url='".(!empty($id) ? $id : ( !empty($static['id']) ? $static['id'] : 0))."' LIMIT 0,1");
        $arr_query = $db->sql_query("SELECT a.*, b.* FROM ".$config['dbprefix']."news AS a LEFT JOIN ".$config['dbprefix']."story AS b ON (a.id=b.post_id) WHERE a.hidden='0' AND a.id='".(!empty($id) ? $id : ( !empty($static['id']) ? $static['id'] : 0))."' OR a.url='".(!empty($id) ? $id : ( !empty($static['id']) ? $static['id'] : 0))."' LIMIT 0,1");
        $query = array($db->sql_fetchrow($arr_query));
        $allow_short_story = false;
        $allow_full_story = true;
   }
   
                // сигнал, что открыта новость и нумерацию выводить не надо...
        $str_up = 1;

} else {
                // это формирует основной список новостей.
                // задаем условие формирования запроса
$wheren = "";
$where = array();

     run_filters('news-where', $where);

                if (!straw_get_rights('edit_all') or !straw_get_rights('delete_all')){
      $where[] = ' a.hidden=0 ';
                } else {
      $where[] = ' (a.hidden=0 OR a.hidden=1) ';          
                }
                
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


$wheren = implode(" AND ", $where);

//////////////////// test
//echo "<br/><br/>".$wheren;
//print_r($where);
////////////////////


                // определяем формат сортировки
      if (!empty($sort) and $sort=="RAND") {       
      $sorte = "RAND()"; 
      } elseif (!empty($sort) and $sort!="RAND") {  
      $sorte = "a.sticky DESC, a.date ASC";  
      } else {
      $sorte = "a.sticky DESC, a.date DESC";
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
".(!empty($number) ? " LIMIT ".((!empty($oskip) ? $oskip : 0) .",".$number) : "")." ");

while($query[] = $db->sql_fetchrow($arr_query));
array_pop($query); // удаляем лишний
}

                // завершение формирования запросов информации












//////////////////////////////////////////////////////////////////////////////////////////////////////////////////





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




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////











if (!empty($out_content)) {

$out_put_news = ""; // зануляем! // nulled!
$fnid = array(); // зануляем номера для полей // nulled numbers for strawberry_fields

foreach ($query as $row) {
$row['category'] = substr(substr($row['category'], 0, -1), 1);
    $tpl['post']      = $row;
  //$tpl['post']['_'] = $row;
$fnid[] = $row['id'];
/////////////

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

if ($sesuser != "robot") { // Роботу предоставим статью целиком, без разделения на страницы
  $row['full'] = explode('<!--nextpage-->', $row['full']);
  $page_count  = !empty($row['full']) ? sizeof($row['full']) : 0;
  $row['full'] = $row['full'][(!empty($page) ? ($page - 1) : '0')];
  $pages       = array();

    if (!empty($page_count) and $page_count > 1){
        for ($i = 1; $i < $page_count + 1; $i++){
            if ((!empty($page) and $page == $i) or (!empty($allow_full_story) and empty($page) and $i == 1)){
                $pages[] = '<b>'.$i.'</b>';
            } else {
                $pages[] = '<a href="'.straw_get_link(array_merge($row, array('page' => $i)), 'page').'">'.$i.'</a>';
            }
        }
    }
}

/////////////

$dateheader = !empty($dateheader) ? $dateheader : "";
    if (!empty($config['date_header']) and $dateheader != langdate($config['date_headerformat'], $row['date'])){
        $tpl['post']['dateheader'] = $dateheader = langdate($config['date_headerformat'], $row['date']);
    } else {
        $tpl['post']['dateheader'] = '';
    }
    
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
                    $tpl['post']['link'][$k] = straw_get_link($row, $k);
                } else {
#zakr skobka
                    $tpl['post']['link'][$type_k.'/'.$k] = straw_get_link($row, $k, $type_k);
                }
                
            }
        }
    }

/////////////

    $tpl['post']['public'] = !empty($row['hidden']) ? "unpublished" : ""; // стиль для неопубликованных новостей (для админа)
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
    $tpl['post']['category']      = array(
                                    'name'        => join(', ', $cat['name']),
                                    'icon'        => join(', ', $cat['icon']),
                                    'id'          => join(', ', $cat['id']),
                                    'description' => join(', ', $cat['desc'])
                                    );
    $tpl['post']['short-story']   = run_filters('news-entry-content', aply_bbcodes($row['short'])); // обработка бб-кодом короткой новости
    $tpl['post']['full-story']    = run_filters('news-entry-content', aply_bbcodes($row['full'])); // обработка бб-кодом полной новости
    $tpl['post']['pages']         = !empty($pages) ? implode(' ', $pages) : ""; // если есть постраничное разделение
    $tpl['post']['alternating']   = straw_that('cn_news_odd', 'cn_news_even'); // стиль для новости (смена цвета фона)
    $tpl['post']                  = run_filters('news-show-generic', $tpl['post']); // подключение плагинов
   
    $tpl_out = !empty($template) ? $template : $tpl['template'];
    
##############
//if (!empty($config['use_dop_fields_n'])) { flds(); } // новая система доп полей. // deactivate
##############

   ob_start();
   include templates_directory.'/'.(!empty($tpl['template']) ? $tpl['template'] : 'default').'/'.(!empty($allow_full_story) ? 'full' : 'active').'.tpl';
   $out_put_news .= replace_news('show', run_filters('news-entry', ob_get_contents()));
   ob_end_clean();


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// была идея вызвать сначала шаблон и использовать его до тех пор, ///////////////////////
/////////////////// пока в новости или в категории не изменится имя шаблона новостей ////////////////////
/*
  ob_start();
   if (!empty($template) and empty($row['template']) and (empty($tpl['template']) or $tpl['template'] == $template)) {
// $f_tpl = "\$out_put_news_tpl=\"".(file_get_contents(templates_directory."/".(!empty($template) ? $template : "default")."/".(!empty($allow_full_story) ? "full" : "active").".tpl"))."\";";
// eval($f_tpl);
echo ($out_put_news_tpl);
$out_put_news .= replace_news('show', run_filters('news-entry', ob_get_contents()));
   } elseif (!empty($row['template']) or (!empty($tpl['template']) and $tpl['template'] != $template)) {
//include templates_directory.'/'.(!empty($row['template']) ? $row['template'] : $tpl['template']).'/'.(!empty($allow_full_story) ? 'full' : 'active').'.tpl';
//$out_put_news .= replace_news('show', run_filters('news-entry', ob_get_contents()));
   }
  ob_end_clean();
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}

// нумерация страниц // pages`s numbers
$pages_out = (!empty($config['cuplace']) and empty($str_up)) ? pnp("news", (!empty($pnum) ? intval($pnum) : 1), (!empty($number) ? intval($number) : 0), (!empty($template) ? $template : (!empty($tpl['template']) ? $tpl['template'] : 'default')), (!empty($wheren) ? $wheren : ''), '','', '') : "";

// сверху или снизу // up or down
echo ((!empty($config['cuplace']) and ($config['cuplace'] == 1 or $config['cuplace'] == 3)) ? $pages_out : '').strawberry_fields("news", $fnid, $out_put_news).((!empty($config['cuplace']) and ($config['cuplace'] == 2 or $config['cuplace'] == 3)) ? $pages_out : '');

}
$out_content = true;
unset($fnid, $str_up);
?>