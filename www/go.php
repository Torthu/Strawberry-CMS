<?php
#_strawberry

error_reporting(E_ALL);
$ap = 1; // ������, ��� �� �� �������� ���������� ��� ����� �����
define("str_define", true);
define('root_directory', str_replace("\\", "/", dirname(__FILE__)."/system"));


### �����
$debug = 0; // ����� �������. 1 - ��������, 0 - ���������.


### ���������� �������
include_once root_directory.'/inc/defined.inc.php';


### ���������� - ���� �� ����������. ���� ���, �� �������� �� �������!
if (!empty($_GET['go']) and $sesuser != "robot") {

### ip/referer
//$ip = getip();
$from = function_exists('getref_self') ? getref_self() : (getenv('HTTP_REFERER') ? getenv('HTTP_REFERER') : (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ""));

### ��������� ������
$go = ""; $i = 0;
foreach ($_GET as $gk => $gv) {
$i++;
$go .= ($gk == "go" and $i == 1) ? $gv : "&".$gk."=".$gv;
}

$go = urldecode(rurl($go, "http"));

} else {
@header("Location: ".way($config['home_page']));
exit();
}
### ��������� ����������� ��� ��������


if (!empty($debug) and !empty($from) and $sesuser != "robot") {

### ���� ����� �������, �� ������� ����������:
echo "������ �� ��������: ".$from."<br>";
echo "��� ����: <a href=\"".$config['http_home']."\">".$config['http_home']."</a><br>";
echo "����������� ����: ".$_GET['go']."<br>";
echo "����� ���������: ".$go."<br>";
echo "��������������� ��: <a href=\"".$go."\">".$go."</a><br>";
echo '�������: '.langdate('Y-m-d H:i:s', time).'<br>';
echo "��� ip: ".$ip;

} elseif (!empty($from) and $sesuser != "robot") {

### ���� ���������� �����, �� ������� ������� ��� � ��������� �� ��������� ����!

$str_check = $db->sql_query("SELECT id FROM ".$config['dbprefix']."golink WHERE link LIKE '%".$go."%'");
if ($db->sql_numrows($str_check) > 0) {
list($go_id) = $db->sql_fetchrow($str_check);
$db->sql_query("UPDATE ".$config['dbprefix']."golink SET link = '".$go."', referer = '".$from."', counter = counter + 1, date = '".time."', ip = '".$ip."' WHERE id = '".$go_id."' ");
} else {
$db->sql_query("INSERT INTO ".$config['dbprefix']."golink VALUES (NULL, '".$go."', '".$from."', 1, '".time."', '".$ip."')");
}
// spesta
loggg(db_directory."/clicks.txt", $go);
@header("Location: ".$go);

} elseif (!empty($from) and $sesuser == "robot") {

@header("Location: ".$go);

} else {

@header("Location: ".way($config['home_page']));

}

exit();
?>