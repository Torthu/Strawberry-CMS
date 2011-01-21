<?php
#_strawberry
if (!defined("str_adm")) { 
header("Location: ../../index.php"); 
exit; 
}

/**
 * ����������� ������� Strawberry 1.1.1, ������� ������ ��������.
 * ���� �������� "������������" - ��� ������, ��� ��������� ������ �������
 * ������� �� ����� ���������� � ��������� ����������.
 * ����� ��������� ���, ��� � ���� ������� ��� ������ �������, �� - ��� - ��� ������������� �
 * ���, ��������, �������� �������� �� ������...
 * @package Functions
 **/

/**
* 
* ����� ����� ����� � �����,
* �������������.
* �������� ����� � ������,
* ����� �������.
* 
* �����-����� ����� � ����,
* �� ���������,
* ������ �� �� ���� �� ����,
* ������� ������. (�)
* 
**/

/*# strawberry original functions #*/

/**
 * ��������� ����������� ���������� ���� ������ � ��.
 * ��������� ����������� ���������� ���� ������ ��� ������������ $username
 * ���� ������ � ��. � �������� ���� ������ ���� ������ ���������� �������� md5x().
 * �����, � ������� ������, �������� � ���������� ���������� $member ������,
 * ���������� ��� ���������� � ��������������� ������������.
 * @see md5x()
 * @param string $username
 * @param string $md5_password
 * @return bool
 */
function check_login($username, $md5_password, $pa){
global $member, $users, $db, $config, $sql_error_out;
if (!empty($users) and !empty($username) and !empty($md5_password)) {
$member = array();
$result = false;

 foreach ($users as $row){
  if (strtolower($username) == strtolower($row['username']) and $md5_password == $row['password']) {
  $result = true;
  $member = $row;
  ## LAST VISIT RECORD
  ## ���� ���� ���������� ������ �� ��������� � ������� �����, �� ���������� ������� ����
    if (langdate('jmY', $member['last_visit']) != langdate('jmY', time) or empty($member['last_visit'])) {
    $db->sql_query("UPDATE ".$config['dbprefix']."users SET last_visit='".time."' WHERE id='".$member['id']."' ");
    }
  ## // LAST VISIT RECORD 
  }
 }

return $result;
} else {
$sql_error_out = "mysql";
}
}




function is_admin() {
global $member;
  if (!empty($member) and is_array($member)) {
   return (($member['usergroup'] == 1) ? 1 : 0);
  } else {
   return 0;
  }
}



/**
 * ��������� ������ ��� �������.
 *
 * ��������� ������ $q_string (����� ��� $_SERVER['QUERY_STRING'], ��������)
 * ��� ������� ���� $type (POST ��� GET), ��������� ����������,
 * ��������� � ������� $strips.
 *
 * ����. �����: �� $strips ���� ������� - �������� ������������
 * ���������� ������ ���� �� ��������� �������, � ��� ������.
 *
 * @param string $q_string
 * @param array $strips
 * @param string $type
 * @return string
 */
function straw_query_string($q_string, $strips=array(), $type = 'get'){

    foreach ($strips as $key){
      $strips[$key] = true;
    }

    foreach(explode('&', $q_string) as $var_peace){
        $parts = explode('=', $var_peace);
        $my_q = "";

        if (!empty($parts[0]) and empty($strips[$parts[0]])){
            if (!empty($type) and strtolower($type) == 'post'){
              $my_q .= '<input type="hidden" name="'.$parts[0].'" value="'.$parts[1].'" />';
            } else {
              $my_q .= '&'.$var_peace;
            }
        }
    }

return $my_q;
}

/**
 * ������� ������ ������� (��� ACP).
 *
 * ������� ������ ������� � ���������� $title, ��������� $description
 * � ����� ����� $field � ������ ����������.
 *
 * @param string $title
 * @param array $description
 * @param string $field
 * @return string
 */
function showRow($title = '', $description = '', $field = ''){
global $i;

    if ($i%2 !== 0 and $title){
      $bg = 'class="enabled"';
    } else {
      $bg = 'class="disabled"';
    }

    echo '<tr '.$bg.'>
           <td width="65%" valign="top" height="20" colspan="2" class="opt-title">&nbsp;<b>'.$title.'</b></td>
            <td width="35%" rowspan="2" valign="middle" align="left" class="opt-space">'.$field.'</tr>
         <tr '.$bg.'>
           <td width="20" class="opt-space">&nbsp;</td>
            <td valign="top" class="opt-desc">'.$description.'</td>
         </tr>';

    $bg = '';
    $i++;
}

/**
 * ������� ���������� ����.
 *
 * ������� ������� ����� select � ���������� name = $name �
 * ������� �������� option �� �����. ������� $options (['value'] => 'description')
 * � ���������� selected ��� ��������, ���� $selected = �������� ����� $options.
 *
 * @param array $options
 * @param string $name
 * @param string $selected
 * @return string
 */
function makeDropDown($options, $name, $selected = '', $dd_style=''){
$output = "";
    foreach ($options as $value => $description){
      $output .= '<option value="'.$value.'"'.(($selected == $value) ? ' selected ' : '').'>'.$description.'</option>';
    }
return '<select size="1" id="'.$name.'" name="'.$name.'" '.$dd_style.'>'.$output.'</select>';
}

/**
 * Enter description here...
 *
 * @access private
 *
 * @param string $ip
 * @param int $id
 * @return bool
 */
function flooder($ip, $id){
global $config, $db;
$row_flod_db = $db->sql_query("SELECT * FROM ".$config['dbprefix']."flood");
while ($row=$db->sql_fetchrow($row_flod_db)) {
  #  foreach ($sql->select(array('table' => 'flood')) as $row)
      if ($row['ip'] == $ip and $row['post_id'] == $id){
      
          if (($row['date'] + $config['flood_time']) > time){
             return true;
          } elseif (!empty($row['date']) and !empty($row['ip']) and !empty($row['post_id'])) {
             $db->sql_query("DELETE FROM ".$config['dbprefix']."flood WHERE date = '".$row['date']."' AND ip = '".$row['ip']."' AND post_id = '".$row['post_id']."' ");
          }
        }
    }
return false;
}

/**
 * ������� ��������� � ACP.
 *
 * ������� ��������� $text, � ���������� $title � ��������� $type � �������� ������ ����������,
 * �������� ���������� ���������� ������� � ������ ����������.
 * ���� $back = true, �� ����� �������� ������, ������� �� ���������� ��������.
 * � $type ����� �������� ��� ����� �������� �� admin/$skin_prefix/.
 *
 * @see echoheader(), echofooter()
 *
 * @param string $type
 * @param string $title
 * @param string $text
 * @param string $back
 */
function msg($type, $title, $text, $back = ''){
  echoheader($type, $title);
  echo '<table border="0" cellpading="0" cellspacing="0" width="100%" height="100%"><tr><td>'.$text.(!empty($back) ? '<br><br><a href="'.$back.'">'.t('��������� �����').'</a>' : '').'</table>';
  echofooter();
  exit;
}

/**
 * ������� ������� ����� ������� ACP.
 *
 * ������� ������� ����� ������� ������ ���������� � ��������� $image
 * � ���������� $header_text.
 *
 * @param string $image
 * @param string $header_text
 */
function echoheader($image, $header_text){
global $is_logged_in, $config, $skin_header, $skin_menu, $skin_prefix;

if (is_dir(root_directory."/setup")) $skin_header .= "<center><font color=\"red\" size=\"4\">".t('������� � ������� �����')." ".sway("<u><b>setup</b></u>")."</font></center>";

    if (!empty($is_logged_in)){
      $skin_header = str_replace('{menu}', $skin_menu, $skin_header);
    } else {
      $skin_header = str_replace('{menu}', ' &nbsp; '.$config['version_name'], $skin_header);
    }
    
$skin_prefix = $skin_prefix ? $skin_prefix : "default";
if (is_file(admin_skins_directory.'/'.$skin_prefix.'/images/'.$image.'.gif')) {
$image = sway("admin/themes/".$skin_prefix."/images/".$image.'.gif');
} else {
$image = sway("admin/themes/".$skin_prefix."/images/default.gif");
}
    $skin_header = str_replace('{image-name}', $image, $skin_header);
    $skin_header = str_replace('{header-text}', $header_text, $skin_header);
    $skin_header = str_replace('{copyrights}', '<div style="font-size: 9px; text-transform: uppercase;"><a target="_blank" title="Original Strawberry 1.1.2" href="http://&#115;&#116;&#114;&#97;&#119;&#98;&#101;&#114;&#114;&#121;&#46;&#103;&#111;&#111;&#100;&#103;&#105;&#114;&#108;&#46;&#114;&#117;/" style="font-size: 9px;">'.$config['version_name'].' 1.1.2</a> powered by <a target="_blank" title="GoodGirl" href="http://&#103;&#111;&#111;&#100;&#103;&#105;&#114;&#108;&#46;&#114;&#117;/" style="font-size: 9px;">goodgirl.ru</a> &copy; 2006 - '.date('Y').'<br><a target="_blank" title="Strawberry 1.2.x" href="http://www.&#115;&#116;&#114;&#97;&#119;&#98;&#101;&#114;&#114;&#121;&#46;&#115;&#117;/" style="font-size: 9px;">Strawberry 1.2.x</a> powered by <a target="_blank" title="Miksar Group Corporation" href="http://www.&#109;&#103;&#99;&#111;&#114;&#112;&#46;&#114;&#117;/" style="font-size: 9px;">MGCorp.ru</a> &copy; 2009 - '.date('Y').'</div>', $skin_header);
    echo $skin_header;
}

/**
 * ������� ������ ����� ������� ACP.
 * @return void
 */
function echofooter(){
global $is_logged_in, $config, $skin_footer, $skin_menu, $skin_prefix;
    $skin_footer = str_replace('{copyrights}', '<div style="font-size: 9px; text-transform: uppercase;"><a target="_blank" title="Original Strawberry 1.1.2" href="http://&#115;&#116;&#114;&#97;&#119;&#98;&#101;&#114;&#114;&#121;&#46;&#103;&#111;&#111;&#100;&#103;&#105;&#114;&#108;&#46;&#114;&#117;/" style="font-size: 9px;">'.$config['version_name'].' 1.1.2</a> powered by <a target="_blank" title="GoodGirl" href="http://goodgirl.ru/" style="font-size: 9px;">goodgirl.ru</a> &copy; 2006 - '.date('Y').'<br><a target="_blank" title="Strawberry 1.2.x" href="http://www.&#115;&#116;&#114;&#97;&#119;&#98;&#101;&#114;&#114;&#121;&#46;&#115;&#117;/" style="font-size: 9px;">Strawberry 1.2.x</a> powered by <a target="_blank" title="Miksar Group Corporation" href="http://www.&#109;&#103;&#99;&#111;&#114;&#112;&#46;&#114;&#117;/" style="font-size: 9px;">MGCorp.ru</a> &copy; 2009 - '.date('Y').'</div>', $skin_footer);
    echo $skin_footer;
}



/**
 * Enter description here...
 *
 * @access private
 *
 * @param string $way
 * @param string $sourse
 * @return string
 */
function replace_comment($way, $sourse){
global $config;

$find = array();
$replace = array();

    if ($way == 'add'){
    
      $find    = array("\r", "\n", "'", "\\", "&");
      $replace = array("", "{nl}", "&#039;", "&#092;", "&amp;");
      $sourse  = str_replace("&amp;", "&", htmlspecialchars($sourse));
      
      if (!get_magic_quotes_gpc()){
        $sourse = addslashes($sourse);
      }


    } elseif ($way == 'show'){
      $find    = array('{nl}', '&#039;', '&amp;#151;', '&#151;', '&#092', '&amp;');
      $replace = array('<br>', '\'', '�', '�', "\\", '&');
      $sourse = stripslashes($sourse);

        foreach (explode(',', $config['smilies']) as $smile){
            $find[]    = ':'.trim($smile).':';
            $replace[] = '<img border="0" alt="'.trim($smile).'" src="'.sway("data/emoticons/".trim($smile).".gif").'" />';
        }
        
    } elseif ($way == 'admin'){

      $find    = array("<br>", "{nl}", "&#039;", "&#092;", "&amp;");
      $replace = array("\n", "\n", "'", "\\", "&");
      $sourse  = stripslashes(unhtmlentities($sourse));

    }

return str_replace($find, $replace, trim($sourse));
}

/**
 * Enter description here...
 *
 * @access private
 *
 * @param string $way
 * @param string $sourse
 * @param bool $replce_n_to_br
 * @param bool $use_html
 * @return string
 */
function replace_news($way, $sourse, $replce_n_to_br = true, $use_html = true){
global $config;

    if ($way == 'show'){
         $find    = array('{nl}', '&#039;', '&amp;#151;', '&#151;', '&#092');
         $replace = array('<br>', '\'', '�', '�', "\\");
         $sourse  = stripslashes($sourse);

        foreach (explode(',', $config['smilies']) as $smile){
            $find[]    = ':'.trim($smile).':';
            $replace[] = '<img border="0" alt="'.trim($smile).'" src="'.sway("data/emoticons/".trim($smile).".gif").'" />';
        }
    } elseif($way == 'add'){
        $find    = array("\r", "\n", "'", "\\");
        $replace = array("", "{nl}", "&#039;", "&#092;");

        if (!get_magic_quotes_gpc()){
          $sourse = addslashes($sourse);
        }
        
    } elseif ($way == 'admin'){
        $find    = array("<br>", "{nl}", "&#039;", "&#092;");
        $replace = array("\n", "\n", "'", "\\");
        $sourse  = stripslashes($sourse);
    }

return str_replace($find, $replace, trim($sourse));
}

/**
 * Enter description here...
 *
 * @access private
 *
 * @param array $array
 * @param bool $bool
 * @return string
 */
function echo_r($array, $bool = false){
    ob_start();
    if (is_bool($array)){
      echo ($array ? 'true' : 'false');
    } else {
      print_r($array);
    }

    $echo = ob_get_contents();
    ob_clean();

    if ($bool){
      return highlight_string($echo, true);
    } else {
      echo highlight_string($echo, true);
    }
}

/**
 * ���������� �����.
 *
 * ���������� �����, ������������ $to, � ����� ������ $subject � ���������� $message.
 * �������� "��������������" ����� $filename � ������.
 *
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param string $filename
 */
function straw_mail($to, $subject, $message, $filename = ''){
global $config;

  $mail     = 'no-reply@'.str_replace('www.', '', $_SERVER['SERVER_NAME']);
  $uniqid   = md5(uniqid(time));
  $headers  = 'From: '.$mail."\n";
  $headers .= 'Reply-to: '.$mail."\n";
  $headers .= 'Return-Path: '.$mail."\n";
  $headers .= 'Message-ID: <'.$uniqid.'@'.$_SERVER['SERVER_NAME'].">\n";
  $headers .= 'MIME-Version: 1.0'."\n";
  $headers .= 'Date: '.gmdate('D, d M Y H:i:s', time)."\n";
  $headers .= 'X-Priority: 3'."\n";
  $headers .= 'X-MSMail-Priority: Normal'."\n";
  $headers .= 'X-Mailer: '.$config['version_name'].' '.$config['version_id']."\n";
  $headers .= 'X-MimeOLE: '.$config['version_name'].' '.$config['version_id']."\n";
  $headers .= 'Content-Type: multipart/mixed;boundary="----------'.$uniqid.'"'."\n\n";
  $headers .= '------------'.$uniqid."\n";
  $headers .= 'Content-type: text/plain;charset='.$config['charset']."\n";
  $headers .= 'Content-transfer-encoding: 7bit';
  
$message = aply_bbcodes($message);

    if (is_file($filename)){
      $file     = fopen($filename, 'rb');
      $message .= "\n".'------------'.$uniqid."\n";
      $message .= 'Content-Type: application/octet-stream;name="'.basename($filename).'"'."\n";
      $message .= 'Content-Transfer-Encoding: base64'."\n";
      $message .= 'Content-Disposition: attachment;';
      $message .= 'filename="'.basename($filename).'"'."\n\n";
      $message .= chunk_split(base64_encode(fread($file, filesize($filename))))."\n";
  }

  mail($to, $subject, $message, $headers);
}

/**
 * ���������� ������ ����� ������.
 *
 * ���������� ������ ����� ���� ������ � ������ �� $mod � ���������� $dir.
 *
 * @link http://forum.dklab.ru/php/advises/FaylovieFunktsii.html
 *
 * @param string $dir
 * @param int $mod
 * @return bool
 */
function chmoddir($dir, $mod){

  $handle = opendir($dir);
  while (false !== ($file = readdir($handle))){
      if ($file != '.' and $file !== '..'){
          if (is_file($dir.'/'.$file)){                        /// HOT FIX!
              chmod($dir.'/'.$file, $mod);
          } else {
              chmod($dir.'/'.$file, $mod);
              chmoddir($dir.'/'.$file, $mod);
          }
      }
  }
  closedir($handle);

    if (chmod($dir, $mod)){
      return true;
    } else {
      return false;
  }
}





/**
 * Enter description here...
 *
 * @access private
 *
 * @param string $action
 * @param array $sort
 * @return array
 */
function c_array($action, $sort = ''){
global $sql, $db, $config;

  if (is_array($sort)){
    $query = array('table' => $action, 'orderby' => $sort);
  } else {
    $query = array('table' => $action);
  }

    foreach ($sql->select($query) as $k => $v){
      $result[] = implode('|', $v);
    }

return ($result ? $result : array());
}

/**
 * �������� ��� �� ����.
 *
 * ������� �������, �������� ��� ���������� ������ $dick
 * �� ���� � �������� ��� ���������� $dick �� "�����" ������ $chicken.
 *
 * @param string $chicken
 * @param string $dick
 * @return string
 */
function chicken_dick($chicken, $dick = '/'){
  $chicken = preg_replace('/^(['.preg_quote($dick, '/').']+)/', '', $chicken);
  $chicken = preg_replace('/(['.preg_quote($dick, '/').']+)/', $dick, $chicken);
  $chicken = preg_replace('/(['.preg_quote($dick, '/').']+)$/', '', $chicken);
return $chicken;
}

/**
 * ������ ������ � ����.
 *
 * ���������� ������ $fwrite � ���� $fopen, ������� ������ ����� ����� $fopen �� $chmod
 * ��� ����� �� ���������, ���� �������� $chmod = false.
 *
 * ���� $clear = true, �� ������ ����� �������� � ���� ��� �������� �������� ������ �
 * �������� �������.
 *
 * @param string $fopen
 * @param string $fwrite
 * @param bool $clear
 * @param int $chmod
 */
function file_write($fopen = '', $fwrite = '', $clear = false, $chmod = '', $type = 'wb+', $crf = false){
global $config;
  if (!empty($clear)) {
    $fwrite = str_replace(array("  ","\r\n"), "", $fwrite);
  }
  $chmod = (!empty($chmod)) ? intval($chmod, 8) : intval($config['chm_file'], 8);
// ���� ����� ���, �� ������� �����...
if (!empty($crf)) {
    $dir = explode('/', chicken_dick($fopen));
    if (count($dir) > 1){
      for ($i = 0; $i < (count($dir) - 1); $i++){
        $path .= $dir[$i].'/';
        if (!is_dir($path)){ @mkdir($path); @chmod($path, intval($config['chm_dir'], 8)); }
      }
    }
}

    @chmod($fopen, 0666);
    $fp = @fopen($fopen, $type);
    flock($fp, 2);
    fwrite($fp, $fwrite);
    flock($fp, 3);
    fclose($fp);
    if(!empty($chmod)) { @chmod($fopen, $chmod); } else { @chmod($fopen, 0644); }
}



/**
 * ������ �� �����.
 *
 * ���������� ���������� ����� $filemame ��� false.
 *
 * @param string $filemame
 * @return string
 */
function file_read($filemame){

if (empty($filemame) or !is_file($filemame)){
  return false;
} else {

$fs = filesize($filemame);
if (empty($fs)) {
  return;
} else {
  $fp = fopen($filemame, 'rb');
  $fo = fread($fp, $fs);
  fclose($fp);
  return $fo;
}
 
}

}

/**
 * ���������� ������������� ������ � ���������� ���� $uri.
 *
 * ���������� ������������� ������, ���������� �������� parse_url(),
 * + ���� abs - ���������� ���� � ���������.
 *
 * @param string $url
 * @return array
 */
function straw_parse_url($url){
global $DOCUMENT_ROOT;

if(!empty($url)) {
    $url         = parse_url($url);
    $url['path'] = !empty($url['path']) ? chicken_dick($url['path']) : '';
    $url['abs']  = $DOCUMENT_ROOT.'/'.$url['path'];

    if (is_file($url['abs'])){
      $url['file'] = end($url['file'] = explode('/', $url['path']));
      $url['path'] = chicken_dick(preg_replace('/'.$url['file'].'$/i', '', $url['path']));
      $url['abs']  = $DOCUMENT_ROOT.'/'.$url['path'];
    }
return $url;
} else {
return;
}

}

/**
 * ���������� ���, �������������� �� ��������� �������� �� urls.ini.
 *
 * ���������� ���, �������������� �� ������� $type ������ $format �� urls.ini.
 *
 * ����. �����: ������ ������ - �����-�� ���������� �������, ������ ������ ��������
 * ��� � ������ ��� ��� ��������.
 *
 * @param array $arr
 * @param string $type
 * @param string $format
 * @return string
 */
function straw_get_link($arr, $type = 'post', $format = '', $nf='0'){
global $config, $users, $link, $rufus_file, $QUERY_STRING, $_SERVER, $mod, $skip, $act, $pnum, $ap;
static $c = array();
$uri = "";
#     if ($type == 'skip' or $type == 'page' or $type == 'cpage' or  $type == 'pnum')
if ($type == 'page' or $type == 'cpage'){
      $mask = preg_replace('/(\?|&)'.$type.'\=([0-9]+)/', '', $_SERVER['REQUEST_URI']);
      $mask = $mask.(strstr($mask, '?') ? '&' : '?').$type.'='.(!empty($arr[$type]) ? $arr[$type] : 0);
       return $mask;
     }

  # ��������� ���-�� �����!
  if (empty($rufus_file) and empty($ap)){
    $rufus_file = parse_ini_file(rufus_file, true);
  } elseif (!empty($ap)) {
    $rufus_file = parse_ini_file(rufus_file_off, true);
  }

    if ($link and !$format){
      $format = chicken_dick($link);
    } elseif (empty($link) and empty($format)){
      $format = 'home';
    }

    if (!is_array($arr)){
      global $row;

      $string = explode('/', $arr);
      $type   = end($string);
      unset($string[(count($string) - 1)]);
      $format = join('/', $string);
      $arr    = $row;
    }

    if (empty($arr['date']) and ($type != 'skip') and ($type != 'pnum')){
      $arr['category'] = !empty($arr['id']) ? $arr['id'] : 0;
    }

    if (empty($arr['author'])){
      $arr['author']  = !empty($arr['username']) ? $arr['username'] : t("����������");
      $arr['user_id'] = !empty($arr['id']) ? $arr['id'] : 0;
    } else {
      $arr['user_id'] = !empty($users[$arr['author']]['id']) ? $users[$arr['author']]['id'] : t("����������") ;
    }

    if ($rufus_file[$format][$type]){
      $rufus_file[$type] = $rufus_file[$format][$type];
    } else {
      $rufus_file[$type] = $format;
      $QUERY_STRING = straw_query_string($QUERY_STRING, array($type));
    }

    if (empty($c)){
      $c = array(
      'home_url' => straw_parse_url($config['http_home_url']), 
      'script_url' => straw_parse_url($config['http_script_dir']), 
      'q_string' => straw_query_string($QUERY_STRING, 
          array(
          'category', 'skip', 'subaction', 'id', 'ucat', 'year', 'month', 'day', 'user', 'page', 'search', 'do', 'PHPSESSID', 
          'title', 'time', 'start_from', 'archives', 'cpage', 'action', 'act', 'numb', 'mod', 'pnum'
                 )
                                                 )
                    );
    }

    $mask     = run_filters('cute-get-link', array('arr' => $arr, 'link' => $rufus_file[$type]));
    $mask     = $mask['link'];
    $mask     = reset($mask = explode(':', $mask));
  $category = reset($cat = (!empty($arr['category']) ? explode(',', $arr['category']) : array()));
 $arr_date = !empty($arr['date']) ? $arr['date'] : 0;
  $arr_title = !empty($arr['title']) ? totranslit($arr['title']) : "";
    $mask     = strtr($mask, array(
            '{add}'         => '',
                '{year}'        => date('Y', $arr_date),
            '{month}'       => date('m', $arr_date),
            '{Month}'       => strtolower(date('M', $arr_date)),
                '{day}'         => date('d', $arr_date),
                '{title}'       => (!empty($arr['url']) ? $arr['url'] : $arr_title),
                '{url}'         => (!empty($arr['url']) ? $arr['url'] : $arr_title),
'{mod}'         => (!empty($arr['mod']) ? $arr['mod'] : (!empty($mod) ? $mod : $config['modul'])),
'{act}'         => (!empty($act) ? $act : ''),
'{snum}'         => (!empty($skip) ? $skip : '0'),
                '{user}'        => urlencode($arr['author']),
                '{user-id}'     => $arr['user_id'],
                '{category-id}' => (!empty($category) ? $category : '0'),
                '{category}'    => (!empty($category) ? end($cat = explode('/', category_get_link($category))) : 'none'),
                '{categories}'  => (!empty($category) ? category_get_link($category) : 'none'),
                '{pnum}' => (!empty($arr['pnum']) ? $arr['pnum'] : (!empty($pnum) ? $pnum : 1))
             ));

  foreach ($arr as $k => $v){
    $mask = str_replace('{'.$k.'}', $v, $mask);
  }

    if (!$config['mod_rewrite']){

      if (!empty($format) and $format == 'home' and !empty($c['home_url']['file'])){
        $result = $c['home_url']['path'].'/'.$c['home_url']['file'];
      } elseif (!empty($format) and substr($format, 0, 5) == 'home/'){
        $result = $c['home_url']['path'].'/'.substr($format, 5);
      } else {
        $result = $c['script_url']['path'].'/'.$format;
      }

     } else {

      if (!empty($format) and $format == 'home'){
        $result = $c['home_url']['path'];
      } elseif (!empty($format) and substr($format, 0, 5) == 'home/'){
        $result = $c['home_url']['path'].'/'.substr($format, 5);
      } elseif (!empty($uri) and substr($uri, 0, 1) == '?'){
        $result = $c['script_url']['path'].'/'.$format;
      } else {
        $result = $c['home_url']['path'];
      }

     }

    /*
    $c['q_string'] = (substr($c['q_string'], 0, 1) == '?' ? substr($c['q_string'], 1) : '');
    $c['q_string'] = (substr($c['q_string'], 0, 1) == '&' ? substr($c['q_string'], 1) : '');
    $c['q_string'] = (substr($c['q_string'], 0, 5) == '&amp;' ? substr($c['q_string'], 5) : '');
    $c['q_string'] = ($c['q_string'] ? (strstr($mask, '?') ? '&' : '?') : '').$c['q_string'];
    */

    $result = chicken_dick($result.'/'.$mask).$c['q_string'];
    $result = str_replace('/?', '?', $result);
    
    $result = htmlspecialchars($c['home_url']['scheme'].'://'.$c['home_url']['host'].(!empty($c['home_url']['port']) ? ':'.$c['home_url']['port'] : '').'/'.$result);

$result = str_replace($config['http_home'].'/','',$result);
##################
if (!empty($nf)) {
$result = str_replace($config['home_page'],$nf,$result);
}
##################

return $result;
}

/**
 * ���������� ������ �� ���������.
 *
 * ���������� ������ �� ��������� � id = $id, �������� ��� ���������-��������.
 * ����� ������� ������� ����, ������� �������� ���������� $link.
 *
 * @param int $id
 * @param string $link
 * @return string
 */
function category_get_link($id, $link = ''){
global $categories;

    if (!empty($categories[$id]['url'])){
      $link = $categories[$id]['url'].($link ? '/'.$link : '');
    }

    if (!empty($categories[$id]['parent'])){
      $link = category_get_link($categories[$id]['parent'], $link);
    }

return chicken_dick($link);
}


/**
 * ���������� ���� ����� ���������.
 * ���������� ���� ����� ��������� � id = $id � ���� ������ � id ���������, ����������� ��������.
 *
 * 15.10.2010 (!UPDATED)
 *
 * @author Scip
 * @remake Miksar
 * @param int $id
 * @return string
 */
function category_get_children($id="", $withid = true, $withsubcat = true){
global $categories;
$cgc_result = array();

if (!empty($categories) and !empty($id)) {
  $categories_dummy = $categories;  // u could avoid this if u RESET $categories;

  if (!empty($withid)){
    $cgc_result[] = $id;
  }

    foreach ($categories_dummy as $cat_id => $row){
      if ($row['parent'] == $id){
        $cgc_result[] = $cat_id;
        if (!empty($withsubcat)) {
          $sc = category_get_children($cat_id, false);
          if (!empty($sc)) {
            $cgc_result[] = $sc;
          }
        }
      }
    }

return implode(',', $cgc_result);
} else {
return;
}
}



/**
 * ���������� �������� ���������.
 *
 * ���������� �������� ��������� � id = $id, ������� �������� ���� ������������ ��������� ������ ���������, �������� ����������� ���� �� ����� ������������ $separator.
 * ����� ������� ������� ��������, ������� �������� ���������� $title.
 *
 * @param int $id
 * @param string $separator
 * @param string $title
 * @return string
 */

function category_get_title($id, $separator = ' &raquo; ', $title = ''){
global $categories;

    if (!empty($categories[$id]['name'])){
      $title = $categories[$id]['name'].(!empty($title) ? $separator.$title : '');
    }

    if (!empty($categories[$id]['parent'])){
      $title = category_get_title($categories[$id]['parent'], $separator, $title);
    }

return chicken_dick($title);
}

### ������ ���������
function cat_get_tit($id, $separator = ' &raquo; ', $title = ''){
global $categories;

$w_m = explode(",", $id);
foreach ($w_m as $iid) {
    if (!empty($categories[$iid]['name'])){
      $title = $categories[$iid]['name'].(!empty($title) ? ', '.$title : '');
    }
}

    if (!empty($categories[$id]['parent'])){
      $title = category_get_title($categories[$id]['parent'], $separator, $title);
    }

return chicken_dick($title);
}

/**
 * ���������� ����� ���������.
 *
 * ���������� ����� ���������, ��������� ������ $tpl � ������� (���������) $prefix ��� ������ ���������. ������ ����� ����� ��������� � id = $id ��� ����� ������� ������ ���� ���������, ���� $id = 0.
 *
 * ���� ��� ������������� � ������� ������:
 * {name} - �������� ���������,
 * {url} - ��� ���������,
 * {prefix} - �������� $prefix,
 * {id} - ID ���������,
 * {icon} - ������ ���������,
 * {template} - ������ ���������,
 * [php] � [/php] - ����� ����� ������ ����������� php-���, ������� ����� �������� (��������: [php]function({id})[/php]).
 *
 * ����� ����� �������� ������ �������� ��� ������ ����� ������� ���������� $no_prefix �������� true.
 *
 * @param string $prefix
 * @param string $tpl
 * @param bool $no_prefix
 * @param int $id
 * @return string
 */
function category_get_tree($prefix = "", $tpl = "", $no_prefix = true, $id = 0, $nf="", $wdc = false){
global $categories;
$johnny_left_teat = "";
    $minus = 0;
    if (!empty($categories)){
    if (empty($wdc)) {
    $scategories = sort_it($categories);
    } else {
    $scategories = $categories;
    }
      foreach ($scategories as $row){
      
          if (!empty($id)){
            if ($id == $row['id']){
              $minus++;
              continue;
            }

            if (!in_array($crow['id'], explode(',', category_get_children($id)))){
              $minus++;
              continue;
            }
          }

          if (empty($tpl)) {
            $tpl = "<a href=\"".(straw_get_link($row, 'category'))."\">{name} (".count_category_entry('{id}').")</a><br>";
          } 

          $pref = (!empty($no_prefix) ? $row['level'] : ($row['level'] + 1));
          $pref = (!empty($minus) ? ($pref - (!$no_prefix ? ($minus - 1) : ($minus - 1))) : $pref);
          $pref = @str_repeat($prefix, $pref);
          $pname = !empty($categories[$row['parent']]['name']) ? substr($categories[$row['parent']]['name'], 0, 10).((strlen($categories[$row['parent']]['name']) > 10 ) ? '...' : '') : '';
          $find = array('/{id}/i', '/{pname}/i', '/{name}/i', '/{parent}/i', '/{url}/i', '/{icon}/i', '/{template}/i', '/{prefix}/i', '/\[php\](.*?)\[\/php\]/ie', '/{description}/i', '/{numb}/i', '/{modul}/i', '/{mod}/i', '/{act}/i', '/{pnum}/i');
          $repl = array($row['id'], $pname, $row['name'], $row['parent'], $row['url'], (!empty($row['icon']) ? '<img src="'.$row['icon'].'" alt="'.$row['icon'].'" border="0" align="absmiddle">' : ''), $row['template'], $pref, '\\1', replace_news('show', $row['description']), $row['numb'], $row['modul'], '', '');
          $johnny_left_teat .= $pref.preg_replace($find, $repl, $tpl);
      }
  }

return $johnny_left_teat;
}

/**
 * ���������� id ���������.
 *
 * ���������� ������ ������������ ��� ���������, �����������
 * ����, ����������� �������� category_get_link();
 *
 * @see category_get_link()
 *
 * @param string $cat
 * @return int
 */
function category_get_id($cat){
global $categories;

    $cat = chicken_dick($cat);
    $cat_id = '0';

    foreach ($categories as $row){
      if ($cat == category_get_link($row['id'])){
        $cat_id = $row['id'];
      } elseif ($cat == category_get_title($row['id'], '/')){
        $cat_id = $row['id'];
      } elseif ($cat == $row['url']){
        $cat_id = $row['id'];
      } elseif ($cat == $row['id']){
        $cat_id = $row['id'];
      } elseif ($cat == '0' or $cat == 'none'){
        $cat_id = '0';
      }
    }

return $cat_id;
}

/**
 * Enter description here...
 *
 * @access private
 *
 * @param string $return1
 * @param string $return2
 * @param int $every
 * @return string
 */
function straw_that($return1 = 'class="enabled"', $return2 = 'class="disabled"', $every = 2){
static $i = 0;
  $i++;
  if ($i%$every == 0){
    return $return1;
  } else {
    return $return2;
  }
}

/**
 * ���������� ������ �� ��������� �����.
 *
 * ���������� ������ �� �������� ������ global.ini � $module.ini, ���� ������� ����������, � ���� �������.
 *
 * ������ �������� ��� �������� ������������ �� �����. ����������� t().
 *
 * @see t()
 *
 * @param string $module
 * @return array
 */
function straw_lang($module = ''){
global $config;

  $module = end($module = explode('/', $module));

  if (!file_exists($local = languages_directory.'/'.$config['lang'].'/'.$module.'.ini')){
    $local = languages_directory.'/ru/'.$module.'.ini';
  }

  if (file_exists($local)){
    $lang = @parse_ini_file($local, true);
  }

return $lang;
}







/**
 * ������� ������.
 *
 * @param string $str
 * @return string
 */
function md5x($str){
  $str = md5(md5($str));
return $str;
}






/**
 * ������� ��������� �������� ������� ������� htmlentities()
 *
 * @param string $string
 * @return string
 */
function unhtmlentities($string){

  $trans_tbl = get_html_translation_table(HTML_ENTITIES);
  $trans_tbl = array_flip($trans_tbl);

return strtr($string, $trans_tbl);
}








/**
 * Enter description here...
 *
 * @access private
 *
 * @param string $str
 * @return string
 */
function straw_namespace($str){
global $sql, $modul, $db, $config, $nid;
$result = array();
$count = 0;
  if (!empty($str)) {
    $row_news_db = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news ".(!empty($nid) ? "WHERE id!='".$nid."'" : "")." ");
////////////
    while ($row=$db->sql_fetchrow($row_news_db)) {
      if (@preg_match("#".$str."-([0-9]+)?#i", $row['url']) or $str==$row['url']) $count++;
      if (@preg_match("#".$str."-([0-9]+)?#i", $row['id'])  or $str==$row['id'])  $count++;
    }
////////////
  } else {
    $str = "";
  }
return $str.(!empty($count) ? '-'.$count : '');
}







/**
 * Enter description here...
 *
 * @access private
 *
 * @param array $array
 * @param int $id
 * @param array $field
 * @return array
 */
function sort_it($array, $id = 0, $field = array('parent', 'id'), $johnny_left_teat = ''){

  foreach ($array as $k => $row){
    if ($row[$field[0]] == $id){
      $johnny_left_teat[$k] = $row;
      $johnny_left_teat = sort_it($array, $row[$field[1]], $field, $johnny_left_teat);
    }
  }

return $johnny_left_teat;
}






### ����� ������������ �� � �� � ���������� �������.
function sort_it_comm($arrayc, $a=0, $b=0, $s=0) {
$row_left = array();
$z = 0;
$h = 0;
  foreach ($arrayc as $k => $crow) {
    if (!$crow['parent'] or $crow['parent'] == 0) {
      if ($z >= $a and $z < ($a + $b)) {
         if ($crow) {
           $row_left[$h] = $crow;
           $h++;

sort_it_comm_answer($arrayc, $crow['id'], $row_left, $h);
/*
  foreach ($arrayc as $zk => $acrow){
    if ($acrow['parent'] == $crow['id'] and $acrow['parent']){
      $row_left[$h] = $acrow;
      $row_left = sort_it_comm_answer($arrayc, $acrow['id'], $row_left, $acrow['id']);
      $h++;
    }
  }
*/

         }
      }
    $z++;
    }
  }
return $row_left;
}

### ����� ������� �� �����������.
function sort_it_comm_answer($arrayca, $cid = 0, $row_left, $h){
global $h, $row_left, $arrayc;
  foreach ($arrayca as $zk => $acrow){
    if ($acrow['parent'] == $cid and $acrow['parent']){
      $row_left[$h] = $acrow;
      $h++;
      $row_left = sort_it_comm_answer($arrayca, $acrow['id'], $row_left, $h);
    }
  }

return $row_left;
}







//print_r($member);

/**
 * Enter description here...
 *
 * @access private
 *
 * @param string $mod
 * @param string $section
 * @param array $arr
 * @return bool
 */
function straw_get_rights($mod = '', $section = 'permissions', $array = ''){
global $usergroups, $member;

    $array  = (!empty($array) ? $array : (!empty($member) ? $member : array()));
    $return = false;
    if (!empty($array['usergroup'])) {
    $group  = $usergroups[$array['usergroup']];
    } else {
    $group  = $usergroups;
    }

    if (!empty($group['access']) and $group['access'] == 'full'){
      $full = true;
    } else {
      $full = false;
    }

  if ($section == 'read' or $section == 'write'){
      if (!empty($full) or !empty($group['access'][$section][$mod])){
          $return = true;
      }
  } elseif ($section == 'permissions'){
      if (!empty($full)){
        $group[$section][$mod] = true;
          $group[$section]['approve_news'] = false;
          $group[$section]['categories'] = false;
      }

      if (!empty($group[$section][$mod])){
        $return = true;
      }
  } elseif ($section == 'fields'){
    if (!empty($full) or (!empty($group['permissions'][$section][$mod]) and $group['permissions'][$section][$mod] !== '0')){
      $return = true;
    }
  }

    if (!empty($mod) and $mod == 'full'){
      if (!empty($group['access']) and $group['access'] == 'full'){
        $return = true;
      } else {
        $return = false;
      }
    }

return $return;
}

/**
 * ��������� ����������� ������ � ini-����.
 *
 * @param string $filename
 * @param array $content
 * @return bool
 */
function write_ini_file($filename, $content){

  foreach ($content as $k => $v){
    if (is_array($v)){
      $result .= '['.$k.']'."\n";
      foreach ($v as $key => $value){
        $result .= $key.' = "'.$value.'"'."\n";
      }
    } else {
      $result .= $key.' = "'.$value.'"'."\n";
    }
  }

  if (@file_write($filename, $result)){
    return true;
  }

return false;
}

/**
 * ������ ����-�����, ��� � "������� �������������"
 *
 * @param string $name
 * @return string
 */
function makePlusMinus($name){
  $result = '<a href="javascript:ShowOrHide(\''.$name.'\', \''.$name.'-plus\')" id="'.$name.'-plus" onclick="javascript:ShowOrHide(\''.$name.'-minus\')" title="'.t('�������� �����').'">+</a><a href="javascript:ShowOrHide(\''.$name.'\', \''.$name.'-minus\')" id="'.$name.'-minus" style="display: none;" onclick="javascript:ShowOrHide(\''.$name.'-plus\')" title="'.t('������ �����').'">-</a>';
return $result;
}





function straw_setcookie($val_name, $val_value = '', $expire = '', $path = '', $domain = '', $secure = ''){
global $config;
$return = setcookie($config['cookie_prefix'].$val_name, $val_value, $expire, $path, $domain, $secure);
return $return;
}




function straw_stripslashes(&$item){
  if (is_array($item)){
    array_walk($item, 'straw_stripslashes');
  } else {
    $item = (get_magic_quotes_gpc() ? stripslashes($item) : $item);
  }
return $item;
}









function straw_htmlspecialchars(&$item){
  if (is_array($item)){
    array_walk($item, 'straw_htmlspecialchars');
  } else {
    $item = htmlspecialchars($item, ENT_QUOTES);
  }
return $item;
}







function array_save($filename, $array, $name = 'array'){
@chmod($filename,0666);
$fp = @fopen($filename, "w");
@flock($fp, LOCK_EX);
  $contents  = "<?php\r\n#_strawberry\r\n\tif (!defined(\"str_conf\")) {\r\n\theader(\"Location: ../../index.php\");\r\n\texit;\r\n\t}\r\n";
  $contents .= '$'.$name.' = ';
  $contents .= var_export($array, true);
  $contents .= ";\r\n";
  $contents .= "\r\n?>";
fwrite($fp, $contents);
@flock($fp, LOCK_UN);
@fclose($fp);
@chmod($filename,0555);
return;
}






function save_config($array){
@chmod(config_file,0666);
$fp = @fopen(config_file, "w");
@flock($fp, LOCK_EX);
  $contents  = "<?php\r\n#_strawberry\r\n\tif (!defined(\"str_conf\")) {\r\n\theader(\"Location: ../../index.php\");\r\n\texit;\r\n\t}\r\n";
  $contents .= '$config = ';
  $contents .= var_export($array, true);
  $contents .= ";\r\n";
  $contents .= '$allowed_extensions = array(\'gif\', \'jpg\', \'png\', \'bmp\', \'jpe\', \'jpeg\');';
  $contents .= "\r\n?>";
fwrite($fp, $contents);
@flock($fp, LOCK_UN);
@fclose($fp);
@chmod(config_file,0555);
return;
}





function tpl($func){
  if (!function_exists($func)){
    return false;
  } else {
    $args = func_get_args();
    array_shift($args); // ���������� ��� �������, �� �� ��� � ��� �����
    return call_user_func_array($func, $args);
  }
}




### ����� ������ �� ������������� ������
function function_help($func, $text = ''){
$result = '<a href="http://strawberry.goodgirl.ru/docs/function/'.$func.'" onclick="window.open(\'http://strawberry.goodgirl.ru/docs/function/'.$func.'\', \'_FunctionHelp\', \'height=480,resizable=yes,scrollbars=yes,width=600\');return false;">'.($text ? $text : $func).'</a>';
return $result;
}


?>