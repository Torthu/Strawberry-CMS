<?php
/**
 * @package Show
 * @access private
 */
#_strawberry
include_once 'head.php';
if (!defined("str_adm")) { header("Location: ../index.php"); exit; }

@ignore_user_abort(true);
@set_time_limit(600);

      function highlight_search($output){
      global $search;
      $output = format_text($output, $search);
      return $output;
      }

$search = htmlspecialchars(texter((!empty($_POST['search']) ? $_POST['search'] : (!empty($_GET['search']) ? $_GET['search'] : '')), false));

$sday = array();
$smonth = array();
$syear = array();

$search_query = $db->sql_query("SELECT MAX(date) AS maxid, MIN(date) AS minid FROM ".$config['dbprefix']."news LIMIT 1 ");
$s_query = $db->sql_fetchrow($search_query);

$sday[] = '';
for ($i = 1; $i < 32; $i++){
  $sday[$i] = $i;
}

$smonth[] = '';
for ($i = 1; $i < 13; $i++){
  $smonth[$i] = $i;
}

$syear[] = '';
for ($i = date('Y', $s_query['minid']); $i <= date('Y', $s_query['maxid']); $i++){
  $syear[$i] = $i;
}
?>










<!-- КОД ПОИСКА / НАЧАЛО  -->
<form method="get" action="<?php echo $PHP_SELF; ?>">
<input name="mod" type="hidden" value="search">

<table width="99%" border="0" cellspacing="1" cellpadding="1" class="text" align="center">
<tr>
<td width="40%" colspan="2"><nobr><?php echo t("Поиск"); ?> (<?php echo t("не менее 3-х символов"); ?>)</nobr></td>
<td width="60%" colspan="2"><input class="regtext" type="text" name="search" size="20" value="<?php echo $search; ?>"></td>
</tr>
<tr>
<td><nobr><?php echo t("В категории"); ?></nobr></td>
<td><select class="regtext" size="1" name="category"><option value="0"><?php echo t('во всех'); ?></option><?php echo category_get_tree('&nbsp;', '<option value="{id}"[php]search_this_cat({id})[/php]>{prefix}{name}</option>'); ?></select></td>
<td><nobr><?php echo t("За дату"); ?> (<?php echo t("год"); ?>/<?php echo t("месяц"); ?>/<?php echo t("день"); ?>)</nobr></td>
<td><?php 
echo makeDropDown($syear, 'year', (!empty($year) ? $year : 2000)); ?>/<?php 
echo makeDropDown($smonth, 'month', (!empty($month) ? $month : 0)); ?>/<?php 
echo makeDropDown($sday, 'day', (!empty($day) ? $day : 0)); ?></td>
</tr>
<tr>
<td colspan="4" align="center"><input class="regok" type="submit" value=" <?php echo t("ИСКАТЬ"); ?> "></td>
</tr>
</table>
</form>
<!-- КОД ПОИСКА / КОНЕЦ -->








<?php
echo '<br><div class="reztit">'.t('Результаты поиска').':</div><br>';
if (!empty($search) and strlen($search) < 3) {
  
      ########## Менее 3 символов
      echo '<br><div class="moder">'.t('Запрос должен состоять минимум из трёх знаков.').'</div><br>';

} elseif (!empty($search) and strlen($search) >= 3) {

      ########## ПОИСК В БАЗЕ ##################################################

///////////////////////////////////////

               // это формирует основной список новостей.
                // задаем условие формирования запроса

$wheren = "";
$where = array();
      run_filters('news-where', $where);

                if (!straw_get_rights('edit_all') or !straw_get_rights('delete_all')){
      $where[] = ' a.hidden=0';
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
          


$where[] = " (a.title LIKE '%".$search."%' OR b.short LIKE '%".$search."%' OR b.full LIKE '%".$search."%')";



                // Запрос категорий переписан. 06.12.2009
$category = !empty($_REQUEST['category']) ? cheker($_REQUEST['category']) : (!empty($category) ? cheker($category) : 0);
  if (!empty($category)){
                // удаляем из запроса возможные пробелы
     $category = str_replace(' ', '', $category);
                // Начало формирования условия по категоии    
      $catarr = explode(',', $category);
      $cvc = count($catarr);
      $cvs = '0';
      $czo = "";
           foreach ($catarr as $cat_var) {
                if (!empty($cat_var)) {
                     $cvs++;
                     if ($cvs != ($cvc+1) and $cvs != 1) { $czo .= ' OR '; }
                     $czo .= "a.category LIKE '%,".$cat_var.",%'";
                }
           }
     $where[]  = " (".$czo.")";
                // Коец формирования условия по категоии
  } else {
  $category = "";
  }

$wheren = $wheren.implode(" AND ", $where);
//echo $wheren.$number;

## определяем режим просмотра информации (полная или короткая новость)

  $allow_comment_form = false;
  $allow_full_story   = false;
  $allow_active_news  = true;
  $allow_comments     = false;
  




   ## определение корневого параметра ссылки
   if (empty($link)) { $link = 'home'; }


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

///////////////////////////////////////


  if (empty($query)){
        
            ############ Нет результатов
            echo '<div class="moder">'.t('Поиск не дал результатов.').'</div><br>';
            
 } elseif (!empty($query)) {

add_filter('news-entry-content', 'highlight_search');

if ($sql_error_out != "mysql") {

   ## определение корневого параметра ссылки
   if (empty($link)) { $link = 'home'; }

   ## определение шаблона новости
   if (empty($template) or strtolower($template) == 'search' or is_file(templates_directory.'/'.$template) or !is_dir(templates_directory.'/'.$template)) { $template = 'search'; }
   
   ## определение уникального кеша
   $cache_uniq = md5($cache->touch_this().$_SERVER['REQUEST_URI'].(!empty($member['usergroup']) ? $member['usergroup'] : '').(!empty($post['id']) ? $post['id'] : ''));

   ## Подключаем вывод информации
   if (!$output = $cache->get('show', $cache_uniq) and ($sql_error_out != "mysql")) {
     ob_start();
########################################







$out_put_news = ""; // зануляем! // nulled!

foreach ($query as $row) {
$row['category'] = substr(substr($row['category'], 0, -1), 1);
$tpl['post']      = $row;



if (empty($template) or strtolower($template) == 'search' or is_file(templates_directory.'/'.$template) or !is_dir(templates_directory.'/'.$template)) { 
$template = 'search'; 
}


if (!empty($template) and is_dir(templates_directory.'/'.$template."_".$config['mytheme'])) {
$tpl['template'] = $template."_".$config['mytheme'];
} elseif (!empty($template) and is_dir(templates_directory.'/'.$template) and $template != "default") {
$tpl['template'] = $template;
} else {
$tpl['template'] = "default";
}


    if ((!empty($categories[$category]['usergroups']) and !in_array($member['usergroup'], explode(',', $categories[$category]['usergroups']))) or (!empty($categories[$row['category']]['usergroups']) and !in_array($member['usergroup'], explode(',', $categories[$row['category']]['usergroups'])))){
      $count--;
      continue;
    }


  $row['full'] = explode('<!--nextpage-->', $row['full']);
  $page_count  = !empty($row['full']) ? sizeof($row['full']) : 0;
  $row['full'] = $row['full'][(!empty($page) ? ($page - 1) : '0')];
  $pages       = array();

    if ($page_count > 1){
        for ($i = 1; $i < $page_count + 1; $i++){
            if (($page and $page == $i) or ($allow_full_story and !$page and $i == 1)){
                $pages[] = '<b>'.$i.'</b>';
            } else {
                $pages[] = '<a href="'.straw_get_link(array_merge($row, array('page' => $i)), 'page').'">'.$i.'</a>';
            }
        }
    }
    
$dateheader = !empty($dateheader) ? $dateheader : "";
    if ($config['date_header'] and $dateheader != langdate($config['date_headerformat'], $row['date'])){
        $tpl['post']['dateheader'] = $dateheader = langdate($config['date_headerformat'], $row['date']);
    } else {
        $tpl['post']['dateheader'] = '';
    }

    if ($cat_arr = explode(',', $row['category'])){
        $cat = array();

        foreach ($cat_arr as $v){
            $cat['id'][]   = $v;
            $cat['name'][] = ($categories[$v]['name'] ? '<a href="'.straw_get_link($categories[$v], 'category').'" title="'.replace_news('admin', $categories[$v]['description']).'">'.$categories[$v]['name'].'</a>' : '');
            $cat['icon'][] = ($categories[$v]['icon'] ? '<a href="'.straw_get_link($categories[$v], 'category').'"><img src="'.$categories[$v]['icon'].'" alt="" border="0" align="absmiddle" alt="'.replace_news('admin', $categories[$v]['description']).'"></a>' : '');
            $cat['desc'][] = $categories[$v]['description'];
        }
    }

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



$tpl['post']['public'] = !empty($row['hidden']) ? "unpublished" : "";
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
    $tpl['post']['title']         = strip_tags(run_filters('news-entry-content', $row['title']));
    $tpl['post']['date']          = langdate($config['timestamp_active'], $row['date']);
    $tpl['post']['category']      = array(
                                    'name'        => join(', ', $cat['name']),
                                    'icon'        => join(', ', $cat['icon']),
                                    'id'          => join(', ', $cat['id']),
                                    'description' => join(', ', $cat['desc'])
                                    );
    $tpl['post']['short-story']   = run_filters('news-entry-content', aply_bbcodes($row['short']));
    $tpl['post']['full-story']    = run_filters('news-entry-content', aply_bbcodes($row['full']));                               
    $tpl['post']['pages']         = join(' ', $pages);
    $tpl['post']['alternating']   = straw_that('cn_news_odd', 'cn_news_even');
    $tpl['post']                  = run_filters('news-show-generic', $tpl['post']);
    $tpl_out = !empty($template) ? $template : $tpl['template'];
##############
//if (!empty($config['use_dop_fields_n'])) { flds(); } // новая система доп полей.
##############

   ob_start();
   include templates_directory.'/'.(!empty($tpl['template']) ? $tpl['template'] : 'default').'/'.(!empty($allow_full_story) ? 'full' : 'active').'.tpl';
   $out_put_news .= replace_news('show', run_filters('news-entry', ob_get_contents()));
   ob_end_clean();
}

if (!empty($_REQUEST)) {
$a_lnk = array();
foreach ($_REQUEST as $skey => $sval){
  if (in_array($skey, array('search', 'category', 'year', 'month', 'day'))){
$a_lnk[] = $skey."=".$sval;
  }
}
$lnk = ((!empty($config['mod_rewrite']) or !empty($config['mod_rewrite_lite'])) ? '?' : '&').implode("&", $a_lnk);
}


//echo $wheren;
// нумерация страниц // pages`s numbers
$pages_out = !empty($config['cuplace']) ? 
pnp("news", (!empty($pnum) ? intval($pnum) : 1), 
(!empty($number) ? intval($number) : 0), 
(!empty($template) ? $template : (!empty($tpl['template']) ? $tpl['template'] : 'default')), 
(!empty($wheren) ? $wheren : ''), '','', $lnk) : "";

// сверху или снизу // up or down
echo ((!empty($config['cuplace']) and ($config['cuplace'] == 1 or $config['cuplace'] == 3)) ? $pages_out : '').$out_put_news.((!empty($config['cuplace']) and ($config['cuplace'] == 2 or $config['cuplace'] == 3)) ? $pages_out : '');




########################################
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
 
         }
  }
  
  
  





#-------------------------------------------------------------------------------
# 
# all old functions in function.inc.php
# strawberry 1.2 ;)
#
#-------------------------------------------------------------------------------

?>