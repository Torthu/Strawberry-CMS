<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../index.php");
exit;
}

/**
 * @package Plugins
 * @access private
 */

/*
Plugin Name:	Рейтинг новостей
Plugin URI: 	http://www.strawberry.su/
Description:	        Функция рейтинга новостей. Используйте <b>&lt;?=rating(); ?&gt;</b> для вывода правильного рейтинга. И <b>&lt;? if (!rating('check')) { вывод формы } ?&gt;</b> для проверки - голосовал ли уже пользователь за эту новость. Проверка работает на куках.
Version: 		0.6
Application: 	Strawberry
Author: 		Лёха zloy и красивый, Mr.Miksar
Author URI:     http://lexa.cutenews.ru
*/






add_filter('news-entry-content', 'rating_update', 20);
function rating_update($content){
global $db, $config, $row;
  if (!empty($_POST['rating_'.$row['id']])){
    if (empty($_COOKIE[$config['cookie_prefix'].'str_post_'.$row['id']])) {
      straw_setcookie('str_post_'.$row['id'], 'voted', (time() + 3600 * 24), '/');
      if (!empty($_POST['rating_'.$row['id']]) and is_numeric($_POST['rating_'.$row['id']])) {
        $db->sql_query("UPDATE ".$config['dbprefix']."news SET rating=rating+".intval($_POST['rating_'.$row['id']]).", votes=votes+1 WHERE id=".$row['id']." ");
      }
    }
      header('Location: '.$_SERVER['REQUEST_URI']);
      exit;
  }
  return $content;	
}







add_filter('template-active', 'rating_macros', 20);
add_filter('template-full', 'rating_macros', 20);
function rating_macros($output){
$output['rating']   = t('Выводит общий рейтинг новости');
$output['votes']    = t('Выводит количество голосов за новость');
$output['rating()'] = t('Выводит "правильный" рейтинг (количество голосов разделённое на общий рейтинг) новости или форму голосования. Вторым параметром можно указать check для проверки кукисов посетителя и если он уже голосовал, то вернётся положительный ответ');
return $output;
}

#-------------------------------------------------------------------------------

function rating($what = ''){
global $tpl, $config;
	if (!empty($what) and $what == 'check') {
		return (!empty($_COOKIE[$config['cookie_prefix'].'str_post_'.$tpl['post']['id']]) ? 1 : 0);
	} else {
		return @round(($tpl['post']['rating'] / $tpl['post']['votes']), 2);
	}
}

?>