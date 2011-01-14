<?php
if (!defined("str_modul")) {
	header("Location: ../../../index.php");
	exit;
}

global $allrobots;

### Параметры модуля
$tit = t("Strawberry`s knowledge base about robots");
 otable();
###############################################################################


$ar = array();
$arv = array();
$all = $duble = $ununical = "";
$c = $cb = $cv = 0;

if (!empty($allrobots) and is_array($allrobots)) {
foreach ($allrobots as $rk => $rv) {
$c++;
$bb = trim(strtolower($rk));
$bv = trim($rv);
$all .= $c.") ".strtolower($bb)." = ".$rv."<br />";
//echo $c.") '".strtolower(str_replace("-::-","/",$bb))."' => '".$bv."',<br />";

if (in_array($bb, $ar)) {
$cb++;
$duble .= "".$cb.") string: <i>".$c."</i> name: <i>".strtolower($bb)."</i><br />";
}
if (in_array($bv, $arv)) {
$cv++;
$ununical .= "".$cv.") string: <i>".$c."</i> name: <i>".strtolower($bv)."</i><br />";
}
$ar[] = $bb;
$arv[] = $bv;
}
}

echo "<hr/>";
echo "<b>Total</b>: ".$c."; <b>Unique</b>: ".($c-$cv)."";
echo "<hr/>";
echo "<h1>Duplicates in the database by a key</h1>";
echo !empty($duble) ? $duble : "no dubles";
echo "<hr/>";
echo "<h1>No unique user agents based on the description</h1>";
echo !empty($ununical) ? $ununical : "no unique";
echo "<hr/>";
echo "<h1>All user agents in the base</h1>";
echo !empty($all) ? $all : "no robots in the base";



###############################################################################
 ctable();
 echo on_page();
?>