<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../index.php");
exit;
}

/*
Plugin Name:	Предыдущий-Следующий пост
Plugin URI:     http://english.cutenews.ru/forum
Description:	Ссылки на предыдущий и следующий пост в полной новости.
Version:		1.2
Application: 	Strawberry
Author:		FI-DD
Author URI:		http://english.cutenews.ru/forum/profile.php?mode=viewprofile&u=2
*/

add_action('head', 'prev_next', 19);
function prev_next(){
global $sql, $db, $config, $cache, $post, $prev_next_links;
$prev_next_links = array();
	if (!empty($post)){
	    if (!$prev_next_links = $cache->unserialize('prev-next-links', $post['id'])){
//////////////////////////////////////////////////////////////////////////////

$min_query = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news WHERE id < ".$post['id']." and hidden!=1 ORDER BY date DESC LIMIT 0,1 ");
$max_query = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news WHERE id > ".$post['id']." and hidden!=1 ORDER BY date ASC LIMIT 0,1 ");
$prev_next_links['prev'] = $db->sql_fetchrow($min_query);
$prev_next_links['next'] = $db->sql_fetchrow($max_query);
$prev_next_links = $cache->serialize($prev_next_links);

//////////////////////////////////////////////////////////////////////////////
	    }
	} else {
		$prev_next_links = array();
	}

return $prev_next_links;
}






add_filter('news-show-generic', 'prev_next_generic', 19);
function prev_next_generic($tpl){
global $prev_next_links;

	if (!empty($prev_next_links)){
	    $tpl['prev-next']['prev']['link']  = !empty($prev_next_links['prev']) ? straw_get_link($prev_next_links['prev']) : "";
	    $tpl['prev-next']['prev']['title'] = !empty($prev_next_links['prev']) ? $prev_next_links['prev']['title'] : "";
	    $tpl['prev-next']['next']['link']  = !empty($prev_next_links['next']) ? straw_get_link($prev_next_links['next']) : "";
	    $tpl['prev-next']['next']['title'] = !empty($prev_next_links['next']) ? $prev_next_links['next']['title'] : "";
	} else {
	    $tpl['prev-next']['prev']['link']  = $tpl['prev-next']['prev']['title'] = $tpl['prev-next']['next']['link']  = $tpl['prev-next']['next']['title'] = "";
	}

return $tpl;
}





add_filter('template-full', 'template_prev_next', 19);
function template_prev_next($template){
$template['prev-next'] = t('Отображает ссылки на предыдущую и следующую новости: $tpl[\'post\'][\'prev-next\'][\'prev\'][\'link\'] и $tpl[\'post\'][\'prev-next\'][\'next\'][\'link\']. А также заголовки: $tpl[\'post\'][\'prev-next\'][\'prev\'][\'title\'] и $tpl[\'post\'][\'prev-next\'][\'next\'][\'title\']');
return $template;
}

?>