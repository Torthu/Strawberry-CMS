<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}

$bl_out = "<div class=\"one\"><div class=\"two\"><div class=\"in\"><b>".t('ÀÐÕÈÂ ÂÅÐÑÈÉ STRAWBERRY')."</b><div style=\"overflow-y: scroll; height: 46px;\">";
##################################
$bl_sdown = array();
   $dir = opendir(".".way("attach/"));
   $n=0;
while ($single_file = readdir($dir)){
$ext = strtolower(end(explode('.', $single_file)));
        if ($ext == "rar" or $ext == "zip" or $ext == "gz"){
                $bl_sdown[$n][1] = $single_file;
                $bl_sdown[$n][2] = $ext;
                $bl_sdown[$n][3] = formatsize(filesize(".".way("attach/".$single_file)));
                $n++;
        }
}

rsort($bl_sdown);
$bl_a = 0;
foreach ($bl_sdown as $sfn) {
if (empty($bl_a)) {
$im_out = "<img src=\"".way("images/icons/navi.png")."\" border=\"0\"/>";
} else {
$im_out = "<img src=\"".way("images/icons/right.png")."\" border=\"0\"/>";
}
$bl_out .= $im_out." <a href=\"".way("attach/".$sfn[1])."\" title=\"".t("Ôîðìàò àðõèâà").": <b>".$sfn[2]."</b>\">".current(explode(".".$sfn[2],$sfn[1]))."</a> (".$sfn[3].")<br />";
$bl_a++;
}

##################################
$bl_out .= "</div></div></div></div>";

?>