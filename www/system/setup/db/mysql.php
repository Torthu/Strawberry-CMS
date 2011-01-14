<?php
#_strawberry

 if (!defined("str_setup")) die("Access dinaed");

$step = $_GET['step'];
$step = (!empty($step) ? $step : 1);

	$config['database']   = 'mysql';
	$config['dbname']     = $_POST['dbname'];
	$config['dbuser']     = $_POST['dbuser'];
	$config['dbpassword'] = $_POST['dbpassword'];
	$config['dbprefix']   = !empty($_POST['dbprefix']) ? $_POST['dbprefix'] : $dbprefix;
	$config['dbserver']   = $_POST['dbserver'];


if ($step != 1){
    include_once (root_directory."/system/inc/db/mysql.class.php");
	$MySQL = new MySQL();
	$MySQL->connect($config['dbuser'], $config['dbpassword'], $config['dbserver']);
	$MySQL->selectdb($config['dbname'], $config['dbprefix']);
}

include_once (root_directory."/system/setup/db/database.inc.php");

if ($step == 2){
}

if ($step == 3){
}

if ($step == 4){
	$config['dbname']     = '';
	$config['dbuser']     = '';
	$config['dbpassword'] = '';
	$config['dbprefix']   = '';
	$config['dbserver']   = '';
$form_act = "setup.php";
} else {
$form_act = "setup.php?mod=no111&amp;act=mysql&amp;step=".($step + 1);
}


?>

<form action="<?php echo way($form_act); ?>" method="post">
<table width="400px" border="0" cellspacing="1" cellpadding="1" align="center">

<?php




if ($step == 1){





?>
 <tr>
  <td colspan="2"><?php echo _CONNECT_DB; ?><hr></td>
</tr>
 <tr>
  <td width="200"><?php echo _LOGIN_DB; ?></td>
  <td><input name="dbuser" type="text" value=""></td>
 </tr><tr>
  <td><?php echo _PASS_DB; ?></td>
  <td><input name="dbpassword" type="text" value=""></td>
 </tr><tr>
  <td><?php echo _SERVER_DB; ?></td>
  <td><input name="dbserver" type="text" value="localhost"></td>
 </tr><tr>
  <td><?php echo _NAME_DB; ?></td>
  <td><input name="dbname" type="text" value=""></td>
 </tr><tr>
  <td><?php echo _PREFIX_DB; ?></td>
  <td><input name="dbprefix" type="text" value="straw_"></td>
</tr>
<?php





} elseif ($step == 2){



echo '<tr>
  <td colspan="2">'._CRATE_TAB_IN_DB.'<hr></td>
</tr>';

include (root_directory."/system/inc/db/mysql.inc.php");

$handle = opendir(root_directory."/system/setup/db/strawberry");
while ($file = readdir($handle)){
if (strtolower(end(explode('.', $file))) == "frm"){
#################################
$fle = unserialize(file_get_contents(root_directory."/system/setup/db/strawberry/".$file));
$string = " CREATE TABLE IF NOT EXISTS `".$config['dbprefix'].substr($file, 0, -4)."` (";
$ea = count($fle);
$ia = 0;
foreach ($fle as $flen => $flec){
$ia++;
if ($ia == 1 and $flen == "primary" and $flec) {
$string .= "".$flen." key (`".$flec."`),";
} elseif ($ia > 1 and $flen != "primary") {
if ($ia != $ea) { $ag = ","; } else { $ag = ""; }
$string .= "`".$flen."` ";

if ($flec['type'] == "int") {
$string .= " int(11) ";
} elseif ($flec['type'] == "string") {
$string .= " varchar(255)";
} elseif ($flec['type'] == "bool") {
$string .= " tinyint(1)";
} else {
$string .= " ".$flec['type'];
}

if (!$flec['default']) {
$string .= " default NULL";
} else {
$string .= " NOT NULL";
}

$string .= $ag;
}
}
$string .= ") ENGINE=MyISAM  DEFAULT CHARSET=cp1251;";
#################################
## echo $string;
$drtab = $db->sql_query($string);
  if (!empty($drtab)) {
    echo '<tr><td>'._TABLE.' "'.$config['dbprefix'].substr($file, 0, -4).'"</td><td><font color="green">'._OK.'</font></td></tr>';
  } else {
    echo '<tr><td>'._TABLE.' "'.$config['dbprefix'].substr($file, 0, -4).'"</td><td><font color="red">'._NO_OK.'</font></td></tr>';
  }
 }
}
echo "<input type=\"hidden\" name=\"dbuser\" value=\"".$config['dbuser']."\">
<input type=\"hidden\" name=\"dbpassword\" value=\"".$config['dbpassword']."\">
<input type=\"hidden\" name=\"dbserver\" value=\"".$config['dbserver']."\"></td>
<input type=\"hidden\" name=\"dbname\" value=\"".$config['dbname']."\">
<input type=\"hidden\" name=\"dbprefix\" value=\"".$config['dbprefix']."\">";



} elseif ($step == 3) {


include (root_directory."/system/inc/db/mysql.inc.php");

echo '<tr>
  <td colspan="2">'._IMPORT_IN_PREPARE_DB.'<hr></td>
</tr>';

$handle = opendir(root_directory."/system/setup/db/strawberry");
while ($file = readdir($handle)){
if (strtolower(end(explode('.', $file))) == "myd"){
#################################
$ba = 0;

$str = " INSERT INTO `".$config['dbprefix'].substr($file, 0, -4)."` (";
///////////////////////////////////////////////////////
$frm = unserialize(file_get_contents(root_directory."/system/setup/db/strawberry/".substr($file, 0, -4).".FRM"));
$ea = count($frm);
$ia = 0;
foreach ($frm as $frmn => $frmc){
$ia++;
if ($ia > 1 and $frmn != "primary") {
if ($ia != $ea) { $ag = ", "; } else { $ag = ""; }
$str .= "`".$frmn."`".$ag;
}
}
///////////////////////////////////////////////////////
$str .= ")  VALUES";
$myd = unserialize(file_get_contents(root_directory."/system/setup/db/strawberry/".$file));
#### print_r($myd); // покажет массив с данными
$aa = count($myd);
$ab = 0;
foreach ($myd as $myde){
$ab++;
$ba = count($myde);
$str .= ' (';
$bb = 0;
for ($bb = 0; $bb < $ba; $bb++) { $str .= "'".str_replace(">", "&gt;", str_replace("<", "&lt;", $myde[$bb]))."'  ".(($bb == ($ba-1)) ? '' : ', '); }
$str .=')'.(($ab == $aa) ? '' : ', ');
}

$str .= '; ';

if (empty($ba)) $str = "";
##########################
#echo $str."<hr><hr>";
$intab = $db->sql_query($str);
if ($intab) {
echo '<tr><td>'._DATA_OF_TABLE.' "'.$config['dbprefix'].substr($file, 0, -4).'"</td><td><font color="green">'._IT_IMPORT.'</font></td></tr>';
} else {
echo '<tr><td>'._DATA_OF_TABLE.' "'.$config['dbprefix'].substr($file, 0, -4).'"</td><td><font color="red">'._IT_NO_IMPORT.'</font></td></tr>';
}
}}

} 

if ($step != 4) {
?>
 <tr>
  <td colspan="2" align="center"><br><input type="submit" value="<?php echo _IT_NEXT; echo '('._STEP.' '.(($step + 1) == 4 ? _LAST_STEP : $step + 1).') &raquo;&raquo;'; ?>"></td>
</tr>
<?php
} else {
echo '<tr><td colspan="2">'._DB_CONVERTED_111.'<hr></td></tr>';
?>
 <tr>
  <td colspan="2" align="center"><br><input type="submit" value="<?php echo _DO_LAST_UPD_DB; ?>"></td>
</tr>
<?php } ?>
</table>
</form>