<?php
/**
 * @package Show
 * @access private
 */
#_strawberry
include_once 'head.php';
if (!defined("str_adm")) { header("Location: ../index.php"); exit; }

$add_way = !empty($ap) ? "" : "system/";
 $money_plus = 0;
 $money_minus = 0;
 $money_plus_all = 0;
$user_rat = 0;
$user_rat_glob = 0;
$aa = 0;
$ac = 0;


if (!empty($config['mytheme']) and is_dir(stpl_directory."/users_".$config['mytheme'])) {
  $tpl['template'] = stpl_directory."/users_".$config['mytheme']."/";
  $utpl = "system/users_".$config['mytheme'];
} else {
  $tpl['template'] = stpl_directory."/users/";
  $utpl = "system/users";
}


if (!empty($money)){
if (!empty($addmony)) echo "<body onload=\"window.close();\">";
  if (($money == 'give' or $money == 'take') and !empty($user_id) and !empty($is_logged_in)){
      if (!empty($_POST['motivation'])){
          if (!empty($_GET['user_id']) and $_GET['user_id'] != $member['username']){
          
          $sql->insert(array(
          'table'  => 'money',
          'values' => array(
                      'to'         => $user_id,
                      'from'       => $member['username'],
                      'motivation' => replace_comment('add', $_POST['motivation']),
                      'money'      => ($money == 'give' ? '+' : '-'),
                      'date'       => time
                      )
          ));
          
         } else {
exit('<b>'.$member['name'].'</b>, '.t('Вы <u>не можете</u> добавлять или убавлять себе рейтинг!').'<br><br><a href="#" onClick="window.close();">'.t('Закрыть окно').'</a>');
         }

      } else {
          include stpl_directory.'/users/motivation.tpl';
      }
  } elseif (!empty($money) and $money == 'show'){
  
  
      $query = $sql->select(array('table' => 'money', 'where' => array('to = '.$user_id)));

      foreach ($query as $row){
        $tpl['money']['to']         = $users[$row['to']]['name'];
        $tpl['money']['from']       = $users[$row['from']]['name'];
        $tpl['money']['action']     = $row['money'];
        $tpl['money']['date']       = langdate('d M Y H:i:s', $row['date']);
        $tpl['money']['motivation'] = replace_comment('show', $row['motivation']);
        $tpl['money']['_']          = $row;
        include stpl_directory.'/users/operations.tpl';
      }
  }

  exit('<a href="#" onClick="window.close();" >'.t('Закрыть окно').'</a>');
if (!empty($addmony)) echo "</body>";
}

#### here was a part with counter...

$user = !empty($_GET['user']) ? cheker($_GET['user']) : '';
$self = $PHP_SELF.(strstr($PHP_SELF, '?') ? '&' : '?');
?>


<table align="center" border="0" cellpadding="2" cellspacing="2">

<?php
foreach ($users as $row){

  if ($row['id'] == $user or $row['username'] == $user or $row['name'] == $user){
    $allow_full_story = true;
  } else {
    $allow_full_story = false;
  }

  if (!empty($user) and empty($allow_full_story)){
    continue;
  }

  if (!$output = $cache->get($row['id'], '', ($allow_full_story ? 'show' : 'list'))){
    if (!$rufus_file){
      $rufus_file = parse_ini_file(rufus_file, true);
    }

      foreach ($rufus_file as $type_k => $type_v){
          if (is_array($type_v)){
              foreach ($type_v as $k => $v){
                  if ($type_k == 'home'){
                      $tpl['user']['link'][$k] = straw_get_link($row, $k);
                  }

                  $tpl['user']['link'][$type_k.'/'.$k] = straw_get_link($row, $k, $type_k);
              }
          }
      }

##################################################################################
if (!empty($user)){
##################
 $money_plus = $sql->count(array('table' => 'money', 'where' => array('to = '.$row['username'], 'and', 'money = +')));
 $money_minus = $sql->count(array('table' => 'money', 'where' => array('to = '.$row['username'], 'and', 'money = -')));
 $money_plus_all = $sql->count(array('table' => 'money', 'where' => array('money = +')));
 
 if (!empty($money_plus) or !empty($money_minus)) {
 $user_rat = intval(($money_plus/($money_plus + $money_minus))*100);
 } else {
 $user_rat = 0;
 }
 
 if (!empty($money_plus_all)) {
 $user_rat_glob = intval(($money_plus/$money_plus_all)*100);
 } else {
 $user_rat_glob = 0;
 }
 
##################
}
##################################################################################
$no_info = t('Нет информации');
$gg = strlen($row['homepage']);

if (!empty($gg) and $gg > 7) {  $tpl['user']['homepage'] = (!empty($row['homepage']) ? '<a target="_blank" href="'.rurl($row['homepage']).'">'.$row['homepage'].'</a>' : ''); } else { $tpl['user']['homepage']  = $no_info; }
      
      $tpl['user']['avatar']         = (!empty($row['avatar']) ? '<img border="0" src="'.$config['path_userpic_upload'].'/'.$row['username'].'.'.$row['avatar'].'">' : '');
      $tpl['user']['icq']            = (!empty($row['icq']) ? '<img src="http://status.icq.com/online.gif?img=5&amp;icq='.$row['icq'].'" width="18" height="18" border="0"> <a target="_blank" href="http://wwp.icq.com/scripts/search.dll?to='.$row['icq'].'">'.$row['icq'].'</a>': $no_info);
      $tpl['user']['location']       = !empty($row['location']) ? $row['location'] : $no_info;
      $tpl['user']['skype']            = (!empty($row['skype']) ? '<img src="http://mystatus.skype.com/smallicon/'.$row['skype'].'" width="18" height="18" border="0"> '.$row['skype'] : $no_info);

      $tpl['user']['about']          = run_filters('news-entry-content', $row['about']);
      $tpl['user']['lj-username']    = (!empty($row['lj_username']) ? '<a target="_blank" href="'.rurl($row['lj_username']).'.livejournal.com/profile"><img src="system/skins/images/user.gif" alt="[info]" align="absmiddle" border="0"></a><a href="'.rurl($row['lj_username'].'.livejournal.com').'">'.$row['lj_username'].'</a>' : $no_info);
      $tpl['user']['name']           = $row['name'];
      $tpl['user']['username']       = $row['username'];
      $tpl['user']['usergroup']      = $usergroups[$row['usergroup']]['name'];
      $tpl['user']['id']             = $row['id'];
      $tpl['user']['date']           = langdate($config['timestamp_active'], $row['date']);
      $tpl['user']['mail']           = (empty($row['hide_mail']) ? $row['mail'] : '');
      $tpl['user']['last_visit']     = (!empty($row['last_visit']) ? langdate($config['timestamp_active'], $row['last_visit']) : $no_info);
      $tpl['user']['about']          = !empty($row['about']) ? run_filters('news-entry-content', $row['about']) : $no_info;
      $tpl['user']['alternating']    = straw_that('cn_users_odd', 'cn_users_even');
      $tpl['user']['publications']   = $row['publications'];

     $tpl['user']['money']['plus']           = $money_plus;
     $tpl['user']['money']['minus']          = $money_minus;
     $tpl['user']['money']['plus_all']       = $money_plus_all;
     $tpl['user']['money']['rating']         = $user_rat;
     $tpl['user']['money']['global_rating']  = $user_rat_glob;
    $tpl['user']['count'] = $sql->count(array('table' => 'users', 'where' => array('deleted = 0')));
    $tpl['user']['on_page'] = $config['users_on_page'];
 //    $tpl['user']['_']                       = $row;

##############
if (!empty($config['use_dop_fields_u_out'])) flds('users'); // новая система доп полей.
##############

      ob_start();
      include $tpl['template'].'/'.(!empty($allow_full_story) ? 'show' : 'list').'.tpl';
      $output = ob_get_clean();
      $output = run_filters('news-entry', $output);
      $output = replace_news('show', $output);
      $output = $cache->put($output);
  }
  echo $output;
}
echo '</table>';


/* старая брехня ))
$usnum = "";
if (!isset($_GET['user']) and !isset($_GET['money']))
{
echo "<br><center class=\"smnbtext\">";
for ($ii=0; $ii <= ($tpl['user']['count']/$tpl['user']['on_page']); $ii++) {
$usnum .= ' <a href="'.$config['home_page'].'?mod=account&amp;act=users&amp;page='.$ii.'">'.($ii+1).'</a> ';
}
if ($ii > 1 and $tpl['user']['count'] != $tpl['user']['on_page']) echo "<hr width=\"30%\"><b class=\"text\">[ ".$usnum." ]</b>";
echo '<hr width="50%">';
echo 'Всего пользователей <b>'.$tpl['user']['count'].'</b>';
if ($tpl['user']['count'] > $tpl['user']['on_page']) echo ' по <b>'.$tpl['user']['on_page'].'</b> на странице.';
echo '<hr width="50%"></center>';
}
*/

if (empty($_GET['user']))
{
$wheren = " deleted=0 ";
  $pnum = (isset($_GET['pnum']) and is_numeric($_GET['pnum'])) ? intval($_GET['pnum']) : "1";
  $oskip = ($pnum-1) * $tpl['user']['on_page'];
  $oskip = !empty($skip) ? intval($skip) : intval($oskip);
$lnk = "&act=users";
echo pnp("users", (!empty($pnum) ? intval($pnum) : 1), (!empty($tpl['user']['on_page']) ? intval($tpl['user']['on_page']) : 0), (!empty($utpl) ? $utpl : 'default'), (!empty($wheren) ? $wheren : ''), '', '', $lnk);
}

?>