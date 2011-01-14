<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../index.php");
exit;
}

/*
Plugin Name:    Последние комментарии
Plugin URI:     http://cutenews.ru
Description:    Выводит список последних комментариев. Используйте вывод: <b> echo latest_comments(10, 0);</b> Первый параметр - сколько заголовков новостей (10), второй параметр - сколько символов оставить в заголовке комментариев.<br><b>all_comments(10, 0)</b> - Данная функция выводит сами комментарии. Параметры как и у первой функции.
Version:        0.6
Application:    Strawberry
Author:         BadDog
Author URI:     http://english.cutenews.ru/
*/


function latest_comments ($number="5", $max_length="0", $tpl = "{bullet} <a href=\"{nlink}\" title=\"\">{ntitle}</a> <span class=\"comm_small\">({ctitle})</span><br>\n") {
global $db, $config, $categories;
$out_tpl = "";
if ($number > 30) { $number = 30; }  //protect against accidental large numbers
if ($number < 0) { $number = 1; }  //need a positive number!

$result = $db->sql_query("SELECT a.date, a.author, a.comment, a.reply, a.post_id, a.id, b.title, b.category FROM ".$config['dbprefix']."comments AS a LEFT JOIN ".$config['dbprefix']."news AS b ON (a.post_id=b.id) ORDER BY a.date DESC LIMIT 0, ".$number." ");
$crl = $db->sql_numrows($result);
 if (!empty($crl) and $crl > 0) {
  while(list($cdate, $cauthor, $ccomment, $creply, $cpost_id, $cid, $n_title, $n_categ) = $db->sql_fetchrow($result)) 
  {
  $cnames = array();
   $n_title = !empty($n_title) ? $n_title : "";
   $cpost_id = !empty($cpost_id) ? "&amp;id=".$cpost_id : "";
   $a_c = explode(",", $n_categ);
   foreach($a_c as $c_numb){if(!empty($c_numb)){$cnames[]=$categories[$c_numb]['name'];}}
   $allcats = @implode(", ", $cnames);
   $in = array('{nlink}','{ntitle}','{ctitle}','{bullet}','{cdate}','{cid}');
   $out = array(''.way($config['home_page']).'?mod=news'.$cpost_id.'', ''.$n_title.'', ''.$allcats.'', '<img src="'.way("images/bullet1.gif").'" border="0" alt="">', langdate("j M Y", $cdate), $cid);
   $out_tpl .= str_ireplace($in, $out, $tpl);
  }
  return $out_tpl;
 } else {
 return;
 }
}





function all_comments($number="5", $max_length="999"){
global $db, $config, $users, $categories;

if ($number > 50){ $number = 50;}  //protect against accidental large numbers
if ($number < 0){ $number = 1;}  //need a positive number!

$result = $db->sql_query("SELECT a.date, a.author, a.comment, a.reply, a.post_id, a.id, b.title, b.category FROM ".$config['dbprefix']."comments AS a LEFT JOIN ".$config['dbprefix']."news AS b ON (a.post_id=b.id) ORDER BY a.date DESC LIMIT 0, ".$number." ");
while(list($cdate, $cauthor, $ccomment, $creply, $cpost_id, $cid, $cat_title, $cat_categ) = $db->sql_fetchrow($result)) {

$cat_title_out = str_stop($cat_title, $max_length);
$cnames = array();
   $a_c = explode(",", $cat_categ);
   foreach($a_c as $c_numb){if(!empty($c_numb)){$cnames[]=str_stop($categories[$c_numb]['name'], $max_length);}}
   $allcats = @implode(", ", $cnames);

            $xid = $cid;
            $author = chuser($cauthor, 1);
            $text = !empty($ccomment) ? str_stop(comm_out($ccomment, "1", "1"), $max_length) : "";
            $answer = !empty($creply) ? str_stop(comm_out($creply), $max_length) : "";
            $date = $cdate;

$dat=date("j.m.Yг. в H:i",$date);                       
$nw_in_cat = !empty($allcats) ? "<br>".t('Категория').": ".$allcats : "<br>".$xid." - ".$max_length;
$nw_in_cat_ans = !empty($answer) ? "<tr><td>&nbsp;&nbsp;<b>".t('Ответ').":</b></td></tr><tr><td style=\"border-top: 1px solid #C0C0C0;\">&nbsp;".$answer."&nbsp;</td></tr>" : "";

echo '<br><table border="0" class="nbtext">';
echo '<tr><td><img src="'.way("images/bullet1.gif").'" border="0" alt="'.t('комментарий пользователя').'"> <b>'.$author.'</b><br><small>'.t('Дата').': '.$dat.'<br>'.t('Новость').': <a href="'.way($config['home_page']).'?mod=news&amp;id='.$cpost_id.'">'.$cat_title_out.'</a>'.$nw_in_cat.'</small></td></tr>';
echo '<tr><td style="border-top: 1px solid #C0C0C0;">'.$text.'</td></tr>';
echo $nw_in_cat_ans;
echo '</table><br>';

}
}



?>
