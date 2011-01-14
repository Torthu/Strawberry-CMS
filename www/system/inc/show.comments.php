<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }

/**
 * @package Show
 * @access private
 */


$post['id'] = !empty($post['id']) ? intval($post['id']) : 0;

// формирование условия для запроса
$where   = array();
$where   = run_filters('comments-where', $where);
$where[] = 'post_id = '.$post['id'];
$cpage = ((!empty($_POST['cpage']) and is_numeric($_POST['cpage'])) ? intval($_POST['cpage']) : ((!empty($_GET['cpage']) and is_numeric($_GET['cpage'])) ? intval($_GET['cpage']) : 0));

// по сколько комментариев выводить на странице
if (empty($config['cnumber']) and !empty($post['comments'])){
  $config['cnumber'] = $post['comments'];
} else {
  $config['cnumber'] = 20;
}

// сортировка комментариев
if (empty($csort) and $config['cup']) {
$csort = "DESC";
} else {
$csort = ($csort == "DESC") ? "DESC" : "ASC";
}

// запрос в базу
$query_c = $db->sql_query("SELECT * FROM ".$config['dbprefix']."comments WHERE post_id = '".$post['id']."' ORDER BY date ".$csort." ");
$query_cs = array();
$j_com_sum=0;
// формируем массив
while ($query_cs[] = $db->sql_fetchrow($query_c)) {
$j_com_sum++;
}




/*
// определение сортировки запроса - выводим с подответами.
if (!$query_css = sort_it_comm($query_cs, $cpage, $config['cnumber'], $j_com_sum)) {
return;
}
//print_r($query_css);
*/

// формируем массив основных комментариев
###################################
$row_left = array();
$comm_b = 0;
$h = 0;
  foreach ($query_cs as $k => $crow) {
    if (empty($crow['parent']) or $crow['parent'] == 0) {
      if ($comm_b >= $cpage and $comm_b < ($cpage + $config['cnumber'])) {
         if (!empty($crow)) {
           $row_left[$h] = $crow;
           $h++;
// формируем массив ответов на основные комментарии
sort_it_comm_answer($query_cs, $crow['id'], $row_left, $h);
         }
      }
    $comm_b++;
    }
  }
###################################


// вывод массива комментариев в упорядоченной форме.
foreach ($row_left as $k => $row){

	$tpl['comment']      = $row;
	$tpl['comment']['_'] = $row;

    if (!empty($tpl['post']['if-right-have']) or ((straw_get_rights('edit') or straw_get_rights('delete')) and $member['username'] == $row['author'])){
        $tpl['comment']['if-right-have'] = true;
    } else {
        $tpl['comment']['if-right-have'] = false;
    }

    if (!empty($row['user_id']) and !empty($users[$row['author']]['username'])){

    if (!empty($users[$row['author']]['mail']) and empty($users[$row['author']]['hide_mail'])){
        $tpl['comment']['mail'] = $users[$row['author']]['mail'];
    } else {
        $tpl['comment']['mail'] = "";
    }

        $tpl['comment']['homepage']    = !empty($users[$row['author']]['homepage']) ? $users[$row['author']]['homepage'] : "";
        $tpl['comment']['admin']      = !empty($row['admin']) ? $row['admin'] : "";
        $tpl['comment']['icq']         = !empty($users[$row['author']]['icq']) ? $users[$row['author']]['icq'] : "";
        $tpl['comment']['location']    = !empty($users[$row['author']]['location']) ? $users[$row['author']]['location'] : "";
        $tpl['comment']['about']       = !empty($users[$row['author']]['about']) ? run_filters('news-comment-content', $users[$row['author']]['about']) : "";
        
      if (!empty($users[$row['author']]['lj_username'])) {
        $tpl['comment']['lj-username'] = '<a href="'.rurl($users[$row['author']]['lj_username'].'.livejournal.com/profile').'"><img src="'.sway('system/skins/images/user.gif').'" alt="[info]" align="absmiddle" border="0"></a> <a href="'.rurl($users[$row['author']]['lj_username'].'.livejournal.com').'">'.$users[$row['author']]['lj_username'].'</a>';
      } else {
        $tpl['comment']['lj-username'] = "";
      }
      
        $tpl['comment']['author']      = !empty($users[$row['author']]['name']) ? $users[$row['author']]['name'] : "UnKnown";
        $tpl['comment']['username']  = !empty($users[$row['author']]['username']) ? $users[$row['author']]['username'] : "UnKnown";
        $tpl['comment']['usergroup'] = (!empty($users[$row['author']]) and !empty($usergroups[$users[$row['author']]['usergroup']]['name'])) ? $usergroups[$users[$row['author']]['usergroup']]['name'] : t('Гости');
        
        $picun = !empty($users[$row['author']]['username']) ? $users[$row['author']]['username'] : "";
        $avaun = !empty($users[$row['author']]['avatar']) ? $users[$row['author']]['avatar'] : "";
        $sys_way = root_directory;
        
        $tpl['comment']['avatar'] = (file_exists(root_directory."/../".$config['userpic_upload']."/".$picun.".".$avaun) ? "<img align=\"left\" width=\"50\" border=\"0\" title=\"".$users[$row['author']]['name']." - пользователь сайта ".$config['home_title']."\" src=\"".way($config['userpic_upload'])."/".$picun.".".$avaun."\" vspace=\"3\" hspace=\"3\">" : "<img align=\"left\" title=\"".$users[$row['author']]['name']." - пользователь сайта ".$config['home_title']."\" width=\"50\" border=\"0\" src=\"".way($config['userpic_upload'])."/user-default.gif\" vspace=\"3\" hspace=\"3\">");
        $tpl['comment']['user-id']     = !empty($users[$row['author']]['id']) ? $users[$row['author']]['id'] : "";
        $tpl['comment']['if-user']     = true;
    
    } else {
    
        $tpl['comment']['if-user'] = false;
        $tpl['comment']['avatar'] = "<img align=\"left\" title=\"".$tpl['comment']['author']." - гость сайта ".$config['home_title']."\" width=\"50\" border=\"0\" src=\"".way($config['userpic_upload'])."/default.gif\" vspace=\"3\" hspace=\"3\">";
    
    }
    
    if (!empty($config['auto_wrap'])){
        $row['comment'] = preg_replace('/([^ .]{'.$config['auto_wrap'].'})/', '\\1 ', $row['comment']);
    }
    
    $tpl['comment']['answer']      = !empty($row['reply']) ? run_filters('news-comment-content', aply_bbcodes($row['reply'])) : "";
    $tpl['comment']['date']         = !empty($row['date']) ? langdate($config['timestamp_comment'], $row['date']) : "";
    $tpl['comment']['story']        = !empty($row['comment']) ? run_filters('news-comment-content', aply_bbcodes($row['comment'])) : "";
    $tpl['comment']['alternating'] = straw_that('cn_comment_odd', 'cn_comment_even');
    $tpl['comment']['number']      = ($k + 1);
    //$tpl['comment']                   = run_filters('comments-show-generic', $tpl['comment']);

    ob_start();
    include templates_directory.'/'.$tpl['template'].'/comments.tpl';
    $output = ob_get_clean();
    $output = run_filters('news-comment', $output);
    $output = replace_comment('show', $output);
    echo $output;
    
}



?>