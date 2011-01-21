<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../index.php");
exit;
}

/**
 * @package Plugins
 */

/*
Plugin Name: 	CN Функции
Plugin URI:     http://cutenews.ru
Description: 	<code>&lt;?=cn_calendar('файл'); ?&gt;</code> - календарь +.<br><code>&lt;?=cn_archives(файл); ?&gt;</code> - список месяцев.<br ><code>&lt;?=cn_category(файл); ?&gt;</code> - список категорий.<br><code>&lt;?=cn_title(файл); ?&gt;</code> - заголовки.<br>Где 'файл' - это имя файла или даже путь к файлу на который будут указывать ссылки в функциях. Если 'файл' не указать, то будет ссылка на главный файл сайта (см. настройки).
Version: 		3.1
Application: 	Strawberry
Author: 		Лёха zloy и красивый
Author URI:     http://lexa.cutenews.ru
*/




add_filter('constructor-functions', 'etc_constructor_functions');
function etc_constructor_functions($functions){
$functions['cn_calendar'] = '';
$functions['cn_archives'] = array('string', 'array');
$functions['cn_category'] = array('string', 'string', 'bool', 'int');
$functions['cn_title'] = array('string', 'bool', 'string');
return $functions;
}








/**
 * Возвращает таблицу месяца
 *
 * @return string
 */
function cn_calendar($nf="", $mod_in = ""){
global $cache, $year, $month, $day, $PHP_SELF, $config, $db;
$post_arr = array();
    if (!$post_arr = $cache->get('calendar')) {
$save = array();
$cat = array();

$cn_query = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news WHERE date < '".time."' AND hidden!=1 ORDER BY date ASC");
        while($row = $db->sql_fetchrow($cn_query))
        {
            if(!empty($row['date'])) $save[] = $row['date'];
            if(!empty($row['category'])) $cat['cat'][$row['date']] = $row['category'];
        }
        $post_arr = !empty($save) ? $cache->put(@join("\r\n", $save)) : array();            
    }

    if (!empty($post_arr) and strstr($post_arr, "\r\n")) { 
      $post_arr = explode("\r\n", $post_arr);
    } else { 
      $post_arr[] = time; 
    }

    $year  = ((!empty($_POST['year'])     and is_numeric($_POST['year']))   ? intval($_POST['year'])    : ((!empty($_GET['year'])   and is_numeric($_GET['year']))    ? intval($_GET['year']) : date("Y")));
    $month = ((!empty($_POST['month']) and is_numeric($_POST['month'])) ? intval($_POST['month']) : ((!empty($_GET['month']) and is_numeric($_GET['month'])) ? intval($_GET['month']) : date("m")));
    $day   = ((!empty($_POST['day'])      and is_numeric($_POST['day']))    ? intval($_POST['day'])     : ((!empty($_GET['day'])    and is_numeric($_GET['day']))     ? intval($_GET['day']) : date("d")));

    if (!empty($year) and !empty($month)){
        $this['month'] = $month;
        $this['year']  = $year;
    } else {
        $this['month'] = date('m', $post_arr[0]);
        $this['year']  = date('Y', $post_arr[0]);
    }

    if (!$calendar = $cache->get((!empty($day) ? $day.'.' : '').$this['month'].'.'.$this['year'])) {
    $events = array();
        foreach ($post_arr as $k=>$date){
            if (!empty($this['year']) and !empty($this['month']) and $this['year'] == date('Y', $post_arr[$k]) and $this['month'] == date('m', $post_arr[$k])){
                $events[date('j', $post_arr[$k])] = $post_arr[$k];
                $events['cat'.date('j', $post_arr[$k])] = !empty($cat['cat'][$post_arr[$k]]) ? $cat['cat'][$post_arr[$k]] : 0;  
            }
            if ($this['month'].$this['year'] != date('mY', $date)){
                $prev_next[] = $date;
            }
        }
        $prev_next[] = time();
        $calendar = $cache->put(calendar($this['month'], $this['year'], $events, $prev_next, $nf, $mod_in));
    }
//print_r($prev_next);
return $calendar;
}
####################################################################################################














####################################################################################################
/**
 * $tpl это шаблон, в котором
 * {link} это ссылка,
 * {date} - дата,
 * {count} - количество постов в категории
 *
 * @param string $tpl
 * @param array $sort
 * @return string
 */
function cn_archives($tpl = '<a href="{link}">{date} ({count})</a><br>', $sort = array('date', 'DESC'), $nf){
global $PHP_SELF, $sql, $cache, $config, $db;
static $uniqid;
    if (!$archives = $cache->get('archives', $uniqid++)){
//foreach ($sql->select(array('table' => 'news', 'select' => array('date'), 'orderby' => $sort)) as $row)
$sort = implode(" ", $sort);
$cn_query = $db->sql_query("SELECT date FROM ".$config['dbprefix']."news WHERE hidden!=1 ORDER BY ".$sort."");
        while($row = $db->sql_fetchrow($cn_query))
	{
		if (!empty($row['date']) and $arch != date('Y/m', $row['date'])){
	          $arch = date('Y/m', $row['date']);
	          $find = array('{date}', '{link}', '{count}');
	          $repl = array(_etc_lang(date('n', $row['date']), 'month').date(' Y', $row['date']), straw_get_link($row, 'month', '', $nf), count_month_entry($row['date']));
	          $archives .= str_replace($find, $repl, $tpl);
	        }
	}
		$archives = $cache->put($archives);
   }
return $archives;
}
####################################################################################################









####################################################################################################
/**
 * @see category_get_tree()
 *
 * @param string $prefix Префикс
 * @param string $tpl Шаблон
 * @param bool $no_prefix Не использовать префикс для категорий, чей родитель 0 (верхний уровень)
 * @param int $level ID категории детей которой показывать
 * @return string Список категорий по шаблону
 */
function cn_category($prefix = '&nbsp;', $tpl = '<a href="[php]straw_get_link($row, category)[/php]">{name} ([php]count_category_entry({id})[/php])</a><br>', $no_prefix = true, $level = 0, $nf=""){
global $PHP_SELF, $cache;
static $uniqid;
if (!$category = $cache->get('category', $uniqid++)){
$category = $cache->put(category_get_tree($prefix, $tpl, $no_prefix, $level));
}
return $category;
}
####################################################################################################










####################################################################################################
/**
 *
 *
 * @param string $separator Разделитель
 * @param bool $reverse Показывать в обратном порядке
 * @return string Заголовки в указаном порядке
 */
function cn_title($separator = ' &raquo; ', $reverse = false, $type = 'title', $nf = ''){
global $config, $cache, $users, $post;
static $uniqid;

	if (!$cn_title = $cache->get($type.'-'.str_replace(array('/', '?', '&', '='), '-', chicken_dick($_SERVER['REQUEST_URI'])), $uniqid++)){
        foreach ($_GET as $k => $v){
            $$k = @htmlspecialchars($v);
        }

	    $result[] = '<a href="'.$config['http_home_url'].'">'.$config['home_title'].'</a>';

        if (!empty($category)){
            if (!strstr($category, ',') and !is_numeric($category)){
                $category = category_get_id($category);
            }

            $title['category'] = explode($separator, category_get_title($category, $separator));
        }

        if (!empty($user) or !empty($author)){
            $user = (!empty($user) ? $user : $author);

            if (is_numeric($user)){
                foreach ($users as $row){
                    if ($row['id'] == $user){
                        $title['user'][]   = $row['name'];
                        $title['author'][] = $row['name'];
                    }
                }
            } else {
                $title['user'][]   = $users[$user]['name'];
                $title['author'][] = $users[$user]['name'];
            }
        }

        if (!empty($year)){
            $title['year'][] = $year;
        }

        if (!empty($month)){
            $f_num  = array('01', '02', '03', '04', '05', '06', '07', '07', '09', '10', '11', '12');
            $f_name = array('jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec');
            $replace = array(t('Январь'), t('Февраль'), t('Март'), t('Апрель'), t('Май'), t('Июнь'), t('Июль'), t('Август'), t('Сентябрь'), t('Октябрь'), t('Ноябрь'), t('Декабрь'));
            $title['month'][] = (is_numeric($month) ? str_replace($f_num, $replace, $month) : str_replace($f_name, $replace, $month));
        }

        if (!empty($day)){
            $title['day'][] = $day;
        }

	    if (!empty($id)){
	        $title['id'][] = replace_news('show', $post['title']);
	    }

	    foreach ($_GET as $k => $v){
	    	if (preg_match('/\//', $v)){
	    		$v_arr = explode('/', $v);

	            for ($i = 0; $i < count($v_arr); $i++){
	            	$uri_tmp  .= $v_arr[$i].'/';
	                $uri[$k][] = chicken_dick($uri_tmp);
	            }
	    	}

	        if (preg_match('/&/', $_SERVER['REQUEST_URI'])){
                $v_arr2 = explode('&', $v);

                for ($i = 0; $i < count($v_arr2); $i++){
                	$uri_tmp  .= $k.'='.$v_arr2[$i].'&';
                	$uri[$k][] = chicken_dick($uri_tmp, '&');
                }
	    	}
	    }

	    foreach ($_GET as $k => $row){
	    	foreach ((array)$title[$k] as $v){
	    		$array['title'][] = $v;
	    	}

	    	foreach ((array)$uri[$k] as $v){
	    		$array['uri'][] = $v;
	    	}
	    }

	    $home = straw_parse_url($config['http_home_url']);
	    $home = $home['scheme'].'://'.$home['host'].($home['port'] ? ':'.$home['port'] : '').($home['path'] ? '/'.$home['path'] : '').'/';
	     // eregi заменить потом нужно на что-нибудь быстрое
	     // заменили на preg_match
	    $home = $home.(preg_match('/&/', $_SERVER['REQUEST_URI']) ? '?' : '');

	    for ($i = 0; $i < count($array['title']); $i++){
	    	$result[] = '<a href="'.$home.$array['uri'][$i].'">'.$array['title'][$i].'</a>';
	    }

	    $result[(count($result) - 1)] = strip_tags($result[(count($result) - 1)]);

	    $cn_title = join($separator, (!empty($reverse) ? array_reverse($result) : $result));
	    $cn_title = $cache->put(($type == 'title' ? strip_tags($cn_title) : $cn_title));
	}

return $cn_title;
}
####################################################################################################











####################################################################################################
/**
 * @access private
 */
function count_month_entry($time){
global $config, $db;
$fday   = strtotime(date('m/01/Y 00:00:01', $time));
$lday   = strtotime(date('m/t/Y 23:59:59', $time));
//$result = $sql->count(array('table' => 'news', 'where' => array('date > '.$fday, 'and', 'date < '.$lday)));
$count = $db->sql_query("SELECT COUNT('id') as c FROM ".$config['dbprefix']."news WHERE date > '".$fday."' AND date < '".$lday."' AND hidden!=1");
$count = $db->sql_fetchrow($count);
return $count['c'];
}
####################################################################################################
/**
 * @access private
 */
function count_category_entry($catid){
global $config, $db;
//$result = $sql->count(array('table' => 'news', 'where' => array('category ? ['.$catid.']')));
$count = $db->sql_query("SELECT COUNT('id') as c FROM ".$config['dbprefix']."news WHERE category IN (".$catid.") AND hidden!=1");
$count = $db->sql_fetchrow($count);
return $count['c'];

}
####################################################################################################












/**
 * @access private
 */
function calendar($cal_month, $cal_year, $events, $prev_next, $nf = '', $mod_in = ''){
global $config, $year, $month, $day, $PHP_SELF, $cache, $sql, $db, $mod, $modul;
$next_of_month = 0;
$prev_of_month = 0;

    $first_of_month  = mktime(0, 0, 0, $cal_month, 7, $cal_year);
    $maxdays         = date('t', $first_of_month) + 1; // 28-31
    $cal_day         = 1;
    $weekday         = date('w', $first_of_month); // 0-6

    if (is_array($prev_next)){
	    sort($prev_next);

	    foreach ($prev_next as $key => $value){
	        if ($value < $first_of_month){
	        $pkey = !empty($key) ? $key : $key;
	        	$prev_of_month = $prev_next[$pkey];
	        }
	    }

	    rsort($prev_next);

	    foreach ($prev_next as $key => $value){
	        if ($value > $first_of_month){
	        $nkey = !empty($key) ? $key : $key;
	        	$next_of_month = $prev_next[$nkey];
	        }
	    }
    }
    

if (date(' Y', $prev_of_month) != 1970) $tomonth['prev'] = '<a href="'.straw_get_link(array('date' => $prev_of_month, 'mod' => $mod_in), 'month', '', $nf).'" title="'._etc_lang(date('n', $prev_of_month), 'month').date(' Y', $prev_of_month).'">««</a>';
if (date(' Y', $next_of_month) != 1970) $tomonth['next'] = '<a href="'.straw_get_link(array('date' => $next_of_month, 'mod' => $mod_in), 'month', '', $nf).'" title="'._etc_lang(date('n', $next_of_month), 'month').date(' Y', $next_of_month).'">»»</a>';


    $buffer = '<table border="0" class="smnbtext" width="90%" align="center">
<tr><td colspan="7" align="right" style="padding-right: 10px;">'.(!empty($tomonth['prev']) ? $tomonth['prev'] : '').' '._etc_lang(date('n', $first_of_month), 'month').' '.$cal_year.' '.(!empty($tomonth['next']) ? $tomonth['next'] : '').'</td></tr>
    <tr class="text" align="left">
     <th>'._etc_lang(1, 'weekday').'</th>
     <th>'._etc_lang(2, 'weekday').'</th>
     <th>'._etc_lang(3, 'weekday').'</th>
     <th>'._etc_lang(4, 'weekday').'</th>
     <th>'._etc_lang(5, 'weekday').'</th>
     <th>'._etc_lang(6, 'weekday').'</th>
     <th>'._etc_lang(7, 'weekday').'</th>
    </tr><tr>';

    if ($weekday > 0){
    	$buffer .= '<td colspan="'.$weekday.'"></td>';
    }

    while ($maxdays > $cal_day){
        if ($weekday == 7){
            $buffer .= '</tr><tr>';
            $weekday = 0;
        }

        # В данный день есть новость
        if (!empty($events[$cal_day])){
            $date['title'] = langdate('l, d M Y', $events[$cal_day]);
            $link = straw_get_link(array('date' => $events[$cal_day], 'mod' => $mod_in.($events['cat'.$cal_day] ? '&category='.$events['cat'.$cal_day] : '')), 'day', '', $nf);

            if ($weekday == '5' or $weekday == '6'){ // Если суббота и воскресенье. Слава КПСС!!!
				if ($day == $cal_day){
					$buffer .= '<td class="moder"><b><u><a href="'.$link.'" title="'.$date['title'].' '.t('Вы сейчас тут!').'" class="moder">'.$cal_day.'</a></u></b></td>';
               	} else {
               		$buffer .= '<td class="admin"><u><b><a href="'.$link.'" title="'.$date['title'].'" class="admin">'.$cal_day.'</a></b></u></td>';
               	}
            } else { // Рабочии дни. Вперёд, стахановцы!!!
				if ($day == $cal_day){ // активный
					$buffer .= '<td class="moder"><u><b><a href="'.$link.'" title="'.$date['title'].' '.t('Вы сейчас тут!').'" class="moder">'.$cal_day.'</a></b></u></td>';
				} else {  // пассивный, дурашка
					$buffer .= '<td><u><b><a href="'.$link.'" title="'.$date['title'].'">'.$cal_day.'</a></b></u></td>';
				}
            }
        } else { // В данный день новостей нет. Хуйовый день... Согласен!
	        if ($weekday == '5' or $weekday == '6'){ // дни, когда по телеку нихуя нет :( Даже по радио одна попса...
	            $buffer .= '<td class="admin">'.$cal_day.'</td>';
	        } else { // работяги хлещат водку после труда
	        	$buffer .= '<td>'.$cal_day.'</td>';
	        }
        }

        $cal_day++;
        $weekday++;
    }

    if ($weekday != 7){
    	$buffer .= '<td colspan="'.(7 - $weekday).'"></td>';
    }

return $buffer.'</tr></table>';
}










/**
 * @access private
 */
function _etc_lang($num, $set){

    $lang = array(
    		'month'   => array(t('Январь'), t('Февраль'), t('Март'), t('Апрель'), t('Май'), t('Июнь'), t('Июль'), t('Август'), t('Сентябрь'), t('Октябрь'), t('Ноябрь'), t('Декабрь')),
    		'weekday' => array(t('Пн'), t('Вт'), t('Ср'), t('Чт'), t('Пт'), t('Сб'), t('Вс'))
    		);

return $lang[$set][($num - 1)];
}
?>