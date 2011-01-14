<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }

/**
 * @package Plugins
 * @access private
 */

// XFields self-cleaning
add_action('deleted-single-entry', 'clean_single_xfields');
add_action('deleted-multiple-entries', 'clean_multiple_xfields');

function clean_single_xfields($hook){
global $row, $id;
  $xfields = new XfieldsData();
  $xfields->delete((!empty($id) ? $id : $row['id']));
  $xfields->save();
}

function clean_multiple_xfields($hook) {
global $selected_news;
  $xfields = new XfieldsData();
  foreach ($selected_news as $news_id){
    $xfields->delete($news_id);
  }
  $xfields->save();
}








// sticky
add_action('new-advanced-options', 'sticky_AddEdit');
add_action('edit-advanced-options', 'sticky_AddEdit');

function sticky_AddEdit(){
global $post, $config;
  if ($config['database'] != 'txtsql'){
    return '<fieldset id="sticky">'
    .'<legend>'.t('Закрепить новость').'</legend>'
    .'<label for="sticky_post"><input type="checkbox" id="sticky_post" name="sticky_post" value="on"'.(!empty($post['sticky']) ? ' checked' : '').'>&nbsp;'.t('Закрепить эту новость?').'</label>'
    .'</fieldset>';
  }
}














// date
add_action('new-advanced-options', 'date_AddEdit', 1);
add_action('edit-advanced-options', 'date_AddEdit', 1);

function date_AddEdit(){
global $row, $config, $post;

    for ($i = 1; $i <= 12; $i++){
    ##$months[date('M', mktime(0, 0, 0, $i))]    = ucfirst(langdate('M', mktime(0, 0, 0, $i)));
        $months[date('M', mktime(0, 0, 0, $i, 1))] = ucfirst(langdate('M', mktime(0, 0, 0, $i, 1)));
    }

    $time    = (!empty($post['date']) ? $post['date'] : (!empty($row['date']) ? $row['date'] : time));
    $result  = '<fieldset id="date"><legend>'.t('Публикация').'</legend>';
    $result .= '&nbsp;&nbsp;<b>'.t('Дата').':</b><br><input type="text" name="day" maxlength="2" style="width: 22px;" value="'.langdate('d', $time).'" title="'.t('День').'">. ';
    $result .= makeDropDown($months, 'month', date('M', $time));
    $result .= '. <input type="text" name="year" maxlength="4" style="width: 32px;" value="'.langdate('Y', $time).'" title="'.t('Год').'"> ';
    $result .= '<br>&nbsp;&nbsp;<b>'.t('Время').':</b> <br><input type="text" name="hour" maxlength="2" style="width: 22px;" value="'.langdate('H', $time).'" title="'.t('Час').'">';
    $result .= ':<input type="text" name="minute" maxlength="2" style="width: 22px;" value="'.langdate('i', $time).'" title="'.t('Минута').'">';
    $result .= ':<input type="text" name="second" maxlength="2" style="width: 22px;" value="'.langdate('s', $time).'" title="'.t('Секунда').'">';
    $result .= '</fieldset>';

return $result;
}






// usergroups
add_action('new-advanced-options', 'AddEdit_usergroups_check_fields', 1000000);
add_action('edit-advanced-options', 'AddEdit_usergroups_check_fields', 1000000);
function AddEdit_usergroups_check_fields(){
global $usergroups_check_fields;
return $usergroups_check_fields;
}







add_action('head', 'head_usergroups_check_fields');
function head_usergroups_check_fields(){
global $mod, $usergroups_check_fields;

  if (!empty($mod) and straw_get_rights($mod, 'read') and ($mod == 'addnews' or $mod == 'editnews')){
    preg_match_all('/fieldset id="(.*?)"><legend>(.*?)<\/legend>/i', run_actions('new-advanced-options'), $fields['new']);
    preg_match_all('/fieldset id="(.*?)"><legend>(.*?)<\/legend>/i', run_actions('edit-advanced-options'), $fields['edit']);

    $fields[1] = array_merge($fields['new'][1], $fields['edit'][1]);
    $fields[1] = array_unique($fields[1]);

    unset($fields[0], $fields['new'], $fields['edit']);

    ob_start();
?>

<script>
<?php 
foreach ($fields[1] as $k => $v) { 
 if (!straw_get_rights($v, 'fields')){ ?>
    _getElementById('<?php echo $v; ?>').style.display = 'none';
<?php } 
} ?>
</script>

<?php
$usergroups_check_fields = ob_get_clean();
  } else {
    $usergroups_check_fields = '';
  }
}








// multicats
function multicats($that){
global $usergroups, $member, $config, $nid, $post;
    if (!empty($usergroups[$member['usergroup']]['permissions']['categories']) and !in_array($that, explode(',', $usergroups[$member['usergroup']]['permissions']['categories']))){
        return 'disabled';
    }
    if (!empty($nid) and !empty($that)){

//foreach ($sql->select(array('table' => 'news', 'where' => array("id = ".$nid))) as $row) {
//$rowcat = $row['category'];
//}

          if (in_array($that, explode(',', $post['category']))){
              return 'checked="checked"';
          } else {
              return;
          }

    }
}












add_action('new-advanced-options', 'multicats_AddEdit', 2);
add_action('edit-advanced-options', 'multicats_AddEdit', 2);

function multicats_AddEdit(){
global $id, $mod, $categories;

  if (!empty($categories) and count($categories) > 20){
    $style = ' style="overflow: scroll; width: 100%; height: 200px;"';
  } else {
    $style = '';
  }

  if ($category = category_get_tree('&nbsp;', '<label for="cat{id}"><input type="checkbox" [php]multicats({id})[/php] name="cat[{id}]" id="cat{id}")">&nbsp;{name}</label><br>')){
    return '<fieldset id="category"><legend>'.t('Категория').'</legend><div class="multicats_addedit"'.$style.'>'.$category.'</div></fieldset>';
  }
}













add_action('new-save-entry', 'multicats_Save', 1);
add_action('edit-save-entry', 'multicats_Save', 1);

function multicats_Save(){
global $category;
    if (!empty($_POST['cat'])){
     foreach ($_POST['cat'] as $k => $v){
       $category_tmp[] = $k;
     }
    $category = ",".implode(',', $category_tmp).",";
  }
}







##############################################################
// cache_remover
add_action('head', 'cache_remover');
function cache_remover(){
global $cache, $nid, $is_logged_in, $action;
    if (!empty($is_logged_in) and $action == 'clearcache'){
      $cache->delete();
    } elseif (!empty($_POST)){
      $cache->delete($nid);
    }
}








































##############################################################
##############################################################
##############################################################
##############################################################
// rufus

add_action('head', 'rufus');

function rufus(){
global $is_logged_in, $mod, $config;
$str = "";
  if (!$config['mod_rewrite'] and !$mod){
    $urls = parse_ini_file(rufus_file, true);
      foreach ($urls as $url_k => $url_v){
          foreach ($url_v as $k => $v){
              @preg_match_all('/'.@str_replace('/', '\/', htaccess_rules_replace($v)).'/i', $_SERVER['REQUEST_URI'], $query);
              for ($i = 0; $i < count($query); $i++){
                  if ($query[$i][0]){
                      if ($clear = preg_replace('/(.*?)=\$([0-9]+)/i', '', str_replace('$'.$i, $query[$i][0], str_replace('?', '', htaccess_rules_format($v))))){
                          $str[] = $clear;
                      }
                  }
              }
          }
      }

      if (!empty($str)){
          $str = preg_replace('/([\&]+)/i', '&', join('&', array_reverse($str)));
          parse_str($str, $_CUTE);

          foreach ($_CUTE as $k => $v){
              $GLOBALS[$k] = $_GET[$k] = @htmlspecialchars($v);
          }
      }
  }
}










add_action('new-advanced-options', 'rufus_AddEdit', 3);
add_action('edit-advanced-options', 'rufus_AddEdit', 3);
function rufus_AddEdit(){
global $row, $post;
return '<fieldset id="url">'
.'<legend>'.t('УРЛ (при желании)').'</legend>'
.'<input type="text" class="regtext" name="url" value="'.(!empty($row['url']) ? $row['url'] : (!empty($post['url']) ? $post['url'] : "")).'">'
.'</fieldset>';
}









add_action('head', 'make_htaccess');
function make_htaccess(){
global $mod, $PHP_SELF, $config;

  $settings         = straw_parse_url($config['http_home_url']);
  $configs          = straw_parse_url($config['http_script_dir']);
  $types            = parse_ini_file(rufus_file, true);
  $settings['path'] = (!empty($settings['path']) ? '/'.$settings['path'].'/' : '/');
  $configs['path']  = (!empty($configs['path']) ? '/'.$configs['path'].'/' : '/');
  $uhtaccess        = new  PluginSettings('uhtaccess');
  $htaccess         = array();

  if (!empty($mod) and !empty($_POST) and !empty($settings['file']) and !empty($config['mod_rewrite'])){

      $htaccess[] = '#DirectoryIndex '.$settings['file'];
      $htaccess[] = '# [user htaccess] '.$uhtaccess->settings;
      $htaccess[] = '<IfModule mod_rewrite.c>';
      $htaccess[] = 'RewriteEngine On';
      $htaccess[] = '#Options +FollowSymlinks';
      $htaccess[] = '#RewriteBase '.$settings['path'];
      
      $htaccess[] = 'RewriteRule rss.xml rss.php [QSA,L]';
      $htaccess[] = 'RewriteRule atom.xml atom.php [QSA,L]';

      foreach ($types as $type_k => $type_v){
          foreach ($type_v as $k => $v){
              $v = preg_replace('/\{(.*?)\:(.*?)\}/i', '{\\1|>|\\2}', $v);
              $v = parse_url($v);
              $v = preg_replace('/\{(.*?)\|>\|(.*?)\}/i', '{\\1:\\2}', $v['path']);

              $htaccess[] = '# ['.$type_k.'] '.$k;
              $htaccess[] = (!$v ? '# [wrong rule] ' : '');
              $htaccess[] = 'RewriteRule ^'.(($type_k == 'home' or substr($type_k, 0, 5) == 'home/') ? '' : '').htaccess_rules_replace($v).'(/?)+$ '.htaccess_rules_format($v, ($type_k == 'home' ? $settings['file'] : (substr($type_k, 0, 5) == 'home/' ? substr($type_k, 5).'/' : $configs['path'].$type_k))).' [QSA,L]';
          }
      }

      $htaccess[] = '</IfModule>';


    if (!is_writable($settings['abs'].'/.htaccess')){
    @chmod($settings['abs'].'/.htaccess', 0666);
    }
    
    file_write($settings['abs'].'/.htaccess', join("\r\n", $htaccess));
    @chmod($settings['abs'].'/.htaccess', 0444);
    
    
  }
}








add_filter('options', 'rufus_AddToOptions');
function rufus_AddToOptions($options){
  $options[] = array(
           'name'     => t('Управление УРЛами'),
           'url'      => 'plugin=rufus',
           'category' => 'options'
           );
return $options;
}






add_action('plugins', 'rufus_CheckAdminOptions');
function rufus_CheckAdminOptions(){
  if (!empty($_GET['plugin']) and $_GET['plugin'] == 'rufus'){
    rufus_AdminOptions();
  }
}






function rufus_AdminOptions(){
global $PHP_SELF, $config;

 // if (!empty($_POST)){
 //   header('Location: '.$PHP_SELF.'?plugin=rufus');
 // }


  $settings         = straw_parse_url($config['http_home_url']);
  $configs          = straw_parse_url($config['http_script_dir']);
  $types            = parse_ini_file(rufus_file, true);
  $settings['path'] = (!empty($settings['path']) ? '/'.$settings['path'].'/' : '/');
  $configs['path']  = (!empty($configs['path']) ? '/'.$configs['path'].'/' : '/');
  $uhtaccess        = new  PluginSettings('uhtaccess');
  $htaccess         = array();


  if (empty($settings['file'])){
    msg('error', t('Управление УРЛами'), t('Извините, но Вы не указали файла, в котором будут отображаться новости или указали неверно. Сделайте это в настройке системы.'));
  }

  echoheader('user', t('Управление УРЛами'));

  if (ini_get('safe_mode') and !empty($config['mod_rewrite']) and !is_writable($settings['abs'].'/.htaccess')){
    echo '<div class="panel"><b style="color: red;">'.t('Возможна ошибка</b><br>На Вашем сервере включён Safe Mode. Возможно, не удастся создать фаил .htaccess. На всякий случай, создайте сами .htaccess в директории <b>%directory</b> и поставьте ему права <b>0666</b><br><br>Затем проверте: проставлены ли права на запись для файла <b>data/urls.ini</b> и <b>data/urls_on.ini</b>.', array('directory' => $settings['abs'])).'</div><br>';
  }

/*
  $htaccess[] = '#_strawberry';
$htaccess[] = '';
  $htaccess[] = '# Управление глобальными переменными';
  $htaccess[] = '# Для большей безопасности ресурса рекомендуется отключить глобальные переменные';
  $htaccess[] = '# php_flag register_globals OFF';
$htaccess[] = '';
  $htaccess[] = '# отключить все ошибки';
  $htaccess[] = '# php_value error_reporting 0 ';
  $htaccess[] = '# максимальный размер загружаемого файла';
  $htaccess[] = '# php_value upload_max_filesize 10M ';
  $htaccess[] = '# максимальный размер передаваемой информации';
  $htaccess[] = '# php_value post_max_size 10M ';
  $htaccess[] = '# объем памяти выделенный для сайта';
  $htaccess[] = '# php_value memory_limit 50M ';
*/

$htaccess[] = '';
  $htaccess[] = '# Index file';
  $htaccess[] = 'DirectoryIndex '.$settings['path'].$settings['file'];
  $htaccess[] = '# [user htaccess] '.$uhtaccess->settings;
$htaccess[] = '';
  $htaccess[] = '# Строки с адресами страниц ошибок';
  $htaccess[] = 'ErrorDocument 401 '.$settings['path'].$config['home_page'].'?error=401';
  $htaccess[] = 'ErrorDocument 402 '.$settings['path'].$config['home_page'].'?error=402';
  $htaccess[] = 'ErrorDocument 403 '.$settings['path'].$config['home_page'].'?error=403';
  $htaccess[] = 'ErrorDocument 404 '.$settings['path'].$config['home_page'].'?error=404';
  $htaccess[] = 'ErrorDocument 500 '.$settings['path'].$config['home_page'].'?error=500';
  $htaccess[] = 'ErrorDocument 501 '.$settings['path'].$config['home_page'].'?error=501';
  $htaccess[] = 'ErrorDocument 502 '.$settings['path'].$config['home_page'].'?error=502';
$htaccess[] = '';
  $htaccess[] = '<IfModule mod_rewrite.c>';
  $htaccess[] = 'RewriteEngine On';
  $htaccess[] = '#Options +FollowSymlinks';
  $htaccess[] = 'RewriteBase '.$settings['path'];
$htaccess[] = '';
  $htaccess[] = 'AddEncoding gzip .gz';
  $htaccess[] = 'RewriteCond %{HTTP:Accept-encoding} !gzip [OR]';
  $htaccess[] = 'RewriteCond %{HTTP_USER_AGENT} Safari [OR]';
  $htaccess[] = 'RewriteCond %{HTTP_USER_AGENT} Konqueror';
  $htaccess[] = 'RewriteRule ^(.*)\.gz(\?.+)?$ $1 [QSA,L]';
  $htaccess[] = 'RewriteRule ^([a-z]+)-([a-z]+)-([0-9a-z_]*)-?(.*)$	 $1-$4?$2=$3	[NC,QSA]';
  $htaccess[] = 'RewriteRule ^([a-z]+)-?\.html$	$1.php	[NC,L,QSA]';
$htaccess[] = '';
  $htaccess[] = '#RSS feed';
  $htaccess[] = 'RewriteRule '.substr($settings['path'], 1).'rss.(txt|xml) '.$settings['path'].'rss.php [NC,L,QSA]';
  $htaccess[] = '#ATOM feed';
  $htaccess[] = 'RewriteRule '.substr($settings['path'], 1).'atom.(txt|xml) '.$settings['path'].'atom.php [NC,L,QSA]';
  $htaccess[] = '#Site map';
  $htaccess[] = 'RewriteRule '.substr($settings['path'], 1).'sitemap.(txt|xml)$ '.$settings['path'].'sitemap.php [NC,L,QSA]';
$htaccess[] = '';


    foreach ($types as $type_k => $type_v){
        foreach ($type_v as $k => $v){
        
          $v = preg_replace('/\{(.*?)\:(.*?)\}/i', '{\\1|>|\\2}', $v);
          $v = parse_url($v);

          $v = preg_replace('/\{(.*?)\|>\|(.*?)\}/i', '{\\1:\\2}', $v['query']);
        //$v = preg_replace('/\{(.*?)\|>\|(.*?)\}/i', '{\\1:\\2}', $v['path']); 

          $htaccess[] = '';
          $htaccess[] = '# ['.$type_k.'] '.$k;
          if (empty($v))  $htaccess[] = '# [wrong rule]';
          $htaccess[] = 'RewriteRule ^'.(($type_k == 'home' or substr($type_k, 0, 5) == 'home/') ? '' : '').htaccess_rules_replace($v).'(/?)+$ '.htaccess_rules_format($v, ($type_k == 'home' ? $settings['path'].$settings['file'] : (substr($type_k, 0, 5) == 'home/' ? substr($type_k, 5).'/' : (substr($type_k, 0, 13) == 'trackback.php' ? $configs['path'].$type_k : $settings['path'].$type_k)))).' [L,QSA]';
        }
    }

  $htaccess[] = '</IfModule>';

  echo '<div class="panel">'.t('Окно "urls.ini" показывает и даёт возможность настроить вид УРЛов. %oaО тегах, хитростях и создание смотрите тут%ca. После редактирования нажмите "%save".', array('save' => t('Сохранить urls.ini'), 'make' => t('Создать .htaccess'), 'oa'=>'<a onClick="javascript:Help(\'rufus\')" href="#">', 'ca'=>'</a>')).'</div>';
  $res = @file_get_contents(htac);
?>

<form method="post" action="index.php?plugin=rufus">
<h3><?php echo t('Файл'); ?>: .htaccess:</h3>
<?php echo t('В этом поле осуществляется только просмотр содержимого файла .htaccess'); ?>
<textarea name="_uhtaccess" rows="10" cols="20" onkeydown="_getElementById('urls').disabled = false;_getElementById('htaccess').disabled = true;"><?php  
echo $res; 
?></textarea>
<h3>Файл: <?php echo (!empty($config['mod_rewrite']) ? "urls_on.ini" : "urls.ini"); ?>:</h3>
<textarea name="urls_content" rows="10" cols="20" onkeydown="_getElementById('urls').disabled = false;_getElementById('htaccess').disabled = true;"><?php echo file_read(rufus_file); ?></textarea>
<br><br>
<input type="submit" name="urls" id="urls" value="  <?php echo t('Сохранить').' '.(!empty($config['mod_rewrite']) ? "urls_on.ini" : "urls.ini"); ?>  " disabled>
<input type="submit" name="htaccess" id="htaccess" value="  <?php echo t('Создать'); ?> .htaccess  ">
<input type="hidden" name="plugin" value="rufus">
</form>

<?php

//////////////////////////////////////////////////////////////////////////////////////
  if (!empty($_POST['urls'])) {
    if (!is_writable(rufus_file)) {
      @chmod(rufus_file, 0666);
    }
        $uhtaccess->settings = !empty($_POST['uhtaccess']) ? $_POST['uhtaccess'] : "";
        $uhtaccess->save();
    file_write(rufus_file, replace_news('admin', $_POST['urls_content']));
    @chmod(rufus_file, 0444);
  }
//////////////////////////////////////////////////////////////////////////////////////
  if (!empty($_POST['htaccess'])) {
  
    if (!is_writable($settings['abs'].'/.htaccess')) {
      @chmod($settings['abs'].'/.htaccess', 0666);
    }

$res = str_replace("#_strawberry\r\n", "", $res);
$res = str_replace("|||strawberry|||htaccess|||", "", $res);
$res = str_replace("\r\n\r\n", "\r\n", $res);
$res = str_replace("\r\n\r\n", "\r\n", $res);
$res = preg_replace("#\#\#\#\r\n\#_strawbery_rewrite_begin(.*)\#_strawbery_rewrite_end\r\n\#\#\##si", "|||strawberry|||htaccess|||", $res);

$htawr = "#_strawberry\r\n\r\n";
$htawr .= str_replace("|||strawberry|||htaccess|||", "\r\n###\r\n#_strawbery_rewrite_begin\r\n".implode("\r\n", $htaccess)."\r\n\r\n#_strawbery_rewrite_end\r\n###\r\n", $res);

      file_write($settings['abs'].'/.htaccess', $htawr);
      @chmod($settings['abs'].'/.htaccess', 0444);
  }
//////////////////////////////////////////////////////////////////////////////////////
  echofooter();
}














function htaccess_rules_replace($output){
global $categories, $config;

  if (!empty($_POST['catid']) and !empty($_POST['name'])){
    $categories[$_POST['catid']]['url'] = ($_POST['url'] ? $_POST['url'] : totranslit($_POST['name']));
    $categories[$_POST['catid']]['parent'] = $_POST['parent'];
  }

  if (!empty($categories) and !empty($config['mod_rewrite'])){
      foreach ($categories as $k => $row){
        $cat[] = $row['url'];

          if (empty($row['parent'])){
              $cats[] = $row['url'];
          } else {
              $cats[] = category_get_link($k);
          }
      }

      if (!empty($cats)){
          $cats = join('|', $cats);
          $cats = '(none|'.$cats.')';
      }

      if (!empty($cat)){
          $cat = join('|', $cat);
          $cat = '(none|'.$cat.')';
      }

  } else {
      $cat  = '([_0-9a-z-]+)';
      $cats = '([/_0-9a-z-]+)';
  }

    $output = preg_replace('/{(.*?):(.*?)}/i', '{\\1}', $output);
    $output = run_filters('htaccess-rules-replace', $output);
    $output = strtr($output, array(
            '{id}'              => '([0-9]+)',
            '{year}'          => '([0-9]{4})',
            '{month}'        => '([0-9]{2})',
            '{Month}'        => '([0-9a-z]{2,3})',
            '{day}'           => '([0-9]{2})',
            '{title}'           => '([_0-9a-z-]+)',
            '{url}'             => '([_0-9a-z-]+)',
            '{mod}'           => '([_0-9a-z-]+)',
            '{act}'            => '([_0-9a-z-]+)',
            '{snum}'         => '([0-9]+)',
            '{user}'          => /*'([_0-9a-zA-Z-]+)'*/'(.*)',
            '{user-id}'       => '([0-9]+)',
            '{category-id}' => '([0-9]+)',
            '{category}'    => $cat,
            '{categories}'  => $cats,
            '{skip}'           => '([0-9]+)',
            '{page}'         => '([0-9]+)',
            '{cpage}'        => '([0-9]+)',
            '{add}'           => '([_0-9a-z-]+)',
            )
            );

return $output;
}








function htaccess_rules_format($output, $result = false){

  $output = run_filters('htaccess-rules-format', $output);
  $output = str_replace('{Month', '{month', $output);
//$output = str_replace('{id}', '{id:rewrite_rule=id}', $output);
  $output = str_replace('{title}', '{id}', $output);
  $output = str_replace('{url}', '{id}', $output);
//$output = str_replace('{add}', 'add', $output);
  $output = str_replace('{categories', '{category', $output);
  $output = str_replace('{category-id', '{category', $output);
  $output = preg_replace('/{(.*?):(.*?)}/i', '{\\1}{\\2}', $output);
  $output = str_replace('{add}', '', $output);
  preg_match_all('/\{(.*?)\}/i', $output, $array);
  for ($i = 0; $i < count($array[1]); $i++){
  //$result .= ($i ? '&' : '?').(!eregi('=', $array[1][$i]) ? $array[1][$i].'=$'.($i + 1) : $array[1][$i]);
    $result .= (!empty($i) ? '&' : '?').(!preg_match('/=/', $array[1][$i]) ? $array[1][$i].'=$'.($i + 1) : $array[1][$i]);
  }
return $result;
}



// etc
add_filter('new-advanced-options', 'advanced_options_empty');
add_filter('edit-advanced-options', 'advanced_options_empty');
function advanced_options_empty($story){
  if ($story != 'short' and $story != 'full'){
    return $story;
  }
}


?>